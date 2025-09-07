<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('顧客一覧') }}
            </h2>

            <div class="flex space-x-2">
                <a href="{{ route('customers.autoCreate') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                    自動登録
                </a>
                <a href="{{ route('customers.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    新規登録
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-400 rounded">
                    {{ session('success') }}
                </div>
            @endif
            <div class="mb-4">
                <form action="{{ route('customers.index') }}" method="GET">
                    <input type="text" name="keyword" placeholder="氏名で検索...">
                    <select name="rank">
                        <option value="">顧客温度感</option>
                        <option value="A">A: 今すぐ</option>
                        <option value="B">B: 検討中</option>
                        <option value="C">C: 情報収集</option>
                    </select>
                    <button type="submit">検索</button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col">氏名</th>
                            <th scope="col">顧客温度感</th>
                            <th scope="col">ステータス</th>
                            <th scope="col">担当者</th>
                            <th scope="col">最終接触日</th>
                            <th scope="col">操作</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->rank }}</td>
                                <td>{{ $customer->status->name }}</td>
                                <td>{{ $customer->user->name }}</td>
                                <td>{{ $customer->updated_at->format('Y/m/d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    {{-- ▼▼▼ この行を修正 ▼▼▼ --}}
                                    <a href="{{ route('customers.show', $customer) }}"
                                        class="text-indigo-600 hover:text-indigo-900">詳細</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>