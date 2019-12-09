<?php

namespace App\Services;

use App\Models\ServerModel;
use App\Models\StatusModel;
use App\Notifications\ServerGroupChangeNotification;
use App\Repositories\DomainRepository;
use App\Repositories\ServerRepository;
use App\Repositories\StatusRepository;
use Faker\Generator as Faker;
use Notification;


class ServerService
{
	protected $serverRepository;
	protected $statusRepository;
	protected $domainRepository;

	public function __construct(ServerRepository $serverRepository,StatusRepository $statusRepository,DomainRepository $domainRepository)
	{
		$this->serverRepository = $serverRepository;
		$this->statusRepository = $statusRepository;
		$this->domainRepository = $domainRepository;
	}

	public function serversToReturn($data)
	{
		$statusData = ['status'=>'toRet'];
		foreach($data as $_data)
		{
			$_data['group'] = null;
			$_data['provider'] = null;
			$_data['domain'] = null;
			$_data['last_status'] = $statusData['status'];
			$this->serverRepository->update($_data,$_data['id']);
			$this->statusRepository->addToServer($statusData,$_data['id']);
		}
		return response()->json(true);
	}

	public function sendServerGroupChangeNotification($to,$data)
	{
		$serverGroupChangeNotification = new ServerGroupChangeNotification($data);
		Notification::send($to,$serverGroupChangeNotification);
	}

	public function checkDomain($data)
	{
		if($this->domainRepository->getDomainByDomain($data['main_domain'])->alreadyAssigned)
		{
			return true;
		}
		return false;
	}


	public function setIpList($data)
	{
		$server = $this->serverRepository->getServer($data['server_id']);
		$domain = $this->domainRepository->getDomain($data['domain_id']);
		$ips = $server->ips;
		return ['main_ip'=>$server->main_ip,'ips'=>$ips,'domain'=>$domain];
	}

	
}