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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->integer('team_one');
            $table->integer('team_two');
            $table->integer('status_id');
            $table->uuid('court_id');
            $table->uuid('championship_id');
            $table->string('round')->nullable();
            $table->integer('extend_id')->nullable();
            $table->string('category')->nullable();
            $table->foreign('team_one')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('team_two')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
            $table->foreign('court_id')->references('id')->on('courts')->onDelete('cascade');
            $table->foreign('championship_id')->references('id')->on('championships')->onDelete('cascade');
            $table->dateTime('schedule')->nullable();
            $table->dateTime('date_start')->nullable();
            $table->dateTime('date_end')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
