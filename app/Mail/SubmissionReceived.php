<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubmissionReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $submission;

    public function __construct(array $submission) {
        $this->submission = $submission;
    }

    public function build() {
        return $this->subject('Your submission has been received')
            ->view('emails.submission_received')
            ->with([
                'submission' => $this->submission,
            ]);
    }
}
