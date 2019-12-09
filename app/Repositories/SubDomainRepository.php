<?php 

namespace App\Repositories;

use App\Http\Resources\SubDomainResource;
use App\Models\SubDomainModel;
use Auth;

class SubDomainRepository
{
	protected $subDomainModel;

	public function __construct(SubDomainModel $subDomainModel)
	{
		$this->subDomainModel = $subDomainModel;
	}

	public function create($data)
	{
		$this->subDomainModel->create($data);
	}

	public function filter($data)
	{
		if(!$data['ip_id']) $data['ip_id'] = '%';
		if(!$data['domain_id']) $data['domain_id'] = '%';
		$records = $this->subDomainModel
		->where('ip_id','like','%'.$data['ip_id'].'%')
		->where('domain_id','like','%'.$data['domain_id'].'%')
		->where( function( $query ) use ( $data ){ $query->where( "subdomain","like","%".$data[ "subdomain" ]."%" )->orWhereNull( "subdomain" ); } )
		->where('is_active','like','%'.$data['is_active'].'%')
		->where('created','like','%'.$data['created'].'%')
		->orderBy('id','desc')
		->paginate($data['limit']);

		return SubDomainResource::collection($records);
	}

	public function update($id,$data)
	{
		$record = $this->subDomainModel->find($id);
		//$data['user_id'] = Auth::user()->username;
		$record->update($data);
		return new SubDomainResource($record);
	}
}