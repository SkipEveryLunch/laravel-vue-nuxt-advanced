<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;
    protected $guarded=[];
    protected $hidden = [
        'password',
    ];
    public function scopeAmbassadors($query){
        $query->where('is_admin',0);
    }
    public function scopeAdmins($query){
        $query->where('is_admin',1);
    }
    public function orders(){
        return $this->hasMany(Order::class);
    }
    public function getAdminRevenueAttribute(){
        return $this->orders()->sum(
            function(Order $order){
            return $order->admin_revenue;
        });
    }
    public function getRevenueAttribute(){
        return $this->orders->sum(
            function(Order $order){
            return $order->ambassador_revenue;
        });
    }
}
