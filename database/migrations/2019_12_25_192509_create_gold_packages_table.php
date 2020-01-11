<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoldPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gold_packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('package_id');
            $table->string('package_name');
            $table->integer('package_price');
            $table->integer('package_gold_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gold_packages');
    }
}
