<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_factions', function (Blueprint $table) {
            $table->integer('fid');
            $table->primary('fid');
            $table->string('name');
            $table->integer('level');
            $table->integer('owner_id');
            $table->string('owner_name');
            $table->text('members');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_factions');
    }
}
