<?php 

namespace App\Repositories;

use App\Geoplugin;
use App\Http\Resources\ServerResource;
use App\Models\DomainModel;
use App\Models\GroupModel;
use App\Models\IpModel;
use App\Models\PaymentModel;
use App\Models\ProviderModel;
use App\Models\ServerModel;
use App\Models\StatusModel;
use App\Notifications\CreateServerNotification;
use App\Repositories\IpRepository;
use App\Services\OpenExchangeRatesService;
use Auth;
use Carbon\Carbon;
use Notification;


class ServerRepository 
{
	protected $serverModel;
	protected $geoPlugin;
	protected $groupModel;
	protected $providerModel;
	protected $domainModel;
	protected $paymentModel;
	protected $openExchangeRatesService;


	public function __construct(ServerModel $serverModel,OpenExchangeRatesService $openExchangeRatesService)
	{
		$this->serverModel = $serverModel;
		$this->geoPlugin = new Geoplugin();
		$this->groupModel = new GroupModel();
		$this->providerModel = new ProviderModel();
		$this->domainModel = new DomainModel();
		$this->paymentModel = new PaymentModel();
		$this->openExchangeRatesService = $openExchangeRatesService;
	}

	public function store($data,$status)
	{
		$this->geoPlugin->locate($data['main_ip']);
		//$data['mainIpCountry'] = $this->geoPlugin->countryName;
		//$data['mainIpCountryCode']=strtolower($this->geoPlugin->countryCode);
		$data['last_status'] = $status['status'];
		//$data['created_by'] = Auth::user()->username;
		$data['created'] = Carbon::now();
		$record = $this->serverModel->create($data);
		$status['created'] = Carbon::now();
		//$status['created_by'] = Auth::user()->username;
		$record->status()->create($status);
		return new ServerResource($record);
	}

	public function filterData($model,$data)
	{
		$columns = [
			"provider_id","group_id","s_name","main_ip","main_domain","date_purchase","price","currency","last_status"
		];

		foreach($columns as $column)
		{
			$model = $this->setModel($column,$model,$data);
		}

		return $model;
	}


	public function setModel($column,$model,$data)
	{
		if( $data[$column] && $column==="last_status" )
		{		
			return $model
			->whereRaw(\DB::raw("(select status from status where status.server_id=server.id order by status.id desc  limit 1 ) = '".$data["last_status"]."'")) ;  
		}
		elseif($data[$column])
		{
			return $model->where(function($query) use ($data,$column){$query->where($column,'like','%'.$data[$column].'%')->orWhereNull($column);});
		}else
		{
			return $model;
		}
	}

	public function fetchForRoot($data)
	{
		$records = $this->filterData($this->serverModel,$data)
		->orderBy($data['sortObject']['champ'],$data['sortObject']['order'])
		->paginate($data['limit']);
		
		return ServerResource::collection($records);
	}

	public function fetchForOthers($data)
	{
		$records = $this->filterData(Auth::user()->group->servers(),$data)
		->orderBy($data['sortObject']['champ'],$data['sortObject']['order'])
		->paginate($data['limit']);

		return ServerResource::collection($records);
	}

	public function all(){
		return $this->serverModel->all();
	}

	public function update($data,$id){
		$record = $this->serverModel->find($id);
		if($data['group']) $data['group_id'] = $data['group']['id'];
		if($data['provider']) $data['provider_id'] = $data['provider']['id'];
		$this->geoPlugin->locate($data['main_ip']);
		$data['mainIpCountry'] = $this->geoPlugin->countryName;
		$data['mainIpCountryCode']=strtolower($this->geoPlugin->countryCode);
		$data['updated_by'] = Auth::user()->username;
		$record->update($data);
		return new ServerResource($record);
	}

	public function serversToReturnForRoot()
	{
		$records = $this->serverModel->where('last_status','toRet')->orderBy('id','desc')->paginate(5);
		return ServerResource::collection($records);
	}

	public function serversToReturnForOthers()
	{
		$records = Auth::user()->group->servers()->where('last_status','toRet')->orderBy('id','desc')->paginate(5);
		return ServerResource::collection($records);
	}

	public function serversInProductionWithIssue()
	{
		$records = $this->serverModel->where('last_status','issue')->orderBy('id','desc')->paginate(5);
		return ServerResource::collection($records);
	}

	public function serversInstalling()
	{
		$records = $this->serverModel->where('last_status','inst')->orderBy('id','desc')->paginate(5);
		return ServerResource::collection($records);
	}

	public function serversExpirationForRoot()
	{
		$records = $this->serverModel
		->whereRaw('(date_expiration >= NOW() - INTERVAL 10 DAY) and (date_expiration < NOW() + INTERVAL 10 DAY)')
		->orderByRaw('(now() - date_expiration) desc')
		->paginate(10);
		return ServerResource::collection($records);
	}

	public function serversExpirationForOthers()
	{
		$records = Auth::user()->group->servers()
		->whereRaw('(date_expiration >= NOW() - INTERVAL 10 DAY) and (date_expiration < NOW() + INTERVAL 10 DAY)')
		->orderByRaw('(now() - date_expiration) desc')->paginate(10);
		return ServerResource::collection($records);
	}

