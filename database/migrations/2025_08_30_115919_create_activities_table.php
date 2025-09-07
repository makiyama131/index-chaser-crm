<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); // どの顧客への活動か
            $table->foreignId('user_id')->constrained()->onDelete('cascade');     // 誰が活動したか
            $table->string('type'); // 活動の種類（例：電話、メール、来店）
            $table->text('note');   // 対応メモ
            $table->timestamps();   // 対応日時はこのタイムスタンプを利用
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};