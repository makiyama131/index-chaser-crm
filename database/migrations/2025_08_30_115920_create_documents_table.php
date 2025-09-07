<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // .../database/migrations/..._create_documents_table.php
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            // ▼▼▼ THIS LINE IS IMPORTANT ▼▼▼
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type');

            $table->string('display_name');
            $table->string('file_path');
            $table->string('mime_type');
            $table->string('thumbnail_path')->nullable();
            $table->unsignedBigInteger('size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
