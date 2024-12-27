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
        Schema::create('emailtemplates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default("")->nullable();
            $table->text('template')->default("")->nullable();
            $table->boolean('isAccepted')->default(1);
            $table->boolean('isDeleted')->default(0);
            $table->boolean('isArchived')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emailtemplates');
    }
};
