<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_items', function (Blueprint $table) {
            $table->integer('id');
            $table->primary('id');
            $table->string('name');
            $table->string('color', 6);
            $table->text('description')->nullable();
            $table->integer('max_count');
            $table->integer('proctype');
            $table->integer('mask');
            $table->text('octet');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_items');
    }
}
