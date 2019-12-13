<?php

namespace App\Http\Controllers;

use App\Models\GroupModel;
use App\Repositories\GroupRepository;
use Illuminate\Http\Request;

class GroupController extends Controller
{

    protected $groupRepository;

    public function __construct(GroupModel $groupModel)
    {
        $this->groupRepository = new GroupRepository($groupModel);
    }
    
    public function index()
    {
        $this->authorize('index',GroupModel::class);
        return view('groups.index');
    }

  
    public function store(Request $request)
    {
        $request->validate(["name"=>"required"]);
         $this->authorize('index',GroupModel::class);
        return $this->groupRepository->store($request->all());
    }
 
    public function update(Request $request, $id)
    {
         $this->authorize('index',GroupModel::class);
        return $this->groupRepository->update($request->all(),$id);
    }

    
    public function destroy($id)
    {
         $this->authorize('index',GroupModel::class);
        return $this->groupRepository->delete($id);
    }

    public function filter(Request $request)
    {
         $this->authorize('index',GroupModel::class);
        return $this->groupRepository->filter($request->filter);
    }

    public function getAll()
    {
        $this->authorize('all',GroupModel::class);
        return $this->groupRepository->getAll();
    }

   
}
