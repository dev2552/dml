<?php 

namespace App\Services;

use App\Repositories\DomainRepository;
use App\Repositories\IpRepository;
use App\Repositories\ServerRepository;
use App\Repositories\SubDomainRepository;
use Auth;

class SubDomainService
{

	protected $subDomainRepository;
	protected $serverRepository;
	protected $domainRepository;
	protected $ipRepository;

	public function __construct(SubDomainRepository $subDomainRepository,ServerRepository $serverRepository,DomainRepository $domainRepository,IpRepository $ipRepository)
	{
		$this->subDomainRepository = $subDomainRepository;
		$this->serverRepository = $serverRepository;
		$this->domainRepository = $domainRepository;
		$this->ipRepository = $ipRepository;
	}

	public function createMany($data)
	{
		$domain = $data[0]['subDomain'];

		$domainId = $this->domainRepository->getDomainByDomain($domain)->id;

		\DB::table("sub_domain")->where("domain_id",$domainId)->delete();

		foreach($data as $k=>$v)
		{
			$this->subDomainRepository->create(
				[
					'ip_id' =>$this->ipRepository->getIpByIp($v['ip'])->id,
					'subdomain' => $v['subDomain'],
					'domain_id' => $domainId,
					'created'=>now(),
					'is_active'=>1,
				]);
		}
		return json_encode(true);
	}

	public function filter($data)
	{
		return $this->subDomainRepository->filter($data);
	}

	public function listIps($data)
	{
		$ips = $this->serverRepository->getServer($data['server_id'])->ips;
		return $ips->pluck('ip');
	}

	public function update($id,$data)
	{
		$data['user_id'] = Auth::user()->id;
		return $this->subDomainRepository->update($id,$data);
	}
}