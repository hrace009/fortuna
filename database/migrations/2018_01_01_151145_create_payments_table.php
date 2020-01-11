<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('order_id');
            $table->integer('game_id');
            $table->string('game_login', 32);
            $table->uuid('user_id');
            $table->integer('amount');
            $table->integer('net_amount')->nullable()->comment('final price, discounting fees.');
            $table->integer('cash_amount');
            $table->string('gateway')->nullable();
            $table->integer('gateway_id')->nullable();
            $table->text('data')->nullable();
            $table->string('transaction_status')->default(9);
            $table->string('transaction_code')->nullable();
            $table->uuid('transaction_reference')->index()->nullable();
            $table->string('payer_id')->nullable();
            $table->string('payer_name', 32)->nullable();
            $table->string('payer_email')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('payments');
    }
}
