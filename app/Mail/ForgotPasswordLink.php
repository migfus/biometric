<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;

class ForgotPasswordLink extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $token;
    public string $url;

    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
        $this->url = route('forgot.show', [
            'forgot' => $token,
            'email' => $user->email,
        ]);
    }

    public function build()
    {
        return $this->subject('Password reset link')
            ->view('emails.forgot_password_link')
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
                'url' => $this->url,
            ]);
    }
}
