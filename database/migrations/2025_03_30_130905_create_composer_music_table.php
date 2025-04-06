<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('composer_music', function (Blueprint $table) {
            
            $table->foreignId('composer_id')->constrained('composers','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('music_id')->constrained('musics','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->primary(['composer_id', 'music_id']);
          
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('composer_music');
    }
};
