<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CreateServerNotification extends Notification
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
            'currency'=>$this->data['currency'],
            'date_expiration'=>$this->data['date_expiration'],
            'date_order'=>$this->data['date_order'],
            'date_purchase'=>$this->data['date_purchase'],
            'description'=>$this->data['description'],
            'main_domain'=>$this->data['main_domain'],
            'group'=>$this->data['group'],
            's_name'=>$this->data['s_name'],
            'note'=>$this->data['note'],
            'numberIps'=>$this->data['numberIps'],
            'price'=>$this->data['price'],
            'provider'=>$this->data['provider'],
            'type'=>$this->data['type'],
            'username'=>$this->data['username'],
        ];
    }
}
