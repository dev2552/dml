@extends('master2')

@section('content')

<input type="hidden" value="{{url('api/getUnreadNotificationsCount')}}" id="notificationsUrl">

@include('loader')


<div style="display: none;" id="app">

<div class="row">
	<div class="col-md-6">
		<h4>List Servers</h4>
	</div>
	<div class="col-md-6">
		<div class="flex-container">
			@can('create',App\Models\ServerModel::class)
				<button type="button" class="btn btn-primary waves-effect waves-light r" @click="createServer()"><i class="icofont-plus"></i></button>
				<button type="button" class="btn btn-warning waves-effect waves-light r" @click="renewalServersModal()">Renewal</button>
				<button type="button" class="btn btn-success waves-effect waves-light r" @click="toggleStatusModal()"> <i class="icofont icofont-ui-edit"></i></button>
				<button  class="btn btn-success waves-effect waves-light r" @click="exportModal()"><i class="icofont-file-excel"></i></button>
			@endcan	
			<button type="button" class="btn btn-primary waves-effect waves-light r" @click="refreshServers()"><i class="icofont-refresh"></i></button>
		</div>
	</div>
</div>

<hr>

	<table class="top table table-bordered table-sm" style="font-size: 0.9rem;text-align: center;">

		<thead>
			<tr class="bg-primary">
				@can('create',App\Models\ServerModel::class)
					<td></td>
				@endcan
				@can('create',App\Models\ServerModel::class)
					<th>Group</th>
				@endcan	
				<th>Name <i @click="sort('name')" class="icofont-sort"></i></th>
				<th>Provider</th>
				<th></th>
				<th>Main Ip</th>
				<th>Main Domain</th>
				<th>N° Ips</th>
				<th>Purchased</th>
				<th>Dates</th>
				<th>Config</th>
				@can('create',App\Models\ServerModel::class)
					<th>
						Price
					 	<i @click="sort('price')" class="icofont-sort"></i>
					 	<select  v-model="filter.currency" @change="getServers()">
							<option></option>
							<option v-for="currency in currencies">@{{currency}}</option>
						</select>
					</th>
				@endcan
				<th>Status</th>
				@can('create',App\Models\ServerModel::class)
					<!--<th>Created By</th>-->
					<!--<th>Updated By</th>-->
					<th>Edit</th>
				@endcan	
			</tr>
		</thead>

		<tbody>

			<tr>
				@can('create',App\Models\ServerModel::class)
					<td></td>
				@endcan	
				
				@can('create',App\Models\ServerModel::class)
					<td>
						<select class="form-control" style="width: 100px;" v-model="filter.group_id" @change="getServers()">
							<option></option>
							<option v-for="group in groups" :value="group.id">@{{group.name}}</option>
						</select>
					</td>
				@endcan	
				<td>
					<input type="text" class="form-control" v-model="filter.s_name" @keyup="getServers()">
				</td>	
				<td>
					<select  class="form-control providers_select" style="width: 150px;"></select>
				</td>
				<td></td>
				<td>
					<input type="text" class="form-control" v-model="filter.main_ip" @keyup="getServers()">
				</td>
				<td>
					<input type="text" class="form-control" v-model="filter.main_domain" @keyup="getServers()">
				</td>
				<td></td>
				<td>
					<input type="text" class="form-control" v-model="filter.date_purchase" @keyup="getServers()">
				</td>
				<td></td>
				<td></td>
				@can('create',App\Models\ServerModel::class)
					<td>
						<input type="text" class="form-control" v-model="filter.price" @keyup="getServers()">
					</td>
				@endcan
				<td>
					<select style="width: 120px;" class="form-control" v-model="filter.last_status" @change="getServers()">
						<option></option>
						<option v-for="s in status" :value="s">@{{setStatus(s)}}</option>
					</select>
				</td>
				@can('create',App\Models\ServerModel::class)
					<!--<td><select style="width:100px;" class="createdByFilter"></select></td>-->
					<!--<td></td>-->
					<td></td>
				@endcan	
				
			</tr>

			<tr style="display: none;" id="spin">

				@can('create',App\Models\ServerModel::class)
					<td colspan="16">
						<img src="{{asset('images/spin.svg')}}" width="400">
					</td>
				@endcan

				@cannot('create',App\Models\ServerModel::class)
					<td colspan="10">
						<img src="{{asset('images/spin.svg')}}" width="400">
					</td>
				@endcannot

			</tr>

			<tr v-if="servers.length==0">
				@can('create',App\Models\ServerModel::class)
					<td colspan="16" style="text-align: center;">
				@endcan
				@cannot('create',App\Models\ServerModel::class)	
					<td colspan="11">
				@endcannot	
				No Data!
				</td>	
			</tr>

			<tr v-if="servers.length>0"  v-for="server in servers">
				@can('create',App\Models\ServerModel::class)
					<td>
						<input type="checkbox" @change=toggleServer($event,server) :checked="list.filter((s)=>(s.id == server.id)).length>0">
					</td>
				@endcan	
				@can('create',App\Models\ServerModel::class)
					<td style="width: 200px;font-size: 0.8rem">@{{server.group ? server.group.name : ''}}</td>
				@endcan	
				@can('create',App\Models\ServerModel::class)
					<td style="width: 200px;font-size: 0.8rem"><a href="javascript:void(0)" @click="showServerDetails(server)">@{{server.s_name}}</a></td>
				@endcan	
				@cannot('create',App\Models\ServerModel::class)
					<td>@{{server.s_name}}</td>
				@endcannot	
				@can('create',App\Models\ServerModel::class)
					<td style="font-size: 0.8rem">
						<a href="javascript:void(0)" @click="showProviderDetails(server.provider)">@{{server.provider ? server.provider.name : ''}}</a>
						<p style="font-size: 0.8rem;">@{{server.provider ? server.provider.email : ''}}</p>
					</td>	
				@endcan	
				@cannot('create',App\Models\ServerModel::class)
					<td>
						<p>@{{server.provider ? server.provider.name : ''}}</p>
					</td>
				@endcannot
				<td><p class="f32"><span class="flag" :class="server.mainIpCountryCode"></span></p></td>
				<td>@{{server.main_ip}}</td>	
				<td>@{{server.main_domain}}</td>
				<td>
					<div  style="position: relative;">
						<div :id="'numberIps_'+server.id" style="position: absolute;top: 20px;background:white;width: 200px;height: 100px;right:50;display: none;box-shadow: 2px 2px 8px;border-radius: 5px;overflow-y: auto;padding: 5px">
							<ul>
								<li v-if="server.ips.length>0" v-for="ip in server.ips">@{{ip.ip}}</li>
							</ul>
						</div>
					</div>
					<a href="javascript:void(0)" @click="numberIpsDetail('numberIps_'+server.id)">@{{server.numberIps}}</a>
				</td>
				<td>@{{server.date_purchase}}</td>
				<td>
					<div  style="position: relative;">
						<div :id="'dates_'+server.id" style="position: absolute;top: 20px;background:white;width: 200px;height: 100px;right:50;display: none;box-shadow: 2px 2px 8px;border-radius: 5px;overflow-y: auto;padding: 5px">
							<ul>
								<li><span>Order :</span><span>@{{server.date_order}}</span></li>
								<li><span>Delivred :</span><span>@{{server.date_delivred}}</span></li>
								<li><span>Expiration :</span><span>@{{server.date_expiration}}</span></li>
								<li><span>Cancelation :</span><span>@{{server.date_cancelation}}</span></li>
							</ul>
						</div>
					</div>
					<a href="javascript:void(0)" @click="datesDetail('dates_'+server.id)"><i class="icofont-calendar"></i>
				</td>
				<td>
					<div  style="position: relative;">
						<div :id="'config_'+server.id" style="position: absolute;top: 20px;background:white;width: 200px;height: 100px;right:50;display: none;box-shadow: 2px 2px 8px;border-radius: 5px;overflow-y: auto;padding: 5px">
							<ul>
								<li><span>OS :</span><span>@{{server.os}}</span></li>
								<li><span>CPU :</span><span>@{{server.cpu}}</span></li>
								<li><span>RAM :</span><span>@{{server.ram}}</span></li>
								<li><span>HDD :</span><span>@{{server.hdd}}</span></li>
							</ul>
						</div>
					</div>
					<a href="javascript:void(0)" @click="configDetail('config_'+server.id)"><i class="icofont-server"></i>
				</td>
				@can('create',App\Models\ServerModel::class)
					<td>@{{server.price ? server.price : ''}} @{{server.currency ? currencyCode(server.currency) : ''}}</td>
				@endcan
				<td>
					<button 
					v-if="server.status" 
					class="btn" 
					:class="setColor(server.status.status)" 
					@click="showStatus(server)"
					>
						@{{ setStatus(server.status.status) }}
					</button>
				</td>
				@can('create',App\Models\ServerModel::class)
					<!--<td>@{{server.createdBy ? server.createdBy.username : ''}}</td>-->
					<!--<td>@{{server.updatedBy ? server.updatedBy.username : ''}}</td>-->
				@endcan
				@can('create',App\Models\ServerModel::class)
					<td>
						 <button @click="editServer(server)" type="button" class="btn btn-primary waves-effect waves-light"><i class="icofont icofont-ui-edit"></i></button>
					</td>
				@endcan
			</tr>

			<tr>
				@can('create',App\Models\ServerModel::class)
					<td colspan="13">
				@endcan
				@cannot('create',App\Models\ServerModel::class)
					<td colspan="7">
				@endcannot
				<ul class="pagination" style="float: left;">
					<li  class="page-item" @click="getServers(pagination.first)"><a class="page-link" >&laquo;</a></li>
				    <li :class="{'disabled':!pagination.prev}" class="page-item" @click="getServers(pagination.prev)"><a class="page-link" >Previous</a></li>
				    <li class="page-item"><a class="page-link">@{{pagination.currentPage}}/@{{pagination.lastPage}}</a></li>
				    <li :class="{'disabled':!pagination.next}" class="page-item" @click="getServers(pagination.next)"><a class="page-link" >Next</a></li>
				    <li  class="page-item" @click="getServers(pagination.last)"><a class="page-link" >&raquo;</a></li>
				</ul>
				<td>
					<select class="form-control top" v-model="filter.limit" style="width: 100px" @change="getServers()">
						<option v-for="limit in limits">@{{limit}}</option>
					</select>
				</td>		
			</tr>
		</tbody>
	</table>


 <div id="addServer" class="modal fade" tabindex="-1" role="dialog">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title">Add Server</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <form>
		        	<div class="row">
		        		<div class="col-md-3">
		        			<div class="form-group">
		        				<label>Group</label>
			        			<select class="form-control" v-model="newModel.group_id">
			        					<option v-for="group in groups" :value="group.id">@{{group.name}}</option>
			        			</select>
		        			</div>
		        			<div class="form-group">
		        				<label>Provider <i class="icofont-plus" @click="createProvider()"></i></label>
			        			<select style="width: 100%;" class="add_server_provider_select"></select>
		        			</div>
		        			<div class="form-group">
		        				<label>Name</label>
			        			<input type="text" class="form-control" v-model="newModel.s_name">
		        			</div>
		        			<div class="form-group">
		        				<label>Order Number</label>
			        			<input type="text" class="form-control" v-model="newModel.order_number">
		        			</div>
		        			<div class="form-group">
		        				<label>Main Ip <i class="icofont-plus" @click="createIp()"></i></label>
			        			<input disabled="" type="text" class="form-control" v-model="newModel.main_ip">
		        			</div>
		        		</div>
		        		<div class="col-md-3">
		        			<div class="form-group">
		        				<label>Main Domain <i class="icofont-plus" @click="createDomain()"></i></label>
		        				{{-- <select  class="form-control" v-model="newModel.domain_id">
										<option v-for="domain in domains" :value="domain.id">@{{domain.domain}}(@{{domain.group ? domain.group.name : ''}})</option>
		        				</select> --}}
		        				<select style="width: 100%;" class="add_server_domain_select"></select>
		        			</div>
		        			<div class="form-group">
		        				<label>SSH User Default</label>
		        				<input type="text" class="form-control" v-model="newModel.ssh_user_default">
		        			</div>
		        			<div class="form-group">
		        				<label>SSH Type</label>
		        				<div class="row">
		        					<div class="form-check form-check-inline"> 
									  
									  <label class="form-check-label" for="inlineRadio1"><input  class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="Normal" v-model="sshType"> Normal</label>
									</div>
									<div class="form-check form-check-inline">
									  
									  <label class="form-check-label" for="inlineRadio2"><input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="Key" v-model="sshType"> Key</label>
									</div>
		        				</div>
		        				@{{ getSshType }}
		        			</div>
		        			<div class="form-group" v-if="getSshType === 'Normal'">
		        				<label>SSH Password Default</label>
		        				<input type="text" class="form-control" v-model="newModel.ssh_pwd_default">
		        			</div>
		        			<div class="form-group" v-if="getSshType === 'Key'">
		        				<label>Key</label>
		        				<input type="text" class="form-control" v-model="newModel.ssh_key">
		        			</div>
		        			<div class="form-group">
		        				<label>Price</label>
		        				<input type="number" step="0.01" class="form-control" v-model="newModel.price">
		        			</div>
		        			<div class="form-group">
		        				<label>Currency</label>
		        				<select class="form-control" v-model="newModel.currency">
		        					<option v-for="currency in currencies">@{{currency}}</option>
		        				</select>
		        			</div>
		        		</div>
		        		<div class="col-md-3">
		        			<div class="form-group">
		        				<label>Date Order</label>
		        				<input type="date" class="form-control" v-model="newModel.date_order">
		        			</div>
		        			<div class="form-group">
		        				<label>Date Delivred</label>
		        				<input type="date" class="form-control" v-model="newModel.date_delivred">
		        			</div>
		        			<div class="form-group">
		        				<label>Date Purchase</label>
		        				<input type="date" class="form-control" v-model="newModel.date_purchase">
		        			</div>
		        			<div class="form-group">
		        				<label>Date Expiration</label>
		        				<input type="date" class="form-control" v-model="newModel.date_expiration">
		        			</div>
		        			<div class="form-group">
		        				<label>OS</label>
		        				<input type="text" class="form-control" v-model="newModel.os">
		        			</div>
		        		</div>
		        		<div class="col-md-3">
		        			<div class="form-group">
		        				<label>CPU</label>
		        				<input type="text" class="form-control" v-model="newModel.cpu">
		        			</div>
		        			<div class="form-group">
		        				<label>RAM</label>
		        				<input type="text" class="form-control" v-model="newModel.ram">
		        			</div>
		        			<div class="form-group">
		        				<label>HDD</label>
		        				<input type="text" class="form-control" v-model="newModel.hdd">
		        			</div>
		        			<div class="form-group">
		        				<label>Band Width</label>
		        				<input type="text" class="form-control" v-model="newModel.band_width">
		        			</div>
		        			<div class="form-group">
		        				<label>Description</label>
		        				<textarea class="form-control" v-model="newModel.description" rows="3"></textarea>
		        			</div>
		        			<div class="form-group">
		        				<label>Note</label>
		        				<textarea class="form-control" v-model="newModel.note" rows="3"></textarea>
		        			</div>
		        			<div class="form-group">
		        				<label>Type</label>
		        				<select class="form-control" v-model="newModel.type">
		        					<option>Production</option>
		        					<option>Developpement</option>
		        					<option>RDP</option>
		        					<option>Administration</option>
		        					<option>Other</option>
		        				</select>
		        			</div>
		        		</div>
		        	</div>
		        </form>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-primary" @click="storeServer()">Save changes</button>
		      </div>
		    </div>
		  </div>
		</div>
		

	<div id="editServer" class="modal fade" tabindex="-1" role="dialog">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title">Edit Server</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <form>
		        	<div class="row">
		        		<div class="col-md-3">
		        			<div class="form-group">
		        				<label>Group</label>
			        			<select class="form-control" v-model="selectedServer.group.id">
			        					<option v-for="group in groups" :value="group.id">@{{group.name}}</option>
			        			</select>
		        			</div>
		        			<div class="form-group">
		        				<label>Provider</label>
			        			<select style="width: 100%" class="edit_server_provider_select"></select>
		        			</div>
		        			<div class="form-group">
		        				<label>Name</label>
			        			<input type="text" class="form-control" v-model="selectedServer.s_name">
		        			</div>
		        			<div class="form-group">
		        				<label>Order Number</label>
			        			<input type="text" class="form-control" v-model="selectedServer.order_number">
		        			</div>
		        			<div class="form-group">
		        				<label>Main Ip</label>
			        			<input type="text" class="form-control" v-model="selectedServer.main_ip">
		        			</div>
		        		</div>
		        		<div class="col-md-3">
		        			<div class="form-group">
		        				<label>Main Domain</label>
		        				<!--<input type="text" class="form-control" v-model="selectedServer.main_domain">-->
		        				<select style="width: 100%" class="edit_server_domain_select"></select>
		        			</div>
		        			<div class="form-group">
		        				<label>SSH User Default</label>
		        				<input type="text" class="form-control" v-model="selectedServer.ssh_user_default">
		        			</div>
		        			{{-- <div class="form-group">
		        				<label>SSH Type</label>
		        				<div class="row">
		        					<div class="form-check form-check-inline"> 
									  
									  <label class="form-check-label" for="inlineRadio1"><input  class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="Normal" v-model="selectedServer.sshType"> Normal</label>
									</div>
									<div class="form-check form-check-inline">
									  
									  <label class="form-check-label" for="inlineRadio2"><input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="Key" v-model="selectedServer.sshType"> Key</label>
									</div>
		        				</div>
		        			</div> --}}
		        			<div class="form-group" v-if="selectedServer.ssh_pwd_default != null">
		        				<label>SSH Password Default</label>
		        				<input type="text" class="form-control" v-model="selectedServer.ssh_pwd_default">
		        			</div>
		        			<div class="form-group" v-if="selectedServer.ssh_key != null">
		        				<label>Key</label>
		        				<input type="text" class="form-control" v-model="selectedServer.ssh_key">
		        			</div>
		        			<div class="form-group">
		        				<label>Price</label>
		        				<input type="number" step="0.01" class="form-control" v-model="selectedServer.price">
		        			</div>
		        			<div class="form-group">
		        				<label>Currency</label>
		        				<select class="form-control" v-model="selectedServer.currency">
		        					<option v-for="currency in currencies">@{{currency}}</option>
		        				</select>
		        			</div>
		        		</div>
		        		<div class="col-md-3">
		        			<div class="form-group">
		        				<label>Date Order</label>
		        				<input type="date" class="form-control" v-model="selectedServer.date_order">
		        			</div>
		        			<div class="form-group">
		        				<label>Date Delivred</label>
		        				<input type="date" class="form-control" v-model="selectedServer.date_delivred">
		        			</div>
		        			<div class="form-group">
		        				<label>Date Purchase</label>
		        				<input type="date" class="form-control" v-model="selectedServer.date_purchase">
		        			</div>
		        			<div class="form-group">
		        				<label>Date Expiration</label>
		        				<input type="date" class="form-control" v-model="selectedServer.date_expiration">
		        			</div>
		        			<div class="form-group">
		        				<label>Date Cancelation</label>
		        				<input type="date" class="form-control" v-model="selectedServer.date_cancelation">
		        			</div>
		        			<div class="form-group">
		        				<label>OS</label>
		        				<input type="text" class="form-control" v-model="selectedServer.os">
		        			</div>
		        		</div>
		        		<div class="col-md-3">
		        			<div class="form-group">
		        				<label>CPU</label>
		        				<input type="text" class="form-control" v-model="selectedServer.cpu">
		        			</div>
		        			<div class="form-group">
		        				<label>RAM</label>
		        				<input type="text" class="form-control" v-model="selectedServer.ram">
		        			</div>
		        			<div class="form-group">
		        				<label>HDD</label>
		        				<input type="text" class="form-control" v-model="selectedServer.hdd">
		        			</div>
		        			<div class="form-group">
		        				<label>Band Width</label>
		        				<input type="text" class="form-control" v-model="selectedServer.band_width">
		        			</div>
		        			<div class="form-group">
		        				<label>Description</label>
		        				<textarea class="form-control" v-model="selectedServer.description" rows="3"></textarea>
		        			</div>
		        			<div class="form-group">
		        				<label>Note</label>
		        				<textarea class="form-control" v-model="selectedServer.note" rows="3"></textarea>
		        			</div>
		        			<div class="form-group">
		        				<label>Type</label>
		        				<select class="form-control" v-model="selectedServer.type">
		        					<option>Production</option>
		        					<option>Developpement</option>
		        					<option>RDP</option>
		        					<option>Administration</option>
		        					<option>Other</option>
		        				</select>
		        			</div>
		        		</div>
		        	</div>
		        </form>
		      </div>
		      <div class="modal-footer">
		      	<button type="button" class="btn btn-default" @click="duplicateServer()">Duplicate</button>
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		         <button type="button" class="btn btn-danger" @click="deleteServer()">Delete</button>
		        <button type="button" class="btn btn-primary" @click="updateServer()">Save changes</button>
		      </div>
		    </div>
		  </div>
		</div>


		<div id="showStatus" class="modal fade " tabindex="-1" role="dialog">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title">Status</h5>
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
		        		<tr v-for="status in listStatus">
		        			<td><button class="btn" :class="setColor(status.status)">@{{setStatus(status.status)}}</button></td>
		        			<td>@{{status.explication}}</td>
		        			<td>@{{status.created}}</td>
		        			<td>@{{ status.createdBy ? status.createdBy.username : ""}}</td>
		        		</tr>
		        	</tbody>
		        </table>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        @can('create',App\Models\ServerModel::class)
		       		<button type="button" class="btn btn-primary" @click="createStatus()">Create New Status</button>
		        @endcan
		      </div>
		    </div>
		  </div>
		</div>

		<div id="createStatus" class="modal fade " tabindex="-1" role="dialog">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title">Status</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		       	<div class="row">
		       		<div class="col-md-6">
		       			<div class="form-group">
		       				<label>Status</label>
		       				<select class="form-control" v-model="newStatus.data.status">
		       					<option v-for="s in status" :value="s">@{{setStatus(s)}}</option>
		       				</select>
		       			</div>
		       			<div class="form-group">
		       				<label>Explication</label>
		       				<textarea class="form-control" rows="3" v-model="newStatus.data.explication"></textarea>
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
		        <h6 style="text-transform: uppercase;color: #1B8BF9">Gnerale Informations:</h6>
		        <div class="row top">
		        	<div class="col-md-3">
		        		<p><span class="bold">Name :</span> <span>@{{selectedServer.s_name}}</span></p>
		        		<p><span class="bold">Main Ip :</span> <span>@{{selectedServer.main_ip}}</span></p>
		        		<p><span class="bold">Main Domain :</span> <span>@{{selectedServer.main_domain}}</span></p>
		        		<p><span class="bold">Status :</span> <span>@{{selectedServer.status ? selectedServer.status.status : ''}}</span></p>
		        		<p><span class="bold">Number Ips :</span> <span class="badge badge-primary">@{{selectedServer.numberIps}}</span></p>
		        		<p><span class="bold">Group :</span> <span>@{{selectedServer.group ? selectedServer.group.name : ''}}</span></p>
		        		<p><span class="bold">Provider :</span> <span>@{{selectedServer.provider ? selectedServer.provider.name : ''}}</span></p>
		        	</div>
		        	<div class="col-md-3">
		        	
		        		<p><span class="bold">Order N° :</span> <span>@{{selectedServer.order_number}}</span></p>
		        		<p><span class="bold">Type :</span> <span>@{{selectedServer.type}}</span></p>
		        		<p><span class="bold">Price :</span> <span>@{{selectedServer.price}} @{{selectedServer.currency}}</span></p>
		        		<p><span class="bold">SSH User Default :</span> <span>@{{selectedServer.ssh_user_default}}</span></p>
		        		<p><span class="bold">SSH Password Default :</span> <span>@{{selectedServer.ssh_pwd_default}}</span></p>
		        		<p><span class="bold">SSh Key :</span> <span>@{{selectedServer.ssh_key}}</span></p>
		        		<p><span class="bold">Price :</span> <span>@{{selectedServer.price}} @{{selectedServer.currency}}</span></p>
		        	</div>
		        	<div class="col-md-3">
		        		<p><span class="bold" style="color: orange;">RAM :</span> <span>@{{selectedServer.ram}}</span></p>
		        		<p><span class="bold" style="color: orange;">CPU :</span> <span>@{{selectedServer.cpu}}</span></p>
		        		<p><span class="bold" style="color: orange;">OS :</span> <span>@{{selectedServer.os}}</span></p>
		        		<p><span class="bold" style="color: orange;">HDD :</span> <span>@{{selectedServer.hdd}}</span></p>
		        		<p><span class="bold">Band Width :</span> <span>@{{selectedServer.band_width}}</span></p>
		        		<p><span class="bold">Note :</span> <span>@{{selectedServer.note}}</span></p>
		        		<p><span class="bold">Description :</span> <span>@{{selectedServer.description}}</span></p>
		        	</div>
		        	<div class="col-md-3">
		        		<p><span class="bold">Created :</span> <span>@{{selectedServer.created}}</span></p>
		        		<p><span class="bold">Purchased :</span> <span>@{{selectedServer.date_purchase}}</span></p>
		        		<p><span class="bold" style="color:green;">Ordered :</span> <span>@{{selectedServer.date_order}}</span></p>
		        		<p><span class="bold" style="color:green;">Delivred :</span> <span>@{{selectedServer.date_delivred}}</span></p>
		        		<p><span class="bold" style="color:green;">Expired :</span> <span>@{{selectedServer.date_expiration}}</span></p>
		        		<p><span class="bold" style="color:green;">Canceled :</span> <span>@{{selectedServer.date_cancelation}}</span></p>
	
		        	</div>
		        </div>
		         <div class="container row top">
		         	<div class="col-md-8">
		         		<h6 style="color: #1B8BF9">Ips:</h6>
				         <div v-if="selectedServer.ips.length>0" class="row">
				         	<table class="table table-bordered table-sm">
				         		<thead>
				         			<th>Ip</th>
				         			<th>Provider</th>
				         			<th>Range</th>
				         			<th>NetMask</th>
				         		</thead>
				         		<tbody>
				         			<tr v-for="ip in selectedServer.ips">
				         				<td>@{{ip.ip}}</td>
				         				<td>@{{ip.provider}}</td>
				         				<td>@{{ip.ip_range}}</td>
				         				<td>@{{ip.netmask}}</td>
				         			</tr>
				         		</tbody>
				         	</table>
				         </div>
				         <div v-if="selectedServer.ips.length==0" class="container">No Data!</div>
		         	</div>

		         	<div class="col-md-4">
		         		<h6 style="color: #1B8BF9">RDNS</h6>
		         		<p> <i  class="icofont-copy" data-clipboard-target="#c_subDomain"></i> </p>
			         	<div id="c_subDomain">
			         		<p style="line-height: 1.2rem" v-for="subdomain in subDomains"> 
			         		<span> @{{subdomain.subdomain}} </span> 
			         			,
			         		<span> @{{subdomain.ip.ip}} </span>
			         		</p>
			         	</div>
		         	</div>

		         </div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div id="providerDetails" class="modal fade" tabindex="-1" role="dialog">
		  <div class="modal-dialog modal-xl" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title">Provider Details ( @{{selectedProvider.name}} )</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <div class="row">
		        	<div class="col-md-6">
		        		<p><span class="bold" style="color: blue">Name :</span> <span class="bold">@{{selectedProvider.name}}</span></p>
		        		<p><span class="bold" style="color: blue">Url :</span> <span class="bold">@{{selectedProvider.url_site}}</span></p>
		        		<p><span class="bold" style="color: blue">Email :</span> <span class="bold">@{{selectedProvider.inscr_email}}</span></p>
		        		<p><span class="bold" style="color: blue">First Name :</span> <span class="bold">@{{selectedProvider.inscrfname}}</span></p>
		        		<p><span class="bold" style="color: blue">Last Name :</span> <span class="bold">@{{selectedProvider.inscrlname}}</span></p>
		        		<p><span class="bold" style="color: blue">Customer Id :</span> <span class="bold">@{{selectedProvider.id_customer}}</span></p>
		        	</div>
		        	<div class="col-md-6">
		        		<p><span class="bold" style="color: blue">CPanel :</span> <span class="bold">@{{selectedProvider.cpanel}}</span></p>
		        		<p><span class="bold" style="color: blue">Login :</span> <span class="bold">@{{selectedProvider.login}}</span></p>
		        		<p><span class="bold" style="color: blue">Password :</span> <span class="bold">@{{selectedProvider.pwd}}</span></p>
		        		<p><span class="bold" style="color: blue">Created :</span> <span class="bold">@{{selectedProvider.created}}</span></p>
		        		<p><span class="bold" style="color: blue">Status :</span> <span class="bold">@{{selectedProvider.status}}</span></p>
		        		<p><span class="bold" style="color: blue">Type :</span> <span class="bold">@{{selectedProvider.type}}</span></p>
		        	</div>
		        	
		        </div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div id="createProvider" class="modal fade" tabindex="-1" role="dialog">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title">Create Provider</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
			       <div class="row">
			       	<div class="col-md-6">
			       		<div class="form-group">
			       			<label>Name</label>
			       			<input type="text" class="form-control" v-model="newProvider.name">
			       		</div>
			       		<div class="form-group">
			       			<label>Country</label>
			       			<select class="form-control" v-model="newProvider.country">
			       				<option v-for="country in countries">@{{country.name}}</option>
			       			</select>
			       		</div>
			       		<div class="form-group">
			       			<label>Url</label>
			       			<input type="text" class="form-control" v-model="newProvider.url_site">
			       		</div>
			       		<div class="form-group">
			       			<label>CPanel</label>
			       			<input type="text" class="form-control" v-model="newProvider.cpanel">
			       		</div>
			       		<div class="form-group">
			       			<label>Login</label>
			       			<input type="text" class="form-control" v-model="newProvider.login">
			       		</div>
			       		<div class="form-group">
			       			<label>Password</label>
			       			<input type="text" class="form-control" v-model="newProvider.pwd">
			       		</div>
			       	</div>
			       	<div class="col-md-6">
			       		<div class="form-group">
			       			<label>Id Customer</label>
			       			<input type="text" class="form-control" v-model="newProvider.id_customer">
			       		</div>
			       		<div class="form-group">
			       			<label>Email</label>
			       			<input type="text" class="form-control" v-model="newProvider.inscr_email">
			       		</div>
			       		<div class="form-group">
			       			<label>First Name</label>
			       			<input type="text" class="form-control" v-model="newProvider.inscrfname">
			       		</div>
			       		<div class="form-group">
			       			<label>Last Name</label>
			       			<input type="text" class="form-control" v-model="newProvider.inscrlname">
			       		</div>
			       		<div class="form-group">
			       			<label>Type</label>
			       			<select class="form-control" v-model="newProvider.type">
			       				<option v-for="providerType in providerTypes">@{{providerType}}</option>
			       			</select>
			       		</div>
			       		<div class="form-group">
			       			<label>Note</label>
			       			<textarea class="form-control" v-model="newProvider.note" cols="2"></textarea>
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

		<div id="createDomain" class="modal fade" tabindex="-1" role="dialog">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title">Create Domain</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
			       <div class="row">
			       	<div class="col-md-6">
			       		<div class="form-group">
			       			<label>Registrars</label>
			       			<select class="form-control" v-model="newDomain.registrar_id">
			       				<option v-for="registrar in registrars" :value="registrar.id">@{{registrar.name}}(@{{registrar.eml_contact}})</option>
			       			</select>
			       		</div>
			       		<div class="form-group">
			       			<label>Domain</label>
			       			<input type="text" class="form-control" v-model="newDomain.domain">
			       		</div>
			       		<div class="form-group">
			       			<label>Group</label>
			       			<select class="form-control" v-model="newDomain.group_id">
			       				<option v-for="group in groups" :value="group.id">@{{group.name}}</option>
			       			</select>
			       		</div>
			       		<div class="form-group">
			       			<label>Date Purchase</label>
			       			<input type="date" class="form-control" v-model="newDomain.entered">
			       		</div>
			       	</div>
			       	<div class="col-md-6">
			       		<div class="form-group">
			       			<label>Date Expiration</label>
			       			<input type="date" class="form-control" v-model="newDomain.datex">
			       		</div>
			       		<div class="form-group">
			       			<label>Description</label>
			       		    <textarea rows="2" v-model="newDomain.description" class="form-control"></textarea>
			       		</div>
			       		<div class="form-group">
			       			<label>Price</label>
			       			<input type="number" step="0.01" class="form-control" v-model="newDomain.price">
			       		</div>
			       		<div class="form-group">
			       			<label>Currency</label>
				       		<select class="form-control" v-model="newDomain.currency">
				       			<option v-for="currency in currencies">@{{currency}}</option>
				       		</select>
			       		</div>
			       	</div>
			       </div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-primary" @click="storeDomain()">Save changes</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div id="createIps" class="modal fade" tabindex="-1" role="dialog">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title">Create Ips</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		       <div class="row">
		       	<div class="col-md-6">
		       		 <div class="form-group">
			       	 	<label>Ips</label>
			       	 	<textarea class="form-control" rows="5" v-model="newModel.listIps.ips"></textarea>
			       	 </div>
			       	 <div class="form-group">
			       	 	<label>Range</label>
			       	 	<input type="text" class="form-control" v-model="newModel.listIps.ip_range">
			       	 </div>
			       	 <div class="form-group">
			       	 	<label>Net Mask</label>
			       	 	<input type="text" class="form-control" v-model="newModel.listIps.netmask">
			       	 </div>
			       	  <div class="form-group">
			       	 	<label>Provider</label>
			       	 	<select style="width: 100%;" class="create_ip_provider_select"></select>
			       	 </div>
		       	</div>
		       	<div class="col-md-6">
		       		 <div class="form-group">
			       	 	<label>Price</label>
			       	 	<input type="number" step="0.01" class="form-control" v-model="newModel.listIps.price">
			       	 </div>
			       	  <div class="form-group">
			       	 	<label>Currency</label>
			       	 	<select class="form-control" v-model="newModel.listIps.currency">
			       	 		<option v-for="currency in currencies">@{{currency}}</option>
			       	 	</select>
			       	 </div>
			       	  <div class="form-group">
			       	 	<label>Type</label>
			       	 	<select class="form-control" v-model="newModel.listIps.type">
			       	 		<option v-for="ipType in ipTypes">@{{ipType}}</option>
			       	 	</select>
			       	 </div>
		       	</div>
		       </div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-primary" @click="closeCreateIps()">Save</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div id="toggleStatusModal" class="modal fade" tabindex="-1" role="dialog">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title">Update Servers</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		       <button type="button" class="btn btn-info waves-effect waves-light r" @click="toggleStatus('ret')">Returned</button>
			   <button type="button" class="btn btn-info waves-effect waves-light r" @click="toReturnModal()">To Return</button>
			   <button type="button" class="btn btn-danger waves-effect waves-light r" @click="toggleStatus('canc')">Canceled by Provider</button>
			    <button type="button" class="btn btn-success waves-effect waves-light r" @click="toggleStatus('prod')">In-Prod</button>
			   <div class="form-group top">
			   	<label>Change Group</label>
			   <select class="form-control" @change="toggleGroup($event)">
			   	<option></option>
			   	<option v-for="group in groups" :value="group.id">@{{group.name}}</option>
			   </select>
			   </div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div id="toReturn" class="modal fade" tabindex="-1" role="dialog">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title">Toggle Status</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		      	<table class="table table-sm">
		      		<thead>
		      			<th>Server Name</th>
		      			<th>Date Expiration</th>
		      		</thead>
		      		<tbody>
		      			<tr v-for="item in serversToReturn">
		      				<td>
		      					@{{item.s_name}}
		      				</td>
		      				<td>
		      					 <div class="form-group">
								   	<input type="date" class="form-control" v-model="item.date_expiration">
								 </div>
		      				</td>
		      			</tr>
		      		</tbody>
		      	</table>
		      
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-primary" @click="toReturn">Save changes</button>
		      </div>
		    </div>
		  </div>
		</div>

			<div id="renewalServers" class="modal fade" tabindex="-1" role="dialog">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title">Renewal Servers</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			      	<table class="table table-sm">
			      		<thead>
			      			<th>Server Name</th>
			      			<th>Price</th>
			      			<th>Currency</th>
			      		</thead>
			      		<tbody>
			      			<tr v-for="item in serversRenewal">
			      				<td>
			      					@{{item.s_name}}
			      				</td>
			      				<td>
									<input type="number" class="form-control" v-model="item.price">
			      				</td>
			      				<td>
			      					 <div class="form-group">
									   	<select class="form-control" v-model="item.currency">
									   		<option v-for="currency in currencies">@{{currency}}</option>
									   	</select>
									 </div>
			      				</td>
			      			</tr>
			      		</tbody>
			      	</table>
			      
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			        <button type="button" class="btn btn-primary" @click="renewalServers()">Renewal</button>
			      </div>
			    </div>
			  </div>
			</div>


			<div id="exportModal" class="modal fade" tabindex="-1" role="dialog">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title">Export Servers</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			       	  <div class="row">
			       	  	<div class="col-md-4">
			       	  		<div class="form-group">
				       	  		<select class="form-control" v-model="exportGroup">
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
			        <button id="exportServers" type="button" class="btn btn-success" @click="exportServers()">Export</button>
			      </div>
			    </div>
			  </div>
			</div>
		
	


	</div>	
