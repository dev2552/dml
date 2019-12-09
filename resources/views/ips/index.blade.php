@extends('master2')

@section('content')

<input type="hidden" value="{{url('api/getUnreadNotificationsCount')}}" id="notificationsUrl">

@include('loader')

<div style="display: none;" id="app">

<div class="row">
    <div class="col-md-6">
        <h4>List Ips</h4>
    </div>
    <div class="col-md-6">
        <div class="flex-container">
             @can('create',App\Models\IpModel::class)
                <button type="button" class="btn btn-primary waves-effect waves-light r" @click="createIp()"><i class="icofont-plus"></i></button>
             @endcan 
            <button type="button" class="btn btn-primary waves-effect waves-light r" @click="refreshIps()"><i class="icofont-refresh"></i></button>
            @can('create',App\Models\IpModel::class)
                <button  class="btn btn-success waves-effect waves-light r" @click="exportModal()"><i class="icofont-file-excel"></i></button>
            @endcan   
        </div>     
    </div>
</div>


<hr>

<table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="bg-primary">
                                        @can('create',App\Models\IpModel::class)
                                        <th>Group</th>
                                        @endcan
                                        <th>Provider</th>
                                        <th></th>
                                        <th>Ip</th>
                                        <th>Netmask</th>
                                        <th>Server</th>
                                        <th>Gateway</th>
                                        @can('create',App\Models\IpModel::class)
                                            <th >
                                                Price 
                                                <i @click="sort('price')" class="icofont-sort"></i>
                                                  <select v-model="filter.currency" @change="filterIps()">
                                                        <option></option>
                                                        <option v-for="currency in currencies">@{{currency}}</option>
                                                  </select>
                                            </th>
                                        @endcan
                                        <th>Status</th>
                                        <th>Active</th>
                                        @can('create',App\Models\IpModel::class)
                                            <!--<th>Created By</th>-->
                                            <!--<th>Updated By</th>-->
                                            <th>Edit</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @can('create',App\Models\IpModel::class)
                                        <td>
                                           
                                        </td>
                                        @endcan
                                         <td>
                                             <input type="text" class="form-control" v-model="filter.provider" @keyup="filterIps()">
                                        </td>
                                        <td></td>
                                        <td>
                                            <input type="text" class="form-control" v-model="filter.ip" @keyup="filterIps()">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" v-model="filter.netmask" @keyup="filterIps()">
                                        </td>
                                         <td>
                                            <select class="form-control servers_select" v-model="filter.server_id" style="width: 250px;" @change="filterIps()">
                                                 <option></option>
                                                    <option v-for="server in servers" :value="server.id">@{{server.s_name}}</option>
                                            </select>
                                        </td>
                                         <td>
                                            <input type="text" class="form-control" v-model="filter.gateway" @keyup="filterIps()">
                                        </td>
                                         @can('create',App\Models\IpModel::class)
                                             <td>
                                                <input type="text" class="form-control" v-model="filter.price" @keyup="filterIps()">
                                            </td>
                                        @endcan
                                         <td>
                                            <select style="width: 120px;" class="form-control" v-model="filter.ip_status" @change="filterIps()">
                                                <option></option>
                                                <option v-for="s in status" :value="s">@{{setStatus(s)}}</option>
                                            </select>
                                        </td>
                                         <td>
                                            <select class="form-control" v-model="filter.is_active" style="width: 100px;" @change="filterIps()">
                                                 <option></option>
                                               <option value="1">Active</option>
                                               <option value="0">Inactive</option>
                                            </select>
                                        </td>
                                        @can('create',App\Models\IpModel::class)
                                            <!--<td><select style="width: 100px" class="createdByFilter"></select></td>-->
                                           <!-- <td></td>-->
                                            <td></td>
                                        @endcan
                                    </tr>
                                    <tr v-if="ips.length>0" v-for="ip in ips">
                                        @can('create',App\Models\IpModel::class)
                                        <td>@{{ip.group ? ip.group.name : ''}}</td>
                                        @endcan
                                        <td>@{{ip.provider}}</td>
                                        <td>  <p class="f32"><span class="flag" :class="ip.ipCountryCode"></span></p></td>
                                        <td>@{{ip.ip}} </td>
                                        <td>@{{ip.netmask}}</td>
                                        <td>@{{ip.server ? ip.server.s_name : ''}}</td>
                                        <td>@{{ip.gateway}}</td>
                                        @can('create',App\Models\IpModel::class)
                                            <td>@{{ip.price}} @{{currencyCode(ip.currency)}}</td>
                                        @endcan
                                        <td><button class="btn" :class="color(ip.ip_status)">@{{setStatus(ip.ip_status)}}</button></td>
                                        <td><i v-if="ip.is_active" class="icofont-check"></i></td>
                                        @can('create',App\Models\IpModel::class)
                                            <!--<td>@{{ip.createdBy ? ip.createdBy.username : ''}}</td>-->
                                            <!--<td>@{{ip.updatedBy ? ip.updatedBy.username : ''}}</td>-->
                                            <td><button class="btn btn-primary" @click="editIp(ip)"><i class="icofont icofont-ui-edit"></i></button></td>
                                        @endcan
                                    </tr>

                                     <tr id="spin" style="text-align: center;display: none;">
                                        @can('create',App\Models\IpModel::class)
                                        <td colspan="11">
                                        @endcan
                                        @cannot('create',App\Models\IpModel::class)
                                         <td colspan="10">
                                        @endcannot
                                           <img src="{{asset('images/spin.svg')}}" width="400">
                                        </td>
                                    </tr>


                                    <tr v-if="ips.length==0" style="text-align: center;">
                                        @can('create',App\Models\IpModel::class)
                                        <td colspan="11">
                                        @endcan
                                        @cannot('create',App\Models\IpModel::class)
                                         <td colspan="10">
                                        @endcannot
                                            No Data!
                                        </td>
                                    </tr>


                                    <tr>
                                        @can('create',App\Models\IpModel::class)
                                        <td colspan="10">
                                        @endcan
                                        @cannot('create',App\Models\IpModel::class)
                                         <td colspan="7">
                                        @endcannot
                                            <ul class="pagination" style="float: left;">
                                                 <li  class="page-item" @click="filterIps(pagination.first)"><a class="page-link" >&laquo;</a></li>
                                                <li :class="{'disabled':!pagination.prev}" class="page-item" @click="filterIps(pagination.prev)"><a class="page-link" >Previous</a></li>
                                                 <li class="page-item"><a class="page-link">@{{pagination.currentPage}}/@{{pagination.lastPage}}</a></li>
                                                <li :class="{'disabled':!pagination.next}" class="page-item" @click="filterIps(pagination.next)"><a class="page-link" >Next</a></li>
                                                    <li  class="page-item" @click="filterIps(pagination.last)"><a class="page-link" >&raquo;</a></li>
                                            </ul>
                                        </td>
                                        <td colspan="1">
                                            <select class="form-control top" v-model="filter.limit" style="width: 100px" @change="filterIps()">
                                                <option v-for="limit in limits">@{{limit}}</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>




   

    <div id="createIp" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Ip</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Server</label>
                            <select style="width: 100%" class="create_ip_server_select"> </select>
                        </div>
                        <div class="form-group">
                            <label>Ip</label>
                           <textarea rows="5" v-model="newIp.ips" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Range</label>
                            <input type="text" class="form-control" v-model="newIp.ip_range">
                        </div>
                         <div class="form-group">
                            <label>Net Mask</label>
                            <input type="text" class="form-control" v-model="newIp.netmask">
                        </div>
                    </div>
                    <div class="col-md-6">
                         <div class="form-group">
                            <label>Provider</label>
                            <select style="width: 100%" class="create_ip_provider_select"></select>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" class="form-control" v-model="newIp.price">
                        </div>
                        <div class="form-group">
                            <label>Currency</label>
                            <select class="form-control" v-model="newIp.currency">
                                <option v-for="currency in currencies">@{{currency}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" v-model="newIp.type">
                                <option v-for="type in types">@{{type}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><input type="checkbox" class="form-check-input" v-model="newIp.is_active">  Enable</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="storeIp()">Save changes</button>
            </div>
        </div>
    </div> 
</div>  


    <div id="editIp" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Ip</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Server</label>
                            <select style="width: 100%" class="edit_ip_server_select"></select>
                        </div>
                        <div class="form-group">
                            <label>Ip</label>
                            <input type="text" class="form-control" v-model="selectedIp.ip">
                        </div>
                        <div class="form-group">
                            <label>Range</label>
                            <input type="text" class="form-control" v-model="selectedIp.ip_range">
                        </div>
                         <div class="form-group">
                            <label>Net Mask</label>
                            <input type="text" class="form-control" v-model="selectedIp.netmask">
                        </div>
                         <div class="form-group">
                            <label>Geteway</label>
                            <input type="text" class="form-control" v-model="selectedIp.gateway">
                        </div>
                    </div>
                    <div class="col-md-6">
                         <div class="form-group">
                            <label>Provider</label>
                            <input type="text" class="form-control" v-model="selectedIp.provider">
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" class="form-control" v-model="selectedIp.price">
                        </div>
                        <div class="form-group">
                            <label>Currency</label>
                            <select class="form-control" v-model="selectedIp.currency">
                                <option v-for="currency in currencies">@{{currency}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" v-model="selectedIp.ip_status">
                                <option v-for="s in status">@{{s}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" v-model="selectedIp.type">
                                <option v-for="type in types">@{{type}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><input type="checkbox" class="form-check-input" v-model="selectedIp.is_active">  Enable</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger"  @click="deleteIp()">Delete</button>
                <button type="button" class="btn btn-primary" @click="updateIp()">Save changes</button>
            </div>
        </div>
    </div> 
</div>  


<div id="exportModal" class="modal fade" tabindex="-1" role="dialog">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Export Ips</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                      <div class="row">
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
                    <button id="exportIps" type="button" class="btn btn-success" @click="exportIps()">Export</button>
                  </div>
                </div>
              </div>
            </div>


    </div>
</div>




@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/flags32.css')}}"/>
	<style>
        .modal-xl{max-width:90% !important;width: 90% !important}
		.flex-container{display: flex;justify-content:flex-end;}
        select.form-control:not([size]):not([multiple]){height: 28px;padding:0rem 0rem !important;}
        input[type=text]{height: 28px !important;}
        table td , table th {font-size: 0.8rem;}
	</style>
@endsection


@section('javascript')
    <script>
        $('#dashboard').removeClass('active');
        $('#serverManagement').addClass('active');
    </script>
    <script src="{{asset('js/currencies.js')}}"></script>
    <script src="{{asset('js/limit.js')}}"></script>
    <script src="{{asset('js/notification.js')}}"></script>
	<script src="{{asset('js/ip.js')}}"></script>
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
                app.filterIps();
            });
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
                app.filterIps();
            });


            $('.edit_ip_provider_select').select2(
            {   
                dropdownParent : $("#editIp"),
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
            }).on('change',function(){
                app.selectedIp.provider.id = this.value;
            })

            $('.edit_ip_server_select').select2(
            {   
                dropdownParent : $("#editIp"),
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
            }).on('change',function(){
                app.selectedIp.server.id = this.value;
            })


            $('.create_ip_server_select').select2(
            {   
                dropdownParent : $("#createIp"),
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
            }).on('change',function(){
                app.newIp.server_id = this.value;
            })

            $('.create_ip_provider_select').select2(
            {   
                dropdownParent : $("#createIp"),
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
                                return { id : item.name , text : item.name };
                            }),

                            pagination : { more : true }
                        }
                    },


                }
            }).on('change',function(){
                app.newIp.provider = this.value;
            })



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
                app.filterIps();
            });
    </script>
@endsection