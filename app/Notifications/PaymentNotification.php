<?php

namespace App\Notifications;

use App\Http\Resources\PaymentResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Auth;

class PaymentNotification extends Notification
{
    use Queueable;

    protected $paymentResource;
    protected $auto;
    protected $renewal;

    public function __construct(PaymentResource $paymentResource,$auto,$renewal)
    {
        $this->paymentResource = $paymentResource;
        $this->auto = $auto;
        $this->renewal = $renewal;

    }

    
    public function via($notifiable)
    {
        return ['database'];
    }


    public function toArray($notifiable)
    {
        return [
           'payment'=> $this->paymentResource,
           'ips' => $this->paymentResource->server->ips,
           'auto'=>$this->auto,
           'renewal'=>$this->renewal,
           'username'=>Auth::user()->username,
        ];
    }
}
