<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersMakeResetPasswordHashUnique extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
           $table->unique(['reset_password_hash']);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['reset_password_hash']);
        });
    }
}
