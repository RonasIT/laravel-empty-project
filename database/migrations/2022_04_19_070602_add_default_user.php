<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use RonasIT\Support\Traits\MigrationTrait;

class AddDefaultUser extends Migration
{
    use MigrationTrait;

    public function up()
    {
        if (config('app.env') !== 'testing') {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@photoclaim-crypto-blog.com',
                'password' => Hash::make('c1d8b3fe'),
                'role_id' => 1
            ]);
        }
    }

    public function down()
    {
        if (config('app.env') !== 'testing') {
            User::where('email', 'admin@photoclaim-crypto-blog.com')->delete();
        }
    }
}
