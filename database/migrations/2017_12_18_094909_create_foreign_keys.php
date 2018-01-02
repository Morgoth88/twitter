<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table){
            $table->foreign('role_id')->references('id')->on('role')->onDelete('cascade');
        });

        Schema::table('message', function (Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('comment', function (Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('message_id')->references('id')->on('message')->onDelete('cascade');
        });

        Schema::table('activity_log', function (Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
