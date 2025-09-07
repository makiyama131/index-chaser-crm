<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // 賃貸仲介特化項目
            $table->string('employment_type')->nullable()->after('rank'); // 雇用形態
            $table->date('birth_date')->nullable()->after('employment_type'); // 生年月日

            // マーケティング関連情報
            $table->string('lead_source_detail')->nullable()->after('birth_date'); // 流入経路 (詳細)
            $table->date('friend_added_date')->nullable()->after('lead_source_detail'); // 友だち追加日
            $table->date('last_reaction_date')->nullable()->after('friend_added_date'); // 最終反応日
            
            // 希望条件の詳細
            $table->string('desired_layout')->nullable()->after('desired_rent_to'); // 希望の間取り
            $table->string('desired_stations')->nullable()->after('desired_layout'); // 希望の最寄駅・エリア
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'employment_type',
                'birth_date',
                'lead_source_detail',
                'friend_added_date',
                'last_reaction_date',
                'desired_layout',
                'desired_stations',
            ]);
        });
    }
};