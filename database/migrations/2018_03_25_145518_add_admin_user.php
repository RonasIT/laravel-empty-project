<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;
use App\Repositories\RoleRepository;
use \Illuminate\Support\Facades\Hash;

class AddAdminUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (env('app.env') != 'testing') {
            $this->createAdminUser();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

    protected function createAdminUser()
    {
        User::create([
            'password' => Hash::make('Z$3UL28$#Kt$Bkph'),
            'name' => 'administrator',
            'email' => 'admin@example.com',
            'role_id' => RoleRepository::ADMIN_ROLE
        ]);
    }
}