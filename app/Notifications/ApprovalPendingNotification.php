<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApprovalPendingNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $titleEn,
        private readonly string $titleUr,
        private readonly string $messageEn,
        private readonly string $messageUr,
        private readonly string $url,
        private readonly string $approvalType,
        private readonly int|string $approvalId,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'category' => 'approval_pending',
            'approval_type' => $this->approvalType,
            'approval_id' => (string) $this->approvalId,
            'title_en' => $this->titleEn,
            'title_ur' => $this->titleUr,
            'message_en' => $this->messageEn,
            'message_ur' => $this->messageUr,
            'url' => $this->url,
        ];
    }
}
