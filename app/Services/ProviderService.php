<?php 

namespace App\Services;

use App\Models\ProviderModel;
use App\Repositories\ProviderRepository;
use Auth;
use Illuminate\Auth\Access\AuthorizationException;

class ProviderService
{
	protected $providerRepository;

	public function __construct(ProviderRepository $providerRepository)
	{
		$this->providerRepository = $providerRepository;
	}

	public function show($id)
	{
		if(!Auth::user()->can('index',ProviderModel::class)) throw new AuthorizationException;
		return $this->providerRepository->getProvider($id);	
	}
}