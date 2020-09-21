<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'firstname' => 'Loïc',
            'lastname' => 'Dessaules',
            'email' => 'loic.dessaules@heig-vd.ch',
            'password' => hash('sha256', 'loic'),
            'deleted' => false,
            'confirmed' => true,
            'tokenAPI' => Str::random(64),

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'role_id' => 1,
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'firstname' => 'Sarah',
            'lastname' => 'Voirin',
            'email' => 'sarah.voirin@epfl.ch',
            'password' => hash('sha256', 'sarah'),
            'deleted' => false,
            'confirmed' => true,
            'tokenAPI' => Str::random(64),

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'role_id' => 2,
        ]);

        DB::table('users')->insert([
            'id' => 3,
            'firstname' => 'Jean',
            'lastname' => 'Dupond',
            'email' => 'jean.dupond@epfl.ch',
            'password' => hash('sha256', 'jean'),
            'deleted' => false,
            'confirmed' => true,
            'tokenAPI' => Str::random(64),

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'role_id' => 3,
        ]);

        DB::table('users')->insert([
            'id' => 4,
            'firstname' => 'Alice',
            'lastname' => 'Martin',
            'email' => 'alice.martin@epfl.ch',
            'password' => hash('sha256', 'alice'),
            'deleted' => false,
            'confirmed' => false,
            'tokenAPI' => Str::random(64),

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'role_id' => 3,
        ]);
    }
}
