<?php

namespace App\Listeners;


use App\Events\TaskCreated;
use Illuminate\Support\Facades\Cache;

class ForgetTaskStatsCache
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskCreated $event): void
    {
        Cache::forget('user:'. $event->task->user_id. ':task-stats');
    }
}
