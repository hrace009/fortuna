<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_characters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('role_id');
            $table->longText('pocket');
            $table->longText('equipment');
            $table->longText('storehouse');
            $table->longText('task');
            $table->string('name');
            $table->integer('level');
            $table->integer('level2');
            $table->integer('cls');
            $table->integer('cash_add');
            $table->integer('cash_total');
            $table->integer('cash_used');
            $table->integer('cash_serial');
            $table->integer('mall_consumption');
            $table->integer('vip_level');
            $table->integer('score_add');
            $table->integer('score_cost');
            $table->integer('score_consume');
            $table->integer('faction_id');
            $table->integer('faction_role');
            $table->integer('create_time');
            $table->integer('lastlogin_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('characters');
    }
}
