<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ServerGroupChangeNotification extends Notification
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
            'lastGroup'=>$this->data['lastGroup'],
            'currentGroup'=>$this->data['currentGroup'],
            'username'=>$this->data['username'],
            'server'=>$this->data['server'],
        ];
    }
}
