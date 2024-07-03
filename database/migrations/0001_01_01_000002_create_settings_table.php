<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->timestamps();
            $table->string('name', 255);

            if (config('database.default') == 'mysql') {
                $table->jsonb('value')->nullable();
            } else {
                $table->jsonb('value')->default('{}');
            }

            $table->boolean('is_public')->default(false);
            $table->primary('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
