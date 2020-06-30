<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'id' => 1,
            'name' => 'Pâte à la farine de blé',
            'image' => null,
            'price' => 4.20,
            'stock' => 1.5,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'unit_id' => 2,
            'user_id' => 1,
            'category_id' => 1,
        ]);

        DB::table('products')->insert([
            'id' => 2,
            'name' => 'Pâte à la farine d\'épeautre',
            'image' => null,
            'price' => 5.30,
            'stock' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'unit_id' => 2,
            'user_id' => 1,
            'category_id' => 1,
        ]);

        DB::table('products')->insert([
            'id' => 3,
            'name' => 'Pâte à la farine de saigle',
            'image' => null,
            'price' => 5.00,
            'stock' => 2.3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'unit_id' => 2,
            'user_id' => 1,
            'category_id' => 1,
        ]);

        DB::table('products')->insert([
            'id' => 4,
            'name' => 'Lentille',
            'image' => null,
            'price' => 6.00,
            'stock' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'unit_id' => 2,
            'user_id' => 1,
            'category_id' => 2,
        ]);

        DB::table('products')->insert([
            'id' => 5,
            'name' => 'Haricot rouge',
            'image' => null,
            'price' => 4.00,
            'stock' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'unit_id' => 2,
            'user_id' => 1,
            'category_id' => 2,
        ]);

        DB::table('products')->insert([
            'id' => 6,
            'name' => 'Haricot blanc',
            'image' => null,
            'price' => 3.50,
            'stock' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'unit_id' => 2,
            'user_id' => 1,
            'category_id' => 2,
        ]);

        DB::table('products')->insert([
            'id' => 7,
            'name' => 'Graine de lin',
            'image' => null,
            'price' => 8.50,
            'stock' => 0.8,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'unit_id' => 2,
            'user_id' => 1,
            'category_id' => 3,
        ]);

        DB::table('products')->insert([
            'id' => 8,
            'name' => 'Graine de sésame',
            'image' => null,
            'price' => 7.50,
            'stock' => 0.6,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'unit_id' => 2,
            'user_id' => 1,
            'category_id' => 3,
        ]);

        DB::table('products')->insert([
            'id' => 9,
            'name' => 'Graine de pavot',
            'image' => null,
            'price' => 8.00,
            'stock' => 0.65,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'unit_id' => 2,
            'user_id' => 1,
            'category_id' => 3,
        ]);

        DB::table('products')->insert([
            'id' => 10,
            'name' => 'Savon dur',
            'image' => null,
            'price' => 6.00,
            'stock' => 10,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'unit_id' => 4,
            'user_id' => 1,
            'category_id' => 4,
        ]);

        DB::table('products')->insert([
            'id' => 11,
            'name' => 'Shampoing solide',
            'image' => null,
            'price' => 5.80,
            'stock' => 17,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'unit_id' => 4,
            'user_id' => 1,
            'category_id' => 4,
        ]);

        DB::table('products')->insert([
            'id' => 12,
            'name' => 'Dentifrice',
            'image' => null,
            'price' => 10.00,
            'stock' => 8,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'unit_id' => 4,
            'user_id' => 1,
            'category_id' => 4,
        ]);

        DB::table('products')->insert([
            'id' => 13,
            'name' => 'Tisane',
            'image' => null,
            'price' => 0.238,
            'stock' => 1250,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'unit_id' => 1,
            'user_id' => 1,
            'category_id' => 5,
        ]);
    }
}
