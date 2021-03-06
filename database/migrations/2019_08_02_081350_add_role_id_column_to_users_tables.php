<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Cts;

class AddRoleIdColumnToUsersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('role_id')->default(Cts::ROLE_GUEST)->unsigned();
        });

        $user = new User();
        $user->name = "admin";
        $user->email = "admin@gmail.com";
        $user->role_id = \App\Cts::ROLE_ADMIN;
        $user->password = Hash::make("qweqwe123");
        $user->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->removeColumn('role_id');
        });
    }
}
