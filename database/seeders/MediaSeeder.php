<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Media;

class MediaSeeder extends Seeder
{
    public function run()
    {
        factory(Media::class)->create([
            'owner_id' => factory(User::class)->create()->id,
        ]);
    }
}
