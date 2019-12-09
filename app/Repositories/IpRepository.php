<?php

namespace App\Repositories;

use App\Geoplugin;
use App\Http\Resources\IpResource;
use App\Models\GroupModel;
use App\Models\IpModel;
use App\Models\ServerModel;
use App\Notifications\CreateIpNotification;
use Auth;
use Notification;
use Carbon\Carbon;

class IpRepository 
{

	protected $ipModel; 
	protected $geoPlugin;
	protected $serverModel;
	protected $groupModel;

	public function __construct(IpModel $ipModel){
		$this->ipModel = $ipModel;
		$this->geoPlugin = new Geoplugin();
		$this->serverModel = new ServerModel();
		$this->groupModel = new GroupModel();
	}

	public function all($count)
	{
		$records = $this->ipModel->paginate($count);
		return IpResource::collection($records);
	}

	public function store($data)
	{
		//$this->geoPlugin->locate($data['ip']);
		//$data['ipCountry'] = $this->geoPlugin->countryName;
		//$data['ipCountryCode']=strtolower($this->geoPlugin->countryCode);
		$data['gateway'] = $this->serverModel->find($data['server_id'])->main_ip;
		//$data['created_by'] = Auth::user()->username; 
		$data['created'] = Carbon::now();
		$data['is_active'] = 1;
		$record = $this->ipModel->create($data);
		return new IpResource($record);
	}

	public function update($data,$id)
	{
		$record = $this->ipModel->find($id);
		//$this->geoPlugin->locate($data['ip']);
		$data['server_id'] = $data['server']['id'];
		//$data['ipCountry'] = $this->geoPlugin->countryName;
		//$data['ipCountryCode']=strtolower($this->geoPlugin->countryCode);
		//$data['updated_by'] = Auth::user()->username;
		$record->update($data);
		return new IpResource($record);
	}

	public function destroy($id)
	{
		$record = $this->ipModel->find($id);
		//$record->update(['deleted_by'=>Auth::user()->id]);
		$record->delete();
		return new IpResource($record);
	}

	public function filterForRoot($data)
	{
		if(!$data['server_id']) $data['server_id'] = '%';
		if(!$data['created_by']) $data['created_by'] = '%';
		$records = $this->ipModel
		->where('server_id','like',$data['server_id'])
		->where(function($query) use ($data){$query->where("provider","like","%".$data["provider"]."%")->orWhereNull("provider");})
		//->where(function($query) use ($data){$query->where("created_by","like","%".$data["created_by"]."%")->orWhereNull("created_by");})
		->where(function($query) use ($data){$query->where("ip","like","%".$data["ip"]."%")->orWhereNull("ip");})
		->where(function($query) use ($data){$query->where("netmask","like","%".$data["netmask"]."%")->orWhereNull("netmask");})
		->where(function($query) use ($data){$query->where("gateway","like","%".$data["gateway"]."%")->orWhereNull("gateway");})
		->where(function($query) use ($data){$query->where("price","like","%".$data["price"]."%")->orWhereNull("price");})
		->where(function($query) use ($data){$query->where("currency","like","%".$data["currency"]."%")->orWhereNull("currency");})
		->where(function($query) use ($data){$query->where("ip_status","like","%".$data["ip_status"]."%")->orWhereNull("ip_status");})
		->where(function($query) use ($data){$query->where("is_active","like","%".$data["is_active"]."%")->orWhereNull("is_active");})
		->orderBy($data['sortObject']['champ'],$data['sortObject']['order'])
		->paginate($data['limit']);
		return IpResource::collection($records);
	}

	public function filterForOthers($data)
	{
		if(!$data['server_id']) $data['server_id'] = '%';
		$records = Auth::user()->group->ips()
		->where('server_id','like',$data['server_id'])
		->where(function($query) use ($data){$query->where("provider","like","%".$data["provider"]."%")->orWhereNull("provider");})
		//->where(function($query) use ($data){$query->where("ip.created_by","like","%".$data["created_by"]."%")->orWhereNull("ip.created_by");})
		->where(function($query) use ($data){$query->where("ip","like","%".$data["ip"]."%")->orWhereNull("ip");})
		->where(function($query) use ($data){$query->where("netmask","like","%".$data["netmask"]."%")->orWhereNull("netmask");})
		->where(function($query) use ($data){$query->where("gateway","like","%".$data["gateway"]."%")->orWhereNull("gateway");})
		->where(function($query) use ($data){$query->where("ip.price","like","%".$data["price"]."%")->orWhereNull("ip.price");})
		->where(function($query) use ($data){$query->where("ip.currency","like","%".$data["currency"]."%")->orWhereNull("ip.currency");})
		->where(function($query) use ($data){$query->where("ip_status","like","%".$data["ip_status"]."%")->orWhereNull("ip_status");})
		->where(function($query) use ($data){$query->where("is_active","like","%".$data["is_active"]."%")->orWhereNull("is_active");})
		->orderBy($data['sortObject']['champ'],$data['sortObject']['order'])
		->paginate($data['limit']);
		return IpResource::collection($records);
	}

	public function storeMany($data)
	{
		$ips = explode("\n", $data['ips']);
		foreach($ips as $ip)
		{
			$data['ip'] = $ip;
			$data['created_by'] = Auth::user()->username;
			$this->store($data);
		}
		return $ips[0];
	}

	public function getIps($data)
	{
		if(!isset($data['search'])) $data['search'] = '';
		$records = $this->ipModel
		->where('ip','like','%'.$data['search'].'%')
		->where("is_active",1)
		->orderBy('id','desc')
		->paginate(10);
		return IpResource::collection($records);
	}

	public function sendCreateNotification($to,$data)
	{
		$createIpNotification = new CreateIpNotification($data);
		Notification::send($to,$createIpNotification);
	}

	public function getModel()
	{
		return $this->ipModel;
	}

	public function getIpByIp($ip)
    {
    	return new IpResource($this->ipModel->where('ip',$ip)->first());
    }
}