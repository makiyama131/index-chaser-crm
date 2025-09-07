{{-- resources/views/documents/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            書類情報の編集
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('documents.update', $document) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        {{-- 表示名 --}}
                        <div>
                            <label for="display_name" class="block text-sm font-medium text-gray-700">表示名</label>
                            <input type="text" name="display_name" id="display_name" value="{{ old('display_name', $document->display_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        {{-- メモ --}}
                        <div class="mt-4">
                            <label for="memo" class="block text-sm font-medium text-gray-700">メモ（任意）</label>
                            <textarea name="memo" id="memo" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('memo', $document->memo) }}</textarea>
                        </div>

                        <div class="mt-6 flex items-center gap-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                更新する
                            </button>
                            <a href="{{ route('customers.show', $document->customer_id) }}" class="text-gray-600 hover:text-gray-900">
                                キャンセル
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>