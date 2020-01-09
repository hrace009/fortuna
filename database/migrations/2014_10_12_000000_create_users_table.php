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
            $table->uuid('id');
            $table->primary('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('gender', 6)->nullable();
            $table->date('birthday')->nullable();
            $table->string('document')->nullable();
            $table->string('status');
            $table->boolean('admin')->default(0);
            $table->text('confirmation_token')->nullable();
            $table->timestamp('email_confirmed_at')->nullable();
            $table->rememberToken();
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
        /* Schema::dropIfExists('users'); */
    }
}
