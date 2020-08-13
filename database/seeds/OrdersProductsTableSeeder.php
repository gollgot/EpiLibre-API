<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('orders_products')->insert([
            'id' => 1,
            'price' => 6,
            'quantity' => 0.5,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'order_id' => 1,
            'product_id' => 1
        ]);

        DB::table('orders_products')->insert([
            'id' => 2,
            'price' => 4,
            'quantity' => 0.25,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'order_id' => 1,
            'product_id' => 2
        ]);

        DB::table('orders_products')->insert([
            'id' => 3,
            'price' => 0.5,
            'quantity' => 0.1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'order_id' => 1,
            'product_id' => 3
        ]);

        DB::table('orders_products')->insert([
            'id' => 4,
            'price' => 1,
            'quantity' => 0.6,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'order_id' => 2,
            'product_id' => 4
        ]);

        DB::table('orders_products')->insert([
            'id' => 5,
            'price' => 1.45,
            'quantity' => 0.32,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'order_id' => 2,
            'product_id' => 5
        ]);

        DB::table('orders_products')->insert([
            'id' => 6,
            'price' => 8.9,
            'quantity' => 1.2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'order_id' => 3,
            'product_id' => 7
        ]);

    }
}
