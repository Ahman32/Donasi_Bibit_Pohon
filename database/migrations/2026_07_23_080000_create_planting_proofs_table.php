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
        Schema::create('planting_proofs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type')->default('planting'); // 'planting' or 'donation'
            $table->string('image_path');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->date('planted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planting_proofs');
    }
};
