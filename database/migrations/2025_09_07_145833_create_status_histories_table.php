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
        Schema::create('status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who made the change
            $table->foreignId('from_status_id')->nullable()->constrained('statuses')->onDelete('set null'); // The old status
            $table->foreignId('to_status_id')->constrained('statuses')->onDelete('cascade'); // The new status
            $table->timestamps(); // The 'created_at' will be the timestamp of the change
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_histories');
    }
};
