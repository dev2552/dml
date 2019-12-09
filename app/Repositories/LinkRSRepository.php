<?php 

namespace App\Repositories;

use App\Models\RequestServerModel;
use DB;

class LinkRSRepository 
{

	protected $requestServerModel;

	public function __construct(RequestServerModel $requestServerModel){
		$this->requestServerModel = $requestServerModel;

	}
	public function save($data){
		return $this->requestServerModel->create($data);
	}

	public function paginate($data){
		return $this->requestServerModel->where('subject','like','%'.$data['criteria'].'%')->orWhere('server','like','%'.$data['criteria'].'%')->orderBy('id','desc')->paginate(10);
	}
}