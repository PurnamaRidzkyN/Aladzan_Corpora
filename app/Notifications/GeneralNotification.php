<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $title;
    public $message;
    public $link;

    public function __construct($title, $message, $link = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->link = $link;
    }

    public function via($notifiable)
    {
        return ['database']; // disimpan di tabel notifications
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'link' => $this->link,
        ];
    }
}
