<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacebookCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facebook_comments', function (Blueprint $table) {
            $table->string('provider_uid', 20)->index();
            $table->foreign('provider_uid')->references('provider_uid')->on('facebook_accounts')->onDelete('cascade');
            $table->longText('comments');
            $table->char('type', 10);
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
        Schema::dropIfExists('facebook_comments');
    }
}
