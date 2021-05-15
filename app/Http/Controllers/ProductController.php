<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;
use App\Events\ProductUpdatedEvent;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $product = Product::create($req->only("title","description","image","price"));
        event(new ProductUpdatedEvent);
        return response($product,Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, Product $product)
    {
        $product->update($req->only('title','description','image','price'));
        event(new ProductUpdatedEvent);
        return response($product,Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::destroy($id);
        event(new ProductUpdatedEvent);
        return response(null,Response::HTTP_NO_CONTENT);
    }
    public function frontend(){

        return \Cache::remember('products_frontend',30*60,fn()=>Product::all());
    }
    public function backend(Request $req){
        $products = \Cache::remember('products_backend',30*60,fn()=> Product::all());

        if($s = $req->input('s')){
            $products = $products->
                filter(function(Product $product)use($s){
                return \Str::contains($product->title,$s)||\Str::contains($product->description,$s);
            });
        }
        if($sort = $req->input('sort')){
            if($sort==='asc'){
                $products = $products->sortBy([
                    fn($a,$b)=>$a['price']<=>$b['price']
                ]);
            }elseif($sort==='desc'){
                $products = $products->sortBy([
                    fn($a,$b)=>$b['price']<=>$a['price']
                ]);
            }

        }
        $page = $req->input('page',1);
        $total = $products->count();
        $perPage = 9;
        return [
            'meta'=>[
                'page'=>$page,
                'total'=>$total,
                'last_page'=>ceil($total/$perPage)
            ],
            'data'=>$products->forPage($page,$perPage)->values()
        ];
    }
}
