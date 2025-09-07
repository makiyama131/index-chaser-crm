<div>
    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">タスク管理</h3>
    <div class="bg-gray-50 p-4 rounded-lg mb-6 border">
         <h4 class="font-semibold mb-2">新しいタスクを追加</h4>
        <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="customer_id" value="{{ $customer->id }}">
            <div><label for="title" class="block text-sm font-medium text-gray-700">タスク内容</label><input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="例：契約書の準備をする" required></div>
            <div><label for="due_date" class="block text-sm font-medium text-gray-700">期日</label><input type="datetime-local" name="due_date" id="due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            <div><button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">タスクを追加</button></div>
        </form>
    </div>
    <div class="space-y-4">
        @forelse ($customer->tasks->sortByDesc('created_at') as $task)
            @include('customers.partials._task_item')
        @empty
            <p class="text-gray-500">この顧客にはタスクがありません。</p>
        @endforelse
    </div>
</div>