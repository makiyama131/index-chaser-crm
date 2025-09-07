<div>
    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">書類管理</h3>
    <div class="bg-gray-50 p-4 rounded-lg mb-6 border">
        <h4 class="font-semibold mb-2">新しい書類をアップロード</h4>
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="customer_id" value="{{ $customer->id }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div><label for="display_name" class="block text-sm font-medium text-gray-700">書類名</label><input type="text" name="display_name" id="display_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></div>
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">書類の種類</label>
                    <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option>図面</option><option>申込書</option><option>契約書</option><option>身分証</option><option>その他</option>
                    </select>
                </div>
                <div><label for="document_file" class="block text-sm font-medium text-gray-700">ファイル</label><input type="file" name="document_file" id="document_file" class="mt-1 block w-full text-sm" required></div>
            </div>
            <div class="mt-4 text-right"><button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">アップロード</button></div>
        </form>
    </div>
    <div class="space-y-4">
        @forelse ($customer->documents->sortByDesc('created_at') as $document)
            @include('customers.partials._document_item')
        @empty
            <p class="text-gray-500">この顧客に紐づく書類はありません。</p>
        @endforelse
    </div>
</div>