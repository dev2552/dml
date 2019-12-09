<?php

namespace App\Notifications;

use App\Http\Resources\RequestResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestNotification extends Notification
{
    use Queueable;

    protected $requestResource;

    
    public function __construct(RequestResource $requestResource)
    {
        $this->requestResource = $requestResource;
    }

   
    public function via($notifiable)
    {
        return ['database'];
    }


    public function toArray($notifiable)
    {
        return [
            'request' => $this->requestResource,
        ];
    }
}
