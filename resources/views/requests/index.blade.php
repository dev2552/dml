@extends('master2')

@section('content')

<input type="hidden" value="{{url('api/getUnreadNotificationsCount')}}" id="notificationsUrl">

@include('loader')

<div style="display: none;" id="app" >

<div class="row">
	<div class="col-md-6">
		<h4>List Requests</h4>
	</div>
	<div class="col-md-6">
		<div class="flex-container">
			<button type="button" class="btn btn-primary waves-effect waves-light r" @click="createRequest()"><i class="icofont-plus"></i></button>
			<button type="button" class="btn btn-primary waves-effect waves-light r" @click="refreshRequests()"><i class="icofont-refresh"></i></button>
		</div>
	</div>
</div>

<hr>

<table class="table table-bordered table-sm">
        			 		<thead>
        			 			<tr class="bg-primary">
        			 				@can('create',App\Models\RequestModel::class)
	        			 			<th>Group</th>
	        			 			@endcan
	        			 			<th >
	        			 				Subject
	        			 				<i @click="sort('subject')" class="icofont-sort"></i>
	        			 			</th>
	        			 			<th>Type</th>
	        			 			<th>Status</th>
	        			 			@can('managerAndRoot',App\Models\RequestModel::class)
	        			 				<th>Created By</th>
	        			 			@endcan
	        			 			
	        			 			<th>Priority</th>
        			 			</tr>
        			 		</thead>
        			 		<tbody>
        			 			<tr>
        			 				@can('create',App\Models\RequestModel::class)
        			 				<td>
        			 					<select class="form-control" style="width: 100px" v-model="filter.group_id" @change="filterRequests()">
        			 						    <option></option>
        			 							<option v-for="group in groups" :value="group.id">@{{group.name}}</option>
        			 					</select>
        			 				</td>
        			 				@endcan
        			 				<td>
        			 					<input type="text" class="form-control" v-model="filter.subject" @keyup="filterRequests()">
        			 				</td>
        			 				<td>
        			 					<select style="width: 120px;" class="form-control" v-model="filter.type" @change="filterRequests()">
        			 						<option></option>
        			 						<option v-for="type in types">@{{type}}</option>
        			 					</select>
        			 				</td>
        			 				<td>
        			 					<select style="width: 100px;" class="form-control" v-model="filter._status" @change="filterRequests()">
        			 						<option></option>
        			 						<option v-for="statusElement in statusElements">@{{statusElement}}</option>
        			 					</select>
        			 				</td>
        			 				@can('managerAndRoot',App\Models\RequestModel::class)
	        			 				<td>
	        			 					<select style="width: 100px;" class="createdByFilter"></select>
	        			 				</td>
        			 				@endcan
        			 				
        			 				<td>
        			 					<select style="width:100px;" class="form-control" v-model="filter.priority" @change="filterRequests()">
        			 						<option></option>
        			 						<option v-for="priority in priorities">@{{priority}}</option>
        			 					</select>
        			 				</td>
        			 			</tr>
        			 			<tr v-if="requests.length>0"  v-for="request in requests">
        			 				@can('create',App\Models\RequestModel::class)
        			 				<td>@{{request.group ? request.group.name : ''}}</td>
        			 				@endcan
        			 				<td @click="showRequest(request)"><a href="javascript:void(0)">@{{request.subject}}</a></td>
        			 				<td>@{{request.type}}</td>
        			 				<td><button  v-if="request.status != null" class="btn" :class="setStatusColor(request.status.status)" @click="showStatus(request)">@{{request.status.status}}</button></td>
        			 				@can('managerAndRoot',App\Models\RequestModel::class)
        			 					<td>@{{request.user ? request.user.username : ''}}</td>
        			 				@endcan
        			 				
        			 				<td><button :class="{'btn-warning':request.priority=='Medium' || request.priority=='Meduim','btn-danger':request.priority=='High','btn-info':request.priority=='Low'}" class="btn">@{{request.priority}}</button></td>
        			 			</tr>

        			 			<tr id="spin" style="text-align: center;display: none;">
        			 				<td colspan="7">
										<img src="{{asset('images/spin.svg')}}" width="400">
        			 				</td>
        			 			</tr>

        			 			<tr v-if="requests.length==0" style="text-align: center;">
        			 				<td colspan="6">
										No Data!
        			 				</td>
        			 			</tr>

        			 			<tr>

        			 				@can('create',App\Models\RequestModel::class)

        			 				<td colspan="5">

        			 				@endcan
        			 				
        			 				@cannot('create',App\Models\RequestModel::class)

	        			 				@can('managerAndRoot',App\Models\RequestModel::class)

	        			 					<td colspan="4">

	        			 				@endcan

	        			 				@cannot('managerAndRoot',App\Models\RequestModel::class)

	        			 					<td colspan="3">

	        			 				@endcannot

        			 				@endcannot	

										 <ul class="pagination">

										 		 <li  class="page-item" @click="filterRequests(pagination.first)"><a class="page-link" >&laquo;</a></li>

										 		 <li :class="{'disabled':!pagination.prev}" class="page-item" @click="filterRequests(pagination.prev)"><a class="page-link" >Previous</a></li>

										 		 <li class="page-item"><a class="page-link">@{{pagination.currentPage}}/@{{pagination.lastPage}}</a></li>

										 		 <li :class="{'disabled':!pagination.next}" class="page-item" @click="filterRequests(pagination.next)"><a class="page-link" >Next</a></li> 

										 		 <li  class="page-item" @click="filterRequests(pagination.last)"><a class="page-link" >&raquo;</a></li>

										 </ul>

        			 				</td>

        			 				<td colspan="1">

        			 					<select class="form-control" style="width: 120px;" v-model="filter.limit" @change="filterRequests()">
        			 						
										 	<option v-for="limit in limits">@{{limit}}</option>
										 	
										 </select>

        			 				</td>

        			 			</tr>
        			 		</tbody>
        			 	</table>

	

	

		

	<div id="createRequest" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			  <div class="modal-content">
			  	 <div class="modal-header">
			  	 	<h5 class="modal-title">Create Request</h5>
			  	 	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  	 </div>
			  	 <div class="modal-body">
			  	 	<input type="hidden" id="user_id" value="{{Auth::user()->id}}">
			  	 	
			  	 	<div class="row">
			  	 		<div class="col-md-6">
			  	 			<div class="form-group">
			  	 				 <label>Type</label>
			  	 				 <select class="form-control" v-model="newRequest.type">
			  	 				 	<option v-for="type in types">@{{type}}</option>
			  	 				 </select>
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Subject</label>
			  	 				<input type="text" class="form-control" v-model="newRequest.subject">
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Request</label>
			  	 				<textarea class="form-control" rows="3" v-model="newRequest.request"></textarea>
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Priority</label>
			  	 				<select class="form-control" v-model="newRequest.priority">
			  	 					<option v-for="priority in priorities">@{{priority}}</option>
			  	 				</select>
			  	 			</div>
			  	 		</div>
			  	 	</div>
			  	 </div>
			  	  <div class="modal-footer">
			  	  	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			  	  	<button type="button" class="btn btn-primary" @click="storeRequest()">Save changes</button>
			  	  </div>
			  </div>
		</div>
	</div>

	<div id="editUser" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			  <div class="modal-content">
			  	 <div class="modal-header">
			  	 	<h5 class="modal-title">Edit User</h5>
			  	 	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  	 </div>
			  	 <div class="modal-body">
			  	 	


			  	 </div>
			  	  <div class="modal-footer">
			  	  	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			  	  	<button type="button" class="btn btn-danger" @click="deleteUser(selectedUser)">Delete</button>
			  	  	<button type="button" class="btn btn-primary" @click="updateUser(selectedUser)">Save changes</button>
			  	  </div>
			  </div>
		</div>
	</div>


	<div id="showRequest" class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Request</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <p>@{{request}}</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	  </div>
	</div>

	<div id="showStatus" class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">@{{status.request.subject}}</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <table class="table">
	        	<thead>
	        		<th>Status</th>
	        		<th>Explication</th>
	        		<th>Created</th>
	        		<th>User</th>
	        	</thead>
	        	<tbody>
	        		<tr v-cloak v-for="status in status.status">
						<td><button class="btn" :class="setStatusColor(status.status)">@{{status.status}}</button></td>
						<td>@{{status.explication}}</td>
						<td>@{{status.created}}</td>
						<td>@{{status.createdBy ? status.createdBy.username : ''}}</td>
	        		</tr>
	        	</tbody>
	        </table>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        @can('create',App\Models\RequestModel::class)
	        <button type="button" class="btn btn-primary" @click="createStatus(status.request)">Create New Status</button>
	        @endcan
	      </div>
	    </div>
	  </div>
	</div>

	<div id="createStatus" class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog " role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Create New Status</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<div class="col-md-6">
	        		<div class="form-group">
	        			<label>Status</label>
	        			<select class="form-control" v-model="newStatus.newStatus.status"> 
	        				<option v-for="statusElement in statusElements">@{{statusElement}}</option>
	        			</select>
	        		</div>
	        		<div class="form-group">
	        			<label>Explication</label>
	        			<textarea class="form-control" v-model="newStatus.newStatus.explication" rows="3"></textarea>
	        		</div>
	        	</div>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary" @click="storeStatus()">Save Changes</button>
	      </div>
	    </div>
	  </div>
	</div>

	</div>

@endsection

@section('css')
	<style>
		.flex-container{display: flex;justify-content:flex-end;}
		select.form-control:not([size]):not([multiple]){height: 28px;padding:0rem 0rem !important;}
		input[type=text]{height: 28px !important;}
		table td {font-size: 0.8rem;}
	</style>
@endsection

@section('javascript')
	<script>
		$('#dashboard').removeClass('active');
		$('#requests').addClass('active');
	</script>
	<script src="{{asset('js/limit.js')}}"></script>
	<script src="{{asset('js/notification.js')}}"></script>
	<script src="{{asset('js/requests.js')}}"></script>
	<script>
		$('.createdByFilter').select2(
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
					},
				},
			}).on('change',function()
			{
				app.filter.user_id = this.value;
				app.filterRequests();

			});
	</script>
@endsection