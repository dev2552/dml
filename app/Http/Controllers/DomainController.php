<?php

namespace App\Http\Controllers;

use App\Exports\DomainsExport;
use App\Http\Requests\StoreDomain;
use App\Models\DomainModel;
use App\Models\PaymentModel;
use App\Repositories\DomainRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\UserRepository;
use App\Services\DomainService;
use App\Services\PaymentService;
use App\User;
use Auth;
use Excel;
use Illuminate\Http\Request;

class DomainController extends Controller
{

    protected $domainRepository;
    protected $paymentRepository;
    protected $userRepository;
    protected $user;
    protected $domainService;

    public function __construct(DomainModel $domainModel,PaymentModel $paymentModel,User $userModel,DomainService $domainService)
    {
        $this->domainRepository = new DomainRepository($domainModel);
        $this->paymentRepository = new PaymentRepository($paymentModel);
        $this->userRepository = new UserRepository($userModel);
        $this->domainService = $domainService;
        $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
    }
    
    public function index()
    {
        $this->authorize('index',DomainModel::class);
        $registrars = $this->domainRepository->getAllRegistrars();
        $groups = $this->domainRepository->getAllGroups();
        return view('domains.index',['registrars'=>$registrars,'groups'=>$groups]);
    }

    public function fetch(Request $request)
    {
        $this->authorize('index',DomainModel::class);
        return $this->domainRepository->fetch($request->all());
    }


    public function store(StoreDomain $request)
    {
        $this->authorize('index',DomainModel::class);

        $this->domainService->createMany($request->all());
    }

    public function add(StoreDomain $request)
    {
        $this->authorize('index',DomainModel::class);
        return $this->domainService->create($request->all());
    }

   
    
    public function update(Request $request, $id)
    {
        return $this->domainRepository->update($request->all(),$id);
    }

    
    public function destroy($id)
    {
        return $this->domainRepository->delete($id);
    }

    public function export(Request $request)
    {
        $this->authorize('index',DomainModel::class);
        $domainsExport = new DomainsExport($this->domainRepository->getModel(),$request->all());
        return Excel::download($domainsExport,'domains.'.now().'.xlsx');
    }

    public function getNewDomains(Request $request)
    {
        $this->authorize('all',DomainModel::class);
        return $this->domainRepository->getNewDomains($request->all());
    }

    public function getDomains(Request $request)
    {
        $this->authorize('all',DomainModel::class);
        return $this->domainRepository->getDomains($request->all());
    }

    
}
