<?php 

namespace App\Repositories;

use App\Http\Resources\StatusResource;
use App\Models\RequestModel;
use App\Models\ServerModel;
use App\Models\StatusModel;
use App\Notifications\StatusNotification;
use Auth;
use Notification;
use Carbon\Carbon;

class StatusRepository 
{
	protected $statusModel;
	protected $requestModel;
	protected $serverModel;

	public function __construct(StatusModel $statusModel,RequestModel $requestModel,ServerModel $serverModel){
		$this->statusModel = $statusModel;
		$this->requestModel = $requestModel;
		$this->serverModel = $serverModel;
	}

	public function store($data,$requestId)
	{
		$record = $this->requestModel->find($requestId);
	//	$record->update([ '_status' => $data['newStatus']['status'] ]);
		//$data['newStatus']['created_by'] = Auth::user()->username;
		$data['newStatus']['created'] = Carbon::now();
		$record->status()->create($data['newStatus']);
		return new StatusResource($record->status->last());
	}

	public function addToServer($data,$serverId){

		$record = $this->serverModel->find($serverId);

		if( $data[ "status" ] === "canc" )
		{
			$record->update( [ "date_cancelation" => now() ] );
		}

		//$data['created_by'] = Auth::user()->username;
		$data['created'] = Carbon::now();
		$record = $record->status()->create($data);
		return new StatusResource($record);
	}

	public function sendNotificationForRoot($data,$userRepository)
	{
		$statusNotification = new StatusNotification($data,$userRepository);
		Notification::send($userRepository->roots(),$statusNotification);
	}

	public function sendNotificationForOthers($data,$userRepository)
	{
		$statusNotification = new StatusNotification($data,$userRepository);
		Notification::send($userRepository->groupUsrers(),$statusNotification);
	}
}