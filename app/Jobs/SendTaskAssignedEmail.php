<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;
use App\Notifications\TaskAssigned;

class SendTaskAssignedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $task;

    public function __construct($task)
    {
        $this->task = $task;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->task->user;
        if($user){
            $user->notify(new TaskAssigned($this->task));
        }
    }
}
