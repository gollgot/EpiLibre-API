<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("firstname", 45);
            $table->string("lastname", 45);
            $table->string("email", 255)->unique();
            $table->string("password", 255);
            $table->boolean("deleted");
            $table->boolean("validated");
            $table->timestamps();

            $table->foreignId('role_id')->constrained();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
