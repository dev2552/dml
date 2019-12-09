<?php

namespace App\Notifications;

use App\Http\Resources\StatusResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusNotification extends Notification
{
    use Queueable;

    protected $data;

   
    public function __construct($data)
    {
        $this->data = $data;
    }

   
    public function via($notifiable)
    {
        return ['database'];
    }

    
    public function toArray($notifiable)
    {
        return [
            
            'request' => $this->data['request'],

            'status' => $this->data['status'],

            //'username' => $this->data['username'],

            'subject' => $this->data['subject'],
        ];
    }
}
