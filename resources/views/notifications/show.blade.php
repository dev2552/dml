<?php //dd($notification->data); ?>@extends('master2')

@section('content')

<input type="hidden" value="{{url('api/getUnreadNotificationsCount')}}" id="notificationsUrl">

<h4>Notification</h4>
<hr>

<div class="container" style="background-color: #0566c7;padding: 12px;">
	<p>
		<span style="text-decoration: underline;font-weight:bold;">Notification Type :</span>
		@if($notification->type == 'App\Notifications\PaymentNotification')
			<span>Payment</span>
		@endif
		@if($notification->type == 'App\Notifications\RequestNotification')
			<span>Request</span>
		@endif
		@if($notification->type == 'App\Notifications\StatusNotification')
			<span>Status Changes</span>
		@endif
		@if($notification->type == 'App\Notifications\CreateIpNotification')
			<span>Create Ips</span>
		@endif
		@if($notification->type == 'App\Notifications\CreatePaymentNotification')
			<span>Create Payment</span>
		@endif
		@if($notification->type == 'App\Notifications\CreateDomainNotification')
			<span>Create Domain</span>
		@endif
		@if($notification->type == 'App\Notifications\ServerGroupChangeNotification')
			<span>Group Change</span>
		@endif
	</p>
	<p>
		<span style="text-decoration: underline;font-weight:bold;">Read at :</span>
		<span> {{$notification->read_at->format('Y-m-d')}} </span>
	</p>
	@if($notification->type == 'App\Notifications\PaymentNotification')
		@if($notification->data['payment']['type'] == 'Domain')
			<p>
				<span style="text-decoration: underline;font-weight:bold;">Domain :</span>
				<span> {{$notification->data['payment']['domain']['domain']}} </span>
			</p>
		@endif
		@if($notification->data['payment']['type'] == 'Server')
			<p>
				<span style="text-decoration: underline;font-weight:bold;">Server :</span>
				<span> {{$notification->data['payment']['server']['s_name']}} </span>
			</p>
			@if($notification->data['auto'] && !$notification->data['renewal'])
			<p style="font-style: italic;">
				<span style="text-decoration: underline;font-weight:bold;">Ips Count :</span>
				<span> {{count($notification->data['ips'])}} </span>
			</p>
			<p style="font-style: italic;">
				<span style="text-decoration: underline;font-weight:bold;">Ips Price :</span>
				<span> {{$notification->data['ips'][0]['price']}}  {{$notification->data['ips'][0]['currency']}} </span>
			</p>
			<p style="font-style: italic;">
				<span style="text-decoration: underline;font-weight:bold;">Ips Total Price :</span>
				<span> {{$notification->data['ips'][0]['price']*count($notification->data['ips'])}}  {{$notification->data['ips'][0]['currency']}} </span>
			</p>
			@endif
		@endif
		@if($notification->data['payment']['type'] == 'Ip')
			<p>
				<span style="text-decoration: underline;font-weight:bold;">Ip :</span>
				<span> {{$notification->data['payment']['ip']['ip']}} </span>
			</p>
		@endif
		<p>
			<span style="text-decoration: underline;font-weight:bold;">Unit Price :</span>
			<span> {{$notification->data['payment']['unit_price']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;">Quantity :</span>
			<span> {{$notification->data['payment']['quantity']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;">Total Price :</span>
			<span> {{$notification->data['payment']['total_price']}} {{$notification->data['payment']['currency']}}</span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;">Description :</span>
			<span> {{$notification->data['payment']['description']}}</span>
		</p>
		{{-- <p>
			<span style="text-decoration: underline;font-weight:bold;">User :</span>
			<span> {{$notification->data['payment']['createdBy']['username']}}</span>
		</p> --}}
	@endif
	@if($notification->type == 'App\Notifications\RequestNotification')
		<p>
			<span style="text-decoration: underline;font-weight:bold;">Type :</span>
			<span>{{$notification->data['request']['type']}}</span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;">Subject :</span>
			<span>{{$notification->data['request']['subject']}}</span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;">Request :</span>
			<span>{{$notification->data['request']['request']}}</span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;">User :</span>
			<span>{{$notification->data['request']['user']['username']}}</span>
		</p>
	@endif
	@if($notification->type == 'App\Notifications\CreateIpNotification')
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Range : </span>
			<span> {{$notification->data['ip_range']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Total Price : </span>
			<span> {{$notification->data['total_price']}} {{$notification->data['currency']}}</span>
		</p>
	{{-- 	<p>
			<span style="text-decoration: underline;font-weight:bold;"> Username : </span>
			<span> {{$notification->data['username']}}</span>
		</p> --}}
	@endif
	@if($notification->type == 'App\Notifications\CreatePaymentNotification')
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Date Creation : </span>
			<span> {{$notification->data['created']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Date Payment : </span>
			<span> {{$notification->data['payment_date']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Description : </span>
			<span> {{$notification->data['description']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Unit Price : </span>
			<span> {{$notification->data['unit_price']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Quantity : </span>
			<span> {{$notification->data['quantity']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Total Price : </span>
			<span> {{$notification->data['total_price']}} {{$notification->data['currency']}}</span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Type : </span>
			<span> {{$notification->data['type']}} ({{$notification->data['type'] == 'Server' ? $notification->data['server'] : ''}}) </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Group : </span>
			<span> {{$notification->data['group']}} </span>
		</p>
	{{-- 	<p>
			<span style="text-decoration: underline;font-weight:bold;"> Username : </span>
			<span> {{$notification->data['username']}} </span>
		</p> --}}
	@endif
	@if($notification->type == 'App\Notifications\CreateDomainNotification')
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Date Purchase : </span>
			<span> {{$notification->data['entered']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Date Expiration : </span>
			<span> {{$notification->data['datex']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Price : </span>
			<span> {{$notification->data['price']}} {{$notification->data['currency']}}</span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Domain : </span>
			<span> {{$notification->data['domain']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Group : </span>
			<span> {{$notification->data['group']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Registrar : </span>
			<span> {{$notification->data['registrar']}} </span>
		</p>
	{{-- 	<p>
			<span style="text-decoration: underline;font-weight:bold;"> Username : </span>
			<span> {{$notification->data['username']}} </span>
		</p> --}}
	@endif
	@if($notification->type == 'App\Notifications\CreateServerNotification')
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Server : </span>
			<span> {{$notification->data['name']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Date Expiration : </span>
			<span> {{$notification->data['date_expiration']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Date Order : </span>
			<span> {{$notification->data['date_order']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Date Purchase : </span>
			<span> {{$notification->data['date_purchase']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Price : </span>
			<span> {{$notification->data['price']}}  {{$notification->data['currency']}}</span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Description : </span>
			<span> {{$notification->data['description']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Domain : </span>
			<span> {{$notification->data['main_domain']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> group : </span>
			<span> {{$notification->data['group']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Note : </span>
			<span> {{$notification->data['note']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Type : </span>
			<span> {{$notification->data['type']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Number Ips : </span>
			<span> {{$notification->data['numberIps']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Provider : </span>
			<span> {{$notification->data['provider']}} </span>
		</p>
	{{-- 	<p>
			<span style="text-decoration: underline;font-weight:bold;"> Username : </span>
			<span> {{$notification->data['username']}} </span>
		</p> --}}
	@endif
	@if($notification->type == 'App\Notifications\ServerGroupChangeNotification')
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Server : </span>
			<span> {{$notification->data['server']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Last Group : </span>
			<span> {{$notification->data['lastGroup']}} </span>
		</p>
		<p>
			<span style="text-decoration: underline;font-weight:bold;"> Current Group : </span>
			<span> {{$notification->data['currentGroup']}} </span>
		</p>
	{{-- 	<p>
			<span style="text-decoration: underline;font-weight:bold;"> Username  : </span>
			<span> {{$notification->data['username']}} </span>
		</p> --}}
	@endif

	@if($notification->type == 'App\Notifications\StatusNotification')

		<p>

			<span style="text-decoration: underline;font-weight:bold;"> Request : </span>

			<span> {{$notification->data['request']}} </span>

		</p>

		<p>

			<span style="text-decoration: underline;font-weight:bold;"> New Status : </span>

			<span> {{$notification->data['status']}} </span>

		</p>

		<p>

			<span style="text-decoration: underline;font-weight:bold;"> Subject : </span>

			<span> {{$notification->data['subject']}} </span>

		</p>

	{{-- 	<p>

			<span style="text-decoration: underline;font-weight:bold;"> Username : </span>

			<span> {{$notification->data['username']}} </span>

		</p> --}}

	@endif
</div>


@endsection

@section('css')
	<style>
		p{color: white;font-size: 1rem;line-height: 2rem;}
	</style>
@endsection

@section('javascript')
	<script>
	$('#dashboard').removeClass('active');
	$('#notifications').addClass('active');
	</script>
	<script src="{{asset('js/notification.js')}}"></script>
@endsection