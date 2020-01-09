<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('track_id', 10);
            $table->string('user_id', 36);
            $table->string('ip_address', 45);
            $table->text('message');
            $table->text('attachments')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->boolean('staff')->default(0);
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
        Schema::dropIfExists('tickets_replies');
    }
}
