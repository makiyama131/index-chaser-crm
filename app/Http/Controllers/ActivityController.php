<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer; // Add this


class ActivityController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'type' => 'required|string|max:50',
            'note' => 'required|string',
        ]);

        Activity::create([
            'customer_id' => $validated['customer_id'],
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'note' => $validated['note'],
        ]);

        return back()->with('success', '活動記録を追加しました。');
    }
}
