<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            反響テキストから自動登録
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('customers.autoStore') }}" method="POST">
                        @csrf
                        <div>
                            <label for="source_text" class="block font-medium text-sm text-gray-700">反響テキスト</label>
                            <textarea name="source_text" id="source_text" rows="20" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="ここにSNSやポータルサイトからの反響テキストを貼り付けてください..."></textarea>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                解析して登録する
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>