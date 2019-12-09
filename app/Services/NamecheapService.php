<?php 

namespace App\Services;

use App\Services\DomainService;
use Request;
use App\XML2Array;




class NamecheapService
{

	protected $domainService;
	protected $registrarService;

	public function __construct(DomainService $domainService,RegistrarService $registrarService,SubDomainService $subDomainService)
	{
		$this->domainService = $domainService;
		$this->registrarService = $registrarService;
		$this->subDomainService = $subDomainService;
	}

	public function clearDomain($id)
	{
		$domain = $this->domainService->getDomain($id);
		$registrar = $domain->registrar;
		$serviceUrl = 'https://api.namecheap.com';
		$apiUsername = $registrar->customerid;
		$apiKey = $registrar->registrar_key;
		$ncUsername = $registrar->customerid;
		$cmdName = 'namecheap.domains.dns.setHosts';
		$clientIpAddress = Request::ip();

		$sld = explode(".",$domain->domain,2)[0];
		$tld = explode(".",$domain->domain,2)[1];

		$url = $serviceUrl.'/xml.response?ApiUser='.$apiUsername.'&ApiKey='.$apiKey.'&UserName='.$ncUsername.'&Command='.$cmdName.'&ClientIp='.$clientIpAddress.'&SLD='.$sld.'&TLD='.$tld;


		$ch = curl_init();
		curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER => 1,CURLOPT_URL => $url]);
		$res = curl_exec($ch);
		curl_close($ch);

		$array =  XML2Array::createArray($res);

		$array =  $array['ApiResponse']['@attributes']['Status'];

		if($array == 'ERROR') return response()->json(['message'=>'ERROR'],406); 

		return $array;
	}

	public function updateDNS($data)
	{
		$registrar = $this->registrarService->getRegistrar($data['registrar_id']);
		$serviceUrl = 'https://api.namecheap.com/xml.response';
		$apiUsername = $registrar->customerid;
		$apiKey = $registrar->registrar_key;
		$ncUsername = $registrar->customerid;
		$cmdName = 'namecheap.domains.dns.setHosts';
		$clientIpAddress = Request::ip();

		$sld = explode(".",$data['ipList'][0]['subDomain'],2)[0];
		$tld = explode(".",$data['ipList'][0]['subDomain'],2)[1];

		$url = 'ApiUser='.$apiUsername.'&ApiKey='.$apiKey.'&UserName='.$ncUsername.'&Command='.$cmdName.'&ClientIp='.$clientIpAddress.'&SLD='.$sld.'&TLD='.$tld;

		$els = ['A','TXT'];
		$types = ['spf','dkim'];

		$index = 0;

		foreach($els as $el)
			{
				if($el == 'A')
				{
					for($i=0;$i<count($data['ipList']);$i++)
					{
						$index++;
						if($i == 0) $url = $url.'&HostName'.$index.'=@&RecordType'.$index.'=A&Address'.$index.'='.$data['ipList'][$i]["ip"].'&TTL'.$index.'=1800';
						else $url = $url.'&HostName'.$index.'='.$data['ipList'][$i]["name"].'&RecordType'.$index.'=A&Address'.$index.'='.$data['ipList'][$i]["ip"].'&TTL'.$index.'=1800';
					}
				}else if($el == 'TXT')
				{
					foreach($types as $type)
					{
						if($type == 'spf')
						{
							for($i=0;$i<count($data['ipList']);$i++)
							{
								$index++;
								if($i == 0) $url = $url.'&HostName'.$index.'=@&RecordType'.$index.'=TXT&Address'.$index.'=v=spf1 ip4:'.$data['ipList'][$i]["ip"].' -all&TTL'.$index.'=1800';
								else $url = $url.'&HostName'.$index.'='.$data['ipList'][$i]["name"].'&RecordType'.$index.'=TXT&Address'.$index.'=v=spf1 ip4:'.$data['ipList'][$i]["ip"].' -all&TTL'.$index.'=1800';
							}
						}else if($type == 'dkim')
						{
							for($i=0;$i<count($data['ipList']);$i++)
							{
								$index++;
								if($i == 0) $url = $url.'&HostName'.$index.'=selector1._domainkey&RecordType'.$index.'=TXT&Address'.$index.'='.$data['DKIM'].'&TTL'.$index.'=1800';
								else $url = $url.'&HostName'.$index.'=selector1._domainkey.'.$data['ipList'][$i]["name"].'&RecordType'.$index.'=TXT&Address'.$index.'='.$data['DKIM'].'&TTL'.$index.'=1800';
							}
						}
					}
				}
			}



		//$hosts = $this->getHosts($registrar,$sld,$tld);

		//if(isset($hosts[0]))
		//{
				//foreach($hosts as $host)
				//{
					//$index++;
					//$url = $url.'&HostName'.$index.'='.$host['@attributes']['Name'].'&RecordType'.$index.'='.$host['@attributes']['Type'].'&Address'.$index.'='.$host['@attributes']['Address'].'&TTL'.$index.'='.$host['@attributes']['TTL'];
				//}
		//}
		//else if(isset($hosts['@attributes']))
		//{
			//$index++;
			//$url = $url.'&HostName'.$index.'='.$hosts['@attributes']['Name'].'&RecordType'.$index.'='.$hosts['@attributes']['Type'].'&Address'.$index.'='.$hosts['@attributes']['Address'].'&TTL'.$index.'='.$hosts['@attributes']['TTL'];
		//}
		//else
		//{
			//$url = $url;
		//}

	

		$ch = curl_init();
		curl_setopt_array($ch,
			[
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => $serviceUrl,
				CURLOPT_POST=>1,
				CURLOPT_POSTFIELDS=>$url,
			]);
		$res = curl_exec($ch);
		curl_close($ch);
		$array = XML2Array::createArray($res);
		if($array['ApiResponse']['Errors'] != "") return response()->json(['message'=>$array['ApiResponse']['Errors']['Error']['@value']],406);
		$this->subDomainService->createMany($data['ipList']);
		return $array['ApiResponse']['@attributes']['Status'];
	}

	public function getHosts($registrar,$sld,$tld)
	{
		$serviceUrl = 'https://api.namecheap.com';
		$apiUsername = $registrar->customerid;
		$apiKey = $registrar->registrar_key;
		$ncUsername = $registrar->customerid;
		$cmdName = 'namecheap.domains.dns.getHosts';
		$clientIpAddress = Request::ip();

		$url = $serviceUrl.'/xml.response?ApiUser='.$apiUsername.'&ApiKey='.$apiKey.'&UserName='.$ncUsername.'&Command='.$cmdName.'&ClientIp='.$clientIpAddress.'&SLD='.$sld.'&TLD='.$tld;


		$ch = curl_init();
		curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER => 1,CURLOPT_URL => $url]);
		$res = curl_exec($ch);
		curl_close($ch);

		$array =  XML2Array::createArray($res);

		if(isset($array['ApiResponse']['CommandResponse']['DomainDNSGetHostsResult']['host'])) return $array['ApiResponse']['CommandResponse']['DomainDNSGetHostsResult']['host'];
		else return [];
		
	}
}