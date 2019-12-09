<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistrar;
use App\Models\RegistrarModel;
use App\Repositories\RegistrarRepository;
use Auth;
use Illuminate\Http\Request;

class RegistrarController extends Controller
{

    protected $registrarRepository;

    public function __construct(RegistrarModel $registrarModel)
    {
        $this->registrarRepository = new RegistrarRepository($registrarModel);
    }

    public function index()
    {
        $this->authorize('index',RegistrarModel::class);
        return view('registrars/index');
    }

    public function fetch(Request $request)
    {
        $this->authorize('index',RegistrarModel::class);
        return $this->registrarRepository->fetch($request->all());
    }

  
    public function store(StoreRegistrar $request)
    {
        $this->authorize('index',RegistrarModel::class);
        return $this->registrarRepository->create($request->all());
    }

   
 
    
    public function update(Request $request, $id)
    {
        return $this->registrarRepository->update($request->all(),$id);
    }

    
    public function destroy($id)
    {
        $this->authorize('index',RegistrarModel::class);
        return $this->registrarRepository->delete($id);
    }

    public function getRegistrars(Request $request)
    {
         $this->authorize('all',RegistrarModel::class);
         return $this->registrarRepository->getRegistrars($request->all());
    }

   
}