	public function serversForRoot()
	{
		$data = [];
		$status = [];
		$counts = [];
		$groups = [];
		$groupsId = $this->serverModel->select('group_id')->distinct()->get();
		$ss = ['canc','inst','issue','pen','prod','ret','toRet','susp'];
		foreach($groupsId as $groupId)
		{
			$group['name'] = $this->groupModel->find($groupId['group_id'])->name;
			$group['counts'] = [];
			foreach($ss as $s)
			{
				if(!in_array($s,$status)){
					$status[] = $s;
					$counts[] = $this->serverModel->where('last_status',$s)->count();
				} 

				$group['counts'][] = $this->serverModel->where('last_status',$s)->where('group_id',$groupId['group_id'])->count();
			}
			$groups[] = $group;
		}

		$data['status'] = $status;
		$data['counts'] = $counts;
		$data['groups'] = $groups;

		return $data;
	}

	public function serversForOthers()
	{
		$data = [];
		$status = [];
		$counts = [];
		$groups = [];
		$groupsId = $this->serverModel->select('group_id')->where('group_id',Auth::user()->group->id)->distinct()->get();
		$ss = ['canc','inst','issue','pen','prod','ret','toRet','susp'];
		foreach($groupsId as $groupId)
		{
			$group['name'] = $this->groupModel->find($groupId['group_id'])->name;
			$group['counts'] = [];
			foreach($ss as $s)
			{
				if(!in_array($s,$status)){
					$status[] = $s;
					$counts[] = $this->serverModel->where('last_status',$s)->count();
				} 

				$group['counts'][] = $this->serverModel->where('last_status',$s)->where('group_id',$groupId['group_id'])->count();
			}
			$groups[] = $group;
		}

		$data['status'] = $status;
		$data['counts'] = $counts;
		$data['groups'] = $groups;

		return $data;
	}

	public function paymentsForRoot()
	{
		$currencies = ['USD','EUR','GBP','MAD'];
		$types = ['Server','Setup Fee','Ip','Domain','Transfer Fee','Inboxe','Other'];
		$groupsId = $this->serverModel->select('group_id')->distinct()->get();
		$groups = [];
		foreach($groupsId as $groupId)
		{
			$group = [];
			$group['groupName'] = $this->groupModel->find($groupId['group_id'])->name;
			$_res = [];
			foreach($types as $type)
			{
				$sumMonthToDate = 0;
				$sumLastMonth = 0;
				
				foreach($currencies as $currency)
				{
					$sumMonthToDate = $sumMonthToDate + $this->openExchangeRatesService->toUSD(\DB::table("payment")->whereBetween('payment_date',[ new Carbon('first day of this month'),now()])->where('group_id',$groupId['group_id'])->where('type',$type)->where('currency',$currency)->sum('total_price'),$currency);

					$sumLastMonth = $sumLastMonth +  $this->openExchangeRatesService->toUSD(\DB::table("payment")->whereBetween('payment_date',[ new Carbon('first day of last month'),new Carbon('last day of last month')])->where('group_id',$groupId['group_id'])->where('type',$type)->where('currency',$currency)->sum('total_price'),$currency);
					
				}

				$_res[] = ['type'=>$type,'sumMonthToDate'=>$sumMonthToDate,'sumLastMonth'=>$sumLastMonth];

			}
			$group['res'] = $_res;
			$groups[] = $group;
		}
		return $groups;
	}

	public function paymentsForOthers()
	{
		$currencies = ['USD','EUR','GBP','MAD'];
		$types = ['Server','Setup Fee','Ip','Domain','Transfer Fee','Inboxe','Other'];
		$groupsId = $this->serverModel->select('group_id')->where('group_id',Auth::user()->group->id)->distinct()->get();
		$groups = [];
		foreach($groupsId as $groupId)
		{
			$group = [];
			$group['groupName'] = $this->groupModel->find($groupId['group_id'])->name;
			$_res = [];
			foreach($types as $type)
			{
				$sumMonthToDate = 0;
				$sumLastMonth = 0;
				
				foreach($currencies as $currency)
				{
					
					$sumMonthToDate = $sumMonthToDate + $this->openExchangeRatesService->toUSD(\DB::table("payment")->whereBetween('payment_date',[ new Carbon('first day of this month'),now()])->where('group_id',$groupId['group_id'])->where('type',$type)->where('currency',$currency)->sum('total_price'),$currency);


					$sumLastMonth = $sumLastMonth +  $this->openExchangeRatesService->toUSD(\DB::table("payment")->whereBetween('payment_date',[ new Carbon('last day of last month'),new Carbon('first day of last month')])->where('group_id',$groupId['group_id'])->where('type',$type)->where('currency',$currency)->sum('total_price'),$currency);
					
				}

				$_res[] = ['type'=>$type,'sumMonthToDate'=>$sumMonthToDate,'sumLastMonth'=>$sumLastMonth];

			}
			$group['res'] = $_res;
			$groups[] = $group;
		}
		return $groups;
	}

	public function delete($id)
	{
		$record = $this->serverModel->find($id);
		//$record->update(['deleted_by'=>Auth::user()->username]);
		$record->delete();
		return response()->json(true);
	}

	public function getAllServers($data)
	{
		if(!isset($data['search'])) $data['search'] = '';
		$records = $this->serverModel->where('s_name','like','%'.$data['search'].'%')->orderBy('id','desc')->paginate(10);
		return $records;
	}

	public function sendCreateNotification($to,$data)
	{
		$createServerNotification = new CreateServerNotification($data);
		Notification::send($to,$createServerNotification);
	}

	public function getServer($id)
	{
		return new ServerResource($this->serverModel->find($id));
	}

	public function getModel()
	{
		return $this->serverModel;
	}

	
}

