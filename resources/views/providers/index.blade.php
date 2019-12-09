@extends('master2')

@section('content')

<input type="hidden" value="{{url('api/getUnreadNotificationsCount')}}" id="notificationsUrl">

@include('loader')

<div style="display: none;" id="app">

<div class="row">
	<div class="col-md-6">
		<h4>List Providers</h4>
	</div>
	<div class="col-md-6">
		<div class="row">
			<div class="col-md-6">
				<div class="flex-container">
					<div  id="reportrange" class="f-right" style="background: #fff; cursor: pointer; padding: 2px 10px; border: 1px solid #ccc; width: 100%">
						 <i class="glyphicon glyphicon-calendar icofont icofont-ui-calendar"></i> 
						  <span></span> 
						  <b class="caret"></b>
					</div>
					<div>
						<button class="btn btn-primary" @click="filterByDates()"><i class="icofont-filter"></i></button>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				 <div class="flex-container">
					<div>
						<button type="button" class="btn btn-primary waves-effect waves-light r" @click="createProvider()"><i class="icofont-plus"></i></button>
					</div>
					<div>
						<button type="button" class="btn btn-primary waves-effect waves-light r" @click="refreshProviders()"><i class="icofont-refresh"></i></button>
					</div>
					<div>
						<a href="{{url('api/exportProviders')}}" class="btn btn-success waves-effect waves-light r" download><i class="icofont-file-excel"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<hr>

