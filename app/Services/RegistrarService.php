<?php 

namespace App\Services;

use App\Repositories\RegistrarRepository;

class RegistrarService
{
	protected $registrarRepository;

	public function __construct(RegistrarRepository $registrarRepository)
	{
		$this->registrarRepository = $registrarRepository;
	}

	public function getDomainsList($id)
	{
		return $this->registrarRepository->getDomainsList($id);
	}

	public function getRegistrar($id)
	{
		return $this->registrarRepository->getRegistrar($id);
	}
}