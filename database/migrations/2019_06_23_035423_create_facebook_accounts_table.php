<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacebookAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facebook_accounts', function (Blueprint $table) {
            $table->string('provider_uid', 20)->primary();
            $table->string('name', 100)->nullable();
            $table->text('access_token', 255)->nullable();
            $table->text('cookie', 100)->nullable();
            $table->unsignedTinyInteger('is_active');
            $table->string('status')->nullable();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('facebook_accounts');
    }
}
