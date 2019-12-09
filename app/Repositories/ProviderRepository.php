<?php 

namespace App\Repositories;

use App\Http\Resources\ProviderResource;
use App\Models\ProviderModel;
use Auth;
use Carbon\Carbon;

class ProviderRepository 
{
	protected $providerModel;

	public function __construct(ProviderModel $providerModel)
	{
		$this->providerModel = $providerModel;
	}

	public function store($data)
	{
		//$data['created_by'] = Auth::user()->username;
		$data['created'] = Carbon::now();
		$record = $this->providerModel->create($data);
		return new ProviderResource($record);
	}

	public function filter($data)
	{
		$start = \Carbon\Carbon::parse( $data["start"] )->startOfDay();
		$end = \Carbon\Carbon::parse( $data["end"] )->endOfDay();
		$records = $this->providerModel
		->where( function( $query ) use ( $data ){ $query->where( "name","like","%".$data["name"]."%" )->orWhereNull("name"); } )
		->where( function( $query ) use ( $data ){ $query->where( "country","like","%".$data["country"]."%" )->orWhereNull("country"); } )
		->where( function( $query ) use ( $data ){ $query->where( "inscr_email","like","%".$data["inscr_email"]."%" )->orWhereNull("inscr_email"); } )
		->where( function( $query ) use ( $data ){ $query->where( "created","like","%".$data["created"]."%" )->orWhereNull("created"); } )
		->where( function( $query ) use ( $data ){ $query->where( "status","like","%".$data["status"]."%" )->orWhereNull("status"); } )
		->where( function( $query ) use ( $data ){ $query->where( "type","like","%".$data["type"]."%" )->orWhereNull("type"); } )
		//->where( function( $query ) use ( $data ){ $query->where( "created_by","like","%".$data["created_by"]."%" )->orWhereNull("created_by"); } )
		->whereBetween('created',[ $start,$end ])
		->orderBy($data['sortObject']['champ'],$data['sortObject']['order'])
		->paginate($data['limit']);
		return ProviderResource::collection($records);
	}

	public function update($data,$id)
	{
		$record = $this->providerModel->find($id);
		//$data['updated_by'] = Auth::user()->username;
		$record->update($data);
		return new ProviderResource($record);
	}

	public function destroy($id)
	{
		$record = $this->providerModel->find($id);
		//$record->update(['deleted_by'=>Auth::user()->username]);
		$record->delete();
		return new ProviderResource($record);
	}

	public function all()
	{
		$records = $this->providerModel->all();
		return $records;
	}

	public function getAll($data)
	{
		if(!isset($data['search'])) $data['search'] = '';
		$records = $this->providerModel->where('name','like','%'.$data['search'].'%')->orderBy('id','desc')->paginate(10);
		return ProviderResource::collection($records);
	}

	public function getProvider($id)
	{
		$record = $this->providerModel->find($id);
		return new ProviderResource($record);
	}
}