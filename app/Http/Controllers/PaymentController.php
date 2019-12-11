<?php

namespace App\Http\Controllers;

use App\Exports\PaymentsExport;
use App\Models\GroupModel;
use App\Models\PaymentModel;
use App\Models\ServerModel;
use App\Repositories\GroupRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\ServerRepository;
use App\Repositories\UserRepository;
use App\User;
use Excel;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

	protected $serverRepository;
	protected $groupRepository;
	protected $paymentRepository;
    protected $userRepository;

	public function __construct(PaymentRepository $paymentRepository,UserRepository $userRepository,GroupRepository $groupRepository,ServerRepository  $serverRepository){
		$this->serverRepository =  $serverRepository;
		$this->groupRepository = $groupRepository;
		$this->paymentRepository = $paymentRepository;
        $this->userRepository = $userRepository;
	}

    public function index(){
        $this->authorize('index',PaymentModel::class);
    	$servers = $this->serverRepository->all();
    	$groups = $this->groupRepository->all();
    	return view('payments.index',['servers'=>$servers,'groups'=>$groups]);
    }

    public function store(Request $request){
        $this->authorize('index',PaymentModel::class);
    	$record = $this->paymentRepository->store($request->all());
        if(!$record) return response()->json(["message"=>"Invalid data"],403);
        $data = ['created'=>$record->created->format('Y-m-d'),
        'currency'=>$record->currency,
        'description'=>$record->description,
        'group'=>$record->group->name,
        'payment_date'=>$record->payment_date->format('Y-m-d'),
        'server'=>($record->server ? $record->server->s_name: '-'),
        'domain'=>($record->domain ? $record->domain->domain: '-'),
        'ip'=>($record->ip ? $record->ip->ip: '-'),
        'quantity'=>$record->quantity,
        'total_price'=>$record->total_price,
        'type'=>$record->type,
        'unit_price'=>$record->unit_price,
        //'username'=>$record->createdBy->username
        ];
        $this->paymentRepository->sendCreateNotification($this->userRepository->roots(),$data);
    }

    public function filter(Request $request){
         $this->authorize('index',PaymentModel::class);
    	return $this->paymentRepository->filter($request->all());
    }

    public function update(Request $request,$id){
         $this->authorize('index',PaymentModel::class);
    	return $this->paymentRepository->update($request->all(),$id);
    }

    public function destroy($id){
         $this->authorize('index',PaymentModel::class);
    	return $this->paymentRepository->destroy($id);
    }

    public function export(Request $request)
    {
         $this->authorize('index',PaymentModel::class);
         $paymentsExport = new paymentsExport($this->paymentRepository->getModel(),$request->all());
        return Excel::download($paymentsExport,'payments.'.now().'.xlsx');
    }
}
