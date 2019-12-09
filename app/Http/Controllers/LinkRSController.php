<?php

namespace App\Http\Controllers;

use App\Models\RequestServerModel;
use App\Repositories\LinkRSRePository;
use Illuminate\Http\Request;

class LinkRSController extends Controller
{
    protected $linkRSRepository;

    public function __construct(RequestServerModel $requestServerModel){
    	$this->linkRSRepository = new LinkRSRePository($requestServerModel);
    }

    public function save(Request $request){
        $this->authorize('index',RequestServerModel::class);
    	return $this->linkRSRepository->save($request->all());
    }

    public function paginate(Request $request){
        $this->authorize('index',RequestServerModel::class);
    	return $this->linkRSRepository->paginate($request->all());
    }
}
