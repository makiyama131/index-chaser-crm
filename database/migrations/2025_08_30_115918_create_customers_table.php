<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // 顧客ID
            $table->string('name'); // 氏名
            $table->string('email')->nullable(); // メールアドレス
            $table->string('phone')->nullable(); // 電話番号
            
            // 希望条件
            $table->string('desired_area')->nullable(); // 希望エリア
            $table->integer('desired_rent_from')->nullable(); // 希望家賃（下限）
            $table->integer('desired_rent_to')->nullable(); // 希望家賃（上限）
            
            // 賃貸仲介特化項目
            $table->text('family_structure')->nullable(); // 家族構成
            $table->text('reason_for_moving')->nullable(); // 転居理由
            $table->string('company_name')->nullable(); // 勤務先
            $table->text('current_residence_info')->nullable(); // 現在の住居情報

            // 管理項目
            $table->string('lead_source')->nullable(); // 反響媒体
            $table->enum('rank', ['A', 'B', 'C'])->default('C'); // 顧客温度感（確度）
            
            // 外部キー制約
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 担当者ID
            $table->foreignId('status_id')->default(1)->constrained(); // ステータスID (デフォルトは「新規」など)

            $table->timestamps(); // 作成日・更新日
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
