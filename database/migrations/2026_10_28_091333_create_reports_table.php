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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('school_id')->constrained()->restrictOnDelete();
            $table->foreignId('distribution_id')->constrained()->restrictOnDelete();
            $table->datetime('incident_at');
            $table->text('description');
            $table->unsignedInteger('affected_count')->nullable();
            $table->string('evidence_path')->nullable();
            $table->string('status')->default('dikirim');
            $table->text('review_note')->nullable();
            $table->timestamps();

            // business rule satu pelapor hanya boleh satu laporan per distribusi saja
            $table->unique(['user_id', 'distribution_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
