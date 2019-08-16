<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('link');
            $table->string('name')->nullable();
            $table->boolean('is_public')->default(false);
            $table->integer('owner_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('media');
    }
}
