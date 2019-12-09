<?php

namespace Tests\Feature;

use App\Http\Controllers\RegistrarController;
use App\Http\Requests\StoreRegistrar;
use App\Models\RegistrarModel;
use App\Repositories\RegistrarRepository;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrarTest extends TestCase
{
    public function testStore()
    {
        $data["name"]="a";
        $data["company"]="a";
        $data["eml_contact"]="a@a.com";
        $data["password"]="a";
        $data["customerid"]="a";
        $data["registrar_key"]="a";
        $data["secret"]="a";
        $data["website"]="a.com";
        $data["phone"]="a";
        $data["address"]="a";
        $data["is_active"]=0;
        $data["description"]="a";
        $user=User::where("username","admin")->first();
        \Auth::login($user);
        $request = StoreRegistrar::create("api/addRegistrar","post",$data);
        $registrarModel = new RegistrarModel;
        $registrarController = new RegistrarController($registrarModel);
        $registrarController->store($request);
        $this->assertDatabaseHas("registrar",$data);
    }
}
