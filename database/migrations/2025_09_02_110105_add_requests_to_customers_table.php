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
        Schema::table('customers', function (Blueprint $table) {
            // $table->text('reason_for_moving')->nullable()->after('last_reaction_date'); // ← この行を削除またはコメントアウト
            $table->text('agent_request')->nullable()->after('reason_for_moving'); // 担当者へのお願い
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
