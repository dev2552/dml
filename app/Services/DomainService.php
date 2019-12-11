<?php 

namespace App\Services;

use App\Http\Resources\DomainResource;
use App\Models\DomainModel;
use App\Models\PaymentModel;
use App\Repositories\DomainRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\UserRepository;
use App\User;

class DomainService 
{
	protected $domainRepository;
	protected $userRepository;
	protected $paymentRepository;

	public function __construct(DomainModel $domainModel,User $userModel,PaymentModel $paymentModel)
	{
		$this->domainRepository = new DomainRepository($domainModel);
		$this->userRepository = new UserRepository($userModel);
		$this->paymentRepository = new PaymentRepository($paymentModel);
	}

	public function createMany($data)
	{
		$domains = explode("\n", $data['domains']);
		if(count($domains) > 0)
		{
			foreach($domains as $domain)
			{
				$_data = $data;

				$_data['domain'] = $domain;

				$record = $this->domainRepository->create($_data);

				//$paymentData = [
					//'type'=>'Domain',
					//'domain_id'=>$record->id,
					//'group_id'=>$record->group->id,
					//'unit_price'=>1,'quantity'=>1,
					//'total_price'=>$record->price,
					//'currency'=>$record->currency,
					//'description'=>'Auto Payment',
					//'created_by'=>$this->userRepository->getUser()->username,
					//'payment_date'=>now(),
					//'create_date'=>now()];

				//$paymentRecord = $this->paymentRepository->store($paymentData);

				$domainData = ['currency'=>$record->currency,
		        'datex'=>$record->datex->format('Y-m-d'),
		        'entered'=>$record->entered->format('Y-m-d'),
		        'domain'=>$record->domain,
		        'group'=>$record->group->name,
		        'price'=>$record->price,
		        'registrar'=>$record->registrar->name,
		        'username'=>$this->userRepository->getUser()->username];

		         $this->domainRepository->sendCreateNotification($this->userRepository->roots(),$domainData);
			}
		}
	}

	public function create($data)
	{
		$record = $this->domainRepository->create($data);

		$paymentData = ['type'=>'Domain','domain_id'=>$record->id,'group_id'=>$record->group->id,'unit_price'=>1,'quantity'=>1,'total_price'=>$record->price,'currency'=>$record->currency,'description'=>'Auto Payment','created_by'=>$this->userRepository->getUser()->username,'payment_date'=>now(),'create_date'=>now()];

		$paymentRecord = $this->paymentRepository->store($paymentData);

		$domainData = ['currency'=>$record->currency,
		        'datex'=>$record->datex->format('Y-m-d'),
		        'entered'=>$record->entered->format('Y-m-d'),
		        'domain'=>$record->domain,
		        'group'=>$record->group->name,
		        'price'=>$record->price,
		        'registrar'=>$record->registrar->eml_contact,
		        'username'=>$this->userRepository->getUser()->username];

		$this->domainRepository->sendCreateNotification($this->userRepository->roots(),$domainData);

		return new DomainResource($record);
	}

	public function getDomain($id)
	{
		return $this->domainRepository->getDomain($id);
	}
}