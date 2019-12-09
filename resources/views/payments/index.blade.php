@extends('master2')

@section('content')

<input type="hidden" value="{{url('api/getUnreadNotificationsCount')}}" id="notificationsUrl">

@include('loader')

<div style="display: none;" id="app">

<div class="row">
	<div class="col-md-6">
		<h4>List Payments</h4>
	</div>
	<div class="col-md-6">
		<div class="row">
			<div class="col-md-6">
				<div class="flex-container">
					<div  id="reportrange" class="f-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
						 <i class="glyphicon glyphicon-calendar icofont icofont-ui-calendar"></i> 
						 <span></span>
						 <b class="caret"></b>
					</div>	 
					<button class="btn btn-primary" @click="filterPayments()"><i class="icofont-filter"></i></button>
				</div>
			</div>
			<div class="col-md-6">
				<div class="flex-container">
					<button type="button" class="btn btn-primary waves-effect waves-light r" @click="createPayment()"><i class="icofont-plus"></i></button>
					<button type="button" class="btn btn-primary waves-effect waves-light r" @click="refreshPayments()"><i class="icofont-refresh"></i></button>
					<button class="btn btn-success waves-effect waves-light r" @click="exportModal()"><i class="icofont-file-excel"></i></button>
				</div>
			</div>
		</div>
	</div>
</div>

<hr>

