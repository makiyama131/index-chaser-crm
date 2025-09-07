<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class CheckDBConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-db-config'; // コマンド名を定義

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays the current database configuration'; // コマンドの説明

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('--- 現在のデータベース設定 ---');

        // config()ヘルパーを使って、実際に読み込まれている設定値を取得
        $connection = Config::get('database.default');
        $host = Config::get("database.connections.{$connection}.host");
        $database = Config::get("database.connections.{$connection}.database");
        $username = Config::get("database.connections.{$connection}.username");
        $password = Config::get("database.connections.{$connection}.password");

        $this->line("Connection: <comment>{$connection}</comment>");
        $this->line("Host: <comment>{$host}</comment>");
        $this->line("Database: <comment>{$database}</comment>");
        $this->line("Username: <comment>{$username}</comment>");
        $this->line("Password: <comment>" . ($password ? '******' : 'EMPTY') . "</comment>");

        $this->info('--------------------------');
        $this->comment('.envファイルの内容と一致しているか確認してください。');

        return 0;
    }
}