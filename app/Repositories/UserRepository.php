<?php 

namespace App\Repositories;

use App\Http\Resources\UserResource;
use App\User;
use Hash;
use Auth;
use App\Models\RoleModel;

class UserRepository 
{
	protected $user;

	public function __construct(User $user){

		$this->user = $user;
	}

	public function store($data){

		$data['password'] = Hash::make($data['password']);

		//$data['created_by'] = Auth::user()->username;

		$data['created'] = now();

		$record = $this->user->create($data);

		return new UserResource($record);
	}


	public function filter($data){

		if(!$data['group_id']) $data['group_id']='%';
		//if(!$data['created_by']) $data['created_by']='%';

		$records = $this->user
		->where(function($query) use ($data){$query->where('username','like','%'.$data['username'].'%')->orWhereNull('username');})
		->where(function($query) use ($data){$query->where('f_name','like','%'.$data['name'].'%')->orWhereNull('f_name');})
		->where(function($query) use ($data){$query->where('email','like','%'.$data['email'].'%')->orWhereNull('email');})
		->where('group_id','like',$data['group_id'])
		//->where( function($query) use ($data){ $query->where('created_by','like',$data['created_by'])->orWhereNull('created_by');})
		->where('active','like','%'.$data['active'].'%')
		->orderBy($data['sortObject']['champ'],$data['sortObject']['order'])
		->paginate($data['limit']);

		return UserResource::collection($records);
	}



	public function update($data,$username){

		$data['group_id'] = $data['group']['id'];

		//$data['updated_by'] = Auth::user()->username;

		$record = $this->user->find($username);

		$record->update($data);

		return new UserResource($record);
	}

	public function destroy($id){

		$record = $this->user->find($id);

		//$record->update(['deleted_by'=>Auth::user()->username]);

		$record->delete();

		return new UserResource($record);
	}



	public function roots()
	{
		$rootRole = RoleModel::find( "root" );
		return $rootRole->users;
	}


	public function groupUsers()
	{
		//return $this->user->find(Auth::user()->username)->group->users->where('role','<>','root')->where('username','<>',Auth::user()->username);
		return auth()->user()->group->users()->where('roule_id','<>','root')->get();
	}


	public function getUsers($data)
	{
		if(!isset($data['search'])) $data['search'] = '';

		$records = $this->user
		->where('username','like','%'.$data['search'].'%')
		->where("active",1)
		->orderBy('username','desc')
		->paginate(10);
		
		return UserResource::collection($records);
	}

	public function getUser()
	{
		return Auth::user();
	}
}