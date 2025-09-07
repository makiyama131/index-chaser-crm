<x-app-layout>
    {{-- Header Section --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-neutral-800 leading-tight">
                    {{ __('顧客一覧') }}
                </h2>
                <p class="text-sm text-neutral-500 mt-1">
                    大切な顧客情報を管理します。
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center space-x-3">
                {{-- 「自動登録」は補助的なアクションなので btn-secondary に変更 --}}
                <a href="{{ route('customers.autoCreate') }}" class="btn-base btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    自動登録
                </a>
                {{-- 「新規登録」がメインのアクションなので btn-primary を維持 --}}
                <a href="{{ route('customers.create') }}" class="btn-base btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    新規登録
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-neutral-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Success Message --}}
            @if (session('success'))
                <div class="animate-fade-in flex items-center p-4 mb-4 text-sm text-success-800 rounded-lg bg-success-100 border border-success-200"
                    role="alert">
                    {{-- Heroicon: check-circle --}}
                    <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Search & Filter Panel --}}
            <div class="bg-white p-6 rounded-xl shadow-soft animate-slide-up">
                @if (session('success'))
                    <div class="animate-fade-in flex items-center p-4 mb-4 text-sm text-success-800 rounded-lg bg-success-100 border border-success-200"
                        role="alert">
                        <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Search & Filter Panel --}}
                <div class="bg-white p-6 rounded-xl shadow-soft animate-slide-up">
                    <form action="{{ route('customers.index') }}" method="GET"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div class="md:col-span-2">
                            <label for="keyword" class="block text-sm font-medium text-neutral-700 mb-1">検索キーワード</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><svg
                                        class="h-5 w-5 text-neutral-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg></div><input type="text" name="keyword" id="keyword"
                                    value="{{ request('keyword', '') }}" placeholder="氏名、電話番号..."
                                    class="w-full pl-10 rounded-lg border-neutral-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm">
                            </div>
                        </div>
                        <div>
                            <label for="sort" class="block text-sm font-medium text-neutral-700 mb-1">並び替え</label>
                            <select name="sort" id="sort"
                                class="w-full rounded-lg border-neutral-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm"
                                onchange="this.form.submit()">
                                <option value="updated_at_desc" @selected(request('sort') == 'updated_at_desc')>更新が新しい順
                                </option>
                                <option value="updated_at_asc" @selected(request('sort') == 'updated_at_asc')>更新が古い順
                                </option>
                                <option value="created_at_desc" @selected(request('sort') == 'created_at_desc')>登録が新しい順
                                </option>
                                <option value="created_at_asc" @selected(request('sort') == 'created_at_asc')>登録が古い順
                                </option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn-base btn-primary w-full"><svg class="h-5 w-5 mr-2"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>検索</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Customer Table Card --}}
            <div class="bg-white overflow-hidden shadow-soft sm:rounded-xl animate-slide-up"
                style="animation-delay: 100ms;">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200">
                        <thead class="bg-neutral-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                    氏名</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                    顧客温度感</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                    ステータス</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                    担当者</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                    最終接触日</th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">操作</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-neutral-200">
                            @forelse ($customers as $customer)
                                <tr class="hover:bg-neutral-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-neutral-900">{{ $customer->name }}</div>
                                        <div class="text-xs text-neutral-500">{{ $customer->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $rankColor = [
                                                'A' => 'bg-red-100 text-red-800',
                                                'B' => 'bg-amber-100 text-amber-800',
                                                'C' => 'bg-sky-100 text-sky-800',
                                            ][$customer->rank] ?? 'bg-neutral-100 text-neutral-800';
                                        @endphp
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $rankColor }}">
                                            {{ $customer->rank }}ランク
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                        {{ $customer->status->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                        {{ $customer->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                        {{ $customer->updated_at->format('Y/m/d') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('customers.show', $customer) }}"
                                            class="text-primary-600 hover:text-primary-800 hover:underline transition-all">
                                            詳細を見る
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-12">
                                        <div class="text-neutral-500">
                                            <p class="font-semibold text-lg">顧客が見つかりません</p>
                                            <p class="mt-2 text-sm">検索条件を変更するか、新しい顧客を登録してください。</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($customers->hasPages())
                    <div class="p-4 border-t border-neutral-200">
                        {{ $customers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>