<?php

namespace App\Http\Controllers;

use App\Models\SubDomainModel;
use App\Services\SubDomainService;
use Illuminate\Http\Request;

class SubDomainController extends Controller
{

	protected $subDomainService;

	public function __construct(SubDomainService $subDomainService)
	{
		$this->subDomainService = $subDomainService;
       
	}


    public function index()
    {
        $this->authorize('index',SubDomainModel::class);
    	return view('subDomains.index');
    }

    public function filter(Request $request)
    {
        $this->authorize('index',SubDomainModel::class);
    	return $this->subDomainService->filter($request->all());
    }

    public function listIps(Request $request)
    {
        // to do authorization
        $this->authorize('index',SubDomainModel::class);
        return $this->subDomainService->listIps($request->all());
    }

    public function update(Request $request,$id)
    {
        $this->authorize('index',SubDomainModel::class);
        return $this->subDomainService->update($id,$request->all());
    }
}
