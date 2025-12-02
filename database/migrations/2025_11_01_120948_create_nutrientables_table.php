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
        Schema::create('nutrientables', function (Blueprint $table) {
            $table->foreignId('nutrition_id')->constrained()->cascadeOnDelete();
            $table->morphs('nutrientable');
            $table->float('value', 8)->nullable();
            $table->unsignedInteger('percentage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutrientables');
    }
};
