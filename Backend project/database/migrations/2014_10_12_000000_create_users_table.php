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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('immat')->unique()->nullable()->change();;
            $table->string('name')->default("")->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('function')->default("Développeur")->nullable();
            $table->string('phoneNumber')->default("")->nullable();
            $table->string('dateOfBirth')->default("")->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('access')->default(1);
            $table->string('image')->default("")->nullable();
            $table->boolean('isAccepted')->default(1);
            $table->boolean('isDeleted')->default(0);
            $table->boolean('isArchived')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');

    }
};
