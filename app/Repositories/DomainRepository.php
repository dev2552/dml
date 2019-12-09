<?php 

namespace App\Repositories;

use App\Http\Resources\DomainResource;
use App\Models\DomainModel;
use App\Models\GroupModel;
use App\Models\RegistrarModel;
use App\Notifications\CreateDomainNotification;
use App\Repositories\GroupRepository;
use App\Repositories\RegistrarRepository;
use Notification;
use Auth;





class DomainRepository 
{
	protected $domainModel;

	protected $registrarRepository;

	protected $groupRepository;

	public function __construct(DomainModel $domainModel)
	{
	  $this->domainModel = $domainModel;
	  $this->registrarRepository = new RegistrarRepository(new RegistrarModel());
	  $this->groupRepository = new GroupRepository(new GroupModel());
	}

	public function fetch($data)
	{
		$records = $this->domainModel
		->where( function( $query ) use ( $data ){ $query->where('group_id','like','%'.$data['group_id'].'%')->orWhereNull('group_id'); } )
		->where( function( $query ) use ( $data ){ $query->where('domain','like','%'.$data['domain'].'%')->orWhereNull('domain'); } )
		->where( function( $query ) use ( $data ){ $query->where('registrar_id','like','%'.$data['registrar_id'].'%')->orWhereNull('registrar_id'); } )
		->where( function( $query ) use ( $data ){ $query->where('entered','like','%'.$data['entered'].'%')->orWhereNull('entered'); } )
		->where( function( $query ) use ( $data ){ $query->where('datex','like','%'.$data['datex'].'%')->orWhereNull('datex'); } )
		->where( function( $query ) use ( $data ){ $query->where('datec','like','%'.$data['datec'].'%')->orWhereNull('datec'); } )
		//->where( function( $query ) use ( $data ){ $query->where('created_by','like','%'.$data['created_by'].'%')->orWhereNull('created_by'); } )
		//->where( function( $query ) use ( $data ){ $query->where('price','like','%'.$data['price'].'%')->orWhereNull('price'); } )
		//->where( function( $query ) use ( $data ){ $query->where('currency','like','%'.$data['currency'].'%')->orWhereNull('currency'); } )
		->where( function( $query ) use ( $data ){ $query->where('status','like','%'.$data['status'].'%')->orWhereNull('status'); } )
		->where( function( $query ) use ( $data ){ $query->where('is_active','like','%'.$data['is_active'].'%')->orWhereNull('is_active'); } )
    	->orderBy($data['sortObject']['champ'],$data['sortObject']['order'])
    	->paginate($data['limit']);
		return DomainResource::collection($records);
	}

	public function all()
	{
		return DomainResource::collection($this->domainModel->orderBy('id','desc')->get());
	}

	public function create(array $data)
	{
		//$data['created_by'] = Auth::user()->username;
		//$data['alreadyAssigned'] = 0;
		$data['datec']=now();
		$data["prod_status"] = 0;
		$data["is_active"]=1;
		$data["is_expired"]=0;
		$record = $this->domainModel->create($data);
		return new DomainResource($record);
	}

	public function update(array $data,$id)
	{
		$record = $this->domainModel->find($id);
		if(!Auth::user()->can('index',$record)) throw new AuthorizationException;
		//$data['updated_by'] = Auth::user()->username;
		$record->update($data);
		return new DomainResource($record);
	}

	public function delete($id)
	{
		$record = $this->domainModel->find($id);
		if(!Auth::user()->can('index',$record)) throw new AuthorizationException;
		$record->delete();
		return new DomainResource($record);
	}

	public function show($id)
	{
		return $this->domainModel->findOrFail();
	}

    public function getDomainModel()
    {
        return $this->domainModel;
    }

    public function setDomainModel($domainModel)
    {
        $this->domainModel = $domainModel;

        return $this;
    }

    public function getAllRegistrars()
    {
    	return $this->registrarRepository->all();
    }

    public function getAllGroups()
    {
    	return $this->groupRepository->all();
    }

    public function getNewDomains($data)
    {
    	if(!isset($data['search'])) $data['search'] = '%';
    	//$records = $this->domainModel->where('status','NEW')->get();
    	$records = $this->domainModel
    	->where('domain','like',$data['search'])
    	->where('status','NEW')
    	->where("is_active",1)
    	->orderBy('id','desc')->paginate(10);
    	return DomainResource::collection($records);
    }

    public function getDomains($data)
    {
    	if(!isset($data['search'])) $data['search'] = '';
    	$records = $this->domainModel
    	->where('domain','like','%'.$data['search'].'%')
    	->where("is_active",1)
    	->orderBy('id','desc')->paginate(10);
    	return DomainResource::collection($records);
    }

    public function sendCreateNotification($to,$data)
    {
    	$createDomainNotification = new CreateDomainNotification($data);
    	Notification::send($to,$createDomainNotification);
    }

    public function getDomain($id)
    {
    	return new DomainResource($this->domainModel->find($id));
    }

     public function getDomainByDomain($domain)
    {
    	return new DomainResource($this->domainModel->where('domain',$domain)->first());
    }

    public function getModel()
    {
    	return $this->domainModel;
    }

}