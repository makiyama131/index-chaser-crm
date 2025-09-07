<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'type' => 'required|string|max:50',
            'note' => 'required|string',
        ]);

        Activity::create([
            'customer_id' => $request->customer_id,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'note' => $request->note,
        ]);

        return redirect()->route('customers.show', $request->customer_id)
                         ->with('success', '活動記録を追加しました。');
    }
}