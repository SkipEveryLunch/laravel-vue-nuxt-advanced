<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;
use App\Models\Order;
use App\Models\User;

class StatsController extends Controller
{
    public function index(Request $req){
        $user = $req->user();
        $links = Link::where('user_id',$user->id)->get();
        return $links->map(function(Link $link){
            $orders = Order::where('code',$link->code)->where('complete',1)->get();
            return [
            'code'=>$this->code,
            'count'=>$orders->count(),
            'revenue'=>$orders->sum(fn(Order $order)=>$order->ambassador_revenue)
            ];
        });
    }
    public function ranking(){
        $ambassadors = User::ambassadors()->get();
        $rankings = $ambassadors->map(function(User $ambassador){
            return[
                'name'=>$ambassador->name,
                'revenue'=>$ambassador->revenue
            ];
        })->sortBy([
            fn($a,$b)=>$b['revenue']<=>$a['revenue']
        ]);
        return $rankings;
    }
}
