<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ダッシュボード') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                @if($overdue_tasks->isNotEmpty())
                    <div class="md:col-span-3 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow-sm sm:rounded-lg"
                        role="alert">
                        <h3 class="text-lg font-medium mb-4">期限切れのタスクがあります</h3>
                        <div class="space-y-2">
                            @foreach ($overdue_tasks as $task)
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-bold">{{ $task->title }} (顧客: {{ $task->customer->name }})</p>
                                        <p class="text-sm">期日: {{ $task->due_date->format('Y/m/d H:i') }}
                                            ({{ $task->due_date->diffForHumans() }})</p>
                                    </div>
                                    <a href="{{ route('tasks.showCompleteForm', $task) }}"
                                        class="text-xs bg-red-500 hover:bg-red-700 text-white py-1 px-3 rounded">
                                        完了報告
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">未完了のタスク</h3>
                        <div class="space-y-4">
                            @forelse ($incomplete_tasks as $task)
                                <div class="border-l-4 border-blue-500 pl-4">
                                    <a href="{{ route('customers.show', $task->customer) }}"
                                        class="font-semibold text-blue-600 hover:underline">
                                        {{ $task->title }}
                                    </a>
                                    <p class="text-sm text-gray-600">顧客: {{ $task->customer->name }}</p>
                                </div>
                                <form action="{{ route('tasks.complete', $task) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="text-xs bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded">
                                        完了
                                    </button>
                                </form>
                            @empty
                                <p class="text-gray-500">未完了のタスクはありません。</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium mb-4 text-red-600">長期未対応顧客アラート</h3>
                            <div class="space-y-2">
                                @forelse ($long_term_untouched_customers as $customer)
                                    <div>
                                        <a href="{{ route('customers.show', $customer) }}"
                                            class="text-blue-600 hover:underline">
                                            {{ $customer->name }}
                                        </a>
                                        <p class="text-sm text-gray-500">最終更新: {{ $customer->updated_at->diffForHumans() }}
                                        </p>
                                    </div>
                                @empty
                                    <p class="text-gray-500">長期未対応の顧客はいません。</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium mb-4">ステータス別顧客数</h3>
                            <ul>
                                @forelse ($status_counts as $status_count)
                                    <li class="flex justify-between">
                                        <span>{{ $status_count->status->name }}</span>
                                        <span class="font-bold">{{ $status_count->count }}人</span>
                                    </li>
                                @empty
                                    <p class="text-gray-500">データがありません。</p>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>