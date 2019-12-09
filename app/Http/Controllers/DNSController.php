<?php

namespace App\Http\Controllers;

use App\Models\SubDomainModel;
use App\Services\DomainService;
use App\Services\GodaddyService;
use App\Services\NamecheapService;
use App\Services\RegistrarService;
use App\Services\ServerService;
use Illuminate\Http\Request;

class DNSController extends Controller
{

	protected $godaddyService;
    protected $registrarService;
    protected $serverService;
    protected $domainService;
    protected $namecheapService;


	public function __construct(GodaddyService $godaddyService,
        RegistrarService $registrarService,
        ServerService $serverService,
        DomainService $domainService,
        NamecheapService $namecheapService)
	{
		$this->godaddyService = $godaddyService;
        $this->registrarService = $registrarService;
        $this->serverService = $serverService;
        $this->domainService = $domainService;
        $this->namecheapService = $namecheapService;
	}

    public function configureDNS()
    {
        $this->authorize('index',SubDomainModel::class);
    	return view('configureDNS');
    }

    public function clearDomain($id)
    {
        $this->authorize('index',SubDomainModel::class);
        $registrar = $this->domainService->getDomain($id)->registrar;
        $service = $this->chooseService($registrar->company);
        if($service != null) return $service->clearDomain($id);
        return response()->json(['message'=>'Registrar Not Supported'],406);
    }

    public function updateDNS(Request $request)
    {
        $this->authorize('index',SubDomainModel::class);
        $registrar = $this->registrarService->getRegistrar($request->registrar_id);
        $service = $this->chooseService($registrar->company);
        if($service != null) return $service->updateDNS($request->all());
        return response()->json(['message'=>'Registrar Not Supported'],406);
    }

    public function getDomainsList($id)
    {
        $this->authorize('index',SubDomainModel::class);
        return $this->registrarService->getDomainsList($id);
    }

    public function setIpList(Request $request)
    {
        $this->authorize('index',SubDomainModel::class);
        return $this->serverService->setIpList($request->all());
    }

    public function chooseService($company)
    {
        if($company == 'GoDaddy') return $this->godaddyService;
        else if($company == 'NameCheap') return $this->namecheapService;
        return null;
    }
}
