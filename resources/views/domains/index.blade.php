@extends('master2')

@section('content')

	<input type="hidden" value="{{url('api/getUnreadNotificationsCount')}}" id="notificationsUrl">

	@include('loader')

	<div style="display: none;" id="app">

	<div class="row">
		<div class="col-md-6">
			<h4>List Domains</h4>
		</div>
		<div class="col-md-6">
			<div class="flex-container">
				<button type="button" class="btn btn-primary waves-effect waves-light r" @click="createDomain()"><i class="icofont-plus"></i></button>
				<button type="button" class="btn btn-primary waves-effect waves-light r" @click="refreshData()"><i class="icofont-refresh"></i></button>
				<button type="button" class="btn btn-success waves-effect waves-light r" @click="openExportModal()"><i class="icofont-file-excel"></i></button>
			</div>
		</div>
	</div>

	<hr>
	
	<table class="table table-bordered table-sm">
        <thead>
           	<tr class="bg-primary">
	            <th>Group</th>
				<th >Domain <i @click="sort('domain')" class="icofont-sort"></i></th>
				<th>Registrar</th>
				<th>Purchased</th>
				<th>Expiration</th>
				<th>Created</th>
				<!--<th>
					Price
					 <i @click="sort('price')" class="icofont-sort"></i>
					 <select v-model="filter.currency" @change="fetchData()">
		                <option></option>
		                <option v-for="currency in currencies">@{{currency}}</option>
		             </select>
				</th>-->
				<th>Status</th>
				<th>Active</th>
				<!--<th>Created By</th>-->
				<!--<th>Updated By </th>-->
				<th> Edit </th>
            </tr>
        </thead>
        <tbody class="text-center">
            <tr>
                <td>
                    <select style="width: 100px" type="text" class="form-control" v-model="filter.group_id" @change="fetchData()">
                        <option></option>
                        <option v-for="group in groups" :value="group.id">@{{group.name}}</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control" v-model="filter.domain" @keyup="fetchData()">
                </td>
                <td>
                    <select style="width: 250px" type="text" class="form-control registrars_select" v-model="filter.registrar_id" >
                        <option></option>
                        <option v-for="registrar in registrars" :value="registrar.id">@{{registrar.name}}(@{{registrar.eml_contact}})</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control" v-model="filter.datePurchase" @keyup="fetchData()">
                </td>
                <td>
                    <input type="text" class="form-control" v-model="filter.dateExpiration" @keyup="fetchData()">
                </td>
                <td>
                    <input type="text" class="form-control" v-model="filter.created_at" @keyup="fetchData()">
                </td>
               <!-- <td>
                   <input type="text" class="form-control" v-model="filter.price" @keyup="fetchData()">
                </td>-->
                <td>
                    <select style="width: 100px" type="text" class="form-control" v-model="filter.status" @change="fetchData()">
                        <option></option>
                        <option v-for="s in status">@{{s}}</option>
                    </select>
                </td>
                <td>
                    <select style="width: 100px" type="text" class="form-control" v-model="filter.is_active" @change="fetchData()">
                        <option></option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </td>
                <!--<td><select style="width: 100px;" class="createdByFilter"></select></td>-->
                <!--<td></td>-->
                <td></td>
            </tr>

            <tr id="spin" style="display: none;"> 
            	<td colspan="9">
            		<img src="{{asset('images/spin.svg')}}" width="400 ">
            	</td>
            </tr>

             <tr v-if="domains.length==0" style="text-align: center;">
                <td colspan="9">No Data!</td>
            </tr>

             <tr v-if="domains.length>0"  v-for="domain in domains">
				<td>@{{domain.group ? domain.group.name : ''}}</td>
				<td>@{{domain.domain}}</td>
				<td>
					@{{domain.registrar ? domain.registrar.name : ''}}
					<p>@{{domain.registrar ? domain.registrar.email : ''}}</p>
				</td>
				<td>@{{domain.entered}}</td>
				<td>@{{domain.datex}}</td>
				<td>@{{domain.datec}}</td>
				<!--<td>@{{domain.price ? domain.price : ''}} @{{domain.price ? currencyCode(domain.currency) : ''}}</td>-->
				<td><button class="btn" :class="color(domain.status)">@{{domain.status}}</button></td>
				<td> <i v-if="domain.is_active" class="icofont-check"></i></td>
				<!--<td>@{{domain.createdBy ? domain.createdBy.username : ''}}</td>-->
				<!--<td>@{{domain.updatedBy ? domain.updatedBy.username : ''}}</td>-->
				<td class="faq-table-btn">
					<button @click="editDomain(domain)" type="button" class="btn btn-primary waves-effect waves-light"> <i class="icofont icofont-ui-edit"></i> </button>
                </td>
            </tr>

           

             <tr>
                <td colspan="8"> 
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

		
		 


		<div id="addDomain" class="modal fade" tabindex="-1" role="dialog">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title">Add Domain</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <form>
		        	<div class="row">
		        		<div class="col-md-6">
		        			<div class="form-group">
								<label>Registrar:</label>
								<select style="width: 100%" class="form-control" v-model="newModel.registrar_id">
										<option v-for="registrar in registrars" :value="registrar.id">@{{registrar.name}} (@{{registrar.eml_contact}})</option>
								</select>
							</div>
							<div class="form-group">
								<label>Domains:</label>
								<textarea class="form-control" v-model="newModel.domains" rows="5"></textarea>
							</div>
							<div class="form-group">
								<label>Group:</label>
								<select class="form-control" v-model="newModel.group_id">
										<option v-for="group in groups" :value="group.id">@{{group.name}}</option>
								</select>
							</div>
							<div class="form-group">
								<label>Date Purchase</label>
							    <input type="date" class="form-control" v-model="newModel.entered" >
							</div>
		        		</div>
		        		<div class="col-md-6">
							<div class="form-group">
								<label>Date Expiration</label>
							   	<input type="date"  class="form-control" v-model="newModel.datex">
							</div>
							<div class="form-group">
								<label>Description:</label>
								<textarea class="form-control" rows="3" v-model="newModel.description" ></textarea>
							</div>
							<!--<div class="form-group">
								<label>Price</label>
								<input type="number" step="0.01" class="form-control" v-model="newModel.price">
							</div>-->
							<!--<div class="form-group">
								<label>Currency</label>
								<select class="form-control" v-model="newModel.currency">
									<option v-for="currency in currencies">@{{currency}}</option>
								</select>
							</div>-->
		        		</div>
		        	</div>
		        </form>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-primary" @click="addDomain()">Save changes</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div id="editDomain" class="modal fade" tabindex="-1" role="dialog">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title">Edit Domain</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <form>
		        	<div class="row">
		        		<div class="col-md-6">
		        			<div class="form-group">
								<label>Registrar:</label>
								<select style="width: 100%" class="form-control" v-model="model.registrar_id">
										<option v-for="registrar in registrars" :value="registrar.id" >@{{registrar.name}} (@{{registrar.eml_contact}})</option>
								</select>
							</div>
							<div class="form-group">
								<label>Domain:</label>
								<input type="text"  placeholder="Domain" class="form-control" v-model="model.domain">
							</div>
							<div class="form-group">
								<label>Group:</label>
								<select class="form-control" v-model="model.group_id">
										<option v-for="group in groups" :value="group.id">@{{group.name}}</option>
								</select>
							</div>
							<div class="form-group">
								<label>Date Purchase}</label>
							    <input type="date" class="form-control" v-model="model.entered" >
							</div>
							<div class="form-group">
								<label>Date Expiration</label>
							   	<input type="date"  class="form-control" v-model="model.datex">
							</div>
		        		</div>
		        		<div class="col-md-6">
							<div class="form-group">
								<label>Status</label>
							   	<select v-model="model.status" class="form-control">
							   		<option v-for="s in status">@{{s}}</option>
							   	</select>
							</div>
							<div class="form-check">
							  <label class="form-check-label"> <input class="form-check-input" type="checkbox" v-model="model.is_active"> Enable</label>
							</div>
							<div class="form-check ">
							  <label class="form-check-label" > <input class="form-check-input" type="checkbox" v-model="model.is_expired"> Expired</label>
							</div>
							<div class="form-group">
								<label>Description:</label>
								<textarea class="form-control" rows="3" v-model="model.description" ></textarea>
							</div>
							<!--<div class="form-group">
								<label>Price</label>
								<input type="number" step="0.01" class="form-control" v-model="model.price">
							</div>-->
							<!--<div class="form-group">
								<label>Currency</label>
								<select class="form-control" v-model="model.currency">
									<option v-for="currency in currencies">@{{currency}}</option>
								</select>
							</div>-->
		        		</div>
		        	</div>
		        </form>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-danger" @click="removeDomain(model)">Delete</button>
		        <button type="button" class="btn btn-primary" @click="updateDomain(model)">Save changes</button>
		      </div>
		    </div>
		  </div>
		</div>


		<div id="exportModal" class="modal fade" tabindex="-1" role="dialog">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title">Export List Domains</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		       	  <div class="row">
		       	  	<div class="col-md-4">
		       	  		<div class="form-group">
			       	  		<select class="form-control" v-model="exportGroup">
			       	  			<option></option>
			       	  			<option v-for="group in groups" :value="group">@{{group.name}}</option>
			       	  		</select>
			       	  	</div>
		       	  	</div>
		       	  	<div class="col-md-8">
		       	  		<div  id="reportrange" class="f-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
		       	  			<i class="glyphicon glyphicon-calendar icofont icofont-ui-calendar"></i> 
		       	  			<span></span>
		       	  			<b class="caret"></b>
		       	  		</div>
		       	  	</div>
		       	  </div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        <button id="exportDomains" type="button" class="btn btn-success" @click="exportDomains()">Export</button>
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
	<script src="{{asset('js/currencies.js')}}"></script>
	<script src="{{asset('js/limit.js')}}"></script>
	<script src="{{asset('js/notification.js')}}"></script>
	<script src="{{asset('js/domain.js')}}"></script>
	<script>
		$('.registrars_select').select2(
			{
				allowClear: true,


				placeholder : { id : '' , 'placeholder' : '' },

				ajax : 
				{
					url : '{{url('api/getRegistrars')}}',

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
								return { id : item.id , text : item.name+'('+item.eml_contact+')' };
							}),

							pagination : { more : true }
						}
					},


				},
			}).on('change',function()
			{
				app.filter.registrar_id = this.value;
				app.fetchData();

			});

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
				app.fetchData();

			});





			
	</script>
@endsection


