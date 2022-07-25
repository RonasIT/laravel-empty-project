<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    public function run()
    {
        factory(Media::class)->create([
            'owner_id' => factory(User::class)->create()->id,
        ]);
    }
}
