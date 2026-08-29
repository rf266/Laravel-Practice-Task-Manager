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
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModel;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
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


    public function faliure(\Throwable $exception): void {
        \Log::error('verification email failed '. $exception->getMessage());
    }
}