<table class="table table-bordered table-sm">
        			 		<thead>
        			 			<tr class="bg-primary">
        			 				<th >Name <i @click="sort('name')" class="icofont-sort"></i></th>
	        			 			<th >Country <i @click="sort('country')" class="icofont-sort"></i></th>
	        			 			<th >Email <i @click="sort('email')" class="icofont-sort"></i></th>
	        			 			<th>Created</th>
	        			 			<th>Status</th>
	        			 			<th>Type</th>
	        			 			<th>Created By</th>
	        			 			<th>Updated By</th>
	        			 			<th>Edit</th>
        			 			</tr>
        			 		</thead>
        			 		<tbody>
        			 			<tr>
        			 				<td>
        			 					<input type="text" class="form-control" v-model="filter.name" @keyup="filterProviders()"> 
        			 				</td>
        			 				<td>
        			 					<input type="text" class="form-control" v-model="filter.country" @keyup="filterProviders()"> 
        			 				</td>
        			 				<td>
        			 					<input type="text" class="form-control" v-model="filter.inscr_email" @keyup="filterProviders()"> 
        			 				</td>
        			 				<td>
        			 					<input type="text" class="form-control" v-model="filter.created" @keyup="filterProviders()"> 
        			 				</td>
        			 				<td>
        			 					<select type="text" class="form-control" v-model="filter.status" @change="filterProviders()"> 
        			 						<option></option>
        			 						<option v-for="s in ss">@{{s}}</option>
        			 					</select>
        			 				</td>
        			 				<td>
        			 					<select type="text" class="form-control" v-model="filter.type" @change="filterProviders()"> 
        			 						<option></option>
        			 						<option v-for="type in types">@{{type}}</option>
        			 					</select>
        			 				</td>
        			 				<td><select style="width: 100px" class="createdByFilter"></select></td>
        			 				<td></td>
        			 				<td></td>
        			 			</tr>





        			 			<tr v-if="providers.length>0"  v-for="provider in providers">
        			 				<td>@{{provider.name}}</td>
        			 				<td>@{{provider.country}}</td>
        			 				<td>@{{provider.inscr_email}}</td>
        			 				<td>@{{provider.created}}</td>
        			 				<td><button  :class="{'btn btn-success':status('Active',provider),'btn btn-danger':status('Inactive',provider),'btn btn-default':status('Other',provider)}">@{{provider.status}}</button></td>
        			 				<td>@{{provider.type}}</td>
        			 				<td>@{{provider.createdBy ? provider.createdBy.username : ''}}</td>
        			 				<td>@{{provider.updatedBy ? provider.updatedBy.username : ''}}</td>
        			 				<td> <button @click="editProvider(provider)" type="button" class="btn btn-primary waves-effect waves-light"> <i class="icofont icofont-ui-edit"></i></button></td>
        			 			</tr>


								<tr id="spin" style="display: none;text-align: center;">
									<td colspan="9"> <img src="{{asset('images/spin.svg')}}" width="400"> </td>
								</tr>

        			 			<tr v-if="providers.length==0" style="text-align: center;">
        			 				<td colspan="9">
        			 					No Data!
        			 				</td>
        			 			</tr>

        			 			<tr>

        			 				<td colspan="8">

        			 					<ul class="pagination">

        			 						 <li  class="page-item" @click="filterProviders(pagination.first)"><a class="page-link" >&laquo;</a></li>

        			 						 <li :class="{'disabled':!pagination.prev}" class="page-item" @click="filterProviders(pagination.prev)"><a class="page-link" >Previous</a></li>

        			 						 <li class="page-item"><a class="page-link">@{{pagination.currentPage}}/@{{pagination.lastPage}}</a></li>

        			 						 <li :class="{'disabled':!pagination.next}" class="page-item" @click="filterProviders(pagination.next)"><a class="page-link" >Next</a></li>

        			 						 <li  class="page-item" @click="filterProviders(pagination.last)"><a class="page-link" >&raquo;</a></li>

        			 					</ul>

        			 				</td>

        			 				<td colspan="1">

        			 					<select class="form-control" v-model="filter.limit" @change="filterProviders()" style="width: 100px;">

        			 						<option v-for="limit in limits">@{{limit}}</option>

        			 					</select>	

        			 				</td>
        			 			</tr>

        			 		</tbody>
        			 	</table>




 

	

	<div id="addProvider" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			  <div class="modal-content">
			  	 <div class="modal-header">
			  	 	<h5 class="modal-title">Add Provider</h5>
			  	 	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  	 </div>
			  	 <div class="modal-body">
			  	 	<div class="row">
			  	 		<div class="col-md-6">
			  	 			<div class="form-group">
			  	 				<label>Name</label>
			  	 				<input type="text" v-model="newModel.name" class="form-control">
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Country</label>
			  	 				<select class="form-control" v-model="newModel.country">
			  	 					<option v-for="country in countries">@{{country.name}}</option>
			  	 				</select>
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Url</label>
			  	 				<input type="text" class="form-control" v-model="newModel.url_site">
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>CPanel</label>
			  	 				<input type="text" class="form-control" v-model="newModel.cpanel">
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Login</label>
			  	 				<input type="text" class="form-control" v-model="newModel.login">
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Password</label>
			  	 				<input type="text" class="form-control" v-model="newModel.pwd">
			  	 			</div>
			  	 		</div>
			  	 		<div class="col-md-6">
			  	 			<div class="form-group">
			  	 				<label>Id Customer</label>
			  	 				<input type="text" class="form-control" v-model="newModel.id_customer">
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Email</label>
			  	 				<input type="text" class="form-control" v-model="newModel.inscr_email">
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>First Name</label>
			  	 				<input type="text" class="form-control" v-model="newModel.inscrfname">
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Last Name</label>
			  	 				<input type="text" class="form-control" v-model="newModel.inscrlname">
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Type</label>
			  	 				<select class="form-control" v-model="newModel.type">
			  	 					<option v-for="type in types">@{{type}}</option>
			  	 					
			  	 				</select>
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Note</label>
			  	 				<textarea class="form-control" v-model="newModel.note" rows="3"></textarea>
			  	 			</div>
			  	 		</div>
			  	 	</div>
			  	 </div>
			  	  <div class="modal-footer">
			  	  	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			  	  	<button type="button" class="btn btn-primary" @click="storeProvider()">Save changes</button>
			  	  </div>
			  </div>
		</div>
	</div>	


		<div id="editProvider" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			  <div class="modal-content">
			  	 <div class="modal-header">
			  	 	<h5 class="modal-title">Edit Provider</h5>
			  	 	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  	 </div>
			  	 <div class="modal-body">
			  	 	<div class="row">
			  	 		<div class="col-md-6">
			  	 			<div class="form-group">
			  	 				<label>Name</label>
			  	 				<input type="text" v-model="model.name" class="form-control">
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Country</label>
			  	 				<select class="form-control" v-model="model.country">
			  	 					<option v-for="country in countries">@{{country.name}}</option>
			  	 				</select>
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Url</label>
			  	 				<input type="text" class="form-control" v-model="model.url_site">
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>CPanel</label>
			  	 				<input type="text" class="form-control" v-model="model.cpanel">
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Login</label>
			  	 				<input type="text" class="form-control" v-model="model.login">
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Password</label>
			  	 				<input type="text" class="form-control" v-model="model.pwd">
			  	 			</div>
			  	 		</div>
			  	 		<div class="col-md-6">
			  	 			<div class="form-group">
			  	 				<label>Id Customer</label>
			  	 				<input type="text" class="form-control" v-model="model.id_customer">
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Email</label>
			  	 				<input type="text" class="form-control" v-model="model.inscr_email">
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>First Name</label>
			  	 				<input type="text" class="form-control" v-model="model.inscrfname">
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Last Name</label>
			  	 				<input type="text" class="form-control" v-model="model.inscrlname">
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Status</label>
			  	 				<select class="form-control" v-model="model.status">
			  	 					<option v-for="s in ss">@{{s}}</option>
			  	 					
			  	 				</select>
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Type</label>
			  	 				<select class="form-control" v-model="model.type">
			  	 					<option v-for="type in types"> @{{type}}</option>
			  	 					
			  	 				</select>
			  	 			</div>
			  	 			<div class="form-group">
			  	 				<label>Note</label>
			  	 				<textarea class="form-control" v-model="model.note" rows="3"></textarea>
			  	 			</div>
			  	 		</div>
			  	 	</div>
			  	 </div>
			  	  <div class="modal-footer">
			  	  	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			  	    <button type="button" class="btn btn-danger" data-dismiss="modal" @click="deleteProvider(model)">Delete</button>
			  	  	<button type="button" class="btn btn-primary" @click="updateProvider(model)">Save changes</button>
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
		$('#serverManagement').addClass('active');
	</script>
	<script src="{{asset('js/limit.js')}}"></script>
	<script src="{{asset('js/countries.js')}}"></script>
	<script src="{{asset('js/notification.js')}}"></script>
	<script src="{{asset('js/provider.js')}}"></script>

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
				app.filter.created_by = this.value;
				app.filterProviders();

			});
	</script>
@endsection