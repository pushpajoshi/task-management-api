<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\TaskRequest;
use App\Models\ActivityLog;
use App\Jobs\SendTaskAssignedEmail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class TaskController extends Controller
{


    public function store(TaskRequest $request){
        $data = $request->validated();
        // Only admin can assign tasks to others
        $task = Task::create($data);
        ActivityLog::create(['user_id'=>auth()->id(),'action'=>'create','entity_type'=>'task','entity_id'=>$task->id]);
        // If assigned to someone else, dispatch notification via job
        if(isset($data['user_id']) && $data['user_id'] != auth()->id()){
            SendTaskAssignedEmail::dispatch($task);
        }
        $task = Task::SimpleDetails()->where('id',$task->id)->first();
        return send_response(201, __('api.succ_task_created'),$task);   
    }

    //tasks listing
    public function index(Request $request) {
    $query = Task::simpleDetails()
        ->with(['user' => function ($q) {
            $q->simpleDetails();
        }]);
        // If user is NOT admin, show only their tasks
        if (Auth::user()->role != 'admin') {
            $query->where('user_id', Auth::id());
        }

        // Apply filters
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->due_date) {
               $request->validate([
                'due_date' => 'date_format:Y-m-d']);
            // Make sure due_date is in Y-m-d format for DB
            $date = Carbon::parse($request->due_date)->format('Y-m-d');
            $query->whereDate('due_date', $date);
        }

        if ($request->assigned_user) {
            $query->where('user_id', $request->assigned_user);
        }

        $tasks = $query->paginate(10);
        $tasks = $query->select('id', 'title', 'description', 'status', 'due_date','user_id')
                    ->paginate($request->get('per_page', 10));

        return send_response(200, __('api.succ'),$tasks);   

    }


    public function show($id){
    $task = Task::simpleDetails()
        ->with([
            'comments' => function ($q) {
                $q->simpleDetails()
                ->with(['user' => fn($u) => $u->simpleDetails()]);
            },
            'user' => fn($q) => $q->simpleDetails(),
        ])
        ->find($id);
        if(!$task){
            return send_error(__('api.err_task_not_found'),null,404, false);
        }     
        return send_response(200, __('api.succ'),$task);   
    }

    public function update(TaskRequest $request, $id){
        $task = Task::SimpleDetails()->find($id);
        if(!$task){
            return send_error(__('api.err_task_not_found'),null,404, false);
        }   
        // only owner or admin
        if(Auth::id() !== $task->user_id && Auth::user()->role !== 'admin'){
            return send_error(__('api.err_unautorized_update_task'),null,403, false);
        }
        $data = $request->validated();
         //Role-based restrictions
         $user = Auth::user();
        if ($user->role !== 'admin') {
            // If normal user, allow only status update
            $data = $request->only('status');
        } else {
            // If admin, allow everything
            $data['user_id'] = $request->user_id ?? $task->user_id;
        }
        $task->update($data);
        ActivityLog::create(['user_id'=>Auth::id(),'action'=>'update','entity_type'=>'task','entity_id'=>$task->id]);
        return send_response(200, __('api.succ_task_updated'),$task);   
    }

    public function destroy($id){
        $task = Task::find($id);
        if(!$task){
            return send_error(__('api.err_task_not_found'),null,404, false);
        }
        //only admin can delelte task 
        if(Auth::user()->role !='admin'){
            return send_error(__('api.err_unautorized_delete_task'),null,403, false);
        }
        $task->delete();
        ActivityLog::create(['user_id'=>auth()->id(),'action'=>'delete','entity_type'=>'task','entity_id'=>$id]);
        return send_response(200, __('api.succ_task_deleted'));   
    }
}
