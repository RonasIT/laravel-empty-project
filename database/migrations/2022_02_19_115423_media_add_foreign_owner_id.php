<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MediaAddForeignOwnerId extends Migration
{
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
        });
    }
}
