@extends('master2')

@section('content')

<input type="hidden" value="{{url('api/getUnreadNotificationsCount')}}" id="notificationsUrl">

@include('loader')

<div style="display: none;" id="app">

<div class="row">
	<div class="col-md-6">
		<h4>List Registrars</h4>
	</div>
	<div class="col-md-6">
		<div class="flex-container">
			<button type="button" class="btn btn-primary waves-effect waves-light r" @click="createRegistrar()"><i class="icofont-plus"></i></button>
			<button type="button" class="btn btn-primary waves-effect waves-light r" @click="refreshData()"><i class="icofont-refresh"></i></button>
		</div>
	</div>
</div>
<hr>

<table class="table table-bordered table-sm">

    <thead>
       	<tr class="bg-primary">
            <th class="text-center ">Name <i @click="sort('name')" class="icofont-sort"></i></th>
            <th class="text-center ">Company <i @click="sort('company')" class="icofont-sort"></i></th>
            <th class="text-center ">Website <i @click="sort('website')" class="icofont-sort"></i></th>
            <th class="text-center ">Email Contact <i @click="sort('eml_contact')" class="icofont-sort"></i></th>
            <th class="text-center ">Link</th>
            <th class="text-center ">Active</th>
            <!--<th>Created By</th>-->
            <!--<th>Updated By</th>-->
            <th>Edit</th>					
        </tr>
    </thead>

    <tbody class="text-center">

    	<tr style="background-color: white">
    		<td><input type="text" class="form-control" v-model="filter.name" @keyup="fetchData()"></td>
			<td> <input type="text" class="form-control" v-model="filter.company" @keyup="fetchData()"></td>
            <td><input type="text" class="form-control" v-model="filter.website" @keyup="fetchData()"> </td>
            <td> <input type="text" class="form-control" v-model="filter.eml_contact" @keyup="fetchData()"></td>
            <td></td>
            <td>
                <select style="width: 100px" class="form-control" v-model="filter.is_active" @change="fetchData()"> 
                    <option></option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </td>
           <!-- <td><select style="width: 100px;" class="createdByFilter"></select></td>-->
            <!--<td></td>-->
            <td></td>
       	</tr>

       	<tr id="spin" style="display: none;">
       		<td colspan="7">
				<img src="{{asset('images/spin.svg')}}" width="400">
       		</td>
       	</tr>

       	<tr v-if="registrars.length==0" style="text-align: center;">
        	<td colspan="7">No Data!</td>
    	</tr>

       	<div id="content">
	        <tr v-if="registrars.length>0" v-for="registrar in registrars">
				<td>@{{registrar.name}}</td>
				<td>@{{registrar.company}}</td>
				<td>@{{registrar.website}}</td>
				<td>@{{registrar.eml_contact}}</td>
				<td><a :href="registrar.website" target="_blank">@{{registrar.website}}</a></td>
				<td> <i v-if="registrar.is_active" class="icofont-check"></i></td>
				<!--<td>@{{registrar.createdBy ? registrar.createdBy.username : ''}}</td>-->
				<!--<td>@{{registrar.updatedBy ? registrar.updatedBy.username : ''}}</td>-->
	            <td class="faq-table-btn">
	            	<div style="display: flex;">
		                <button @click="editRegistrar(registrar)" type="button" class="btn btn-primary waves-effect waves-light">
		                	<i class="icofont icofont-ui-edit"></i>
		                </button>
		               {{--  &nbsp;
		                <a target="_blank" href="{{asset('godaddy/godaddy.html')}}" class="btn btn-primary">Login</a> --}}
	           		</div>
	            </td>
	        </tr>
    	</div>

        

    <tr>
        <td colspan="6">
			<ul class="pagination" style="float: left;">
				<li  class="page-item" @click="fetchData(pagination.first)"><a class="page-link" >&laquo;</a></li>
				<li :class="{'disabled':!pagination.prev}" class="page-item" @click="fetchData(pagination.prev)"><a class="page-link">Previous</a></li>
				<li class="page-item"><a class="page-link">@{{pagination.currentPage}}/@{{pagination.lastPage}}</a></li>
				<li :class="{'disabled':!pagination.next}" class="page-item" @click="fetchData(pagination.next)"><a class="page-link" >Next</a></li>
				<li  class="page-item" @click="fetchData(pagination.last)"><a class="page-link" >&raquo;</a></li>
			</ul>
		</td>
        <td colspan="1">
            <select class="form-control top" v-model="filter.limit" style="width: 100px" @change="fetchData()">
				<option v-for="limit in limits">@{{limit}}</option>
			</select>
        </td>
    </tr>

    </tbody>
    </table>

	

		

		<div id="addRegistrar" class="modal fade" tabindex="-1" role="dialog">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title">Add Registrar</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <form>
		        	<div class="row">
		        		<div class="col-md-6">
		        			<div class="form-group">
		        				<label>Name:</label>
								<input type="text" name="name"  class="form-control" v-model="newModel.name">
								
							</div>
							<div class="form-group">
								<label>Company:</label>
								<input type="text" name="company"  class="form-control" v-model="newModel.company">
								
							</div>
							<div class="form-group">
								<label>Email Contact:</label>
								<input type="text" name="email" class="form-control" v-model="newModel.eml_contact">
								
							</div>
							<div class="form-group">
								<label>Password:</label>
								<input type="text" name="password"  class="form-control" v-model="newModel.password">
								
							</div>
							<div class="form-group">
								<label>Customer Id:</label>
								<input type="text" name="customerId" class="form-control" v-model="newModel.customerid">
								
							</div>
							<div class="form-group">
								<label>Key:</label>
								<input type="text" name="key"  class="form-control" v-model="newModel.registrar_key">
								
							</div>
		        		</div>
		        		<div class="col-md-6">
		        			<div class="form-group">
		        				<label>Secret:</label>
								<input type="text" name="secret"  class="form-control" v-model="newModel.secret">
								
							</div>
							<div class="form-group">
								<label>Website:</label>
								<input type="text" name="website"  class="form-control" v-model="newModel.website">
								
							</div>
							<div class="form-group">
								<label>Phone:</label>
								<input type="text" name="phone"  class="form-control" v-model="newModel.phone">
								
							</div>
							<div class="form-group">
								<label>Address:</label>
								<input type="text" name="address"  class="form-control" v-model="newModel.address">
								
							</div>
							<div class="form-group">
							    <div class="form-check">
							      <label class="form-check-label"><input class="form-check-input" type="checkbox" name="enable" v-model="newModel.is_active"> Enable</label>
							    </div>
							</div>
						     <div class="form-group">
						     	 <label>Description</label>
							      <textarea rows="3" name="description" class="form-control" v-model="newModel.description"></textarea>
							      
						    </div>
		        		</div>
		        	</div>
		        </form>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-primary" @click="addRegistrar()">Save changes</button>
		      </div>
		    </div>
		  </div>
		</div>
		<div id="editRegistrar" class="modal fade" tabindex="-1" role="dialog">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title">Edit Registrar</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <form>
		        	<div class="row">
		        		<div class="col-md-6">
		        			<div class="form-group">
								<label>Name:</label>
								<input type="text" name="name" placeholder="Name" class="form-control" v-model="model.name">
							</div>
							<div class="form-group">
								<label>Company:</label>
								<input type="text" name="company" placeholder="Company" class="form-control" v-model="model.company">
							</div>
							<div class="form-group">
								<label>Email Contact:</label>
								<input type="text" name="email" placeholder="Email Contact" class="form-control" v-model="model.eml_contact">
							</div>
							<div class="form-group">
								<label>Password:</label>
								<input type="text" name="password" placeholder="Password" class="form-control" v-model="model.password">
							</div>
							<div class="form-group">
								<label>Customer Id:</label>
								<input type="text" name="customerId" placeholder="Customer Id" class="form-control" v-model="model.customerid">
							</div>
							<div class="form-group">
								<label>Key:</label>
								<input type="text" name="key" placeholder="Key" class="form-control" v-model="model.registrar_key">
							</div>
		        		</div>
		        		<div class="col-md-6">
		        			<div class="form-group">
								<label>Secret:</label>
								<input type="text" name="secret" placeholder="Secret" class="form-control" v-model="model.secret">
							</div>
							<div class="form-group">
								<label>Website:</label>
								<input type="text" name="website" placeholder="Website" class="form-control" v-model="model.website">
							</div>
							<div class="form-group">
								<label>Phone:</label>
								<input type="text" name="phone" placeholder="Phone" class="form-control" v-model="model.phone">
							</div>
							<div class="form-group">
								<label>Address:</label>
								<input type="text" name="address" placeholder="Address" class="form-control" v-model="model.address">
							</div>
							<div class="form-group">
							    <div class="form-check">
							     
							      <label class="form-check-label"><input class="form-check-input" type="checkbox" name="enable" v-model="model.is_active"> Enable</label>
							    </div>
							</div>
						     <div class="form-group">
							      <label>Description</label>
							      <textarea rows="3" name="description" class="form-control" placeholder="Description" v-model="model.description"></textarea>
						    </div>
		        		</div>
		        	</div>
		        </form>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-danger" @click="removeRegistrar(model)">Delete</button>
		        <button type="button" class="btn btn-primary" @click="updateRegistrar(model)">Save changes</button>
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
		table td,table th {font-size: 0.8rem;}
	</style>
@endsection

@section('javascript')
	<script>
		$('#dashboard').removeClass('active');
		$('#configuration').addClass('active');
	</script>
	<script src="{{asset('js/limit.js')}}"></script>
	<script src="{{asset('js/notification.js')}}"></script>
	<script src="{{asset('js/registrar.js')}}"></script>
	<script>
		$('.createdByFilter').select2(
		{
			allowClear:true,
			placeholder:{id:'','placeholder':''},
			ajax : 
			{
				url : '{{url('api/getUsers')}}',
				data : function(params)
				{
					return {search:params.term,page:params.page };
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
			app.fetchData();
		});
	</script>
@endsection


