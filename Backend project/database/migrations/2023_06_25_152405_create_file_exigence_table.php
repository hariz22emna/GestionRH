<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('file_exigence', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('file_type_id');
            $table->unsignedBigInteger('user_id');
            $table->string('details');
            $table->string('full_filename');
            $table->binary('file_content');
            $table->boolean('isDeleted')->default(0);
            $table->timestamps();

            $table->foreign('file_type_id')
                ->references('id')
                ->on('file_types')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
        DB::statement("ALTER TABLE file_exigence MODIFY file_content LONGBLOB");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_exigence');
    }
};
