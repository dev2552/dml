<?php 

namespace App\Services;

use App\Services\DomainService;
use App\Services\SubDomainService;

class GodaddyService
{

	protected $subDomainService;
	protected $domainService;
	protected $registrarService;

	public function __construct(SubDomainService $subDomainService,DomainService $domainService,RegistrarService $registrarService)
	{
		$this->subDomainService = $subDomainService;
		$this->domainService = $domainService;
		$this->registrarService = $registrarService;
	}

	public function clearDomain($id) 
	{
		$registrar = $this->domainService->getDomain($id)->registrar;
		$key = $registrar->registrar_key;
		$secret = $registrar->secret;
		$ch = curl_init();
		$string = 'Authorization: sso-key '.$key.':'.$secret; 
		$headers = [$string,'Content-Type: application/json'];
		$url = 'https://api.godaddy.com/v1/domains/'.$this->domainService->getDomain($id)->domain.'/records';
		$nameServers = $this->getNameServers($registrar,$this->domainService->getDomain($id)->domain);
		if(isset($nameServers->message)) return response()->json(['message'=>$nameServers->message],406);
		$nameServers = $nameServers->nameServers;
		$data = '[{"data":"'.$nameServers[0].'","name":"@","type":"NS"},{"data":"'.$nameServers[1].'","name":"@","type":"NS"},{"data":"@","name":"www","type":"CNAME"}]';
		$options = [CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER=>true,
		CURLOPT_CUSTOMREQUEST=>'PUT',
		CURLOPT_POSTFIELDS=>$data,                 
		CURLOPT_HTTPHEADER=>$headers];
		curl_setopt_array($ch,$options);
		$res = curl_exec($ch);
		curl_close($ch);

		$res = json_decode($res);

		if(isset($res->code)) return response()->json(['message'=>$res->message],406);



	}

	public function updateDNS($data)
	{
		$registrar = $this->registrarService->getRegistrar($data['registrar_id']);
		$key = $registrar->registrar_key;
		$secret = $registrar->secret;
		$ch = curl_init();
		$string = 'Authorization: sso-key '.$key.':'.$secret;
		$headers = [$string,'Content-Type: application/json'];
		$url = 'https://api.godaddy.com/v1/domains/'.$data['ipList'][0]['subDomain'].'/records';
		$els = ['A','TXT'];
		$types = ['spf','dkim'];
		$_data = '[';
		foreach($els as $el)
			{
				if($el == 'A')
				{
					for($i=0;$i<count($data['ipList']);$i++)
					{
						if($i==0) $_data = $_data.'{"data":"'.$data['ipList'][$i]["ip"].'","name":"@","type":"A","ttl":1800},';
						else $_data = $_data.'{"data":"'.$data['ipList'][$i]["ip"].'","name":"'.$data['ipList'][$i]["name"].'","type":"A","ttl":1800},';
					}
				}else if($el == 'TXT')
				{
					foreach($types as $type)
					{
						if($type == 'spf')
						{
							for($i=0;$i<count($data['ipList']);$i++)
							{
								if($i==0) $_data = $_data.'{"data":"v=spf1 ip4:'.$data['ipList'][$i]["ip"].' -all","name":"@","type":"TXT","ttl":1800},';
								else $_data = $_data.'{"data":"v=spf1 ip4:'.$data['ipList'][$i]["ip"].' -all","name":"'.$data['ipList'][$i]["name"].'","type":"TXT","ttl":1800},';
							}
						}else if($type == 'dkim')
						{
							for($i=0;$i<count($data['ipList']);$i++)
							{
								if($i==0) $_data = $_data.'{"data":"'.$data['DKIM'].'","name":"selector1._domainkey","type":"TXT","ttl":1800},';
								else if($i==count($data['ipList'])-1) $_data = $_data.'{"data":"'.$data['DKIM'].'","name":"selector1._domainkey.'.$data['ipList'][$i]["name"].'","type":"TXT","ttl":1800}';
								else $_data = $_data.'{"data":"'.$data['DKIM'].'","name":"selector1._domainkey.'.$data['ipList'][$i]["name"].'","type":"TXT","ttl":1800},';
							}
						}
					}
				}
			}
			$_data = $_data.']';
		$options = [CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER=>true,
		CURLOPT_CUSTOMREQUEST=>'PATCH',
		CURLOPT_POSTFIELDS=>$_data,                 
		CURLOPT_HTTPHEADER=>$headers];
		curl_setopt_array($ch,$options);
		$res = curl_exec($ch);
		curl_close($ch);
		$res = json_decode($res);
		if(!is_null($res)) if($res->message) return response()->json(['message'=>$res->message],406);
		$this->subDomainService->createMany($data['ipList']);
		return $res;
	}


	public function getNameServers($registrar,$domain)
	{
		$key = $registrar->registrar_key;
		$secret = $registrar->secret;
		$ch = curl_init();
		$string = 'Authorization: sso-key '.$key.':'.$secret; 
		$headers = [$string,'Content-Type: application/json'];
		$url = 'https://api.godaddy.com/v1/domains/'.$domain;
		$options = [CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER=>true,              
		CURLOPT_HTTPHEADER=>$headers];
		curl_setopt_array($ch,$options);
		$res = curl_exec($ch);

		curl_close($ch);
			
		$res = json_decode($res);

		return $res;
	}


}