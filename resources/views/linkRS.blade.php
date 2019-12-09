@extends('master2')

@section('content')

<input type="hidden" value="{{url('api/getUnreadNotificationsCount')}}" id="notificationsUrl">

	@section('breadcrumb')
		<ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
             <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="icofont icofont-home"></i></a></li>
             <li class="breadcrumb-item"><a href="{{route('linkRS')}}">Link Request/Server</a></li>
       </ol>	
	@endsection

	<h4>Link Request to Server</h4>
	<hr>
		
		@include('loader')

	<div style="display: none;" id="app">
		<div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Servers</label>
						<select style="width: 100%" class="form-control servers_select" v-model="newModel.server">
								<option v-for="server in servers" :value="server.name">@{{server.name}}</option>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>requests</label>
						<select style="width: 100%" class="form-control requests_select" v-model="newModel.subject">
								<option v-for="request in requests" :value="request.subject">@{{request.subject}}</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<button class="btn btn-primary" @click="save()">Save</button>
				</div>
			</div>

			<div class="form-group top col-md-6 col-xs-12">
				<input type="text" class="form-control" placeholder="Filter" v-model="filter.criteria" @keyup="paginate()">
			</div>

			<table class="table top table-bordered table-sm">
				<thead>
					<tr class="bg-primary">
						<th>Server</th>
						<th>Request</th>
						<th>Created</th>
					</tr>
				</thead>
				<tbody>
					<tr v-if="records.length>0"  v-for="record in records">
						<td>@{{record.server}}</td>
						<td>@{{record.subject}}</td>
						<td>@{{record.created_at}}</td>
					</tr>
					<tr v-if="records.length==0" style="text-align: center;">
						<td colspan="3">
							No Data!
						</td>
					</tr>
				</tbody>
			</table>

			<nav>
			  <ul class="pagination">
			    <li :class="{'disabled':!pagination.prev}" class="page-item" @click="paginate(pagination.prev)"><a class="page-link" >Previous</a></li>
			      <li class="page-item"><a class="page-link">@{{pagination.currentPage}}/@{{pagination.lastPage}}</a></li>
			    <li :class="{'disabled':!pagination.next}" class="page-item" @click="paginate(pagination.next)"><a class="page-link" >Next</a></li>
			  </ul>
			</nav>

		</div>
	</div>

@endsection

@section('javascript')
	<script>
		$('#dashboard').removeClass('active');
		$('#requests').addClass('active');
	</script>
	<script src="{{asset('js/notification.js')}}"></script>*
	<script src="{{asset('js/linkRS.js')}}"></script>

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
								return { id : item.name , text : item.name };
							}),

							pagination : { more : true }
						}
					},


				}
		}).on('change',function()
		{
			app.newModel.server = this.value;
		})



		$('.requests_select').select2(
		{
				allowClear: true,

				placeholder : { id : '' , 'placeholder' : '' },

				ajax : 
				{
					url : '{{url('api/getRequests')}}',

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
								return { id : item.subject , text : item.subject };
							}),

							pagination : { more : true }
						}
					},


				}
		}).on('change',function()
		{
			app.newModel.subject = this.value;
		})
	</script>
@endsection

@section('css')
	<style>
		input[type=text]{height: 28px !important;}
		table td {font-size: 0.8rem;}
	</style>
@endsection