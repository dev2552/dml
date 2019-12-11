@extends('master2')

@section('content')

<input type="hidden" value="{{url('api/getUnreadNotificationsCount')}}" id="notificationsUrl">

@include('loader')

<div style="display: none;" id="app">

<div class="row">
	<div class="col-md-6">
		<h4>Sub Domains</h4>
	</div>
	<div class="col-md-6">
		<div class="flex-container">
			<button type="button" class="btn btn-primary waves-effect waves-light r" @click="refreshSubDomains()"><i class="icofont-refresh"></i></button>
		</div>
	</div>
</div>


<hr>

<table class="table table-bordered table-sm">
	<thead>
		<tr class="bg-primary">
			<th>Ip</th>
			<th>SubDomain</th>
			<th>Domain</th>
			<th>Created</th>
			<th>Active</th>
			<!--<th>Updated By</th>-->
			<th>Edit</th>
		</tr>
	</thead>
	
	<tbody>	
		<tr>
			<td>
				<!--<input type="text" class="form-control" v-model="filter.ip" @keyup="filterSubDomains()">-->
				<select style="width: 250px;" class="ips_select"></select>
			</td>
			<td>
				<!--<input type="text" class="form-control" v-model="filter.subdomain" @keyup="filterSubDomains()">-->
				<select style="width: 250px;" class="domains_select"></select>
			</td>
			<td><input type="text" class="form-control" v-model="filter.domain" @keyup="filterSubDomains()"></td>
			
			<td>
				<input type="text" class="form-control" v-model="filter.created_at" @keyup="filterSubDomains() ">
			</td>
			<td>
				<select class="form-control" v-model="filter.enable" @change="filterSubDomains()">
					<option></option>
					<option value="1">Active</option>
					<option value="0">Inactive</option>
				</select>
			</td>
			<!--<td></td>-->
			<td></td>
		</tr>	

		<tr id="spin" style="display: none;text-align: center;">
			<td colspan="8">
				<img src="{{asset('images/spin.svg')}}" width="400">
			</td>
		</tr>

		<tr v-if="subDomains.length == 0" style="text-align: center;">
			<td colspan="8">No Data !</td>
		</tr>

		<tr v-for="subDomain in subDomains">
			<td>@{{subDomain.ip.ip}}</td>
			<td>@{{subDomain.subdomain}}</td>
			<td>@{{subDomain.domain.domain}}</td>
			
			<td>@{{subDomain.created}}</td>
			<td><i v-if="subDomain.is_active" class="icofont-check-alt"></i></td>
			<!--<td>@{{subDomain.user ? subDomain.user.username : ''}}</td>-->
			<td>
				<button class="btn btn-primary" @click="editSubDomain(subDomain)"><i class="icofont-ui-edit"></i></button>
			</td>
		</tr>

		<tr>
			<td colspan="5">
				<ul class="pagination" style="float: left;">
					<li  class="page-item" @click="filterSubDomains(pagination.first)"><a class="page-link" >&laquo;</a></li>
					<li :class="{'disabled':!pagination.prev}" class="page-item" @click="filterSubDomains(pagination.prev)"><a class="page-link" >Previous</a></li>
					<li class="page-item"><a class="page-link">@{{pagination.currentPage}}/@{{pagination.lastPage}}</a></li>
					<li :class="{'disabled':!pagination.next}" class="page-item" @click="filterSubDomains(pagination.next)"><a class="page-link" >Next</a></li>
					<li  class="page-item" @click="filterSubDomains(pagination.last)"><a class="page-link" >&raquo;</a></li>
				</ul>
			</td>
			<td>
				<select class="form-control" v-model="filter.limit" @change="filterSubDomains()">
					<option v-for="limit in limits">@{{limit}}</option>
				</select>
			</td>
		</tr>
	</tbody>
</table>

<div id="editSubDomain" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Sub Domain</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
        	<div class="col-md-6">
        		<div class="form-group">
        			<select style="width: 100%" class="form-control edit_subDomain_servers_select"></select>
        		</div>
        	</div>
        	<div class="col-md-6">
        		<div class="form-group">
        			<select style="width: 100%" class="form-control" v-model="selectedSubDomain.ip">
        				<option v-for="ip in ips" :value="ip">@{{ip}}</option>
        			</select>
        		</div>
        	</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" @click="updateSubDomain()">Save changes</button>
      </div>
    </div>
  </div>
</div>

</div>

@endsection



@section('javascript')

<script>

	$('#dashboard').removeClass('active');
	
	$('#configuration').addClass('active');

</script>

<script src="{{asset('js/limit.js')}}"></script>

<script src="{{asset('js/notification.js')}}"></script>

<script src="{{asset('js/subDomain.js')}}"></script>

<script>

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

		app.filterSubDomains();
	})




	$('.edit_subDomain_servers_select').select2(
		{
			allowClear: true,

			placeholder : { id : '' , 'placeholder' : '' },

			dropdownParent : $('#editSubDomain'),
			
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
			app.selectedSubDomain.server_id = this.value;

			app.listIps();
		})


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

			app.filterSubDomains();
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

			app.filterSubDomains();
		});

</script>

@endsection



@section('css')

<style>

	.flex-container{display: flex;justify-content:flex-end;}
	select.form-control:not([size]):not([multiple]){height: 28px;padding:0rem 0rem !important;}
	input[type=text]{height: 28px !important;}
	table td {font-size: 0.8rem;}

</style>

@endsection