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
        // Eager load relationships that are always needed
        $query = Customer::with(['user', 'status']);

        // --- Comprehensive Search Logic ---
        if ($keyword = $request->keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('characteristic_memo', 'like', "%{$keyword}%")
                    ->orWhere('icon_emoji', 'like', "%{$keyword}%");
            });
        }

        // --- Expanded Sorting Logic ---
        $sort = $request->input('sort', 'updated_at_desc');

        // For status sorting, we need to join the tables
        if (str_starts_with($sort, 'status_')) {
            $direction = str_ends_with($sort, 'asc') ? 'asc' : 'desc';
            $query->join('statuses', 'customers.status_id', '=', 'statuses.id')
                ->orderBy('statuses.name', $direction)
                ->select('customers.*'); // IMPORTANT: Select only customer columns to avoid conflicts
        } else {
            // For other sorting options
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
                case 'rank_asc':
                    $query->orderBy('rank', 'asc');
                    break;
                case 'rank_desc':
                    $query->orderBy('rank', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                default: // 'updated_at_desc'
                    $query->orderBy('updated_at', 'desc');
                    break;
            }
        }

        $customers = $query->where('user_id', Auth::id())->paginate(20)->withQueryString();
        $statuses = Status::all();

        return view('customers.index', [
            'customers' => $customers,
            'statuses' => $statuses,
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
            'icon_emoji' => 'nullable|string|max:10',      // <-- Add this
            'characteristic_memo' => 'nullable|string|max:255', // <-- Add this
        ]);

        // 2. データベースへの保存処理
        $customer = new Customer();
        $customer->name = $validated['name'];
        $customer->email = $validated['email'];
        $customer->phone = $validated['phone'];
        $customer->rank = $validated['rank'];
        $customer->user_id = Auth::id(); // ログイン中の担当者のIDをセット
        // status_idはマイグレーションでデフォルト値(1)が設定されているので、ここでは不要

        $customer->status_id = 1; // Set default status to "新規反響" (New Inquiry)


        $customer->save(); // 保存

        // 3. 顧客一覧画面にリダイレクト
        return redirect()->route('customers.index')->with('success', '顧客を登録しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $statuses = Status::all();

        // --- Data for Timeline ---
        // Get past events (all activities, sorted newest first)
        $past_events = $customer->activities()->orderBy('created_at', 'desc')->get();

        // Get future events (incomplete tasks with a due date, sorted oldest first)
        $future_events = $customer->tasks()
            ->where('status', '!=', '完了')
            ->whereNotNull('due_date')
            ->orderBy('due_date', 'asc')
            ->get();

        // Get incomplete tasks without a due date
        $undated_tasks = $customer->tasks()
            ->where('status', '!=', '完了')
            ->whereNull('due_date')
            ->get();

        return view('customers.show', compact(
            'customer',
            'statuses',
            'past_events',
            'future_events',
            'undated_tasks'
        ));
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
        $text = mb_convert_encoding($rawText, 'UTF-8', 'UTF-8');

        // --- Data Extraction ---
        preg_match('/名前\s*(.+)/u', $text, $nameMatches);
        preg_match('/電話番号\s*(\d{3,4}-\d{4}-\d{4})/u', $text, $phoneMatches);
        preg_match('/生年月日\s*([0-9\-]+)/u', $text, $birthDateMatches);
        preg_match('/流入経路\s*(.+)/u', $text, $leadSourceMatches);
        preg_match('/転居理由\s*(.+)/u', $text, $reasonMatches);
        preg_match('/担当者へのお願い\s*([\s\S]*?)(?=具体的なお引越し時期|全体管理|$)/u', $text, $requestMatches);

        $phone = $phoneMatches[1] ?? null;

        // --- Database Save (using updateOrCreate) ---
        $customer = Customer::updateOrCreate(
            ['phone' => $phone], // Search for a customer with this phone number
            [
                // Update or Create with this information
                'name' => trim($nameMatches[1] ?? null),
                'birth_date' => $birthDateMatches[1] ?? null,
                'lead_source_detail' => trim($leadSourceMatches[1] ?? null),
                'reason_for_moving' => trim($reasonMatches[1] ?? null),
                'agent_request' => trim($requestMatches[1] ?? null),
                'status_id' => 1, // Always set the status to "New Inquiry"
                'user_id' => null, // Always start as unassigned
                'rank' => 'C',
            ]
        );



        // --- Tag Processing ---
        preg_match_all('/[◉◎](.+)/u', $text, $tagLines);

        $tagIds = [];
        if (!empty($tagLines[1])) {
            foreach ($tagLines[1] as $tagLine) {
                $tagName = trim(preg_replace('/\s+/', ' ', $tagLine));
                if (!empty($tagName)) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
            }
        }

        if (!empty($tagIds)) {
            $customer->tags()->sync($tagIds);
        }

        return redirect()->route('customers.show', $customer)->with('success', 'テキストから顧客情報を登録/更新しました。');
    }

    public function updateCharacteristicMemo(Request $request, Customer $customer)
    {
        // Simple authorization check
        if ($customer->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'icon_emoji' => 'nullable|string|max:10',
            'characteristic_memo' => 'nullable|string|max:255',
        ]);

        $customer->update($validated);

        return back()->with('success', '顧客情報を更新しました。');
    }
}
