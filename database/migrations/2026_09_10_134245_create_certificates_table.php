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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->unique()->constrained()->onDelete('cascade');
            $table->string('certificate_number')->unique();
            $table->string('recipient_name');
            $table->string('seedling_name');
            $table->integer('tree_count');
            $table->date('issued_date');
            $table->string('signer_name')->default('Kepala Desa Labuhan Lombok');
            $table->string('signer_title')->default('Pemerintah Desa Labuhan Lombok');
            $table->text('appreciation_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
