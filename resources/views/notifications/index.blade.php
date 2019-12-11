@extends('master2')

@section('content')

	<input type="hidden" value="{{url('api/getUnreadNotificationsCount')}}" id="notificationsUrl">

	<h4>Notifications</h4>
	<hr>

	@if(session()->has('done'))
		<div onclick="$(this).toggle()" class="alert alert-success">
			<p>{{session()->get('done')}}</p>
		</div>
	@endif

	@if(!isset($search))
	<div class="alert alert-warning">
	 <p>This page list unread notifications</p>
	 <p>To see all notifications click on search button</p>
	</div>
	@endif

	<div class="row">
		<div class="col-md-4">
			<a class="btn btn-primary" href="{{route('markAsRead')}}">Mark all as Read</a>
		</div>
		<div class="col-md-8">
			<form action="{{route('notifications.search')}}" method="get">
				@csrf
				<div class="row">
					<div class="col-md-5">
						<select class="form-control" style="height: 35px;font-size: 0.8rem;" name="type">
							@php 
							$types = 
							[
								'Group Change'=>'App\Notifications\ServerGroupChangeNotification',
								'Create Domain'=>'App\Notifications\CreateDomainNotification',
								'Create Payment'=>'App\Notifications\CreatePaymentNotification',
								'Create Ips'=>'App\Notifications\CreateIpNotification',
								'Status Changes'=>'App\Notifications\StatusNotification',
								'Request'=>'App\Notifications\RequestNotification',
								'Payment'=>'App\Notifications\PaymentNotification',
							]; 
							@endphp
							<option></option>
							@foreach($types as $key=>$value)
								<option value="{{$value}}">{{$key}}</option>
							@endforeach
						</select>
					</div>

					<div class="col-md-5">
						<div  id="reportrange" class="f-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
							<i class="glyphicon glyphicon-calendar icofont icofont-ui-calendar"></i> 
							<span></span>
							<b class="caret"></b>
						</div>
						<input type="hidden" id="start" name="start">
						<input type="hidden" id="end" name="end">
					</div>

					<div class="col-md-2">
						<button class="btn btn-primary"><i class="icofont-search-2"></i></button>
					</div>
				</div>
			</form>
		</div>
	</div>


	<table class="table top table-sm table-bordered">
		<thead>
			@if(isset($search))
				<th>Notification</th>
			@else
				<th>Unread Notification</th>
			@endif
			<th>Created at</th>
			<th>Read at</th>
		</thead>
		<tbody>
			@if(count($notifications)==0)
				<tr>
					<td colspan="3" style="text-align: center;">No Data!</td>
				</tr>
			@endif
			@foreach($notifications as $notification)
				<tr>
					<td>
						@if($notification->type == 'App\Notifications\PaymentNotification')
							@if($notification->data['renewal'])
								<a href="{{route('notifications.show',['notification'=>$notification->id])}}">Renewal {{-- By {{$notification->data['payment']['createdBy']['username']}} for --}} {{$notification->data['payment']['type']}}</a> 
							@else
							<a href="{{route('notifications.show',['notification'=>$notification->id])}}">Payment {{-- By {{$notification->data['payment']['createdBy']['username']}} for --}} {{$notification->data['payment']['type']}}</a> 
							@endif
						@endif
						@if($notification->type == 'App\Notifications\RequestNotification')
							<a href="{{route('notifications.show',['notification'=>$notification->id])}}">New Request By {{$notification->data['request']['user']['username']}}</a>
						@endif
						@if($notification->type == 'App\Notifications\StatusNotification')
							<a href="{{route('notifications.show',['notification'=>$notification->id])}}"><span style="color: black">{{-- {{$notification->data['username']}}</span> --}} Updated  Status ( <span style="color: green">{{$notification->data['subject']}}</span> ) to <span style="color: orange">{!! $notification->data['status']!!}</span></a>
						@endif
						@if($notification->type == 'App\Notifications\CreateIpNotification')
							<a href="{{route('notifications.show',['notification'=>$notification->id])}}">{{-- {$notification->data['username']}}  --}}created Ips</a>
						@endif
						@if($notification->type == 'App\Notifications\CreatePaymentNotification')
							<a href="{{route('notifications.show',['notification'=>$notification->id])}}">{{-- {{$notification->data['username']}}  --}}created Payment ({{$notification->data['total_price']}} {{$notification->data['currency']}})</a>
						@endif
						@if($notification->type == 'App\Notifications\CreateDomainNotification')
							<a href="{{route('notifications.show',['notification'=>$notification->id])}}">{{-- {{$notification->data['username']}}  --}}created Domain {{-- ({{$notification->data['price']}} {{$notification->data['currency']}}) --}}</a>
						@endif
						@if($notification->type == 'App\Notifications\CreateServerNotification')
							<a href="{{route('notifications.show',['notification'=>$notification->id])}}">{{-- {{$notification->data['username']}}  --}}created Server ({{$notification->data['price']}} {{$notification->data['currency']}})</a>
						@endif
						@if($notification->type == 'App\Notifications\ServerGroupChangeNotification')
							<a href="{{route('notifications.show',['notification'=>$notification->id])}}">{{-- {{$notification->data['username']}}  --}}changed  Server({{$notification->data['server']}}) Group from {{$notification->data['lastGroup']}} to {{$notification->data['currentGroup']}} </a>
						@endif
					</td>
					<td>
						{{$notification->created_at->format('Y-m-d')}}
					</td>
					<td>
						@if($notification->read_at)
							{{$notification->read_at}}
						@else
							<i class="icofont-eye-blocked" style="color: red;font-size: 1.5rem"></i>
						@endif
						
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

		@if(!isset($search)) {!! $notifications->render() !!} @endif
	

@endsection

@section('javascript')
	<script>
	$('#dashboard').removeClass('active');
	$('#notifications').addClass('active');
	</script>
	<script src="{{asset('js/notification.js')}}"></script>
	<script>

		 cb = function(start,end){
			$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			$('#start').val(start.format('YYYY-MM-DD h:m:s'));
			$('#end').val(end.format('YYYY-MM-DD h:m:s'));
		 }

		 var start =  moment().subtract(29,'days').add(1,'minute');

		 var end =  moment().add(1,'minute');
	
		 $('#reportrange').daterangepicker(
		 	{
		 		startDate : start,
		 		endDate : end,
		 		"drops": "down",
		 		"showDropdowns" : true,
		 		ranges : 
		 		{
		 		   'Today': [moment(), moment()],
		           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
		           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
		           'This Month': [moment().startOf('month'), moment().endOf('month')],
		           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		 		},
		 		"linkedCalendars" : false,
		 	},cb);

		 cb(start,end);

		 

	</script>
@endsection

@section('css')
	<style>
		.alert-success p{color: white !important;}
		.alert-warning p{color: black !important;}
	</style>
@endsection