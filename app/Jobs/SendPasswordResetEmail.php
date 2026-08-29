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

class SendPasswordResetEmail implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModel;


    public function __construct()
    {

    }

    public function handle(): void
    {
        Mail::to($this->user->email)->send(new PasswordResetEmail($this->user, $this->resetUrl));
    }

    public function failed(\Throwable $exception): void {
        \Log::error('password reset failed '. $exception->getMessage());
    }
}
