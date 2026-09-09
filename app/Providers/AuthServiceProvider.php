<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Task;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider {

    public function boot() {

    Gate::define('viewTask', function(User $user, Task  $task){
        return $user->id === $task->assigned_to;
    });

    Gate::define('deleteTask', function(User $user, Task  $task){
        return $user->id === $task->assigned_to;
    });

    Gate::define('editTask', function(User $user, Task  $task){
        return $user->id === $task->assigned_to;
    });

    Gate::define('viewProfile', function(User $user, User $profile){
        return $user->id === $profile->id;
    });

    Gate::define('editProfile', function(User $user, User $profile){
        return $user->id === $profile->id;
    });

    Gate::define('uploadAvatar', function(User $user, User $profile){
        return $user->id === $profile->id;
    });

    Gate::define('viewDocument', function(User $user, \App\Models\Document $document){
        return $user->id ===$document->user_id;
    });

    Gate::define('deleteDocument', function(User $user, \App\Models\Document $document){
        return $user->id ===$document->user_id;
    });

    Gate::define('downloadDocument', function(User $user, \App\Models\Document $document){
        return $user->id ===$document->user_id;
    });

    





    }


}



