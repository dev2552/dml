<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CreateDomainNotification extends Notification
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
            'currency' => $this->data['currency'],
            'datex' => $this->data['datex'],
            'entered' => $this->data['entered'],
            'domain' => $this->data['domain'],
            'group' => $this->data['group'],
            'price' => $this->data['price'],
            'registrar' => $this->data['registrar'],
            'username'=>$this->data['username'],
        ];
    }
}