</div>

@endsection

@section('javascript')
	<script>
		$('#dashboard').removeClass('active');
		$('#serverManagement').addClass('active');
		new ClipboardJS('.icofont-copy');
	</script>
	<script src="{{asset('js/countries.js')}}"></script>
	<script src="{{asset('js/currencies.js')}}"></script>
	<script src="{{asset('js/limit.js')}}"></script>
	<script src="{{asset('js/notification.js')}}"></script>
	<script src="{{asset('js/server.js')}}"></script>
	<script>
		$('.providers_select').select2(
			{
				allowClear: true,
				placeholder : { id : '' , 'placeholder' : '' },
				ajax : 
				{
					url : '{{url('api/getProviders')}}',
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
								return { id : item.id , text : item.name };
							}),
							pagination : { more : true }
						}
					},


				}
			}).on('change',function()
			{
				app.filter.provider_id = this.value;
				app.getServers();
			});


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
				app.filter.domain_id = this.value;
				app.getServers();
			});


			$('.add_server_provider_select').select2({
				allowClear:true,
				placeholder:{id:'','placeholder':''},
				dropdownParent : $("#addServer"),
				ajax:{
					url:'{{url('api/getProviders')}}',
					data:function(params){
						return {search:params.term,page:params.page};
					},
					dataType:'json',
					processResults:function(data){
						data.page=data.page||1;
						return {
							results:data.data.map(function(item){
								return {id:item.id,text:item.name};
							}),
							pagination:{more:true}
						}
					}
				}
			}).on('change',function(){
				app.newModel.provider_id = this.value;
				app.toggleProvider(this.value);
			});


			$('.add_server_domain_select').select2({
				allowClear:true,
				dropdownParent: $('#addServer'),
				placeholder:{id:'','placeholder':''},
				dropdownParent : $("#addServer"),
				ajax:{
					url:'{{url('api/getNewDomains')}}',
					data:function(params){
						return {search:params.term,page:params.page};
					},
					dataType:'json',
					processResults:function(data){
						data.page=data.page||1;
						return {
							results:data.data.map(function(item){
								return {id:item.domain,text:item.domain+'('+item.group.name+')'};
							}),
							pagination:{more:true}
						}
					}
				}
			}).on('change',function(){
				app.newModel.main_domain = this.value;
			});


			$('.create_ip_provider_select').select2({
				allowClear:true,
				placeholder:{id:'','placeholder':''},
				dropdownParent : $("#createIps"),
				ajax:{
					url:'{{url('api/getProviders')}}',
					data:function(params){
						return {search:params.term,page:params.page};
					},
					dataType:'json',
					processResults:function(data){
						data.page=data.page||1;
						return {
							results:data.data.map(function(item){
								return {id:item.name,text:item.name};
							}),
							pagination:{more:true}
						}
					}
				}
			}).on('change',function(){
				app.newModel.listIps.provider = this.value;
			});


			$('.edit_server_provider_select').select2({
				allowClear:true,
				placeholder:{id:'','placeholder':''},
				dropdownParent : $("#editServer"),
				ajax:{
					url:'{{url('api/getProviders')}}',
					data:function(params){
						return {search:params.term,page:params.page};
					},
					dataType:'json',
					processResults:function(data){
						data.page=data.page||1;
						return {
							results:data.data.map(function(item){
								return {id:item.id,text:item.name};
							}),
							pagination:{more:true}
						}
					}
				}
			}).on('change',function(){
				app.selectedServer.provider.id = this.value;
			});


			$('.edit_server_domain_select').select2({
				allowClear:true,
				placeholder:{id:'','placeholder':''},
				dropdownParent : $("#editServer"),
				ajax:{
					url:'{{url('api/getDomains')}}',
					data:function(params){
						return {search:params.term,page:params.page};
					},
					dataType:'json',
					processResults:function(data){
						data.page=data.page||1;
						return {
							results:data.data.map(function(item){
								return {id:item.domain,text:item.domain};
							}),
							pagination:{more:true}
						}
					}
				}
			}).on('change',function(){
				app.selectedServer.main_domain = this.value;
			});

			$('.createdByFilter').select2({
				allowClear:true,
				placeholder:{id:'','placeholder':''},
				ajax:{
					url:'{{url('api/getUsers')}}',
					data:function(params){
						return {search:params.term,page:params.page};
					},
					dataType:'json',
					processResults:function(data){
						data.page=data.page||1;
						return {
							results:data.data.map(function(item){
								return {id:item.username,text:item.username};
							}),
							pagination:{more:true}
						}
					}
				}
			}).on('change',function(){
				app.filter.created_by = this.value;
				app.getServers();
			});
	</script>
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/flags32.css')}}"/>
	<style>
		.flex-container{display: flex;justify-content:flex-end;}
		.btn-black{background-color: black;color:white;}
		.modal-xl{max-width:90% !important;width: 90% !important}
		p{font-size:0.9rem !important;line-height: 2rem}
		.bold{font-weight: bold;}
		table p {font-size: 0.8rem !important}
		table td,table th{font-size: 0.8rem}
		table a {font-size: 0.8rem}
		table td , table tr{padding: 5px}
		.btn {font-size: 0.8rem;}
		select.form-control:not([size]):not([multiple])
		{
			height: 28px;
			padding:0rem 0rem !important;
		}
		input[type=text]
		{
			height: 28px !important;
		}
		.select2-container--default .select2-selection--single
		{
			border: 1px solid #ccc;
		}

	</style>
@endsection