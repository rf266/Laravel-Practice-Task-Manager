<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogTaskActivity implements ShouldQueue
{
    
    public function handle(TaskCreated $event): void
    {
        Log::info('task created', ['task_id'=>$event->task->id, 
        'user_id' => $event->task->user_id,]);
    }
}
