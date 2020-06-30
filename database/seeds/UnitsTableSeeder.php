<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('units')->insert([
            'id' => 1,
            'name' => 'Gramme',
            'abbreviation' => 'g',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('units')->insert([
            'id' => 2,
            'name' => 'Kilogramme',
            'abbreviation' => 'kg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('units')->insert([
            'id' => 3,
            'name' => 'Litre',
            'abbreviation' => 'L',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('units')->insert([
            'id' => 4,
            'name' => 'Pièce',
            'abbreviation' => 'pc',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
