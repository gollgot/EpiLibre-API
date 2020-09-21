<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->insert([
            'id' => 1,
            'totalPrice' => doubleval(10.5),
            'created_at' => "2020-08-13 17:23:30",
            'updated_at' => "2020-08-13 17:23:30",
            'user_id' => 1
        ]);

        DB::table('orders')->insert([
            'id' => 2,
            'totalPrice' => doubleval(2.45),
            'created_at' => "2020-07-04 10:20:00",
            'updated_at' => "2020-07-04 10:20:00",
            'user_id' => 2
        ]);

        DB::table('orders')->insert([
            'id' => 3,
            'totalPrice' => doubleval(8.9),
            'created_at' => "2020-07-20 21:00:00",
            'updated_at' => "2020-07-20 21:00:00",
            'user_id' => 1
        ]);
    }
}
