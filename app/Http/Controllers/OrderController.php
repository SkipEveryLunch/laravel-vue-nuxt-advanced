<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Link;
use App\Models\Product;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('orderItems')->get();
        return OrderResource::collection($orders);
    }

    /**
     * Show the form for creating a new resource.
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
        if(!$link = Link::where('code',$req->input('code'))->first()){
            abort(400,'invalid call');
        }

        try{

            \DB::beginTransaction();
            $order = new Order();

            $order->code = $link->code;
            $order->user_id = $link->user->id;
            $order->ambassador_email = $link->user->email;
            $order->first_name = $req->input('first_name');
            $order->last_name = $req->input('last_name');
            $order->email = $req->input('email');
            $order->address = $req->input('address');
            $order->country = $req->input('country');
            $order->city = $req->input('city');
            $order->zip = $req->input('zip');

            $order->save();

            abort(400,'error');
            foreach($req->input('products') as $item){
                $product = Product::find($item['product_id']);

                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_title = $product->title;
                $orderItem->price = $product->price;
                $orderItem->quantity = $item['quantity'];
                $orderItem->ambassador_revenue = .1 * $product->price * $item['quantity'];
                $orderItem->admin_revenue = .9 * $product->price * $item['quantity'];

                $orderItem->save();
            }
            \DB::commit();
        }catch(\Throwable $e){
            \DB::rollBack();
            abort(500,'an error happened during storing an order');
        }
        return $order->load('orderItems');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
