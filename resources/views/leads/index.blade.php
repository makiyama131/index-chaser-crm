<x-app-layout>
    <div x-data="{ 
        isModalOpen: false, 
        modalType: '', 
        customer: null,
        openModal(type, customerData) {
            this.modalType = type;
            this.customer = customerData;
            this.isModalOpen = true;
        }
    }">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                リード管理 (新規・追客リスト)
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="mb-6 bg-white p-6 rounded-xl shadow-sm">
                    <form action="{{ route('leads.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        {{-- Keyword Search --}}
                        <div class="md:col-span-3">
                            <label for="keyword" class="block text-sm font-medium">検索キーワード</label>
                            <input type="text" name="keyword" value="{{ $keyword ?? '' }}" placeholder="名前、電話番号などで検索..." class="mt-1 w-full rounded-md border-gray-300">
                        </div>
                        {{-- Assignment Filter --}}
                        <div>
                            <label for="assignment_filter" class="block text-sm font-medium">担当者</label>
                            <select name="assignment_filter" class="mt-1 w-full rounded-md border-gray-300" onchange="this.form.submit()">
                                <option value="all_leads" @selected(($assignment_filter ?? 'all_leads') == 'all_leads')>全てのリード</option>
                                <option value="unassigned" @selected(($assignment_filter ?? '') == 'unassigned')>未担当のみ</option>
                                <option value="my_leads" @selected(($assignment_filter ?? '') == 'my_leads')>自分の担当のみ</option>
                            </select>
                        </div>
                        {{-- Sorting --}}
                        <div>
                            <label for="sort" class="block text-sm font-medium">並び替え</label>
                            <select name="sort" class="mt-1 w-full rounded-md border-gray-300" onchange="this.form.submit()">
                                <option value="created_at_desc" @selected(($sort ?? 'created_at_desc') == 'created_at_desc')>反響が新しい順</option>
                                <option value="updated_at_desc" @selected(($sort ?? '') == 'updated_at_desc')>更新が新しい順</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-800 border rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700">絞り込み</button>
                        </div>
                    </form>
                </div>


                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg border border-green-200" role="alert">
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase">顧客情報</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase">ステータス</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase">最終活動</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase">担当者</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase">反響日</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase">アクション</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($leads as $lead)
                                        @php $lastActivity = $lead->activities->first(); @endphp
                                        <tr>
                                            <td class="px-6 py-4"><a href="{{ route('customers.show', $lead) }}" class="text-blue-600 hover:underline font-semibold">{{ $lead->name }}</a><div class="text-sm text-gray-500">{{ $lead->phone }}</div></td>
                                            <td class="px-6 py-4">{{ $lead->status->name }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                @if ($lastActivity)
                                                    <div class="font-semibold">{{ $lastActivity->type }}</div>
                                                    <div class="text-xs text-gray-500">{{ Str::limit($lastActivity->note, 30) }}</div>
                                                @else
                                                    <span class="text-xs text-gray-400">活動なし</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">{{ $lead->user->name ?? '未担当' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $lead->created_at->format('Y/m/d') }}</td>
                                            <td class="px-6 py-4 space-x-2">
                                                @if ($lead->user_id === null)
                                                    <form action="{{ route('leads.assign', $lead) }}" method="POST" class="inline-block"> @csrf @method('PATCH') <button type="submit" class="text-xs bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded">自分に割当</button></form>
                                                @endif
                                                <button @click="openModal('activity', {{ json_encode($lead) }})" class="text-xs bg-gray-500 hover:bg-gray-700 text-white py-1 px-2 rounded">活動記録</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">対応が必要なリードはありません。</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $leads->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div x-show="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 z-40" @click="isModalOpen = false" x-cloak></div>
        <div x-show="isModalOpen" x-transition class="fixed inset-0 flex items-center justify-center z-50" x-cloak>
            <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-lg" @click.away="isModalOpen = false">
                
                <div x-show="modalType === 'activity'">
                    <h3 class="text-lg font-medium mb-4" x-text="`活動記録: ${customer.name}`"></h3>
                    <form :action="'{{ url('activities') }}'" method="POST">
                        @csrf
                        <input type="hidden" name="customer_id" :value="customer.id">
                        <select name="type" class="w-full rounded-md border-gray-300" required>
                            <option>電話</option><option>メール</option><option>SMS</option><option>来店</option><option>その他</option>
                        </select>
                        <textarea name="note" rows="4" class="mt-4 w-full rounded-md border-gray-300" placeholder="対応メモ..." required></textarea>
                        <div class="mt-4 text-right">
                            <button type="button" @click="isModalOpen = false" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">キャンセル</button>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">記録を追加</button>
                        </div>
                    </form>
                </div>

                <div x-show="modalType === 'status'">
                    </div>

            </div>
        </div>

    </div>
</x-app-layout>