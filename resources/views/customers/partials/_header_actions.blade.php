<div class="flex justify-between items-center">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        顧客詳細
    </h2>
    <div class="flex items-center space-x-2">
        <a href="{{ route('customers.edit', $customer) }}" class="px-4 py-2 bg-yellow-500 text-white font-semibold rounded-md hover:bg-yellow-600">
            編集
        </a>
        <form onsubmit="return confirm('本当に削除しますか？');" action="{{ route('customers.destroy', $customer) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 text-white font-semibold rounded-md hover:bg-red-700">
                削除
            </button>
        </form>
    </div>
</div>