@extends('master2')

@section('content')

<input type="hidden" value="{{url('api/getUnreadNotificationsCount')}}" id="notificationsUrl">

<h4>Configure DNS</h4>

<hr>

@include('loader')

<div style="display: none;" id="app" class="container">

	<div>

		<h5>Clear Domain</h5>

		<div class="form-group">

			<select class="form-control domains_select" v-model="domainToClear" @change="clearDomain()">
				<option></option>
				<option v-for="domain in domains" :value="domain.id">@{{domain.domain}}</option>
			</select>

		</div>

	</div>

	<div>

		<hr>

		<h5> Registrar </h5>

		<div class="form-group">

			<select class="form-control registrars_select" v-model="selectedRegistrar" @change="setDomainsList()">
				<option></option>
				<option v-for="registrar in registrars" :value="registrar.id">@{{registrar.name}}(@{{registrar.email}})</option>
			</select>

		</div>

		<div class="row">

			<div class="col-md-6">

				<div class="form-group">

					{{--<select :size="6"  multiple class="form-control" style="overflow: scroll;">
						
						<option v-for="domain1 in domainsList" :value="domain1" @click="toDomainsList(2,domain1)">@{{domain1.domain}}</option>

					</select>--}}

					<div style="overflow: scroll;height: 200px">
						<p v-if="domainsListLoading">wait...</p>
						<p style="cursor: pointer;" v-for="domain1 in domainsList" @click="toDomainsList(2,domain1)">@{{ domain1.domain }}</p>
					</div>

				</div>

			</div>

			<div class="col-md-6">

				<div class="form-group">

					{{--<select :size="6" multiple class="form-control" style="overflow: scroll;">

						<option v-for="domain2 in selectedDomains" :value="domain2" @click="toDomainsList(1,domain2)">@{{domain2.domain}}</option>

					</select>--}}

					<div style="overflow: scroll;height: 200px">
						<p style="cursor: pointer;" v-for="domain2 in selectedDomains" @click="toDomainsList(1,domain2)">@{{ domain2.domain }}</p>
					</div>

				</div>

			</div>

		</div>

	</div>

	<hr>

	<div> <!--mo3taz -->

		<h5>Server</h5>

		<div class="form-group">

			

			<select  class="form-control servers_select" v-model="selectedServer" @change="setIpList()">
				
				<option></option>
				
				<option v-for="server in servers" :value="server.id">@{{server.name}}</option>

			</select>

		</div>

		<div class="row">

			<div class="col-md-6">

				<div class="form-group">

					<i  class="icofont-copy" data-clipboard-target="#c_ips"></i>

					<hr style="border: 0">

					{{--<select id="c_ips" :size="6"  multiple class="form-control" style="overflow: scroll;">
						
						<option v-for="ip in ipList" :value="ip.ip" >@{{ip.ip}}</option>

					</select>--}}

					<div id="c_ips" style="height: 200px;overflow: scroll;">

						<ul style="list-style: none;">
							<li v-for="ip in ipList">@{{ ip.ip }}</li>
						</ul>

					</div>

				</div>

			</div>

			<div class="col-md-6">

				<div class="form-group">

					<i  class="icofont-copy" data-clipboard-target="#c_subdomains"></i>

					<hr style="border: 0">

					{{--<select  :size="6" multiple class="form-control" style="overflow: scroll;">

						<option v-for="subDomain in ipList" :value="subDomain.subDomain" >@{{subDomain.subDomain}}</option>

					</select>--}}

					<div id="c_subdomains" style="height: 200px;overflow: scroll;">

						<ul style="list-style: none;">
							<li v-for="subDomain in ipList">@{{ subDomain.subDomain }}</li>
						</ul>

					</div>
					

				</div>

			</div>

		</div>

		<div class="form-group">

			<input type="text" placeholder="DKIM key" class="form-control" v-model="DKIM">

		</div>

	</div>

	<button class="btn btn-primary"  @click="updateDNS()">Update DNS</button>

	{{--<div id="c_subdomains" style="visibility: hidden;">
		<li v-for="subDomain in ipList">@{{subDomain.subDomain}}</li>
	</div>--}}

</div>



@endsection

@section('javascript')

<script>

	$('#dashboard').removeClass('active');
	$('#configuration').addClass('active');

	new ClipboardJS('.icofont-copy');

</script>

<script src="{{asset('js/randomWord.js')}}"></script>
<script src="{{asset('js/notification.js')}}"></script>
<script src="{{asset('js/configureDNS.js')}}"></script>

<script>

	$('.domains_select').select2(
		{
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
		app.domainToClear = this.value;

		app.clearDomain();
	})

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


				}
		}).on('change',function()
	{
		app.selectedRegistrar = this.value;

		app.setDomainsList();
	})

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
		app.selectedServer = this.value;

		app.setIpList();
	})

</script>

@endsection

@section('css')

@endsection