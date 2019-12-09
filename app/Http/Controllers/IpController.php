<?php

namespace App\Http\Controllers;

use App\Exports\IpsExport;
use App\Http\Requests\StoreIp;
use App\Models\GroupModel;
use App\Models\IpModel;
use App\Models\PaymentModel;
use App\Models\ProviderModel;
use App\Models\ServerModel;
use App\Repositories\GroupRepository;
use App\Repositories\IpRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\ProviderRepository;
use App\Repositories\ServerRepository;
use App\Repositories\UserRepository;
use App\User;
use Auth;
use Excel;
use Illuminate\Http\Request;

class IpController extends Controller
{

	protected $ipRepository;
	protected $serverRepository;
    protected $providerRepository;
    protected $groupRepository;
    protected $paymentRepository;
    protected $userRepository;
    protected $user;
 
	public function __construct(IpRepository $ipRepository,ServerRepository $serverRepository,ProviderRepository $providerRepository,GroupRepository $groupRepository,PaymentRepository $paymentRepository,UserRepository $userRepository)
	{
		$this->ipRepository = $ipRepository;
		$this->serverRepository = $serverRepository;
        $this->providerRepository = $providerRepository;
        $this->groupRepository = $groupRepository;
        $this->paymentRepository = $paymentRepository;
        $this->userRepository = $userRepository;
        $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
	}

    public function index()
    {
        $this->authorize('index',IpModel::class);
    	$servers = $this->serverRepository->all();
        $providers = $this->providerRepository->all();
        $groups = $this->groupRepository->all();
    	return view('ips.index',['servers'=>$servers,'providers'=>$providers,'groups'=>$groups]);
    }

    public function all(Request $request)
    {
        $this->authorize('index',IpModel::class);
    	return $this->ipRepository->all($request->count);
    }

    public function store(StoreIp $request)
    {
        $this->authorize('create',IpModel::class);
        $data = $request->all();
        if(count($data) == 0) return response()->json(false);
        $ips = explode("\n",$data['ips']);
        foreach($ips as $ip)
        {
            $_data = $data;
            $_data['ip'] = $ip;
            $record = $this->ipRepository->store($_data);
            $_paymentData = [
                'type'=>'Ip',
                'ip_id'=>$record->id,
                'group_id'=>$record->group->id,
                'unit_price'=>1,
                'quantity'=>1,
                'total_price'=>$record->price,
                'currency'=>$record->currency,
                'description'=>'Auto Payment',
                //'created_by'=>$this->user->username,
                'payment_date'=>now(),
                'create_date'=>now()
            ];
            $paymentRecord = $this->paymentRepository->store($_paymentData);
        }
        $data['username'] = Auth::user()->username;
        $this->ipRepository->sendCreateNotification($this->userRepository->roots(),$data);
        return response()->json(true);
    }

    public function update(Request $request,$id)
    {
         $this->authorize('create',IpModel::class);
    	return $this->ipRepository->update($request->all(),$id);
    }

    public function destroy($id)
    {
        $this->authorize('create',IpModel::class);
    	return $this->ipRepository->destroy($id);
    }

    public function filter(Request $request)
    {
        $this->authorize('index',IpModel::class);
    	if($this->user->isRoot()) return $this->ipRepository->filterForRoot($request->all());
        else return $this->ipRepository->filterForOthers($request->all());
    }

    public function export(Request $request)
    {
        $this->authorize('create',IpModel::class);
        $ipsExport = new IpsExport($this->ipRepository->getModel(),$request->all());
        return Excel::download($ipsExport,'ips.'.now().'.xlsx');
    }

    public function storeMany(Request $request)
    {
        $this->authorize('index',IpModel::class);
        return $this->ipRepository->storeMany($request->all());
    }

    public function getIps(Request $request)
    {
         $this->authorize('index',IpModel::class);
         return $this->ipRepository->getIps($request->all());
    }
}
