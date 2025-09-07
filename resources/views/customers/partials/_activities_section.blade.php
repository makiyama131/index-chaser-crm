<div>
    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">活動記録</h3>
    <div class="bg-gray-50 p-4 rounded-lg mb-6 border">
        <h4 class="font-semibold mb-2">活動を記録する</h4>
        <form action="{{ route('activities.store') }}" method="POST">
            @csrf
            <input type="hidden" name="customer_id" value="{{ $customer->id }}">
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">活動の種類</label>
                <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option>電話</option><option>メール</option><option>SMS</option><option>来店</option><option>その他</option>
                </select>
            </div>
            <div class="mt-4">
                <label for="note" class="block text-sm font-medium text-gray-700">対応メモ</label>
                <textarea id="note" name="note" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="顧客とのやり取りや、ヒアリング内容などを記録します。" required></textarea>
            </div>
            <div class="mt-4"><button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">記録を追加</button></div>
        </form>
    </div>
     <div class="space-y-4">
        @forelse ($customer->activities->sortByDesc('created_at') as $activity)
            @include('customers.partials._activity_item')
        @empty
            <p class="text-gray-500">この顧客にはまだ活動記録がありません。</p>
        @endforelse
    </div>
</div>