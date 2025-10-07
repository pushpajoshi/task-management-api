<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class ReportController extends Controller
{
   

    public function tasksSummary(Request $request)
{
    $authUser = Auth::user();
   $cacheKey = $authUser->role === 'admin' 
    ? 'reports:tasks-summary' 
    : 'reports:tasks-summary:user:' . $authUser->id;

    $result = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($authUser) {

        if ($authUser->role == 'admin') {
            // Admin: all tasks
            $totalPerStatus = Task::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->get();

            $overdue = Task::where('due_date', '<', now()->toDateString())
                ->where('status', '!=', 'done')
                ->count();

            $tasksPerUser = Task::selectRaw('user_id, count(*) as total')
                ->groupBy('user_id')
                  ->with([
                    'user' => fn($q) => $q->simpleDetails(),
                ])->whereHas('user')->get();

            return [
                'total_per_status' => $totalPerStatus,
                'overdue_count'    => $overdue,
                'tasks_per_user'   => $tasksPerUser
            ];
        } else {
            // Non-admin: only their tasks
            $totalPerStatus = Task::where('user_id', $authUser->id)
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->get();

            $overdue = Task::where('user_id', $authUser->id)
                ->where('due_date', '<', now()->toDateString())
                ->where('status', '!=', 'done')
                ->count();

            return [
                'total_per_status' => $totalPerStatus,
                'overdue_count'    => $overdue,
            ];
        }
    });

    return send_response(200, __('api.succ'),$result);   
}


    public function activityLogs(Request $request){
        $query = ActivityLog::query();
        $authUser = Auth::user();
        if($authUser->role != 'admin'){
            $query = $query->where('user_id',$authUser->id);
        }
        if($request->user_id && $authUser->role == 'admin') 
        {
            $query = $query->where('user_id',$request->user_id);
        }
        if($request->from && $request->to) {
            $request->validate([
            'from' => 'date_format:Y-m-d',
            'to'   => 'date_format:Y-m-d',
        ]);
        $query = $query->whereDate('created_at', '>=', $request->from)->whereDate('created_at', '<=', $request->to);
        }
        $logs = $query->paginate(10);
        return send_response(200, __('api.succ'),$logs);   

    }
}
