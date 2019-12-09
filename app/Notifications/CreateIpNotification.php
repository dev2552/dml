<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CreateIpNotification extends Notification
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
            'username' => $this->data['username'],
            'ip_range' => $this->data['ip_range'],
            'total_price' => $this->data['price']*count(explode("\n", $this->data['ips'])),
            'currency' => $this->data['currency'],
        ];
    }
}
