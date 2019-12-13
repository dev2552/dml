<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CreatePaymentNotification extends Notification
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
            'created' => $this->data['created'],
            'currency' => $this->data['currency'],
            'description' => $this->data['description'],
            'group' => $this->data['group'],
            'payment_date' => $this->data['payment_date'],
            'server' => $this->data['server'],
             'domain' => $this->data['domain'],
              'ip' => $this->data['ip'],
            'quantity' => $this->data['quantity'],
            'total_price' => $this->data['total_price'],
            'type' => $this->data['type'],
            'unit_price' => $this->data['unit_price'],
           'username' => $this->data['username'],
        ];
    }
}
