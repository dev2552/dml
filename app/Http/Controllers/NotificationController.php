<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

	protected $user;
    protected $request;

	public function __construct(Request $request)
	{
        $this->request = $request;
		$this->middleware(function($request,$next){
			$this->user = Auth::user();
			return $next($request);
		});
	}
    public function index()
    {
    	$notifications = $this->user->unreadNotifications()->paginate(10);
    	return view('notifications.index',['notifications'=>$notifications]);
    }

    public function show($id)
    {
    	$notification = $this->user->notifications->find($id);
    	$notification->markAsRead();
    	return view('notifications.show',['notification'=>$notification]);
    }

    public function getUnreadNotificationsCount()
    {
        return Auth::user()->unreadNotifications->count();
    }

    public function markAsRead()
    {
        $this->user->unreadNotifications->markAsRead();
        $this->request->session()->flash('done','Done');
        return redirect()->route('notifications.index');
    }

    public function search()
    {

        if(!$this->request->type)
        {
            $notifications = $this->user->notifications()
            ->whereBetween('created_at',[Carbon::parse($this->request->start)->startOfDay(),Carbon::parse($this->request->end)->endOfDay()])
            ->get();
        }

        else
        {
            $notifications = $this->user->notifications()
            ->where('type',$this->request->type)
            ->whereBetween('created_at',[Carbon::parse($this->request->start)->startOfDay(),Carbon::parse($this->request->end)->endOfDay()])
            ->get();
        }

        return view('notifications.index',['notifications'=>$notifications,'search'=>true]);
    }
}
