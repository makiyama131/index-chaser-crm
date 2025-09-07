<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Status; // ← これを追記
use Illuminate\Support\Facades\Auth; // ← これを追加！
use App\Models\Tag; // ← これを追記



class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::query()->with(['user', 'status']);

        // --- Comprehensive Search Logic ---
        if ($keyword = $request->keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        // --- Sorting Logic ---
        $sort = $request->input('sort', 'updated_at_desc'); // Default to newest updated
        switch ($sort) {
            case 'updated_at_asc':
                $query->orderBy('updated_at', 'asc');
                break;
            case 'created_at_desc':
                $query->orderBy('created_at', 'desc');
                break;
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            default: // 'updated_at_desc'
                $query->orderBy('updated_at', 'desc');
                break;
        }

        // Filter by the logged-in user's customers
        $customers = $query->where('user_id', Auth::id())->paginate(20)->withQueryString();

        // Pass the search and sort values back to the view
        return view('customers.index', [
            'customers' => $customers,
            'keyword' => $keyword,
            'sort' => $sort,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. バリデーション
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'rank' => 'required|in:A,B,C',
        ]);

        // 2. データベースへの保存処理
        $customer = new Customer();
        $customer->name = $validated['name'];
        $customer->email = $validated['email'];
        $customer->phone = $validated['phone'];
        $customer->rank = $validated['rank'];
        $customer->user_id = Auth::id(); // ログイン中の担当者のIDをセット
        // status_idはマイグレーションでデフォルト値(1)が設定されているので、ここでは不要

        $customer->save(); // 保存

        // 3. 顧客一覧画面にリダイレクト
        return redirect()->route('customers.index')->with('success', '顧客を登録しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        // Get a list of all statuses and pass it to the view
        $statuses = Status::all();
        return view('customers.show', compact('customer', 'statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        // 詳細画面(show)と同様に、ルートモデルバインディングで取得した顧客情報をビューに渡す
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        // 1. バリデーション
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // ★★★ 更新時のuniqueバリデーションの書き方 ★★★
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'rank' => 'required|in:A,B,C',
        ]);

        // 2. データの更新
        $customer->name = $validated['name'];
        $customer->email = $validated['email'];
        $customer->phone = $validated['phone'];
        $customer->rank = $validated['rank'];

        $customer->save(); // 保存

        // 3. 詳細画面にリダイレクト
        return redirect()->route('customers.show', $customer)->with('success', '顧客情報を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        // 1. データを削除
        $customer->delete();

        // 2. 顧客一覧画面にリダイレクト
        return redirect()->route('customers.index')->with('success', '顧客情報を削除しました。');
    }

    public function showAutoCreateForm()
    {
        return view('customers.auto-create');
    }

    public function updateRank(Request $request, Customer $customer)
    {
        $request->validate(['rank' => 'required|in:A,B,C']);
        $customer->rank = $request->rank;
        $customer->save();
        return back()->with('success', '顧客温度感を更新しました。');
    }

    /**
     * 顧客のステータスを更新する
     * ▼▼▼ このメソッドを追記 ▼▼▼
     */
    public function updateStatus(Request $request, Customer $customer)
    {
        $request->validate(['status_id' => 'required|exists:statuses,id']);
        $customer->status_id = $request->status_id;
        $customer->save();
        return back()->with('success', 'ステータスを更新しました。');
    }




    public function parseAndStore(Request $request)
    {
        $rawText = $request->input('source_text');
        // ▼▼▼ この一行で、不正な文字コードの問題を解決します ▼▼▼
        $text = mb_convert_encoding($rawText, 'UTF-8', 'UTF-8');


        // --- データ抽出 ---
        preg_match('/名前\s*(.+)/', $text, $nameMatches);
        preg_match('/電話番号\s*(\d{3,4}-\d{4}-\d{4})/', $text, $phoneMatches);
        preg_match('/生年月日\s*([0-9\-]+)/', $text, $birthDateMatches);
        preg_match('/流入経路\s*(.+)/', $text, $leadSourceMatches);
        preg_match('/転居理由\s*(.+)/', $text, $reasonMatches);
        // 「担当者へのお願い」から次のセクション（またはテキストの終わり）までを抽出
        preg_match('/担当者へのお願い\s*([\s\S]*?)(?=具体的なお引越し時期|全体管理|$)/', $text, $requestMatches);

        // --- データベースへの保存 ---
        $customer = new Customer();
        $customer->name = trim($nameMatches[1] ?? null);
        $customer->phone = $phoneMatches[1] ?? null;
        $customer->birth_date = $birthDateMatches[1] ?? null;
        $customer->lead_source_detail = trim($leadSourceMatches[1] ?? null);
        $customer->reason_for_moving = trim($reasonMatches[1] ?? null);
        $customer->agent_request = trim($requestMatches[1] ?? null);
        $customer->status_id = 1; // 新規反響
        $customer->user_id = Auth::id();
        $customer->save();

        // --- タグの処理 ---
        // 1. ◉ または ◎ で始まる行を全て抽出
        preg_match_all('/[◉◎](.+)/u', $text, $tagLines);

        $tagIds = [];
        if (!empty($tagLines[1])) {
            foreach ($tagLines[1] as $tagLine) {
                // 2. タグの文字列を整形
                $tagName = trim(preg_replace('/\s+/', ' ', $tagLine));

                // 3. データベースに同じ名前のタグがなければ新規作成、あればそれを使用
                $tag = Tag::firstOrCreate(['name' => $tagName]);

                // 4. タグのIDを配列に集める
                $tagIds[] = $tag->id;
            }
        }

        // 5. 集めたIDのタグを全て顧客に紐付ける
        if (!empty($tagIds)) {
            $customer->tags()->sync($tagIds);
        }

        return redirect()->route('customers.show', $customer)->with('success', 'テキストから顧客情報を自動登録しました。');
    }
}
