<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            {{-- Heroicon: home --}}
            <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-neutral-800">ダッシュボード</h1>
                <p class="text-sm text-neutral-500 mt-1">今日のタスクと顧客状況の概要</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-neutral-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Overdue Tasks Alert --}}
            @if($overdue_tasks->isNotEmpty())
                <div class="bg-danger-50 border-l-4 border-danger-500 rounded-r-lg p-6 shadow-soft">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 text-danger-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-danger-800">緊急：期限切れのタスクがあります</h3>
                            <div class="mt-3 space-y-3">
                                @foreach ($overdue_tasks as $task)
                                    <div
                                        class="bg-white rounded-lg p-3 border border-danger-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                        <div>
                                            <p class="font-semibold text-neutral-800">{{ $task->title }} (顧客:
                                                {{ $task->customer->name }})
                                            </p>
                                            <p class="text-sm text-danger-600 font-medium">期日:
                                                {{ $task->due_date->format('m/d H:i') }}
                                                ({{ $task->due_date->diffForHumans() }})
                                            </p>
                                        </div>
                                        <a href="{{ route('tasks.showCompleteForm', $task) }}"
                                            class="btn-base bg-danger-600 hover:bg-danger-700 text-white text-xs px-3 py-1.5 flex-shrink-0">完了報告</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                {{-- Main Content Area (Today's Tasks) --}}
                <div class="xl:col-span-2 bg-white rounded-xl shadow-soft border border-neutral-200/50">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-neutral-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <h3 class="text-xl font-semibold text-neutral-800">今日のタスク</h3>
                        </div>
                        <div class="space-y-3 max-h-[28rem] overflow-y-auto pr-2">
                            @forelse ($incomplete_tasks as $task)
                                <div
                                    class="p-3 rounded-lg border border-neutral-200 hover:bg-neutral-50 hover:border-neutral-300 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('tasks.showCompleteForm', $task) }}" title="完了報告へ"
                                            class="block w-5 h-5 border-2 border-red-400 rounded-full hover:bg-red-200 transition"></a>
                                        <div>
                                            <p class="font-bold">{{ $task->title }} (顧客: {{ $task->customer->name }})</p>
                                            <p class="text-sm">期日: {{ $task->due_date->format('Y/m/d') }}
                                                ({{ $task->due_date->diffForHumans() }})</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-16">
                                    <p class="text-neutral-500">未完了のタスクはありません。素晴らしい一日です！</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                

                {{-- Sidebar --}}
                <div class="space-y-8">
                    {{-- Untouched Customers Alert --}}
                    <div class="bg-white rounded-xl shadow-soft border border-neutral-200/50 p-6">
                        <div class="flex items-center gap-3 mb-4"><svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-warning-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-neutral-800">要注意顧客</h3>
                        </div>
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-2">
                            @forelse ($long_term_untouched_customers as $customer)
                                <a href="{{ route('customers.show', $customer) }}"
                                    class="block p-2 rounded-lg hover:bg-warning-50 transition-colors">
                                    <p class="font-medium text-neutral-700">{{ $customer->name }}</p>
                                    <p class="text-sm text-warning-600">最終更新: {{ $customer->updated_at->diffForHumans() }}
                                    </p>
                                </a>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-sm text-neutral-500">長期未対応の顧客はいません。</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Customer Status Stats --}}
                    <div class="bg-white rounded-xl shadow-soft border border-neutral-200/50 p-6">
                        <div class="flex items-center gap-3 mb-4"><svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-neutral-800">顧客ステータス</h3>
                        </div>
                        <div class="space-y-2">
                            @foreach ($status_counts as $status_count)
                                <div class="flex items-center justify-between"><span
                                        class="text-sm text-neutral-600">{{ $status_count->status->name }}</span><span
                                        class="font-semibold text-neutral-800">{{ $status_count->count }} 人</span></div>
                            @endforeach
                            <div class="flex items-center justify-between pt-2 border-t border-neutral-200 mt-3 !"><span
                                    class="font-bold text-neutral-800">合計</span><span
                                    class="font-bold text-primary-600 text-lg">{{ $status_counts->sum('count') }}
                                    人</span></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Today's Activity Summary --}}
            <div class="bg-white rounded-xl shadow-soft border border-neutral-200/50 p-6">
                <div class="flex items-center gap-3 mb-4"><svg xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-neutral-800">活動サマリー</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-neutral-50 rounded-lg p-4 border border-neutral-200">
                        <p class="text-sm text-neutral-500">未完了タスク</p>
                        <p class="text-2xl font-bold text-primary-600">{{ $incomplete_tasks->count() }}</p>
                    </div>
                    <div class="bg-neutral-50 rounded-lg p-4 border border-neutral-200">
                        <p class="text-sm text-neutral-500">期限切れ</p>
                        <p class="text-2xl font-bold text-danger-600">{{ $overdue_tasks->count() }}</p>
                    </div>
                    <div class="bg-neutral-50 rounded-lg p-4 border border-neutral-200">
                        <p class="text-sm text-neutral-500">要注意顧客</p>
                        <p class="text-2xl font-bold text-warning-600">{{ $long_term_untouched_customers->count() }}</p>
                    </div>
                    <div class="bg-neutral-50 rounded-lg p-4 border border-neutral-200">
                        <p class="text-sm text-neutral-500">総顧客数</p>
                        <p class="text-2xl font-bold text-neutral-800">{{ $status_counts->sum('count') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        /* スクロールバーのスタイリングは省略 */
    </style>
    <script> /* 汎用的なスクリプトは省略 */</script>
</x-app-layout>