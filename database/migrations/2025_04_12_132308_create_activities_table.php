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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // Utilise foreignId pour garantir la cohérence du type
            $table->string('file_path');
            $table->string('file_name');
            $table->string('language')->nullable();
            $table->integer('duration')->comment('Duration in seconds');
            $table->integer('lines')->default(0);
            $table->timestamp('activity_time')->nullable();
            $table->json('stats')->nullable()->comment('Full stats payload');
            $table->timestamps();
            
            $table->index(['project_id', 'activity_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
