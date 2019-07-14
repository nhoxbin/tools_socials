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
            $table->increments('id');
            $table->longText('post_ids');
            $table->longText('comment_ids');
            $table->unsignedBigInteger('facebook_account_id');
            $table->foreign('facebook_account_id')->references('id')->on('facebook_accounts')->onDelete('cascade');
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
