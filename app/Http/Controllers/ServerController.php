<?php

namespace App\Http\Controllers;

use App\Exports\ServersExport;
use App\Http\Requests\StoreServer;
use App\Http\Resources\ServerResource;
use App\Models\DomainModel;
use App\Models\GroupModel;
use App\Models\IpModel;
use App\Models\PaymentModel;
use App\Models\ProviderModel;
use App\Models\RequestModel;
use App\Models\RequestServerModel;
use App\Models\ServerModel;
use App\Models\StatusModel;
use App\Repositories\DomainRepository;
use App\Repositories\GroupRepository;
use App\Repositories\IpRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\ProviderRepository;
use App\Repositories\RequestRepository;
use App\Repositories\ServerRepository;
use App\Repositories\StatusRepository;
use App\Repositories\UserRepository;
use App\Services\ServerService;
use App\User;
use Auth;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;


class ServerController extends Controller
{

    protected $serverRepository;
    protected $groupRepository;
    protected $providerRepository;
    protected $domainRepository;
    protected $requestRepository;
    protected $ipRepository;
    protected $paymentRepository;
    protected $statusRepository;
    protected $userRepository;
    protected $user;
    protected $serverService;

    public function __construct(ServerRepository $serverRepository,GroupRepository $groupRepository,ProviderRepository $providerRepository,DomainRepository $domainRepository,RequestRepository $requestRepository,IpRepository $ipRepository,PaymentRepository $paymentRepository,StatusRepository $statusRepository,UserRepository $userRepository,ServerService $serverService)
    {
        $this->serverRepository = $serverRepository;
        $this->groupRepository =$groupRepository;
        $this->providerRepository = $providerRepository;
        $this->domainRepository = $domainRepository;
        $this->requestRepository = $requestRepository;
        $this->ipRepository = $ipRepository;
        $this->paymentRepository = $paymentRepository;
        $this->statusRepository = $statusRepository;
        $this->userRepository = $userRepository;
        $this->serverService = $serverService;
        $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
    }
    
    public function index()
    {
        $this->authorize('index',ServerModel::class);
        $groups = $this->groupRepository->all();
        $providers = $this->providerRepository->all();
        $domains = $this->domainRepository->all();
        return view('servers.index',['groups'=>$groups,'providers'=>$providers,'domains'=>$domains]);
    }

   
  

  
    public function store(StoreServer $request)
    {
        $this->authorize('create',ServerModel::class);
       if($this->serverService->checkDomain($request->all())) return response()->json(['message'=>'Domain Already Assigned'],412);
        $data = ['status'=>'pen','explication'=>'New Server','user_id'=>$this->user->id];
        $record =  $this->serverRepository->store($request->all(),$data);
        $this->domainRepository->update(['group_id'=>$record->group->id,'status'=>'PRODUCTION','alreadyAssigned'=>true],$this->domainRepository->getDomainByDomain($record->main_domain)->id);
       $paymentData = ['type'=>'Server','server_id'=>$record->id,'group_id'=>$record->group->id,'unit_price'=>1,'quantity'=>1,'total_price'=>$record->price,'currency'=>$record->currency,'description'=>'Auto Payment','user_id'=>$this->user->username,'payment_date'=>now(),'created'=>now(),];
       $paymentRecord = $this->paymentRepository->store($paymentData);
        $_data = $request->all();
        $_data = $_data['listIps'];
        if(count($_data) > 0)
        {
            foreach(explode("\n",$_data['ips']) as $ip)
            {
                $_ip = $_data;
                $_ip['ip'] = $ip;
                $_ip['server_id'] = $record->id;
                $_ip['ip_status']='New';
                $rcd = $this->ipRepository->store($_ip);
                $_paymentData = ['type'=>'Ip','ip_id'=>$rcd->id,'group_id'=>$record->group->id,'unit_price'=>1,'quantity'=>1,'total_price'=>$rcd->price,'currency'=>$rcd->currency,'description'=>'Auto Payment','user_id'=>$this->user->username,"date_payment"=>now()];
                $this->paymentRepository->store($_paymentData);
            }
            $_data['username'] = $this->user->username;
            if( !isset( $_data["ip_range"] ) ) $_data["ip_range"] = "";
            $this->ipRepository->sendCreateNotification($this->userRepository->roots(),$_data);
        }
        
        $serverData = ['currency'=>$record->currency,
        'date_expiration'=>$record->date_expiration->format('Y-m-d'),
        'date_order'=>$record->date_order->format('Y-m-d'),
        'date_purchase'=>$record->date_purchase->format('Y-m-d'),
        'description'=>$record->description,
        'main_domain'=>$record->main_domain,
        'group'=>$record->group->name,
        's_name'=>$record->s_name,
        'note'=>$record->note,
        'numberIps'=>$record->numberIps,
        'price'=>$record->price,
        'provider'=>$record->provider->inscr_email,
        'type'=>$record->type,
        'username'=>$this->user->username];
        $this->serverRepository->sendCreateNotification($this->userRepository->roots(),$serverData);
        return $record;
    }

    
   

   
    public function update(Request $request, $id)
    {
        $this->authorize('create',ServerModel::class);
        $record = $this->serverRepository->update($request->all(),$id);
        $this->domainRepository->update(['group_id'=>$record->group->id,'status'=>'In-Prod'],$this->domainRepository->getDomainByDomain($record->main_domain)->id);
        return $record;
    }

  
    public function fetch(Request $request)
    {
        $this->authorize('index',ServerModel::class);
        if($this->user->isRoot())  return $this->serverRepository->fetchForRoot($request->all());
        else return $this->serverRepository->fetchForOthers($request->all());
    }

