<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RonasIT\Support\Traits\MigrationTrait;

class CreateRolesTable extends Migration
{
    use MigrationTrait;

    public function up()
    {
        $this->createTable();
        $this->addRoles();
    }

    public function down()
    {
        Schema::drop('roles');
    }

    public function createTable()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
    }

    public function addRoles()
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'administrator'
            ],
            [
                'id' => 2,
                'name' => 'user'
            ]
        ];

        DB::table('roles')->insert($roles);
    }
}
