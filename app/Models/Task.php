<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
class Task extends Model
{
    protected $fillable = ['title', 'description', 'is_completed', 'user_id'];


    protected function casts() : array {
        return [
            'title'=>'encrypted',
            'description' => 'encrypted',
        ];
    }

    protected static function  booted() : void {
        static::saved(function (Task $task) {
            Cache::forget('user:' . $task->user_id . ':task-stats');

        });

         static::deleted(function (Task $task) {
            Cache::forget('user:' . $task->user_id . ':task-stats');

        });

    } 



}


