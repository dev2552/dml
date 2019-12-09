<?php

namespace App\Http\Controllers;

use App\Models\GroupModel;
use App\Repositories\GroupRepository;
use App\Repositories\UserRepository;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
	protected $userRepository;
	protected $groupRepository;


	public function __construct(User $user){
		$this->userRepository = new UserRepository($user);
		$this->groupRepository = new GroupRepository(new GroupModel());
	}

    public function index()
    {
        $this->authorize('index',User::class);
    	$groups = $this->groupRepository->all();
    	return view('users/index',['groups'=>$groups]);
    }

    public function store(Request $request){
         $this->authorize('index',User::class);
    	return $this->userRepository->store($request->all());
    }

    public function filter(Request $request){
         $this->authorize('index',User::class);
    	return $this->userRepository->filter($request->all());
    }

    public function update(Request $request,$id){
         $this->authorize('index',User::class);
        return $this->userRepository->update($request->all(),$id);
    }

    public function destroy($id){
         $this->authorize('index',User::class);
        return $this->userRepository->destroy($id);
    }

    public function logout(){
       Auth::logout();
       return redirect()->route('index');
    }

    public function profile(){
        $this->authorize('show',User::class);
        return view('profile');
    }

    public function updateProfile(Request $request){
        $this->authorize('show',User::class);
        $request->validate([
            'currentPassword'=>'required',
            'newPassword'=>'required|confirmed',
        ]);
        if(!Hash::check($request->currentPassword,Auth::user()->password)){
            session()->flash('notUserPassword','Current Password is Incorrect');
            return redirect()->back();
        }
        Auth::user()->password = Hash::make($request->newPassword);
        Auth::user()->update();
        session()->flash('sucess','Password Updated');
        return redirect()->back();
    }

    public function lock()
    {
        session()->put('lock',true);
        return view('lock');
    }

    public function unlock(Request $request)
    {
        if( Hash::check($request->lock,Auth::user()->password) ) {
            session()->forget('lock');
            return redirect()->route('home');
        }
        return redirect()->route('lock');
    }

    public function getUsers(Request $request)
    {
        $this->authorize('filterBy',User::class);
        
        return $this->userRepository->getUsers($request->all());
    }
}
