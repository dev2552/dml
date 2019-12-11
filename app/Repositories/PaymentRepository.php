<?php 

namespace App\Repositories;

use App\Http\Resources\PaymentResource;
use App\Models\PaymentModel;
use App\Notifications\CreatePaymentNotification;
use App\Notifications\PaymentNotification;
use App\User;
use Auth;
use Carbon\Carbon;
use Notification;
use Whoops\Exception\ErrorException;

class PaymentRepository 
{
	protected $paymentModel; 

	public function __construct(PaymentModel $paymentModel){
		$this->paymentModel = $paymentModel;
	}

	public function store($data)
	{
		if(!isset($data["type"])) return false;
		
		if($data['type'] == 'Server')
		{
			if(!isset($data['server_id'])) $data['server_id'] = $data['paymentable']['id'];

		}else if($data['type'] == 'Domain')
		{
			if(!isset($data['domain_id'])) $data['domain_id'] = $data['paymentable']['id'];
		}
		else if ($data['type'] == 'Ip')
		{
			if(!isset($data['ip_id'])) $data['ip_id'] = $data['paymentable']['id'];
		}
		$data['created'] = Carbon::now();
		if( !isset( $data["total_price"] ) )
		{
			$data['total_price'] = $data['unit_price']*$data['quantity'];
		}
		$data["payment_date"]=now();
		$record = $this->paymentModel->create($data);
		return new PaymentResource($record);
	}

	public function filter($data){

		if(!$data['group_id']) $data['group_id'] = '%';
		if(!$data['server_id']) $data['server_id'] = '%';
		if(!$data['domain_id']) $data['domain_id'] = '%';
		if(!$data['ip_id']) $data['ip_id'] = '%';

		$types = ['Server','Ip','Domain'];

		if(in_array($data['type'],$types))
		{
			$records = $this->paymentModel
			->where( function( $query ) use ( $data ){ $query->where( "group_id","like",$data[ "group_id" ] )->orWhereNull( "group_id" ); } )
			->where( function( $query ) use ( $data ){ $query->where( "type","like","%".$data[ "type" ]."%" )->orWhereNull( "type" ); } )
			->where( function( $query ) use ( $data ){ $query->where( "total_price","like","%".$data[ "total_price" ]."%" )->orWhereNull( "total_price" ); } )
			->where( function( $query ) use ( $data ){ $query->where( "currency","like","%".$data[ "currency" ]."%" )->orWhereNull( "currency" ); } )
			->where(function($query) use ($data)
			{
				return $query->where('server_id','like',$data['server_id'])->orWhereNull("server_id");
				//->orWhere('domain_id','like',$data['domain_id'])
				//->orWhere('ip_id','like',$data['ip_id']);
			})
			->where( function( $query ) use ( $data ){ $query->where( "created","like","%".$data[ "created" ]."%" )->orWhereNull( "created" ); } )
			->where( function( $query ) use ( $data ){ $query->where( "payment_date","like","%".$data[ "payment_date"]."%" )->orWhereNull( "payment_date" ); } )
			->where(function($query) use ($data){$query->where('description','like','%'.$data['description'].'%')->orWhereNull('description');})
			//->where( function( $query ) use ( $data ){ $query->where( "created_by","like","%".$data[ "created_by" ]."%" )->orWhereNull( "created_by" ); } )
			->whereBetween('created',[Carbon::parse($data['start'])->startOfDay(),Carbon::parse($data['end'])->endOfDay()])
			->orderBy($data['sortObject']['champ'],$data['sortObject']['order'])
			->paginate($data['limit']);
			return PaymentResource::collection($records);
		}
		else
		{
			$records = $this->paymentModel
			->where( function( $query ) use ( $data ){ $query->where( "group_id","like",$data[ "group_id" ] )->orWhereNull( "group_id" ); } )
			->where( function( $query ) use ( $data ){ $query->where( "type","like","%".$data[ "type" ]."%" )->orWhereNull( "type" ); } )
			->where( function( $query ) use ( $data ){ $query->where( "total_price","like","%".$data[ "total_price" ]."%" )->orWhereNull( "total_price" ); } )
			->where( function( $query ) use ( $data ){ $query->where( "currency","like","%".$data[ "currency" ]."%" )->orWhereNull( "currency" ); } )
			->where( function( $query ) use ( $data ){ $query->where( "created","like","%".$data[ "created" ]."%" )->orWhereNull( "created" ); } )
			->where( function( $query ) use ( $data ){ $query->where( "payment_date","like","%".$data[ "payment_date" ]."%" )->orWhereNull( "payment_date" ); } )
			->where( function( $query ) use ( $data ){ $query->where( "description","like","%".$data[ "description" ]."%" )->orWhereNull( "description" ); } )
			//->where( function( $query ) use ( $data ){ $query->where( "created_by","like","%".$data[ "created_by" ]."%" )->orWhereNull( "created_by" ); } )
			->whereBetween('created',[Carbon::parse($data['start'])->startOfDay(),Carbon::parse($data['end'])->endOfDay()])
			->orderBy($data['sortObject']['champ'],$data['sortObject']['order'])
			->paginate($data['limit']);
			return PaymentResource::collection($records);
		}
		
	}

	public function update($data,$id){
		$record = $this->paymentModel->find($id);
		if(isset($data['paymentable']))
		 {
		 	if($data['type'] == 'Server') $data['server_id'] = $data['server']['id'];
		 	//if($data['type'] == 'Domain') $data['domain_id'] = $data['domain']['id'];
		 	//if($data['type'] == 'Ip') $data['ip_id'] = $data['ip']['id'];
		}
		$data['group_id'] = $data['group']['id'];
		//$data['updated_by'] = Auth::user()->username;
		$record->update($data);
		return new PaymentResource($record);
	}

	public function destroy($id)
	{
		$record = $this->paymentModel->find($id);
		//$record->update(['deleted_by'=>Auth::user()->id]);
		$record->delete();
		return new PaymentResource($record);
	}

	public function sendNotification($paymentModel,$userRepository,$auto,$renewal)
	{
		$paymentNotification = new PaymentNotification($paymentModel,$auto,$renewal);
		Notification::send($userRepository->roots(),$paymentNotification);
	}

	public function sendCreateNotification($to,$data)
	{
		$createPaymentNotification = new CreatePaymentNotification($data);
		Notification::send($to,$createPaymentNotification);
	}

	public function getModel()
	{
		return $this->paymentModel;
	}
}