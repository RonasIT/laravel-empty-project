<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNovaPasswordResetsTable extends Migration
{
    public function up()
    {
        Schema::create('nova_password_resets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->string('email')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nova_password_resets');
    }
}
