<x-app-layout>
    {{-- Header Section --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-4">
                {{-- Customer Avatar --}}
                <div
                    class="w-16 h-16 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center border-2 border-primary-200">
                    <span class="text-2xl font-bold">{{ substr($customer->name, 0, 1) }}</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-neutral-800">{{ $customer->name }}</h1>
                    <p class="text-sm text-neutral-500 mt-1">顧客詳細 / Customer Details</p>
                </div>
            </div>
            {{-- Action Buttons --}}
            <div class="flex items-center space-x-3">
                <a href="{{ route('customers.edit', $customer) }}"
                    class="btn-base bg-white hover:bg-neutral-50 text-neutral-700 border border-neutral-300 shadow-sm">
                    {{-- Heroicon: pencil --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L14.732 3.732z" />
                    </svg>
                    編集
                </a>
                <form onsubmit="return confirm('本当にこの顧客情報を削除しますか？');"
                    action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-base bg-danger-600 hover:bg-danger-700 text-white shadow-sm">
                        {{-- Heroicon: trash --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        削除
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-neutral-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="animate-fade-in flex items-center p-4 mb-6 text-sm text-success-800 rounded-lg bg-success-100 border border-success-200"
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

            <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 animate-fade-in-up">

                {{-- Sidebar (Customer Info) --}}
                <div class="xl:col-span-1 space-y-6">
                    {{-- Basic Info Card --}}
                    <div class="bg-white p-6 rounded-xl shadow-soft border border-neutral-200/50">
                        <h3 class="text-lg font-semibold text-neutral-800 mb-4">基本情報</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="rank" class="block text-sm font-medium text-neutral-600 mb-1">顧客温度</label>
                                <form action="{{ route('customers.updateRank', $customer) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <select name="rank" id="rank"
                                        class="w-full rounded-lg border-neutral-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm"
                                        onchange="this.form.submit()">
                                        <option value="A" @selected($customer->rank == 'A')>A: 今すぐ</option>
                                        <option value="B" @selected($customer->rank == 'B')>B: 検討中</option>
                                        <option value="C" @selected($customer->rank == 'C')>C: 情報収集</option>
                                    </select>
                                </form>
                            </div>
                            <div>
                                <label for="status"
                                    class="block text-sm font-medium text-neutral-600 mb-1">ステータス</label>
                                <form action="{{ route('customers.updateStatus', $customer) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <select name="status_id" id="status"
                                        class="w-full rounded-lg border-neutral-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm"
                                        onchange="this.form.submit()">
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status->id }}" @selected($customer->status_id == $status->id)>
                                                {{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div class="space-y-3 pt-5 mt-5 border-t border-neutral-200">
                            <div>
                                <p class="text-xs text-neutral-500">担当者</p>
                                <p class="text-sm font-medium text-neutral-800">{{ $customer->user->name ?? '未設定' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-neutral-500">メールアドレス</p>
                                <p class="text-sm font-medium text-neutral-800 break-all">
                                    {{ $customer->email ?? '未登録' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-neutral-500">電話番号</p>
                                <p class="text-sm font-medium text-neutral-800">{{ $customer->phone ?? '未登録' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Tags Card --}}
                    <div class="bg-white p-6 rounded-xl shadow-soft border border-neutral-200/50">
                        <h3 class="text-lg font-semibold text-neutral-800 mb-4">タグ</h3>
                        <div class="flex flex-wrap gap-2">
                            @forelse ($customer->tags as $tag)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                    {{ $tag->name }}
                                </span>
                            @empty
                                <p class="text-sm text-neutral-500">タグがありません</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Main Content --}}
                <div class="xl:col-span-3 space-y-6">
                    {{-- Activity Feed --}}
                    <div class="bg-white rounded-xl shadow-soft border border-neutral-200/50">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-neutral-800 mb-4">活動記録</h3>
                            <form action="{{ route('activities.store') }}" method="POST" class="space-y-3 mb-6">
                                @csrf
                                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                <textarea name="note" rows="3"
                                    class="w-full rounded-lg border-neutral-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm"
                                    placeholder="対応内容を入力..." required></textarea>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <select name="type"
                                        class="w-full sm:w-1/2 rounded-lg border-neutral-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm"
                                        required>
                                        <option>電話</option>
                                        <option>メール</option>
                                        <option>SMS</option>
                                        <option>来店</option>
                                        <option>その他</option>
                                    </select>
                                    <button type="submit"
                                        class="btn-base w-full sm:w-auto justify-center bg-primary-600 hover:bg-primary-700 text-white shadow-sm">記録を追加</button>
                                </div>
                            </form>
                            {{-- Activity List --}}
                            <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                @forelse ($customer->activities->sortByDesc('created_at') as $activity)
                                    <div class="p-4 rounded-lg bg-neutral-50 border border-neutral-200">
                                        <div class="flex justify-between items-center mb-1">
                                            <p
                                                class="text-xs font-semibold text-primary-700 bg-primary-100 px-2 py-0.5 rounded-full">
                                                {{ $activity->type }}</p>
                                            <p class="text-xs text-neutral-500">
                                                {{ $activity->created_at->format('Y/m/d H:i') }} by
                                                {{ $activity->user->name }}</p>
                                        </div>
                                        <p class="text-sm text-neutral-700 whitespace-pre-wrap">{{ $activity->note }}</p>
                                    </div>
                                @empty
                                    <div class="text-center py-10">
                                        <p class="text-neutral-500">まだ活動記録がありません。</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Tasks & Documents --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Task Manager --}}
                        <div class="bg-white p-6 rounded-xl shadow-soft border border-neutral-200/50">
                            <h3 class="text-lg font-semibold text-neutral-800 mb-4">タスク管理</h3>
                            <form action="{{ route('tasks.store') }}" method="POST" class="space-y-2 mb-4">
                                @csrf
                                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                <input type="text" name="title" placeholder="新しいタスク..."
                                    class="w-full rounded-lg border-neutral-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm"
                                    required>
                                <div class="flex gap-2">
                                    <input type="datetime-local" name="due_date"
                                        class="w-full rounded-lg border-neutral-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm">
                                    <button type="submit"
                                        class="btn-base justify-center bg-neutral-800 hover:bg-neutral-700 text-white shadow-sm px-4">追加</button>
                                </div>
                            </form>
                            <div class="space-y-2 max-h-80 overflow-y-auto pr-2">
                                @forelse ($customer->tasks->sortByDesc('created_at') as $task)
                                    <div
                                        class="p-3 rounded-lg flex items-center gap-3 @if($task->status == '完了') bg-neutral-100 @else border border-neutral-200 @endif">
                                        @if ($task->status != '完了')
                                            <a href="{{ route('tasks.showCompleteForm', $task) }}"
                                                class="flex-shrink-0 w-5 h-5 border-2 border-neutral-300 rounded-full hover:bg-success-100 hover:border-success-400 transition"></a>
                                        @else
                                            <div
                                                class="flex-shrink-0 w-5 h-5 bg-success-500 rounded-full flex items-center justify-center">
                                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-grow">
                                            <p
                                                class="text-sm font-medium @if($task->status == '完了') text-neutral-500 line-through @else text-neutral-800 @endif">
                                                {{ $task->title }}</p>
                                            @if ($task->due_date)
                                                <p
                                                    class="text-xs @if($task->status != '完了' && $task->due_date->isPast()) text-danger-600 font-semibold @else text-neutral-500 @endif">
                                                    期限: {{ $task->due_date->format('m/d H:i') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <p class="text-sm text-neutral-500">タスクはありません。</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Document Manager --}}
                        <div class="bg-white p-6 rounded-xl shadow-soft border border-neutral-200/50">
                            <h3 class="text-lg font-semibold text-neutral-800 mb-4">書類管理</h3>
                            <div class="bg-neutral-50 p-4 rounded-lg mb-6 border border-neutral-200">
                                <form action="{{ route('documents.store') }}" method="POST"
                                    enctype="multipart/form-data" class="space-y-3">
                                    @csrf
                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                    <input type="text" name="display_name" placeholder="書類名"
                                        class="w-full rounded-lg border-neutral-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm"
                                        required>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <select name="type"
                                            class="w-full rounded-lg border-neutral-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm"
                                            required>
                                            <option>図面</option>
                                            <option>申込書</option>
                                            <option>契約書</option>
                                            <option>身分証</option>
                                            <option>その他</option>
                                        </select>
                                        <input type="file" name="document_file"
                                            class="block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100"
                                            required>
                                    </div>
                                    <button type="submit"
                                        class="btn-base w-full justify-center bg-primary-600 hover:bg-primary-700 text-white shadow-sm">アップロード</button>
                                </form>
                            </div>
                            <div class="space-y-3 max-h-80 overflow-y-auto pr-2">
                                @forelse ($customer->documents->sortByDesc('created_at') as $document)
                                    <div
                                        class="p-3 rounded-lg border border-neutral-200 hover:bg-neutral-50 hover:border-neutral-300 transition-all duration-200">
                                        <div class="flex items-start justify-between gap-3">
                                            {{-- Icon & Info --}}
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="flex-shrink-0 w-10 h-10 bg-neutral-100 rounded-lg flex items-center justify-center">
                                                    {{-- Heroicon: document-text --}}
                                                    <svg class="w-6 h-6 text-neutral-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="flex-grow">
                                                    <a href="{{ route('documents.preview', $document) }}" target="_blank"
                                                        class="text-sm font-semibold text-primary-600 hover:underline">{{ $document->display_name }}</a>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">{{ $document->type }}</span>
                                                        <p class="text-xs text-neutral-500">
                                                            {{ $document->created_at->format('Y/m/d') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Actions --}}
                                            <div class="flex items-center gap-2 flex-shrink-0">
                                                <a href="{{ route('documents.download', $document) }}" title="ダウンロード"
                                                    class="text-neutral-400 hover:text-primary-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('documents.destroy', $document) }}" method="POST"
                                                    onsubmit="return confirm('本当にこの書類を削除しますか？');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" title="削除"
                                                        class="text-neutral-400 hover:text-danger-600">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <p class="text-sm text-neutral-500">書類はまだありません。</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</x-app-layout>