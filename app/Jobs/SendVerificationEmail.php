<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\VerificationEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmail implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */

    public User $user;
    public string $verificationUrl;

    public function __construct(User $user,string $verificationUrl)
    {
        $this->user = $user;
        $this->verificationUrl = $verificationUrl;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(
            new VerificationEmail($this->user, $this->verificationUrl)
        );
    }


    public function failed(\Throwable $exception): void {
        \Log::error('verification email failed '. $exception->getMessage());
    }
}
