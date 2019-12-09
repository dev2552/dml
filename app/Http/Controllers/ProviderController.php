<?php

namespace App\Http\Controllers;

use App\Exports\ProvidersExport;
use App\Http\Requests\StoreProvider;
use App\Models\ProviderModel;
use App\Repositories\ProviderRepository;
use Excel;
use Illuminate\Http\Request;
use App\Services\ProviderService;

class ProviderController extends Controller
{

    protected $providerRepository;
    protected $providerService;

    public function __construct(ProviderRepository $providerRepository,ProviderService $providerService)
    {
        $this->providerRepository = $providerRepository;
        $this->providerService = $providerService;
    }
   
    public function index()
    {
        $this->authorize('index',ProviderModel::class);
        return view('providers.index');
    }

   
   

    
    public function store(StoreProvider $request)
    {
        $this->authorize('index',ProviderModel::class);
        return $this->providerRepository->store($request->all());
    }

    
   
 
    public function update(Request $request, $id)
    {
        $this->authorize('index',ProviderModel::class);
        return $this->providerRepository->update($request->all(),$id);
    }

   
    public function destroy($id)
    {
        $this->authorize('index',ProviderModel::class);
        $this->providerRepository->destroy($id);
    }

    public function filter(Request $request)
    {
        $this->authorize('index',ProviderModel::class);
        return $this->providerRepository->filter($request->all());
    }

    public function export(ProvidersExport $providersExport)
    {
        $this->authorize('index',ProviderModel::class);
        return Excel::download($providersExport,'providers.'.now().'.xlsx');
    }

    public function getAll(Request $request)
    {
         $this->authorize('all',ProviderModel::class);
         return $this->providerRepository->getAll($request->all());
    }

    public function show($id)
    {
        return $this->providerService->show($id);
    }
}
