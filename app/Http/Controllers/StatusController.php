<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use App\Models\ServerModel;
use App\Models\StatusModel;
use App\Repositories\StatusRepository;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Auth;

class StatusController extends Controller
{
    protected $statusRepository;
    protected $userRepository;

    public function __construct(StatusModel $statusModel,RequestModel $requestModel,ServerModel $serverModel,User $userModel){
    	$this->statusRepository = new StatusRepository($statusModel,$requestModel,$serverModel);
        $this->userRepository = new UserRepository($userModel);
    }

    public function store(Request $request,$requestId){
        $this->authorize('index',StatusModel::class);
    	$record = $this->statusRepository->store($request->all(),$requestId);
        $data = [
            'request'=>$record->request->request,
            'status'=>$record->status,
            'username'=>Auth::user()->username,
            'subject'=>$record->request->subject];
        $this->statusRepository->sendNotificationForRoot($data,$this->userRepository);
    }

    public function addToServer(Request $request,$serverId){
        $this->authorize('index',StatusModel::class);
    	return $this->statusRepository->addToServer($request->all(),$serverId);
    }

}
