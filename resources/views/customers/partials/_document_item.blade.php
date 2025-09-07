<div class="p-4 border rounded-md flex items-start justify-between">
    <div class="flex items-center gap-4">
        @php
            $icon_color = 'text-gray-500'; $badge_color = 'bg-gray-100 text-gray-800';
            switch ($document->type) {
                case '図面': $icon_color = 'text-blue-500'; $badge_color = 'bg-blue-100 text-blue-800'; break;
                case '申込書': $icon_color = 'text-yellow-500'; $badge_color = 'bg-yellow-100 text-yellow-800'; break;
                case '契約書': $icon_color = 'text-green-500'; $badge_color = 'bg-green-100 text-green-800'; break;
            }
        @endphp
        <div class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center flex-shrink-0">
            <svg class="w-8 h-8 {{ $icon_color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
        </div>
        <div class="flex-grow">
            <div class="flex items-center gap-2">
                <a href="{{ route('documents.preview', $document) }}" target="_blank" class="font-semibold text-blue-600 hover:underline">{{ $document->display_name }}</a>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $badge_color }}">{{ $document->type }}</span>
            </div>
            @if ($document->memo)
            <p class="mt-1 text-sm text-gray-600 border-l-2 border-gray-300 pl-2">メモ: {{ Str::limit($document->memo, 100) }}</p>
            @endif
            <p class="text-sm text-gray-500">担当: {{ $document->user->name }} | {{ $document->created_at->format('Y/m/d') }}</p>
        </div>
    </div>
    <div class="flex items-center gap-4 flex-shrink-0">
        <a href="{{ route('documents.download', $document) }}" class="text-gray-500 hover:text-gray-700 text-sm">DL</a>
        <a href="{{ route('documents.edit', $document) }}" class="text-gray-500 hover:text-gray-700 text-sm">編集</a>
        <form action="{{ route('documents.destroy', $document) }}" method="POST" onsubmit="return confirm('本当にこの書類を削除しますか？');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">削除</button>
        </form>
    </div>
</div>