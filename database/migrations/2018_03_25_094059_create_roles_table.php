<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use RonasIT\Support\Traits\MigrationTrait;

class CreateRolesTable extends Migration
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

        $this->createTable();

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

        Schema::drop('roles');

        DB::commit();
    }

    public function createTable()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
        });
    }
}