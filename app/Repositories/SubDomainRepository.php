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

	public function  filterData($model,$data)
	{
		$columns = ["ip_id","domain_id","subdomain","is_active","created"];

		foreach ($columns as $column) {
			$model = $this->setModel($column,$model,$data);
		}

		return $model;
	}

	public function setModel($column,$model,$data)
	{
		if($data[$column])
		{
			return $model->where(function($q)use($data,$column){$q->where($column,$data[$column])->orWhereNull($column);});
		}

		return $model;
	}

	public function filter($data)
	{
		$records = $this->filterData($this->subDomainModel,$data)
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