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
        Schema::create('media_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('filename');
            $table->string('original_name');
            $table->string('mime_type');
            $table->string('extension');
            $table->bigInteger('size'); // in bytes
            $table->string('path');
            $table->string('url');
            $table->string('disk')->default('public');
            $table->json('metadata')->nullable(); // for additional info like dimensions, etc.
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
}; 