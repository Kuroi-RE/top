<?php

namespace App\Jobs;

use App\Mail\VerificationEmail;
use App\Models\EmailVerificationToken;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly User $user,
    ) {}

    /**
     * Execute the job.
     *
     * Creates a verification token record (expires in 24 hours) and
     * dispatches the VerificationEmail mailable to the user's email address.
     */
    public function handle(): void
    {
        // Generate a unique token and create the token record
        $token = EmailVerificationToken::generateToken();

        EmailVerificationToken::create([
            'user_id'    => $this->user->id_user,
            'token'      => $token,
            'expires_at' => now()->addHours(24),
        ]);

        // Send the verification email
        Mail::to($this->user->email)->send(new VerificationEmail($this->user, $token));
    }
}
