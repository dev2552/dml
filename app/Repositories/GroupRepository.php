<?php 

namespace App\Repositories;

use App\Http\Resources\GroupResource;
use App\Models\GroupModel;
use Auth;
use Carbon\Carbon;

class GroupRepository
{
	protected $groupModel;

	public function __construct(GroupModel $groupModel)
	{
		$this->groupModel = $groupModel;
	}

	public function store(array $data)
	{
		//$data['created_by'] = Auth::user()->username;
		$data['created'] = Carbon::now();
		$record = $this->groupModel->create($data);
		return new GroupResource($record);
	}

	public function update(array $data,$id)
	{
		$record = $this->groupModel->find($id);
		//$data['updated_by'] = Auth::user()->username;
		$data['created'] = $record->created->format('Y-m-d H:i:s');
		$record->update($data);
		return new GroupResource($record);
	}

	public function delete($id)
	{
		$record = $this->groupModel->find($id);
		//$record->update(['deleted_by'=>Auth::user()->username]);
		$record->delete();
		return new GroupResource($record);
	}

	public function all()
	{
		return $this->groupModel->all();
	}

	public function filter($filter)
	{
		$records = $this->groupModel->where('name','like','%'.$filter.'%')->orderBy('id','desc')->paginate(20);
		return GroupResource::collection($records);
	}

	public function getAll()
	{
		$records = $this->groupModel->all();
		return GroupResource::collection($records);
	}

	public function getGroup($id)
	{
		return new GroupResource($this->groupModel->find($id));
	}
}