<table class="table table-bordered table-sm">
        			 		<thead>
        			 			<tr class="bg-primary">
        			 				<th>Group</th>
	        			 			<th>Type</th>
	        			 			<th>
	        			 				Price 
	        			 				<i @click="sort('price')" class="icofont-sort"></i>
	        			 				<select  v-model="filter.currency" @change="filterPayments()">
        			 						<option></option>
        			 						<option v-for="currency in currencies">@{{currency}}</option>
        			 					</select>
	        			 			</th>
	        			 			<th v-if="filter.type">@{{filter.type}}</th>
	        			 			<th v-if="!filter.type">All</th>
	        			 			<th>Created</th>
	        			 			<th>Date Payment</th>
	        			 			<th>Description</th>
	        			 			<!--<th>Created By</th>-->
	        			 			<!--<th>Updated By</th>-->
	        			 			<th>Edit</th>
        			 			</tr>
        			 		</thead>
        			 		<tbody>
        			 			<tr>
        			 				<td>
        			 					<select style="width: 100px;" class="form-control" v-model="filter.group_id" @change="filterPayments()">
        			 							<option></option>
        			 							<option v-for="group in groups" :value="group.id">@{{group.name}}</option>
        			 					</select>
        			 				</td>
        			 				<td>
        			 					<select style="width: 100px;" class="form-control" v-model="filter.type" @change="filterPayments()">
        			 						<option></option>
        			 						<option v-for="type in types">@{{type}}</option>
        			 					</select>
        			 				</td>
        			 				<td>
        			 					<input type="text" class="form-control" v-model="filter.total_price" @keyup="filterPayments()">
        			 				</td>
        			 				<td v-show="filter.type == 'Server'">
        			 					<select style="width: 250px;" class="servers_select"></select>
        			 				</td>
        			 				<td v-show="filter.type == 'Domain'">
        			 					<select style="width: 250px;" class="domains_select"></select>
        			 				</td>
        			 				<td v-show="filter.type == 'Ip'">
        			 					<select style="width: 250px;" class="ips_select"></select>
        			 				</td>
        			 				<td v-if="!filter.type"></td>
        			 				<td>
        			 					<input type="text" class="form-control" v-model="filter.created" @keyup="filterPayments()">
        			 				</td>
        			 				<td>
        			 					<input type="text" class="form-control" v-model="filter.payment_date" @keyup="filterPayments()">
        			 				</td>
        			 				<td>
        			 					<input type="text" class="form-control" v-model="filter.description" @keyup="filterPayments()">
        			 				</td>
        			 				<!--<td>
        			 					<select style="width: 200px;" class="users_select"></select>
        			 				</td>-->
        			 				<!--<td></td>-->
        			 				<td></td>
        			 				
        			 			</tr>
        			 			<tr v-if="payments.length>0"  v-for="payment in payments">
        			 				<td>@{{payment.group ? payment.group.name : ''}}</td>
        			 				<td>@{{payment.type}}</td>
        			 				<td>@{{payment.total_price}} @{{currencyCode(payment.currency)}}</td>
        			 				<td v-if="filter.type == 'Server'"><a @click="showServerDetails(payment.server)" href="javascript:void(0)">@{{payment.server ? payment.server.s_name : ''}}</a></td>
        			 				<td v-else-if="filter.type == 'Ip'"><a href="javascript:void(0)">@{{payment.ip ? payment.ip.ip : ''}}</a></td>
        			 				<td v-else-if="filter.type == 'Domain'"><a href="javascript:void(0)">@{{payment.domain ? payment.domain.domain : ''}}</a></td>
        			 				<td v-else><span style="text-align: center;">-</span></td>
        			 				<td>@{{payment.created}}</td>
        			 				<td>@{{payment.payment_date}}</td>
        			 				<td>@{{payment.description ? payment.description.substr(0,12) : ""}}..</td>
        			 				<!--<td>@{{payment.createdBy ? payment.createdBy.username : ''}}</td>-->
        			 				<!--<td>@{{payment.updatedBy ? payment.updatedBy.username : ''}}</td>-->
        			 				<td><button class="btn btn-primary" @click="editPayment(payment)"><i class="icofont icofont-ui-edit"></i></button></td>
        			 			</tr>
        			 			<tr id="spin" style="text-align: center;display: none;">
        			 				<td colspan="8">
        			 					<img src="{{asset('images/spin.svg')}}" width="400">
        			 				</td>
        			 			</tr>
        			 			<tr v-if="payments.length==0" style="text-align: center;">
        			 				<td colspan="8">
        			 					No Data!
        			 				</td>
        			 			</tr>
        			 			
        			 			<tr>
        			 				<td colspan="7">
        			 					 <ul class="pagination">
        			 					 	 <li  class="page-item" @click="filterPayments(pagination.first)"><a class="page-link" >&laquo;</a></li>
										    <li :class="{'disabled':!pagination.prev}" class="page-item" @click="filterPayments(pagination.prev)"><a class="page-link" >Previous</a></li>
										     <li class="page-item"><a class="page-link">@{{pagination.currentPage}}/@{{pagination.lastPage}}</a></li>
										    <li :class="{'disabled':!pagination.next}" class="page-item" @click="filterPayments(pagination.next)"><a class="page-link" >Next</a></li>
										    	 <li  class="page-item" @click="filterPayments(pagination.last)"><a class="page-link" >&raquo;</a></li>
										 </ul>
        			 				</td>
        			 				<td colspan="1">
        			 					<select class="form-control" v-model="filter.limit"  style="width: 100px;" @change="filterPayments()">
										 	<option v-for="limit in limits">@{{limit}}</option>
										</select> 
        			 				</td>
        			 			</tr>
        			 		</tbody>
        			 	</table>





	


	<div id="createPayment" class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Create Payment</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<div class="col-md-6">
	        		<div class="form-group">
	        			<label>Type</label>
	        			<select class="form-control" v-model="newPayment.type">
	        				<option v-for="type in types">@{{type}}</option>
	        			</select>
	        		</div>
	        		<div v-show="newPayment.type == 'Server'" class="form-group">
	        			<label>Server</label>
	        			<select style="width: 100%" class="create_payment_server_select"></select>
	        		</div>
	        		<!--<div v-show="newPayment.type == 'Domain'" class="form-group">
	        			<label>Domain</label>
	        			<select style="width: 100%" class="create_payment_domain_select"></select>
	        		</div>-->
	        		<!--<div v-show="newPayment.type == 'Ip'" class="form-group">
	        			<label>Ip</label>
	        			<select style="width: 100%" class="create_payment_ip_select"></select>
	        		</div>-->
	        		<div class="form-group">
	        			<label>Group</label>
	        			<select class="form-control fx-right" v-model="newPayment.group_id">
	        				<option v-for="group in groups" :value="group.id">@{{group.name}}</option>
	        			</select>
	        		</div>
	        		<div class="form-group">
	        			<label>Description</label>
	        			<textarea rows="2"  class="form-control" v-model="newPayment.description"></textarea>
	        		</div>
	        		<div class="form-group">
	        			<label>Unit Price</label>
	        			<input type="number" class="form-control" v-model="newPayment.unit_price">
	        		</div>
	        		
	        	</div>
	        	<div class="col-md-6">
	        			<div class="form-group">
	        			<label>Quantity</label>
	        			<input type="number" class="form-control" v-model="newPayment.quantity">
	        		</div>
	        		<div class="form-group">
	        			<label>Total Price</label>
	        			<input disabled="" type="number" class="form-control" :value="newPayment.quantity*newPayment.unit_price">
	        		</div>
	        		<div class="form-group">
	        			<label>Currency</label>
	        			<select class="form-control" v-model="newPayment.currency">
	        				<option v-for="currency in currencies">@{{currency}}</option>
	        			</select>
	        		</div>
	        		<div class="form-group">
	        			<label>Date Payment</label>
	        			<input type="date" class="form-control" v-model="newPayment.payment_date">
	        		</div>
	        		<div class="form-group">
	        			<label>Created</label>
	        			<input type="date" class="form-control" v-model="newPayment.created">
	        		</div>
	        	</div>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary" @click="storePayment()">Save changes</button>
	      </div>
	    </div>
	  </div>
	</div>

	<div id="editPayment" class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Edit Payment</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<div class="col-md-6">
	        		<div class="form-group">
	        			<label>Type</label>
	        			<select class="form-control" v-model="selectedPayment.type">
	        				<option v-for="type in types">@{{type}}</option>
	        			</select>
	        		</div>
	        		<div v-show="selectedPayment.type == 'Server' && selectedPayment.server" class="form-group">
	        			<label>Server</label>
	        			<select style="width: 100%;" class="edit_payment_server_select"></select>
	        		</div>
	        		<div v-show="selectedPayment.type == 'Domain' && selectedPayment.domain" class="form-group">
	        			<label>Domain</label>
	        			<select style="width: 100%" class="edit_payment_domain_select"></select>
	        		</div>
	        		<div v-show="selectedPayment.type == 'Ip' && selectedPayment.ip" class="form-group">
	        			<label>Ip</label>
	        			<select style="width: 100%" class="edit_payment_ip_select"></select>
	        		</div>
	        		<div class="form-group">
	        			<label>Group</label>
	        			<select class="form-control fx-right" v-model="selectedPayment.group.id">
	        				<option v-for="group in groups" :value="group.id">@{{group.name}}</option>
	        			</select>
	        		</div>
	        		<div class="form-group">
	        			<label>Unit Price</label>
	        			<input type="number" class="form-control" v-model="selectedPayment.unit_price">
	        		</div>
	        		<div class="form-group">
	        			<label>Description</label>
	        			<textarea rows="2" class="form-control" v-model="selectedPayment.description"></textarea>
	        		</div>
	        	</div>
	        	<div class="col-md-6">
	        			<div class="form-group">
	        			<label>Quantity</label>
	        			<input type="number" class="form-control" v-model="selectedPayment.quantity">
	        		</div>
	        		<div class="form-group">
	        			<label>Total Price</label>
	        			<input type="number" class="form-control" v-model="selectedPayment.total_price">
	        		</div>
	        		<div class="form-group">
	        			<label>Currency</label>
	        			<select class="form-control" v-model="selectedPayment.currency">
	        				<option v-for="currency in currencies">@{{currency}}</option>
	        			</select>
	        		</div>
	        		<div class="form-group">
	        			<label>Date Payment</label>
	        			<input type="date" class="form-control" v-model="selectedPayment.payment_date">
	        		</div>
	        		<div class="form-group">
	        			<label>Create</label>
	        			<input type="date" class="form-control" v-model="selectedPayment.created">
	        		</div>
	        	</div>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-danger" @click="deletePayment()">Delete</button>
	        <button type="button" class="btn btn-primary" @click="updatePayment()">Save changes</button>
	      </div>
	    </div>
	  </div>
	</div>


	<div id="serverDetails" class="modal fade" tabindex="-1" role="dialog">
		  <div class="modal-dialog modal-xl" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title">Server Details ( @{{selectedServer.s_name}} )</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h3 style="text-transform: uppercase;color: #1B8BF9">Gnerale Informations:</h3>
		        <div class="row top">
		        	<div class="col-md-3">
		        		<p><span class="bold">Name :</span> <span>@{{selectedServer.s_name}}</span></p>
		        		<p><span class="bold">Main Ip :</span> <span>@{{selectedServer.main_ip}}</span></p>
		        		<p><span class="bold">Main Domain :</span> <span>@{{selectedServer.main_domain}}</span></p>
		        		<p><span class="bold">Status :</span> <span>@{{selectedServer.last_status}}</span></p>
		        		<p><span class="bold">Number Ips :</span> <span>@{{selectedServer.numberIps}}</span></p>
		        		<p><span class="bold">Group :</span> <span>@{{selectedServer.group.name}}</span></p>
		        	</div>
		        	<div class="col-md-3">
		        		<p><span class="bold">Provider :</span> <span>@{{selectedServer.provider.name}}</span></p>
		        		<p><span class="bold">Order N° :</span> <span>@{{selectedServer.order_number}}</span></p>
		        		<p><span class="bold">Type :</span> <span>@{{selectedServer.type}}</span></p>
		        		<p><span class="bold">Price :</span> <span>@{{selectedServer.price}} @{{selectedServer.currency}}</span></p>
		        		<p><span class="bold">SSH User Default :</span> <span>@{{selectedServer.ssh_user_default}}</span></p>
		        		<p><span class="bold">SSH Password Default :</span> <span>@{{selectedServer.ssh_password_default}}</span></p>
		        		<p><span class="bold">Key :</span> <span>@{{selectedServer.ssh_key}}</span></p>
		        	</div>
		        	<div class="col-md-3">
		        		<p><span class="bold" style="color: orange;">RAM :</span> <span>@{{selectedServer.ram}}</span></p>
		        		<p><span class="bold" style="color: orange;">CPU :</span> <span>@{{selectedServer.cpu}}</span></p>
		        		<p><span class="bold" style="color: orange;">OS :</span> <span>@{{selectedServer.os}}</span></p>
		        		<p><span class="bold" style="color: orange;">Band Width :</span> <span>@{{selectedServer.band_width}}</span></p>
		        		<p><span class="bold">Price :</span> <span>@{{selectedServer.price}} @{{selectedServer.currency}}</span></p>
		        		<p><span class="bold">Note :</span> <span>@{{selectedServer.note}}</span></p>
		        		<p><span class="bold">Description :</span> <span>@{{selectedServer.description}}</span></p>
		        	</div>
		        	<div class="col-md-3">
		        		<p><span class="bold" style="color: green;">Created :</span> <span>@{{selectedServer.created}}</span></p>
		        		<p><span class="bold" style="color: green;">Ordered :</span> <span>@{{selectedServer.date_order}}</span></p>
		        		<p><span class="bold" style="color: green;">Purchased :</span> <span>@{{selectedServer.date_purchase}}</span></p>
		        		<p><span class="bold" style="color: green;">Delivred :</span> <span>@{{selectedServer.date_delivred}}</span></p>
		        		<p><span class="bold" style="color: green;">Expired :</span> <span>@{{selectedServer.date_expiration}}</span></p>
		        		<p><span class="bold" style="color: green;">Canceled :</span> <span>@{{selectedServer.date_cancelation}}</span></p>
	
		        	</div>
		        </div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div id="exportModal" class="modal fade" tabindex="-1" role="dialog">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title">Export Payment List</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		       	  <div class="row">
		       	  	<div class="col-md-2">
		       	  		<div class="form-group">
			       	  		<select class="form-control" v-model="exportCurrency">
			       	  			<option></option>
			       	  			<option v-for="currency in currencies">@{{currency}}</option>
			       	  		</select>
			       	  	</div>
		       	  	</div>
		       	  	<div class="col-md-2">
		       	  		<div class="form-group">
			       	  		<select class="form-control" v-model="exportGroup">
			       	  			<option></option>
			       	  			<option v-for="group in groups" :value="group">@{{group.name}}</option>
			       	  		</select>
			       	  	</div>
		       	  	</div>
		       	  	<div class="col-md-8">
		       	  		<div  id="reportrange2" class="f-right" style="background: #fff; cursor: pointer; padding: 2px 10px; border: 1px solid #ccc; width: 100%">
		       	  			<i class="glyphicon glyphicon-calendar icofont icofont-ui-calendar"></i> 
		       	  			<span></span>
		       	  			<b class="caret"></b>
		       	  		</div>
		       	  	</div>
		       	  </div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        <button id="exportPayments" type="button" class="btn btn-success" @click="exportPayments()">Export</button>
		      </div>
		    </div>
		  </div>
		</div>

