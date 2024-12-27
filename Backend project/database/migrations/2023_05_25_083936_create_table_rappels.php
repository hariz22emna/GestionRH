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
        Schema::create('rappels', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->unsignedBigInteger('resourceId');
            $table->foreign('resourceId')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger(column: 'userId');
            $table->string('googleCalendarId')->nullable();
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->string('repeatNumber')->default("Aucune")->nullable();
            $table->string('periodicity')->default("Quotidien")->nullable();
            $table->string('typeAlerte')->default("E-mail")->nullable();
            $table->timestamp('rappelDate')->nullable();
            $table->timestamp('expireDate')->nullable();
            $table->boolean('isAccepted')->default(1);
            $table->boolean('isDeleted')->default(0);
            $table->boolean('isArchived')->default(0);
            $table->foreignId('parentId')->nullable()->references('id')->on('rappels')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_rappels');
    }
};
