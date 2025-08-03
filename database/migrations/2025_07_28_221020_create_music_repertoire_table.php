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
        Schema::create('music_repertoire', function (Blueprint $table) {
            $table->foreignId('music_id')->constrained('musics','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('repertoire_id')->constrained('repertoires','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->primary(['music_id', 'repertoire_id']);
            $table->string('tom')->nullable();
            $table->integer('position');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('music_repertoire');
    }
};
