<?php 

namespace App\Repositories;

use App\Http\Resources\RequestResource;
use App\Http\Resources\StatusResource;
use App\Models\RequestModel;
use App\Models\StatusModel;
use App\Notifications\RequestNotification;
use App\PaginateCollection;
use Auth;
use Carbon\Carbon;
use DB;
use Notification;




class RequestRepository 
{
	protected $requestModel;
	protected $statusModel;

	//private $queryOrder = "CASE WHEN _status = 'Inprocess' THEN 1 WHEN _status = 'new' THEN 2 WHEN _status = 'Solved' THEN 3 WHEN _status = 'Other' THEN 4 WHEN _status = 'NotFound' THEN 5 END ";


	public function __construct(RequestModel $requestModel,StatusModel $statusModel){
		$this->requestModel = $requestModel;
		$this->statusModel = $statusModel;
	}

	public function store($data,$status){
		//$data['_status'] = $status['status'];
		$data['created'] = Carbon::now();
		$data['user_id']=Auth::user()->username;
		$record = $this->requestModel->create($data);
		$status['user_id'] = Auth::user()->username;
		$status['created'] = Carbon::now();
		$record->status()->create($status);
		return new RequestResource($record);
	}

	public function list($data){
		$records = $this->requestModel->with(['group','user','status'])->orderBy('id','desc')->paginate($data['limit']);
		return RequestResource::collection($records);
	}

	public function filterData($model,$data)
	{
		$columns = [
			"subject","type","priority","user_id","_status"
		];

		foreach( $columns as $column )
		{
			if($data[$column])
			{
				if($column=="_status")
				{
					$model=$model
					->whereRaw(\DB::raw("(select status from status where status.request_id=request.id order by status.id desc  limit 1 ) = '".$data["_status"]."'"));
					continue; 
				}
			$model = $model->where( function( $query ) use ( $data ){ $query->where($column,$data[$column] )->orWhereNull($column); } );
			}
			
		}

		return $model;
	}

	
	

	public function filterForRoot($data){

		$records = $this->filterData($this->requestModel,$data) 
		->orderBy($data['sortObject']['champ'],$data['sortObject']['order'])
		->paginate($data["limit"]);

		return RequestResource::collection($records);

	}

	

	public function filterForManager($data){

		$records = $this->filterData(Auth::user()->group->requests(),$data) 
		->orderBy($data['sortObject']['champ'],$data['sortObject']['order'])
		->paginate($data["limit"]);
		return RequestResource::collection($records);
	}

	public function filterForUser($data)
	{
		$records = $this->filterData(Auth::user()->requests(),$data) 
		->orderBy($data['sortObject']['champ'],$data['sortObject']['order'])
		->paginate($data["limit"]);
		return RequestResource::collection($records);
	}

	public function filterByStatus($data){
		$records = $this->requestModel->whereHas('status',function($query) use ($data){
			$query->where('status','like','%'.$data['criteria'].'%');
		})->orderBy('id','desc')->paginate($data['limit']);
		return RequestResource::collection($records);
	}

	public function filterByType($data){
		$records = $this->requestModel->where('type','like','%'.$data['criteria'].'%')->orderBy('id','desc')->paginate($data['limit']);
		return RequestResource::collection($records);
	}

	public function filterByGroup($data){
		$records = $this->requestModel->whereHas('group',function($query) use ($data){
			$query->where('name','like','%'.$data['criteria'].'%');
		})->orderBy('id','desc')->paginate($data['limit']);
		return RequestResource::collection($records);
	}

	public function filterByPriority($data){
		$records = $this->requestModel->where('priority','like','%'.$data['criteria'].'%')->orderBy('id','desc')->paginate($data['limit']);
		return RequestResource::collection($records);
	}

	public function all(){
		return $this->requestModel->all();
	}

	public function requestsForRoot($data)
	{
		if($data['requestFilter']) 
		{
			$records = $this->requestModel
			->whereRaw(\DB::raw("(select status from status where status.request_id=request.id order by status.id desc  limit 1 ) = '".$data["requestFilter"]."'"))
			->orderBy('id','desc')
			->paginate(10);
		}else
		{
			$records = $this->requestModel
			->where(function($query){
				$query
			->whereRaw(\DB::raw("(select status from status where status.request_id=request.id order by status.id desc  limit 1 ) = 'New'")) 
		->orWhereRaw(\DB::raw("(select status from status where status.request_id=request.id order by status.id desc  limit 1 ) = 'Inprocess'")) ;
				
			})
			->orderBy('id','desc')
			->paginate(10);
		}
		
		return RequestResource::collection($records);
	}

	public function requestsForUser($data)
	{
		if($data['requestFilter'])
		{
			$records = Auth::user()->requests()
			->whereRaw(\DB::raw("(select status from status where status.request_id=request.id order by status.id desc  limit 1 ) = '".$data["requestFilter"]."'"))
			->orderBy('id','desc')
			->paginate(10);
		}
		else
		{
			$records = Auth::user()->requests()
			->where(function($query){
				$query
			->whereRaw(\DB::raw("(select status from status where status.request_id=request.id order by status.id desc  limit 1 ) = 'New'")) 
		->orWhereRaw(\DB::raw("(select status from status where status.request_id=request.id order by status.id desc  limit 1 ) = 'Inprocess'")) ;
				
			})
			->orderBy('id','desc')
			->paginate(10);
		}
		
		return RequestResource::collection($records);
	}

	public function requestsForManager($data)
	{
		if($data['requestFilter'])
		{
			$records = Auth::user()->group->requests()
			//->where('_status',$data['requestFilter'])
			->orderBy('id','desc')
			->paginate(10);
		}
		else
		{
			$records = Auth::user()->group->requests()
			//->where(function($query){$query->where('_status','New')->orWhere('_status','Inprocess');})
			//->orderBy('_status','asc')
			->orderBy('id','desc')
			->paginate(10);
		}
		
		return RequestResource::collection($records);
	}

	public function sendNotificationForRoot($requestResource,$userRepository)
	{
		$requestNotification = new RequestNotification($requestResource);
		Notification::send($userRepository->roots(),$requestNotification);
	}

	public function sendNotificationForOthers($requestResource,$userRepository)
	{
		$requestNotification = new RequestNotification($requestResource);
		Notification::send($userRepository->groupUsers(),$requestNotification);
	}

	public function getRequests($data)
	{
		if(!isset($data['search'])) $data['search'] = '';
		$records = $this->requestModel->where('request','like','%'.$data['search'].'%')->orderBy('id','desc')->paginate(10);
		return RequestResource::collection($records);
	}



}