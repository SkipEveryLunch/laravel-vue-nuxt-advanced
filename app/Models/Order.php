<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function OrderItems(){
        return $this->hasMany(OrderItem::class);
    }
    public function getNameAttribute(){
        return $this->first_name .' '.$this->last_name;
    }
    public function getAdminRevenueAttribute(){
        return $this->orderItems->sum(function(OrderItem $item){
            return $item->admin_revenue;
        });
    }
    public function getRevenueAttribute(){
        return $this->orderItems->sum(function(OrderItem $item){
            return $item->revenue;
        });
    }
}
