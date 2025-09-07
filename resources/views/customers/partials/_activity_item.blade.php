<div class="p-4 border rounded-md bg-gray-50">
    <div class="flex justify-between items-center">
        <span class="font-semibold text-gray-800">{{ $activity->type }}</span>
        <span class="text-sm text-gray-500">{{ $activity->created_at->format('Y/m/d H:i') }}</span>
    </div>
    <p class="mt-2 text-gray-700 whitespace-pre-wrap">{{ $activity->note }}</p>
    <p class="text-sm text-right text-gray-500">記録者: {{ $activity->user->name }}</p>
</div>