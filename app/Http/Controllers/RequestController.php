<?php

namespace App\Http\Controllers;

use App\Http\Resources\RequestResource;
use App\Models\GroupModel;
use App\Models\RequestModel;
use App\Models\StatusModel;
use App\Repositories\GroupRepository;
use App\Repositories\RequestRepository;
use App\Repositories\StatusRepository;
use App\Repositories\UserRepository;
use App\User;
use Auth;
use Illuminate\Http\Request;


class RequestController extends Controller
{
    protected $requestRepository;
    protected $groupRepository;
    protected $userRepository;
    protected $user;


    public function __construct(RequestModel $requestModel,GroupModel $groupModel,User $userModel,StatusModel $statusModel){
    	$this->requestRepository = new RequestRepository($requestModel,$statusModel);
        $this->groupRepository = new GroupRepository($groupModel);
        $this->userRepository = new UserRepository($userModel);
         $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index(){
        $this->authorize('index',RequestModel::class);
        $groups = $this->groupRepository->all();
    	return view('requests/index',['groups'=>$groups]);
    }

    public function store(Request $request){
        $this->authorize('index',RequestModel::class);
        $data = ['status' => 'new','explication' => 'New Request'];
    	$record = $this->requestRepository->store($request->all(),$data);
        if($this->user->isRoot())
        {
            $this->requestRepository->sendNotificationForRoot($record,$this->userRepository);
        }else
        {
            $this->requestRepository->sendNotificationForRoot($record,$this->userRepository);
            $this->requestRepository->sendNotificationForOthers($record,$this->userRepository);
        }

        return $record;

    }

    public function list(Request $request){
        $this->authorize('index',RequestModel::class);
        return $this->requestRepository->list($request->all());
    }

    public function filter(Request $request){
        $this->authorize('index',RequestModel::class);
        if($this->user->isRoot()) return $this->requestRepository->filterForRoot($request->all());
        else if($this->user->isUser()) return $this->requestRepository->filterForUser($request->all());
        else return $this->requestRepository->filterForManager($request->all());
    }

    public function getRequests(Request $request)
    {
        return $this->requestRepository->getRequests($request->all());
    }
}
