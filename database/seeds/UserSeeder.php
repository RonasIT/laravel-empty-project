<?php 

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        factory(User::class)->create([
            'role_id' => Role::USER
        ]);
    }
}
