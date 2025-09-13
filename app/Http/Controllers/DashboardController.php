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
        $today = Carbon::today();

        $baseTaskQuery = fn() => Task::where('user_id', $user->id)->where('status', '!=', '完了')->with('customer');

        // Data for Task Agenda
        $overdue_tasks = $baseTaskQuery()->whereNotNull('due_date')->where('due_date', '<', $today)->orderBy('due_date', 'asc')->get();
        $today_tasks = $baseTaskQuery()->whereDate('due_date', $today)->orderBy('due_date', 'asc')->get();
        $this_week_tasks = $baseTaskQuery()->whereBetween('due_date', [Carbon::tomorrow()->startOfDay(), $today->copy()->endOfWeek()])->orderBy('due_date', 'asc')->get();
        $future_tasks = $baseTaskQuery()->where('due_date', '>', $today->copy()->endOfWeek())->orderBy('due_date', 'asc')->get();
        $no_due_date_tasks = $baseTaskQuery()->whereNull('due_date')->orderBy('created_at', 'desc')->get();

        // Data for Sidebar & Summary (This adds the missing variables)
        $long_term_untouched_customers = Customer::where('user_id', $user->id)->where('updated_at', '<', Carbon::now()->subDays(14))->get();
        $status_counts = Customer::where('user_id', $user->id)->select('status_id', DB::raw('count(*) as count'))->groupBy('status_id')->with('status')->get();
        $incomplete_tasks = Task::where('user_id', $user->id)->where('status', '!=', '完了')->get();

        return view('dashboard', [
            'overdue_tasks' => $overdue_tasks,
            'today_tasks' => $today_tasks,
            'this_week_tasks' => $this_week_tasks,
            'future_tasks' => $future_tasks,
            'no_due_date_tasks' => $no_due_date_tasks,
            'long_term_untouched_customers' => $long_term_untouched_customers,
            'status_counts' => $status_counts,
            'incomplete_tasks' => $incomplete_tasks, // Pass the missing variable
        ]);
    }
}
