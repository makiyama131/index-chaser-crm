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
            ['id' => 2, 'name' => '初動ライン', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'ラインラリー', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => '打ち合わせ', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => '内見', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'name' => '申し込み', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'name' => '契約', 'created_at' => $now, 'updated_at' => $now], // "契約書" is likely a status, so let's call it "契約"
            ['id' => 8, 'name' => '失注', 'created_at' => $now, 'updated_at' => $now], // Kept this one
        ];

        // データを挿入
        DB::table('statuses')->insert($statuses);
    }
}
