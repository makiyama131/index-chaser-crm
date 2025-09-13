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

        {{-- Header Section --}}
        <x-slot name="header">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="font-bold text-2xl text-neutral-800 leading-tight">
                        {{ __('顧客一覧') }}
                    </h2>
                    <p class="text-sm text-neutral-500 mt-1">
                        大切な顧客情報を管理します。
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center space-x-3">
                    <a href="{{ route('customers.autoCreate') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                        自動登録
                    </a>
                    <a href="{{ route('customers.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        新規登録
                    </a>
                </div>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                @if (session('success'))
                    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg border border-green-200"
                        role="alert">
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Search & Filter Panel --}}
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <form action="{{ route('customers.index') }}" method="GET"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div class="md:col-span-2">
                            <label for="keyword" class="block text-sm font-medium text-neutral-700 mb-1">検索キーワード</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><svg
                                        class="h-5 w-5 text-neutral-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg></div><input type="text" name="keyword" id="keyword"
                                    value="{{ request('keyword', '') }}" placeholder="氏名、電話番号..."
                                    class="w-full pl-10 rounded-lg border-neutral-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            </div>
                        </div>
                        <div>
                            <label for="sort" class="block text-sm font-medium text-neutral-700 mb-1">並び替え</label>


                            <select name="sort" id="sort"
                            class="w-full rounded-lg border-neutral-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                            onchange="this.form.submit()">
                            <optgroup label="時間順">
                                <option value="updated_at_desc" @selected(request('sort', 'updated_at_desc') == 'updated_at_desc')>更新が新しい順</option>
                                <option value="updated_at_asc" @selected(request('sort') == 'updated_at_asc')>更新が古い順</option>
                                <option value="created_at_desc" @selected(request('sort') == 'created_at_desc')>登録が新しい順</option>
                                <option value="created_at_asc" @selected(request('sort') == 'created_at_asc')>登録が古い順</option>
                            </optgroup>
                            <optgroup label="属性順">
                                <option value="status_asc" @selected(request('sort') == 'status_asc')>ステータス順 (昇順)</option>
                                <option value="status_desc" @selected(request('sort') == 'status_desc')>ステータス順 (降順)</option>
                                <option value="rank_asc" @selected(request('sort') == 'rank_asc')>温度感順 (A→C)</option>
                                <option value="rank_desc" @selected(request('sort') == 'rank_desc')>温度感順 (C→A)</option>
                                <option value="name_asc" @selected(request('sort') == 'name_asc')>氏名順 (昇順)</option>
                                <option value="name_desc" @selected(request('sort') == 'name_desc')>氏名順 (降順)</option>
                            </optgroup>
                            </select>
                        </div>
                        <div><button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"><svg
                                    class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>検索</button></div>
                    </form>
                </div>

                {{-- Customer Table Card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-neutral-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">顧客情報
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">顧客温度感
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">ステータス
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">担当者
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">最終更新
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">アクション
                                    </th>
                                </tr>
                            </thead>
                            @forelse ($customers as $customer)
                                <tbody x-data="{ isExpanded: false }" class="border-t border-neutral-200">
                                    <tr class="hover:bg-neutral-50/50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 text-2xl flex items-center justify-center">
                                                    {{ $customer->icon_emoji ?? '👤' }}</div>
                                                <div class="ml-4"><a href="{{ route('customers.show', $customer) }}"
                                                        class="text-sm font-medium text-gray-900 hover:underline">{{ $customer->name }}</a>
                                                    <div class="text-sm text-gray-500">{{ $customer->characteristic_memo }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php $rankColor = ['A' => 'bg-red-100 text-red-800', 'B' => 'bg-yellow-100 text-yellow-800', 'C' => 'bg-blue-100 text-blue-800',][$customer->rank] ?? 'bg-gray-100 text-gray-800'; @endphp
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $rankColor }}">{{ $customer->rank }}ランク</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                            {{ $customer->status->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                            {{ $customer->user->name ?? '未担当' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                            {{ $customer->updated_at->diffForHumans() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <button @click="openModal('activity', {{ json_encode($customer) }})"
                                                class="inline-flex items-center justify-center p-2 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                                                title="活動記録">活動記録</button>
                                            <button @click="openModal('status', {{ json_encode($customer) }})"
                                                class="inline-flex items-center justify-center p-2 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600"
                                                title="状態変更">ステータス変更</button>
                                            <button @click="isExpanded = !isExpanded"
                                                class="inline-flex items-center justify-center p-2 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                                title="詳細表示"><span x-show="!isExpanded">詳細</span><span
                                                    x-show="isExpanded">詳細</span></button>
                                        </td>
                                    </tr>
                                    <tr x-show="isExpanded" x-transition>
                                        <td colspan="6" class="p-4 bg-neutral-50">
                                            <div class="p-3 bg-white rounded-md border">
                                                <div class="flex justify-between items-center mb-2">
                                                    <h4 class="text-xs font-semibold text-gray-600">顧客メモ・希望条件</h4><button
                                                        @click="openModal('memo', {{ json_encode($customer) }})"
                                                        class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold">[編集]</button>
                                                </div>
                                                <div
                                                    class="text-sm text-gray-700 grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                                                    <p><strong>電話番号:</strong> {{ $customer->phone ?? 'N/A' }}</p>
                                                    <p><strong>メール:</strong> {{ $customer->email ?? 'N/A' }}</p>
                                                    <p><strong>希望家賃:</strong> {{ $customer->desired_rent_from ?? '指定なし' }} 〜
                                                        {{ $customer->desired_rent_to ?? '指定なし' }}</p>
                                                    <p><strong>希望間取り:</strong> {{ $customer->desired_layout ?? 'N/A' }}</p>
                                                    <p class="md:col-span-2"><strong>希望エリア:</strong>
                                                        {{ $customer->desired_stations ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            @empty
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center py-12">
                                            <div class="text-neutral-500">
                                                <p class="font-semibold text-lg">顧客が見つかりません</p>
                                                <p class="mt-2 text-sm">検索条件を変更するか、新しい顧客を登録してください。</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforelse
                        </table>
                    </div>
                    @if ($customers->hasPages())
                    <div class="p-4 border-t border-neutral-200">{{ $customers->links() }}</div>@endif
                </div>
            </div>
        </div>

        <div x-show="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 z-40" @click="isModalOpen = false"
            x-cloak></div>
        <div x-show="isModalOpen" x-transition class="fixed inset-0 flex items-center justify-center z-50" x-cloak>
            <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-lg" @click.away="isModalOpen = false">
                <div x-show="modalType === 'activity'">
                    <h3 class="text-lg font-semibold mb-4" x-text="`活動記録: ${customer.name}`"></h3>
                    <form :action="'{{ url('activities') }}'" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="customer_id" :value="customer.id"><input type="hidden"
                            name="redirect_to" value="{{ route('customers.index') }}">
                        <select name="type" class="w-full rounded-lg border-neutral-300" required>
                            <option>電話</option>
                            <option>メール</option>
                            <option>SMS</option>
                            <option>来店</option>
                            <option>その他</option>
                        </select>
                        <textarea name="note" rows="4" class="w-full rounded-lg border-neutral-300"
                            placeholder="対応メモ..." required></textarea>
                        <div class="mt-4 text-right space-x-2"><button type="button" @click="isModalOpen = false"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase hover:bg-gray-50">キャンセル</button><button
                                type="submit"
                                class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700">記録を追加</button>
                        </div>
                    </form>
                </div>
                <div x-show="modalType === 'status'">
                    <h3 class="text-lg font-semibold mb-4" x-text="`ステータス変更: ${customer.name}`"></h3>
                    <form :action="'{{ url('customers') }}/' + customer.id + '/update-status'" method="POST">
                        @csrf @method('PATCH')
                        <select name="status_id" class="w-full rounded-lg border-neutral-300" required>
                            @foreach ($statuses as $status)<option :value="{{ $status->id }}"
                                :selected="customer.status_id == {{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                        <div class="mt-4 text-right space-x-2"><button type="button" @click="isModalOpen = false"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase hover:bg-gray-50">キャンセル</button><button
                                type="submit"
                                class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700">更新する</button>
                        </div>
                    </form>
                </div>
                <div x-show="modalType === 'memo'">
                    <h3 class="text-lg font-semibold mb-4" x-text="`メモ・アイコン編集: ${customer.name}`"></h3>
                    <form :action="'{{ url('customers') }}/' + customer.id + '/update-memo'" method="POST"
                        class="space-y-4">
                        @csrf @method('PATCH')
                        <div><label for="modal_icon_emoji" class="block text-sm font-medium text-gray-700">アイコン
                                (絵文字)</label><input type="text" id="modal_icon_emoji" name="icon_emoji"
                                :value="customer.icon_emoji" placeholder="例: 😀, 🚗, 🏠"
                                class="mt-1 block w-full rounded-lg border-neutral-300 shadow-sm"></div>
                        <div><label for="modal_characteristic_memo"
                                class="block text-sm font-medium text-gray-700">特徴メモ</label><input type="text"
                                id="modal_characteristic_memo" name="characteristic_memo"
                                :value="customer.characteristic_memo" placeholder="例: 岡山から上京"
                                class="mt-1 block w-full rounded-lg border-neutral-300 shadow-sm"></div>
                        <div class="mt-6 text-right space-x-2"><button type="button" @click="isModalOpen = false"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase hover:bg-gray-50">キャンセル</button><button
                                type="submit"
                                class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700">更新</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>