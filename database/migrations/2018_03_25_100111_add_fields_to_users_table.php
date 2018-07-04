<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use RonasIT\Support\Traits\MigrationTrait;


class AddFieldsToUsersTable extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        DB::beginTransaction();

        $this->updateTable();

        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        DB::beginTransaction();

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role_id', 'reset_password_hash']);
        });

        DB::commit();
    }

    public function updateTable()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('role_id');
            $table->string('reset_password_hash')->nullable();
        });
    }
}