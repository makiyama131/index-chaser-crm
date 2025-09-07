{{-- resources/views/customers/partials/_task_item.blade.php --}}
<div class="p-4 border rounded-md @if($task->status == '完了') bg-gray-50 @endif">
    <div class="flex justify-between items-start">
        <div>
            <p class="font-semibold @if($task->status == '完了') text-gray-400 line-through @endif">{{ $task->title }}</p>
            @if ($task->due_date)
                <p class="text-sm @if($task->status != '完了' && $task->due_date->isPast()) text-red-600 font-bold @else text-gray-500 @endif">
                    期日: {{ $task->due_date->format('Y/m/d H:i') }}
                </p>
            @endif
            <p class="text-sm text-gray-500">担当: {{ $task->user->name }}</p>
        </div>
        @if ($task->status != '完了')
            <a href="{{ route('tasks.showCompleteForm', $task) }}" class="flex-shrink-0 text-xs bg-green-500 hover:bg-green-700 text-white py-1 px-3 rounded">完了報告</a>
        @endif
    </div>
    @if ($task->status == '完了' && $task->completionActivity)
    <div class="mt-3 pt-3 border-t border-gray-200">
        <p class="text-sm font-semibold text-green-700">完了報告 ({{ $task->completionActivity->created_at->format('Y/m/d H:i') }})</p>
        <p class="mt-1 text-sm text-gray-600 whitespace-pre-wrap">{{ $task->completionActivity->note }}</p>
    </div>
    @endif
</div>