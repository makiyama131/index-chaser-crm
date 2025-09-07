{{-- resources/views/tasks/complete.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            タスク完了報告
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium">タスク: {{ $task->title }}</h3>
                    <p class="text-sm text-gray-600 mb-6">顧客: {{ $task->customer->name }}</p>

                    <form action="{{ route('tasks.complete', $task) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <label for="note" class="block text-sm font-medium text-gray-700">対応メモ（活動記録として保存されます）</label>
                            <textarea id="note" name="note" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                対応を記録してタスクを完了
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>