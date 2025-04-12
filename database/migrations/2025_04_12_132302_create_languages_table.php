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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // Utilise foreignId pour garantir la cohérence du type
            $table->string('name');
            $table->integer('files')->default(0);
            $table->integer('lines')->default(0);
            $table->integer('time_ms')->default(0);
            $table->timestamps();
            
            $table->unique(['project_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