    public function linkRS()
    {
        $this->authorize('index',RequestServerModel::class);
        $servers = $this->serverRepository->all();
        $requests = $this->requestRepository->all();
        return view('linkRS',['servers'=>$servers,'requests'=>$requests]);
    }

    public function export(Request $request)
    {
        $this->authorize('create',ServerModel::class);
        $serversExport = new ServersExport($this->serverRepository->getModel(),$request->all());
        return Excel::download($serversExport,'servers.'.now().'.xlsx');
    }

    public function delete($id)
    {
        $this->authorize('create',ServerModel::class);
        return $this->serverRepository->delete($id);
    }

    public function getAllServers(Request $request)
    {
         $this->authorize('index',ServerModel::class);
         return $this->serverRepository->getAllServers($request->all());
    }

    public function renewalServers(Request $request)
    {
        
        $this->authorize('create',ServerModel::class);
        foreach($request->all() as $data)
        {
            $paymentData = ['type'=>'Server',
            'server_id'=>$data['id'],
            'group_id'=>$data['group']['id'],
            'unit_price'=>1,
            'quantity'=>1,
            'total_price'=>$data['price'],
            'currency'=>$data['currency'],
            'description'=>'Renewal',
            'user_id'=>$this->user->id,
            'payment_date'=>now(),
            'created'=>now(),];
            
            $paymentRecord = $this->paymentRepository->store($paymentData);
            $this->paymentRepository->sendNotification($paymentRecord,$this->userRepository,true,true);

            $dateExpiration = new carbon($data['date_expiration']);
            $data['date_expiration'] = $dateExpiration->addMonth();
            $this->serverRepository->update($data,$data['id']);

            $statusData = ['status'=>'toRet','explication'=>'Auto Payment'];
            $this->statusRepository->addToServer($statusData,$data['id']);
        }
        return response()->json(true);
    }

    public function toggleStatus(Request $request)
    {
        $this->authorize('create',ServerModel::class);
        foreach($request->list as $data)
        {
            if($request->status == 'canc')
            {
                $data['date_cancelation'] = now();
               
            }
            $statusData = ['status'=>$request->status];
            $this->statusRepository->addToServer($statusData,$data['id']);

            $data['date_expiration'] = $request->dateExpiration;
            $data['last_status'] = $statusData['status'];
            $this->serverRepository->update($data,$data['id']);
        }
        return response()->json(true);
    }

    public function toggleGroup(Request $request)
    {
         $this->authorize('create',ServerModel::class);

         foreach($request->list as $data)
         {
            $_data = ['lastGroup'=>$this->serverRepository->getServer($data['id'])->group->name,
            'currentGroup'=>$this->groupRepository->getGroup($request->groupId)->name,
            'username'=>$this->user->username,
            'server'=>$this->serverRepository->getServer($data['id'])->s_name];
            $this->serverService->sendServerGroupChangeNotification($this->userRepository->roots(),$_data);


            $data['group']['id'] = $request->groupId;
            $record = $this->serverRepository->update($data,$data['id']);

            $this->domainRepository->update(['group_id'=>$record->group->id,'status'=>'PRODUCTION'],$this->domainRepository->getDomainByDomain($record->main_domain)->id);
         }
         return response()->json(true);
    }

    public function serversToReturn(Request $request)
    {
        $this->authorize('create',ServerModel::class);
        return $this->serverService->serversToReturn($request->all());
    }

   
}
