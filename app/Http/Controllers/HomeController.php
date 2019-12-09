<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use App\Models\ServerModel;
use App\Repositories\RequestRepository;
use App\Repositories\ServerRepository;
use App\User;
use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{

    protected $serverRepository;
    protected $requestRepository;
    protected $user;

    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ServerRepository $serverRepository,RequestRepository $requestRepository)
    {
        $this->middleware([ 'auth' , function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        } ] );
        $this->serverRepository = $serverRepository;
        $this->requestRepository = $requestRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $this->authorize('show',User::class);
        return redirect()->route('home');
    }

    public function serversToReturn()
    {
         $this->authorize('show',User::class);
        if($this->user->isRoot()) return $this->serverRepository->serversToReturnForRoot();
        else return $this->serverRepository->serversToReturnForOthers();
    }

    public function serversInProductionWithIssue()
    {
        $this->authorize('show',User::class);
        return $this->serverRepository->serversInProductionWithIssue();
    }

    public function serversInstalling()
    {
         $this->authorize('show',User::class);
        return $this->serverRepository->serversInstalling();
    }

    public function serversExpiration()
    {
        $this->authorize('show',User::class);
        if($this->user->isRoot()) return $this->serverRepository->serversExpirationForRoot();
        else return $this->serverRepository->serversExpirationForOthers();
    }

    public function requests(Request $request)
    {
         $this->authorize('show',User::class);
        if($this->user->isRoot()) return $this->requestRepository->requestsForRoot($request->all());
        elseif($this->user->isManager()) return $this->requestRepository->requestsForManager($request->all());
        else  return $this->requestRepository->requestsForUser($request->all());
    }

    public function servers()
    {
         $this->authorize('show',User::class);
       if($this->user->isRoot()) return $this->serverRepository->serversForRoot();
       else return $this->serverRepository->serversForOthers();
    }

    public function payments()
    {
         $this->authorize('show',User::class);
        if($this->user->isRoot()) return $this->serverRepository->paymentsForRoot();
        else  return $this->serverRepository->paymentsForOthers();
    }
}
