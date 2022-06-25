<?php
namespace App\Modules\Media\database\seeders;

use App\Models\User;
use App\Modules\Media\Models\Media;
use Illuminate\Database\Seeder;
use function factory;

class MediaSeeder extends Seeder
{
    public function run()
    {
        factory(Media::class)->create([
            'owner_id' => factory(User::class)->create()->id,
        ]);
    }
}
