<?php

// database/migrations/2024_01_01_000000_create_smarties_entries_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('smarties_entries', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('contributor');
            $table->unsignedTinyInteger('red')->default(0);
            $table->unsignedTinyInteger('orange')->default(0);
            $table->unsignedTinyInteger('yellow')->default(0);
            $table->unsignedTinyInteger('green')->default(0);
            $table->unsignedTinyInteger('blue')->default(0);
            $table->unsignedTinyInteger('pink')->default(0);
            $table->unsignedTinyInteger('purple')->default(0);
            $table->unsignedTinyInteger('brown')->default(0);
            $table->unsignedTinyInteger('total')->default(0);
            $table->timestamps();

            // Indexes for common queries
            $table->index('date');
            $table->index('contributor');
            $table->index(['contributor', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smarties_entries');
    }
};
