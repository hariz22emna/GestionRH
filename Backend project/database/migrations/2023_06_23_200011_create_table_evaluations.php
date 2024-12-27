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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('technologyId')->constrained('technologies')->onDelete('cascade'); // Ajoute la suppression en cascade
            $table->foreignId('userId')->constrained('users');
            $table->foreignId('resourceId')->constrained('users');
            $table->string('note')->nullable()->default('');
            $table->string('total')->nullable()->default('');
            $table->string('status')->nullable()->default('');
            $table->boolean('isAccepted')->default(true);
            $table->boolean('isDeleted')->default(false);
            $table->boolean('isArchived')->default(false);
            $table->timestamps();
        });
    }
    
};