<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // DBファサードをインポート
use Carbon\Carbon; // Carbonをインポート

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 既存のデータをクリア
        // DB::table('statuses')->truncate();

        $now = Carbon::now();

        // 初期データを定義
        $statuses = [
            ['id' => 1, 'name' => '新規反響', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => '要追客', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => '対応中', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => '来店予約', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => '申込', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'name' => '契約完了', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'name' => '失注', 'created_at' => $now, 'updated_at' => $now],
        ];

        // データを挿入
        DB::table('statuses')->insert($statuses);
    }
}