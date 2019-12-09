<?php 

namespace App\Repositories;

use App\Http\Resources\DomainResource;
use App\Http\Resources\RegistrarResource;
use App\Models\RegistrarModel;
use Auth;
use Illuminate\Auth\Access\AuthorizationException;



class RegistrarRepository 
{
	protected $registrarModel;

	public function __construct(RegistrarModel $registrarModel)
	{
	  $this->registrarModel = $registrarModel;
	}

	public function fetch($data)
	{
		$records = $this->registrarModel
		->where( function( $query ) use ( $data ){ $query->where( "name","like","%".$data["name"]."%" )->orWhereNull("name"); } )
		->where( function( $query ) use ( $data ){ $query->where( "company","like","%".$data["company"]."%" )->orWhereNull("company"); } )
		->where( function( $query ) use ( $data ){ $query->where( "website","like","%".$data["website"]."%" )->orWhereNull("website"); } )
		->where( function( $query ) use ( $data ){ $query->where( "eml_contact","like","%".$data["eml_contact"]."%" )->orWhereNull("eml_contact"); } )
		//->where( function( $query ) use ( $data ){ $query->where( "created_by","like","%".$data["created_by"]."%" )->orWhereNull("created_by"); } )
		->where( function( $query ) use ( $data ){ $query->where( "is_active","like","%".$data["is_active"]."%" )->orWhereNull("is_active"); } )
		->orderBy($data['sortObject']['champ'],$data['sortObject']['order'])
		->paginate($data['limit']);
		return RegistrarResource::collection($records);
	}

	public function all()
	{
		return $this->registrarModel->all();
	}

	public function create(array $data)
	{
		//$data['created_by'] = Auth::user()->username;
		return $this->registrarModel->create($data);
	}

	public function update(array $data,$id)
	{
		$record = $this->registrarModel->find($id);
		if(!Auth::user()->can('index',$record)) throw new AuthorizationException;
		//$data['updated_by'] = Auth::user()->username;
		$record->update($data);
		return response()->json(true);
	}

	public function delete($id)
	{
		$record = $this->registrarModel->find($id);
		if(!Auth::user()->can('index',$record)) throw new AuthorizationException;
		//$record->update(['deleted_by'=>Auth::user()->username]);
		$record->delete();
		return response()->json(true);
	}

	public function show($id)
	{
		return $this->registrarModel->findOrFail();
	}

    public function getRegistrarModel()
    {
        return $this->registrarModel;
    }

    public function setRegistrarModel($registrarModel)
    {
        $this->registrarModel = $registrarModel;

        return $this;
    }

    public function with($relation)
    {
    	return $this->registrarModel->with($relation);
    }

    public function getRegistrars($data)
    {
    	if(!isset($data['search'])) $data['search'] = '';
    	$records = $this->registrarModel
    	->where('name','like','%'.$data['search'].'%')
    	->where("is_active",1)
    	->orderBy('id','desc')
    	->paginate(10);
    	return RegistrarResource::collection($records);
    }

    public function getDomainsList($id)
    {
    	return DomainResource::collection($this->registrarModel->find($id)->domains);
    }

    public function getRegistrar($id)
    {
    	$record = $this->registrarModel->find($id);
    	return new RegistrarResource($record);
    }

    
}