<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkProduct extends Model
{
    use HasFactory;
    public function links(){
        return $this->belongsToMany(Link::class,"link_products");
    }
    public function products(){
        return $this->belongsToMany(Product::class,"link_products");
    }
}
