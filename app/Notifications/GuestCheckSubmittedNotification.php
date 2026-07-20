<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Notifications\Notification;

class GuestCheckSubmittedNotification extends Notification
{
    public function __construct(
        public int $check_id,
        public array $submission,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            // 'title' => $this->submission['check'] . ' at ' . Carbon::now()->format('M j, Y g:i A'),
            'title' => $this->submission['full_name'],
            'content' => sprintf(
                '%s: %s - %s',
                $this->submission['check'],
                Carbon::now()->format('M j, Y g:i A'),
                $this->submission['office'],
            ),
            'check_id' => $this->check_id,
            'href' => route('dashboard.employees.show', ['employee' => $this->submission['employee_no']], false),
            'employee_no' => $this->submission['employee_no'],
            'full_name' => $this->submission['full_name'],
            'office' => $this->submission['office'],
            'check' => $this->submission['check'],
        ];
    }
}
