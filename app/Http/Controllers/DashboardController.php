<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Get overdue tasks (due date is in the past and status is not '完了')
        $overdue_tasks = Task::where('user_id', $user->id)
            ->where('status', '!=', '完了')
            ->whereNotNull('due_date')
            ->where('due_date', '<', Carbon::now())
            ->with('customer')
            ->get();

        // 1. Get all incomplete tasks for the logged-in user
        $incomplete_tasks = Task::where('user_id', $user->id)
            ->where('status', '!=', '完了')
            ->with('customer') // Eager load customer info
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Get customers who haven't been updated in 14 days
        $long_term_untouched_customers = Customer::where('user_id', $user->id)
            ->where('updated_at', '<', Carbon::now()->subDays(14))
            ->get();

        // 3. Get customer counts grouped by status
        $status_counts = Customer::where('user_id', $user->id)
            ->select('status_id', DB::raw('count(*) as count'))
            ->groupBy('status_id')
            ->with('status') // Eager load status names
            ->get();

        return view('dashboard', [
            'overdue_tasks' => $overdue_tasks, // Pass new data
            'incomplete_tasks' => $incomplete_tasks,
            'long_term_untouched_customers' => $long_term_untouched_customers,
            'status_counts' => $status_counts,
        ]);
    }
}
