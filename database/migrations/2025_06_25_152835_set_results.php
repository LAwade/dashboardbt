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
        Schema::create('set_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('game_id');
            $table->integer('team_win');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->integer('game_win')->default(0);
            $table->integer('game_lose')->default(0);
            $table->foreign('team_win')->references('id')->on('teams')->onDelete('cascade');
            $table->boolean('wo')->default(false);
            $table->boolean('tie_break')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_results');
    }
};
