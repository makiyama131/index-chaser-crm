<div>
    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">基本情報</h3>
    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 border border-green-400 rounded">
            {{ session('success') }}
        </div>
    @endif
    <dl class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-8">
        <div class="col-span-1"><dt class="text-sm font-medium text-gray-500">氏名</dt><dd class="mt-1 text-lg text-gray-900">{{ $customer->name }}</dd></div>
        <div class="col-span-1">
            <dt class="text-sm font-medium text-gray-500">顧客温度感 (確度)</dt>
            <dd class="mt-1">
                <form action="{{ route('customers.updateRank', $customer) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <select name="rank" class="text-lg text-gray-900 rounded-md border-gray-300 shadow-sm w-full" onchange="this.form.submit()">
                        <option value="A" @selected($customer->rank == 'A')>A: 今すぐ</option>
                        <option value="B" @selected($customer->rank == 'B')>B: 検討中</option>
                        <option value="C" @selected($customer->rank == 'C')>C: 情報収集</option>
                    </select>
                </form>
            </dd>
        </div>
        <div class="col-span-1">
            <dt class="text-sm font-medium text-gray-500">ステータス</dt>
            <dd class="mt-1">
                <form action="{{ route('customers.updateStatus', $customer) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <select name="status_id" class="text-lg text-gray-900 rounded-md border-gray-300 shadow-sm w-full" onchange="this.form.submit()">
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}" @selected($customer->status_id == $status->id)>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </dd>
        </div>
        <div class="col-span-1"><dt class="text-sm font-medium text-gray-500">担当者</dt><dd class="mt-1 text-lg text-gray-900">{{ $customer->user->name ?? '未設定' }}</dd></div>
        <div class="col-span-1"><dt class="text-sm font-medium text-gray-500">メールアドレス</dt><dd class="mt-1 text-lg text-gray-900">{{ $customer->email ?? '未登録' }}</dd></div>
        <div class="col-span-1"><dt class="text-sm font-medium text-gray-500">電話番号</dt><dd class="mt-1 text-lg text-gray-900">{{ $customer->phone ?? '未登録' }}</dd></div>
    </dl>
</div>