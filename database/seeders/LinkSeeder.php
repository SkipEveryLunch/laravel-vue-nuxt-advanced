<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Link;
use App\Models\LinkProduct;
use App\Models\Product;

class LinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Link::factory(30)->create()->
        each(function(Link $link){
            LinkProduct::create([
                'link_id'=>$link->id,
                'product_id'=>Product::inRandomOrder()->first()->id,
            ]);
        });
    }
}