</div>

@endsection

@section('css')
	<style>
		.flex-container{display: flex;justify-content:flex-end;}
		.modal-xl{max-width:90% !important;width: 90% !important}
		p{font-size:1rem !important;line-height: 2rem}
		.bold{font-weight: bold;}
		select.form-control:not([size]):not([multiple]){height: 28px;padding:0rem 0rem !important;}
		input[type=text]{height: 28px !important;}
		table td {font-size: 0.8rem;}
	</style>
@endsection


@section('javascript')
	<script>
		$('#dashboard').removeClass('active');
		$('#payments').addClass('active');
	</script>
	<script src="{{asset('js/limit.js')}}"></script>
	<script src="{{asset('js/currencies.js')}}"></script>
	<script src="{{asset('js/notification.js')}}"></script>
	<script src="{{asset('js/payments.js')}}"></script>
	<script>
		$('.users_select').select2(
			{
				allowClear: true,

				placeholder : { id : '' , 'placeholder' : '' },

				ajax : 
				{
					url : '{{url('api/getUsers')}}',

					data : function(params)
					{
						return { search : params.term , page : params.page };
					},

					dataType : 'json',

					processResults : function(data)
					{
						data.page = data.page || 1;

						return {
							results : data.data.map(function(item)
							{
								return { id : item.username , text : item.username };
							}),

							pagination : { more : true }

						}
					}
				}
			}).on('change',function()
			{
				app.filter.created_by = this.value;
				app.filterPayments();
			});


		$('.servers_select').select2(
			{
				allowClear: true,

				placeholder : { id : '' , 'placeholder' : '' },

				ajax : 
				{
					url : '{{url('api/getAllServers')}}',

					data : function(params)
					{
						return { search : params.term , page : params.page };
					},

					dataType : 'json',

					processResults : function(data)
					{
						data.page = data.page || 1;

						return {
							results : data.data.map(function(item)
							{
								return { id : item.id , text : item.s_name };
							}),

							pagination : { more : true }
						}
					},


				}
			}).on('change',function()
		{
			app.filter.server_id = this.value;

			app.filterPayments();
		});


		$('.domains_select').select2({
			allowClear: true,

			placeholder : { id : '' , 'placeholder' : '' },

			ajax : 
				{
					url : '{{url('api/getDomains')}}',

					data : function(params)
					{
						return { search : params.term , page : params.page };
					},

					dataType : 'json',

					processResults : function(data)
					{
						data.page = data.page || 1;

						return {
							results : data.data.map(function(item)
							{
								return { id : item.id , text : item.domain };
							}),

							pagination : { more : true }
						}
					},


				}
		}).on('change',function()
		{
			app.filter.domain_id = this.value;

			app.filterPayments();
		});


		$('.ips_select').select2(
			{
				allowClear: true,

				placeholder : { id : '' , 'placeholder' : '' },
				
				ajax : 
				{
					url : '{{url('api/getIps')}}',

					data : function(params)
					{
						return { search : params.term , page : params.page };
					},

					dataType : 'json',

					processResults : function(data)
					{
						data.page = data.page || 1;

						return {
							results : data.data.map(function(item)
							{
								return { id : item.id , text : item.ip };
							}),

							pagination : { more : true }
						}
					},


				}
			}).on('change',function()
		{
			app.filter.ip_id = this.value;

			app.filterPayments();
		});

			$('.create_payment_server_select').select2({
				dropdownParent:$('#createPayment'),
				allowClear:true,
				placeholder:{id:'','placeholder':''},
				ajax:{
					url:'{{url('api/getAllServers')}}',
					data:function(params){
						return {search:params.term,page:params.page };
					},
					dataType:'json',
					processResults:function(data){
						data.page=data.page || 1;
						return {
							results:data.data.map(function(item){
								return {id:item.id,text:item.s_name};
							}),
							pagination:{more:true}
						}
					},
				},
			}).on('change',function(){
				app.newPayment.paymentable.id= this.value;
			});

			$('.create_payment_domain_select').select2({
				dropdownParent:$('#createPayment'),
				allowClear:true,
				placeholder:{id:'','placeholder':''},
				ajax:{
					url:'{{url('api/getDomains')}}',
					data:function(params){
						return {search:params.term,page:params.page };
					},
					dataType:'json',
					processResults:function(data){
						data.page=data.page || 1;
						return {
							results:data.data.map(function(item){
								return {id:item.id,text:item.domain};
							}),
							pagination:{more:true}
						}
					},
				},
			}).on('change',function(){
				app.newPayment.paymentable.id= this.value;
			});

			$('.create_payment_ip_select').select2({
				dropdownParent:$('#createPayment'),
				allowClear:true,
				placeholder:{id:'','placeholder':''},
				ajax:{
					url:'{{url('api/getIps')}}',
					data:function(params){
						return {search:params.term,page:params.page };
					},
					dataType:'json',
					processResults:function(data){
						data.page=data.page || 1;
						return {
							results:data.data.map(function(item){
								return {id:item.id,text:item.ip};
							}),
							pagination:{more:true}
						}
					},
				},
			}).on('change',function(){
				app.newPayment.paymentable.id= this.value;
			});

			$('.edit_payment_server_select').select2({
				dropdownParent:$('#editPayment'),
				allowClear:true,
				placeholder:{id:'','placeholder':''},
				ajax:{
					url:'{{url('api/getAllServers')}}',
					data:function(params){
						return {search:params.term,page:params.page };
					},
					dataType:'json',
					processResults:function(data){
						data.page=data.page || 1;
						return {
							results:data.data.map(function(item){
								return {id:item.id,text:item.s_name};
							}),
							pagination:{more:true}
						}
					},
				},
			}).on('change',function(){
				app.selectedPayment.server.id = this.value;
			});

			$('.edit_payment_domain_select').select2({
				dropdownParent:$('#editPayment'),
				allowClear:true,
				placeholder:{id:'','placeholder':''},
				ajax:{
					url:'{{url('api/getDomains')}}',
					data:function(params){
						return {search:params.term,page:params.page };
					},
					dataType:'json',
					processResults:function(data){
						data.page=data.page || 1;
						return {
							results:data.data.map(function(item){
								return {id:item.id,text:item.domain};
							}),
							pagination:{more:true}
						}
					},
				},
			}).on('change',function(){
				app.selectedPayment.domain.id = this.value;
			});

			$('.edit_payment_ip_select').select2({
				dropdownParent:$('#editPayment'),
				allowClear:true,
				placeholder:{id:'','placeholder':''},
				ajax:{
					url:'{{url('api/getIps')}}',
					data:function(params){
						return {search:params.term,page:params.page };
					},
					dataType:'json',
					processResults:function(data){
						data.page=data.page || 1;
						return {
							results:data.data.map(function(item){
								return {id:item.id,text:item.ip};
							}),
							pagination:{more:true}
						}
					},
				},
			}).on('change',function(){
				app.selectedPayment.ip.id = this.value;
			});

	</script>
@endsection