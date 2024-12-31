<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('musics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('singer_id')->constrained('singers');
            $table->foreignId('rhythm_id')->constrained('rhythms');
            $table->foreignId('tone_id')->constrained('tones');
            $table->string('music_name')->unique();
            $table->string('introduction')->nullable();
            $table->text('music')->nullable();
            $table->json('chords');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('musics');
    }
};
