<?php
namespace Database\Seeders;

use App\Modules\Media\database\seeders\MediaSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UserSeeder::class);

        $this->call(MediaSeeder::class);
    }
}
