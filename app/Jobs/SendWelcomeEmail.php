<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\WelcomeEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class SendWelcomeEmail implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public User $user;


    public function __construct(User $user)
    {
        $this->user = $user;
        
    }

    public function handle(): void
    {
        Mail::to($this->user->email)->send(new WelcomeEmail($this->user));
    }

    public function failed(\Throwable $exception): void {
        \Log::error('welcome email failed '. $exception->getMessage());
    }
}
