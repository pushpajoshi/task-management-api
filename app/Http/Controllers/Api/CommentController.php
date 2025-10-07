<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Task;
use App\Http\Requests\CommentRequest;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    public function index($taskId){
        $comments = Comment::SimpleDetails()->with(['user'=>function($q){
            $q->SimpleDetails();
        }])->where('task_id',$taskId)->get();
        return send_response(200, __('api.succ'),$comments);   
    }

    public function store(CommentRequest $request, $taskId){
        $task = Task::find($taskId);
      if(!$task){
            return send_error(__('api.err_task_not_found'),null,404, false);
        }   
         if(Auth::id() !== $task->user_id && Auth::user()->role !== 'admin'){
            return send_error(__('api.err_unauthorized_comment_task'),null,403, false);
        }
        $data = $request->validated();
        $data['task_id'] = $task->id;
        $data['user_id'] = Auth::id();
        if($request->hasFile('attachment')){
            $path = $request->file('attachment')->store('comments','public');
            $data['attachment_path'] = $path;
        }
        $comment = Comment::create($data);
        $comment = Comment::SimpleDetails()->where('id',$comment->id)->first();
        ActivityLog::create(['user_id'=>auth()->id(),'action'=>'create','entity_type'=>'comment','entity_id'=>$comment->id]);
        return send_response(200, __('api.succ_commented'),$comment);   
    }
}
