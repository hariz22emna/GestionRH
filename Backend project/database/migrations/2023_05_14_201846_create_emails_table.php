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
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('senderEmail')->default("")->nullable();
            $table->string('recipientEmail')->default("")->nullable();
            $table->string('object')->default("")->nullable();
            $table->string('mailContent')->default("")->nullable();
            $table->unsignedBigInteger('templateId')->nullable()->constrained();
            $table->foreign('templateId')->references('id')->on('emailtemplates');
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
        Schema::dropIfExists('emails');
    }
};
