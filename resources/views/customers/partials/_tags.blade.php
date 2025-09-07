{{-- resources/views/customers/partials/_tags.blade.php --}}
<div>
    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">タグ</h3>
    <div class="flex flex-wrap gap-2">
        @forelse ($customer->tags as $tag)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                {{ $tag->name }}
            </span>
        @empty
            <p class="text-gray-500">この顧客にはタグがありません。</p>
        @endforelse
    </div>
</div>