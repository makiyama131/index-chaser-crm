<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- Noto Sans JP を Inter と共に読み込むように変更 --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">


        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-neutral-800 antialiased">
        {{-- 背景をよりソフトな neutral-50 に変更 --}}
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-neutral-50">
            <div>
                <a href="/" class="flex items-center gap-3 text-neutral-800">
                    {{-- 新しいロゴコンポーネントを配置 --}}
                    <x-application-logo class="w-10 h-10" />
                    <span class="text-2xl font-bold">CRM System</span>
                </a>
            </div>

            {{-- カードスタイルをアプリ全体のデザインに統一 --}}
            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-soft border border-neutral-200/50 overflow-hidden sm:rounded-xl">
                {{ $slot }}
            </div>

            <div class="mt-8 text-center text-sm text-neutral-500">
                &copy; {{ date('Y') }} CRM System. All Rights Reserved.
            </div>
        </div>
    </body>
</html>