<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderProducts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Order::factory(100)->create()->each(function ($order) {
            // Seed the relation with one address
            $products = OrderProducts::factory(20)->make([
                'order_id' =>  $order->id
            ]);

            $order->orderProducts()->saveMany($products);
        });
    }

}
