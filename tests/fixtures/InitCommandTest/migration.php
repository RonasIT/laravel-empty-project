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
                'name' => 'TestAdmin',
                'email' => 'mail@mail.com',
                'password' => Hash::make('123456'),
                'role_id' => '1'
            ]);
        }
    }

    public function down()
    {
        if (config('app.env') !== 'testing') {
            User::where('email', 'mail@mail.com')->delete();
        }
    }
}
