@extends('master2')

@section('content')

<input type="hidden" value="{{url('api/getUnreadNotificationsCount')}}" id="notificationsUrl">

@include('loader')

<div style="display: none;" id="app">

<div class="row">
	<div class="col-md-6">
		<h5>List Users</h5>
	</div>
	<div class="col-md-6">
		 <div class="flex-container">
		 	<button type="button" class="btn btn-primary waves-effect waves-light r" @click="createUser()"><i class="icofont-plus"></i></button>
		 	<button type="button" class="btn btn-primary waves-effect waves-light r" @click="refreshUsers()"><i class="icofont-refresh"></i></button>
		 </div>	
	</div>
</div>	

<hr>

<table class="table table-bordered table-sm">
        			 		<thead>
        			 			<tr class="bg-primary">
        			 				<th >Username <i @click="sort('username')" class="icofont-sort"></i></th>
	        			 			<th >Role </th>
	        			 			<th >Name <i @click="sort('name')" class="icofont-sort"></i></th>
	        			 			<th >Email <i @click="sort('email')" class="icofont-sort"></i></th>
	        			 			<th>Group</th>
	        			 			<th>Status</th>
	        			 			<!--<th>Created By</th>-->
	        			 			<!--<th>Updated By</th>-->
	        			 			<th>Edit</th>
        			 			</tr>
        			 		</thead>
        			 		<tbody>
        			 			<tr>
        			 				<td>
        			 					<input type="text" class="form-control" v-model="filter.username" @keyup="filterUsers()">
        			 				</td> 
        			 				<td>
        			 					{{-- <select style="width: 100px;" class="form-control" v-model="filter.role" @change="filterUsers()">
        			 						<option></option>
        			 						<option v-for="role in roles">@{{role}}</option>
        			 					</select> --}}
        			 				</td>
        			 				<td>
        			 					<input type="text" class="form-control" v-model="filter.name" @keyup="filterUsers()">
        			 				</td> 
        			 				
        			 				<td>
        			 					<input type="text" class="form-control" v-model="filter.email" @keyup="filterUsers()">
        			 				</td> 
        			 				<td>
        			 					<select style="width: 100px;" class="form-control" v-model="filter.group_id" @change="filterUsers()">
        			 						<option></option>
        			 						<option v-for="group in groups" :value="group.id">@{{group.name}}</option>
        			 					</select>
        			 				</td>
        			 				<td>
        			 					<select style="width: 100px;" class="form-control" v-model="filter.active" @change="filterUsers()">
        			 						<option></option>
        			 						<option value="1">Active</option>
        			 						<option value="0">Inactive</option>
        			 					</select>
        			 				</td>
        			 				<!--<td><select style="width: 100px" class="createdByFilter"></select></td>-->
        			 				<!--<td></td>-->
        			 				<td></td>
        			 			</tr>

        			 			<tr v-if="users.length>0"  v-for="user in users">
        			 				<td>@{{user.username}}</td>
        			 				<td>@{{user.role}}</td>
        			 				<td>@{{user.f_name}} @{{user.l_name}}</td>
        			 				<td>@{{user.email}}</td>
        			 				<td>@{{user.group.name}}</td>
        			 				<td><i v-if="user.enable" class="icofont-check"></i></td>
        			 				<!--<td>@{{user.createdBy ? user.createdBy.username : ''}}</td>-->
        			 				<!--<td>@{{user.updatedBy ? user.updatedBy.username : ''}}</td>-->
        			 				<td>
        			 					<button @click="editUser(user)" type="button" class="btn btn-primary waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit">
	                                    <i class="icofont icofont-ui-edit"></i>
	                                    </button>
	                                </td>
        			 			</tr>
        			 				<tr id="spin" style="text-align: center;display: none;">
        			 				<td colspan="7">
        			 					<img src="{{asset('images/spin.svg')}}" width="400">
        			 				</td>
        			 			</tr>
        			 			<tr v-if="users.length==0" style="text-align: center;">
        			 				<td colspan="7">
        			 					No Data!
        			 				</td>
        			 			</tr>

        			 			<tr>
        			 				<td colspan="6">
										  <ul class="pagination">
										  	<li  class="page-item" @click="filterUsers(pagination.first)"><a class="page-link" >&laquo;</a></li>
										    <li :class="{'disabled':!pagination.prev}" class="page-item" @click="filterUsers(pagination.prev)"><a class="page-link">Previous</a></li>
										    <li class="page-item"><a class="page-link">@{{pagination.currentPage}}/@{{pagination.lastPage}}</a></li>
										    <li :class="{'disabled':!pagination.next}" class="page-item" @click="filterUsers(pagination.next)"><a class="page-link">Next</a></li>
											    <li  class="page-item" @click="filterUsers(pagination.last)"><a class="page-link" >&raquo;</a></li>
										  </ul>
        			 				</td>
        			 				<td colspan="1">
        			 					<select class="form-control" v-model="filter.limit" @change="filterUsers()" style="width: 100px;">
										 	<option v-for="limit in limits">@{{limit}}</option>
										 </select>
        			 				</td>
        			 			</tr>
        			 			
        			 		</tbody>
        			 	</table>	

	

		

	<div id="createUser" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			  <div class="modal-content">
			  	 <div class="modal-header">
			  	 	<h5 class="modal-title">Add User</h5>
			  	 	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  	 </div>
			  	 <div class="modal-body">
			  	 	<div class="row">
			  	 		<div class="col-md-6">
			  	 			<div class="form-group">
				  	 			<label>Username</label>
				  	 			<input type="text" class="form-control" v-model="newUser.username">
				  	 		</div>
				  	 		<div class="form-group">
				  	 			<label>Email</label>
				  	 			<input type="text" class="form-control" v-model="newUser.email">
				  	 		</div>
				  	 		<div class="form-group">
				  	 			<label>Password</label>
				  	 			<input type="text" class="form-control" v-model="newUser.password">
				  	 		</div>
				  	 		<div class="form-group">
				  	 			<label>Role</label>
				  	 			<select  class="form-control" v-model="newUser.roule_id">
					  	 				<option v-for="role in roles" :value="role.role">@{{role.role}}</option>
				  	 			</select>
				  	 		</div>
			  	 		</div>

			  	 	<div class="col-md-6">
			  	 		<div class="form-group">
				  	 		<label>Group</label>
				  	 		<select class="form-control" v-model="newUser.group_id">
				  	 			<option v-for="group in groups" :value="group.id">@{{group.name}}</option>
				  	 		</select>
				  	 	</div>
				  	 	<div class="form-group">
				  	 		<label>First Name</label>
				  	 		<input type="text" class="form-control" v-model="newUser.f_name">
				  	 	</div>
				  	 	<div class="form-group">
				  	 		<label>Last Name</label>
				  	 		<input type="text" class="form-control" v-model="newUser.l_name">
				  	 	</div>
				  	 	<div class="form-check">
				  	 		<label class="form-check-label"><input type="checkbox" class="form-check-input" v-model="newUser.active">Active</label>
				  	 	</div>
			  	 	</div>
			  	 				  	 	</div>

			  	 </div>
			  	  <div class="modal-footer">
			  	  	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			  	  	<button type="button" class="btn btn-primary" @click="storeUser()">Save changes</button>
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
			  	 	<div class="row">
			  	 		<div class="col-md-6">
			  	 			<div class="form-group">
				  	 			<label>Username</label>
				  	 			<input disabled="" type="text" class="form-control" v-model="selectedUser.username">
				  	 		</div>
				  	 		<div class="form-group">
				  	 			<label>Email</label>
				  	 			<input type="text" class="form-control" v-model="selectedUser.email">
				  	 		</div>
				  	 		<div class="form-group">
				  	 			<label>Role</label>
				  	 			<select  class="form-control" v-model="selectedUser.role">
					  	 				<option v-for="role in roles" :value="role.role">@{{role.role}}</option>
				  	 			</select>
				  	 		</div>
			  	 		</div>

			  	 	<div class="col-md-6">
			  	 		<div class="form-group">
				  	 		<label>Group</label>
				  	 		<select class="form-control" v-model="selectedUser.group.id">
				  	 		<option v-for="group in groups" :value="group.id">@{{group.name}}</option>
				  	 		</select>
				  	 	</div>
				  	 	<div class="form-group">
				  	 		<label>First Name</label>
				  	 		<input type="text" class="form-control" v-model="selectedUser.f_name">
				  	 	</div>
				  	 	<div class="form-group">
				  	 		<label>Last Name</label>
				  	 		<input type="text" class="form-control" v-model="selectedUser.l_name">
				  	 	</div>
				  	 	<div class="form-check">
				  	 		<label class="form-check-label"><input type="checkbox" class="form-check-input" v-model="selectedUser.active">Active</label>
				  	 	</div>
			  	 	</div>
			  	 				  	 	</div>

			  	 </div>
			  	  <div class="modal-footer">
			  	  	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			  	  	<button type="button" class="btn btn-danger" @click="deleteUser(selectedUser)">Delete</button>
			  	  	<button type="button" class="btn btn-primary" @click="updateUser(selectedUser)">Save changes</button>
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
		table td , table th{font-size: 0.8rem;}
	</style>
@endsection

@section('javascript')
	<script>
		$('#dashboard').removeClass('active');
		$('#permissions').addClass('active');
	</script>
	<script src="{{asset('js/limit.js')}}"></script>
	<script src="{{asset('js/notification.js')}}"></script>
	<script src="{{asset('js/users.js')}}"></script>
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


                }
            }).on('change',function()
            {
                app.filter.created_by = this.value;
                app.filterUsers();
            });
	</script>
@endsection