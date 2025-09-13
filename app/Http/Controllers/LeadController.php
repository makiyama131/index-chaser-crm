<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Status; // Add this
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    /**
     * Display a list of unassigned leads.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Customer::with(['user', 'status', 'activities' => fn($q) => $q->latest()]);

        // --- Keyword Search ---
        if ($keyword = $request->keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        // --- Assignment Filter ---
        $assignment_filter = $request->input('assignment_filter', 'all_leads'); // Default
        switch ($assignment_filter) {
            case 'unassigned':
                $query->whereNull('user_id');
                break;
            case 'my_leads':
                $query->where('user_id', $user->id);
                break;
            default: // 'all_leads'
                // No additional user filter needed for all leads
                break;
        }

        // --- Sorting ---
        $sort = $request->input('sort', 'created_at_desc'); // Default to newest created
        if (str_starts_with($sort, 'status_')) {
            // ... (sorting by status name)
        } else {
            switch ($sort) {
                case 'updated_at_desc':
                    $query->orderBy('updated_at', 'desc');
                    break;
                // ... (add other sorting cases as in CustomerController)
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        // We also need to keep the original logic to only show early-stage leads
        $earlyStageStatusIds = Status::whereIn('name', ['新規反響', '初動ライン', 'ラインラリー'])->pluck('id');
        $query->whereIn('status_id', $earlyStageStatusIds);


        $leads = $query->paginate(20)->withQueryString();
        $statuses = Status::all();

        return view('leads.index', [
            'leads' => $leads,
            'statuses' => $statuses,
            'keyword' => $keyword,
            'sort' => $sort,
            'assignment_filter' => $assignment_filter,
        ]);
    }

    /**
     * Assign a lead to the currently authenticated user.
     */
    public function assign(Customer $customer)
    {
        // Check if the lead is already assigned to prevent race conditions
        if ($customer->user_id !== null) {
            return back()->with('error', 'This lead has already been assigned.');
        }

        // Assign the customer to the current user
        $customer->user_id = Auth::id();
        $customer->save();

        return redirect()->route('customers.show', $customer)
            ->with('success', "Lead '{$customer->name}' has been assigned to you.");
    }
}
