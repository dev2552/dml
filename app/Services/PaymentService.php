<?php 


namespace App\Services;

use App\Notifications\PaymentNotification;
use App\User;

class PaymentService
{
	private $userModel;

	private $paymentNotification;

	public function __construct(User $userModel)
	{
		$this->userModel = $userModel;
		
	}

	public function sendNotification($paymentModel)
	{
		$this->paymentNotification = new PaymentNotification($paymentModel);
		Notification::send($this->userModel->roots(),$this->paymentNotification);
	}
}