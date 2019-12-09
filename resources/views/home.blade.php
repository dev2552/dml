@section('serversToReturn')
        <div class="card">
            <div class="card-header">
                <h5 class="card-header-text" style="text-transform: lowercase;">Servers To Return</h5>
            </div>
            <div class="card-block">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr class="bg-primary">
                                @can('index',App\User::class)
                                <th class="p-t-0">Group</th>
                                @endcan
                                <th class="p-t-0">Name</th>
                                <th class="p-t-0">Provider</th>
                                <th class="p-t-0">Expired</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="serversToReturn.length>0 && !serversToReturnLoading" v-for="serverToReturn in serversToReturn">
                                @can('index',App\User::class)
                                    <td>@{{serverToReturn.group ? serverToReturn.group.name : ''}}</td>
                                @endcan
                                <td>
                                    @can('index',App\User::class)
                                        <a @click="showServerDetails(serverToReturn)" href="javascript:void(0)">@{{serverToReturn.s_name}}</a>
                                    @endcan
                                    @cannot('index',App\User::class)
                                        <a href="javascript:void(0)">@{{serverToReturn.s_name}}</a>
                                    @endcannot
                                </td>
                                <td>
                                    @can('index',App\User::class)
                                        <a @click="showProviderDetails(serverToReturn.provider)" href="javascript:void(0)">@{{serverToReturn.provider ? serverToReturn.provider.name : ''}}</a>
                                        <p><span style="font-size: 0.8rem">@{{serverToReturn.provider ? serverToReturn.provider.inscr_email : ''}}</p>
                                    @endcan
                                    @cannot('index',App\User::class)
                                        <a  href="javascript:void(0)">@{{serverToReturn.provider ? serverToReturn.provider.name : ''}}</a>
                                    @endcannot                                   
                                    </td>
                                <td>@{{serverToReturn.date_expiration}}</td>
                            </tr>
                             <tr v-if="serversToReturnLoading" style="text-align: center;">
                                        <td colspan="3">
                                          <img src="{{asset('images/spin.svg')}}" width="200">
                                        </td>
                                    </tr>
                                                        <tr v-if="serversToReturn.length==0 && !serversToReturnLoading" style="text-align: center;">
                                <td colspan="4">
                                    No Data!
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                        <ul class="pagination" style="float: left;">
                                            <li  class="page-item" @click="getServersToReturn(serversToReturnPagination.first)"><a class="page-link" >&laquo;</a></li>
                                             <li :class="{'disabled':!serversToReturnPagination.prev}" class="page-item" @click="getServersToReturn(serversToReturnPagination.prev)"><a class="page-link" >Previous</a></li>
                                            <li class="page-item"><a class="page-link">@{{serversToReturnPagination.currentPage}}/@{{serversToReturnPagination.lastPage}}</a></li>
                                            <li :class="{'disabled':!serversToReturnPagination.next}" class="page-item" @click="getServersToReturn(serversToReturnPagination.next)"><a class="page-link" >Next</a></li>
                                              <li  class="page-item" @click="getServersToReturn(serversToReturnPagination.last)"><a class="page-link" >&raquo;</a></li>
                                        </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection

@section('serversInProductionWithIssue')
    @can('index',App\User::class)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-header-text" style="text-transform: lowercase;">Servers In Production With Issue</h5>
                    </div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr class="bg-primary">
                                        <th class="p-t-0">Group</th>
                                        <th class="p-t-0">Name</th>
                                        <th class="p-t-0">Provider</th>
                                        <th class="p-t-0">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="serversInProductionWithIssue.length>0 && !serversInProductionWithIssueLoading" v-for="serverInProductionWithIssue in serversInProductionWithIssue">
                                        <td>@{{serverInProductionWithIssue.group ? serverInProductionWithIssue.group.s_name : ''}}</td>
                                        <td><a @click="showServerDetails(serverInProductionWithIssue)" href="javascript:void(0)">@{{serverInProductionWithIssue.s_name}}</a></td>
                                        <td><a @click="showProviderDetails(serverInProductionWithIssue.provider)" href="javascript:void(0)">@{{serverInProductionWithIssue.provider ? serverInProductionWithIssue.provider.name : ''}}</a><p><span style="font-size: 0.8rem">@{{serverInProductionWithIssue.provider ? serverInProductionWithIssue.provider.inscr_email : ''}}</span></p></td>
                                        <td>@{{serverInProductionWithIssue.created}}</td>
                                        
                                    </tr>
                                     <tr v-if="serversInProductionWithIssueLoading" style="text-align: center;">
                                        <td colspan="3">
                                          <img src="{{asset('images/spin.svg')}}" width="200">
                                        </td>
                                    </tr>
                                                                        <tr v-if="serversInProductionWithIssue.length==0 && !serversInProductionWithIssueLoading" style="text-align: center;">
                                        <td colspan="4">
                                            No Data!
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                             <ul class="pagination" style="float: left;">
                                                 <li  class="page-item" @click="getServersInProductionWithIssue(serversInProductionWithIssuePagination.first)"><a class="page-link" >&laquo;</a></li>
                                                <li :class="{'disabled':!serversInProductionWithIssuePagination.prev}" class="page-item" @click="getServersInProductionWithIssue(serversInProductionWithIssuePagination.prev)"><a class="page-link" >Previous</a></li>
                                                 <li class="page-item"><a class="page-link">@{{serversInProductionWithIssuePagination.currentPage}}/@{{serversInProductionWithIssuePagination.lastPage}}</a></li>
                                                <li :class="{'disabled':!serversInProductionWithIssuePagination.next}" class="page-item" @click="getServersInProductionWithIssue(serversInProductionWithIssuePagination.next)"><a class="page-link">Next</a></li>
                                                 <li  class="page-item" @click="getServersInProductionWithIssue(serversInProductionWithIssuePagination.last)"><a class="page-link" >&raquo;</a></li>
                                             </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            @endcan
@endsection

@section('serversInstalling')
@can('index',App\User::class)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-header-text" style="text-transform: lowercase;">Servers Installing</h5>
                    </div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr class="bg-primary">
                                        <th class="p-t-0">Group</th>
                                        <th class="p-t-0">Name</th>
                                        <th class="p-t-0">Provider</th>
                                        <th class="p-t-0">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="serversInstalling.length>0 && !serversInstallingLoading" v-for="serverInstalling in serversInstalling">
                                        <td>@{{serverInstalling.group ? serverInstalling.group.name : ''}}</td>
                                        <td><a @click="showServerDetails(serverInstalling)" href="javascript:void(0)">@{{serverInstalling.s_name}}</a></td>
                                        <td><a @click="showProviderDetails(serverInstalling.provider)" href="javascript:void(0)">@{{serverInstalling.provider ? serverInstalling.provider.name : ''}}</a><p><span style="font-size: 0.8rem">@{{serverInstalling.provider ? serverInstalling.provider.inscr_email : ''}}</span></p></td>
                                        <td>@{{serverInstalling.created}}</td>
                                        
                                    </tr>
                                     <tr v-if="serversInstallingLoading" style="text-align: center;">
                                        <td colspan="3">
                                          <img src="{{asset('images/spin.svg')}}" width="200">
                                        </td>
                                    </tr>
                                                                       <tr v-if="serversInstalling==0 && !serversInstallingLoading" style="text-align: center;">
                                        <td colspan="4">
                                            No Data!
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                             <ul class="pagination" style="float: left;">
                                                 <li  class="page-item" @click="getServersInstalling(serversInstallingPagination.first)"><a class="page-link" >&laquo;</a></li>
                                                <li :class="{'disabled':!serversInstallingPagination.prev}" class="page-item" @click="getServersInstalling(serversInstallingPagination.prev)"><a class="page-link" >Previous</a></li>
                                                 <li class="page-item"><a class="page-link">@{{serversInstallingPagination.currentPage}}/@{{serversInstallingPagination.lastPage}}</a></li>
                                                <li :class="{'disabled':!serversInstallingPagination.next}" class="page-item" @click="getServersInstalling(serversInstallingPagination.next)"><a class="page-link" >Next</a></li>
                                                 <li  class="page-item" @click="getServersInstalling(serversInstallingPagination.last)"><a class="page-link" >&raquo;</a></li>
                                             </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            @endcan
@endsection

@section('serversExpiration')
        <div class="col-md-6" class="h">

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-header-text" style="text-transform: lowercase;">Servers Expiration</h5>
                    </div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr class="bg-primary">
                                        @can('index',App\User::class)
                                        <th class="p-t-0">Group</th>
                                        @endcan
                                        <th class="p-t-0">Name</th>
                                        <th class="p-t-0">Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="serversExpiration.length>0 && !serversExpirationLoading" v-for="serverExpiration in serversExpiration">
                                        @can('index',App\User::class)
                                        <td>@{{serverExpiration.group ? serverExpiration.group.name : ''}}</td>
                                        @endcan
                                        <td>
                                            @can('index',App\User::class)
                                            <a @click="showServerDetails(serverExpiration)" href="javascript:void(0)">@{{serverExpiration.s_name}}</a>
                                            @endcan
                                            @cannot('index',App\User::class)
                                            <a href="javascript:void(0)">@{{serverExpiration.s_name}}</a>
                                            @endcannot
                                        </td>
                                        <td><span :class="expirationColor(serverExpiration.days)" class="badge">@{{serverExpiration.days}}</span></td>
                                        
                                    </tr>
                                    <tr v-if="serversExpirationLoading" style="text-align: center;">
                                        <td colspan="3">
                                          <img src="{{asset('images/spin.svg')}}" width="200">
                                        </td>
                                    </tr>
                                    <tr v-if="serversExpiration.length==0 &&!serversExpirationLoading" style="text-align: center;">
                                        <td colspan="3">
                                            No Data!
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                             <ul class="pagination" style="float: left;">
                                                <li  class="page-item" @click="getServersExpiration(serversExpirationPagination.first)"><a class="page-link" >&laquo;</a></li>
                                                <li :class="{'disabled':!serversExpirationPagination.prev}" class="page-item" @click="getServersExpiration(serversExpirationPagination.prev)"><a class="page-link" >Previous</a></li>
                                                 <li class="page-item"><a class="page-link">@{{serversExpirationPagination.currentPage}}/@{{serversExpirationPagination.lastPage}}</a></li>
                                                <li :class="{'disabled':!serversExpirationPagination.next}" class="page-item" @click="getServersExpiration(serversExpirationPagination.next)"><a class="page-link" >Next</a></li>
                                                 <li class="page-item" @click="getServersExpiration(serversExpirationPagination.last)"><a class="page-link" >&raquo;</a></li>
                                             </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
@endsection

@section('serversPayment')
     <div class="col-md-6" class="h">

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-header-text" style="text-transform: lowercase;">Payments</h5>
                    </div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr class="bg-primary">
                                        <th class="p-t-0">Group</th>
                                        <th class="p-t-0">Month to Date</th>
                                        <th class="p-t-0">Last Month</th>
                                        <th class="p-t-0">Total</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-if="paymentElements.length>0 && !paymentsLoading" v-for="paymentElement in paymentElements">
                                        <td><a href="javascript:void(0)" @click="paymentDetail(paymentElement['groupName'])">@{{paymentElement['groupName']}}</a></td>
                                        <td>
                                            <ul>
                                                <li>@{{new Intl.NumberFormat().format(paymentElement.res.reduce((a,b)=>(a+b['sumMonthToDate']),0))}} $</li>
                                            </ul>
                                        </td>
                                         <td>
                                            <ul>
                                                <li>@{{new Intl.NumberFormat().format(paymentElement.res.reduce((a,b)=>(a+b['sumLastMonth']),0))}} $</li>
                                            </ul>
                                        </td>
                                         <td>
                                            <ul>
                                                <li >@{{new Intl.NumberFormat().format(paymentElement.res.reduce((a,b)=>(a+b['sumMonthToDate']+b['sumLastMonth']),0))}} $</li>
                                            </ul>
                                        </td>
                                        
                                    </tr>
                                    <tr v-if="paymentElements.length>0 && !paymentsLoading">
                                        <td>Total</td>
                                        <td>
                                            <ul>
                                                <li>@{{new Intl.NumberFormat().format(paymentElements.reduce((a,b)=>(a+b.res.reduce((x,y)=>(x+y['sumMonthToDate']),0)),0))}} $</li>
                                            </ul>
                                        </td>
                                        <td>
                                             <ul>
                                                <li>@{{new Intl.NumberFormat().format(paymentElements.reduce((a,b)=>(a+b.res.reduce((x,y)=>(x+y['sumLastMonth']),0)),0))}} $</li>
                                            </ul>
                                        </td>
                                        <td>
                                             <ul>
                                                <li>@{{new Intl.NumberFormat().format(paymentElements.reduce((a,b)=>(a+b.res.reduce((x,y)=>(x+y['sumLastMonth']+y['sumMonthToDate']),0)),0))}} $</li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr v-if="paymentsLoading" style="text-align: center;">
                                        <td colspan="4">
                                           <img src="{{asset('images/spin.svg')}}" width="200">
                                        </td>
                                    </tr>
                                    <tr  v-if="paymentElements.length==0 && !paymentsLoading" style="text-align: center;">
                                        <td colspan="4">
                                            No Data!
                                        </td>
                                    </tr>
                                  
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
@endsection

@section('requests')
    

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-header-text" style="text-transform: lowercase;">Requests</h5>
                    </div>
                    <div class="card-block">
                        <select @click="getRequests()" v-model="requestFilter">
                            <option></option>
                            <option>Inprocess</option>
                            <option>New</option>
                        </select>
                        <div class="table-responsive top">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr class="bg-primary">
                                        @can('index',App\User::class)
                                        <th class="p-t-0">Group</th>
                                        @endcan
                                        <th class="p-t-0">Subject</th>
                                        <th class="p-t-0">Status</th>
                                        <th class="p-t-0">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="requests.length>0 && !requestsLoading" v-for="request in requests">
                                        @can('index',App\User::class)
                                        <td>@{{request.group ? request.group.name : ''}}</td>
                                        @endcan
                                        <td><a href="javascript:void(0)" @click="showRequestDetails(request)">@{{request.subject}}</td>
                                        <td><button v-if="request.status" class="btn" :class="color(request.status.status)">@{{request.status ? request.status.status : ''}}</button></td>
                                        <td>@{{request.created}}</td>
                                    </tr>
                                    <tr v-if="requestsLoading" style="text-align: center;">
                                        <td colspan="4">
                                           <img src="{{asset('images/spin.svg')}}" width="200">
                                        </td>
                                    </tr>
                                    <tr v-if="requests.length==0 && !requestsLoading" style="text-align: center;">
                                        <td colspan="4">
                                            No Data!
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                             <ul class="pagination" style="float: left;">
                                                  <li  class="page-item" @click="getRequests(requestsPagination.first)"><a class="page-link" >&laquo;</a></li>
                                                <li :class="{'disabled':!requestsPagination.prev}" class="page-item" @click="getRequests(requestsPagination.prev)"><a class="page-link">Previous</a></li>
                                             <li class="page-item"><a class="page-link" >@{{requestsPagination.currentPage}}/@{{requestsPagination.lastPage}}</a></li>
                                                <li :class="{'disabled':!requestsPagination.next}" class="page-item" @click="getRequests(requestsPagination.next)"><a class="page-link" >Next</a></li>
                                                 <li  class="page-item" @click="getRequests(requestsPagination.last)"><a class="page-link" >&raquo;</a></li>
                                             </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            
@endsection

@section('servers')
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-header-text" style="text-transform: lowercase;">Servers</h5>
                    </div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered  table-striped">
                                <thead>
                                    <tr class="bg-primary">
                                        <th class="p-t-0">Status</th>
                                        @can('create',App\User::class)
                                        <th class="p-t-0">Count</th>
                                        @endcan
                                        <th class="p-t-0" v-for="groupName in servers['groups']">@{{groupName['name']}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="i in servers['status'].length">
                                        <td>@{{setServerStatus(servers['status'][i-1])}}</td>
                                         @can('create',App\User::class)
                                        <td>@{{servers['counts'][i-1]}}</td>
                                        @endcan
                                        <td v-for="j in servers['groups'].length">@{{servers['groups'][j-1]['counts'][i-1]}}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;" > Total </td>
                                         @can('create',App\User::class)
                                        <td>@{{servers['counts'].reduce((a,b)=>(a+b),0)}}</td>
                                        @endcan
                                         <td v-for="j in servers['groups'].length">@{{servers['groups'][j-1]['counts'].reduce((a,b)=>(a+b),0)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
               </div>
@endsection

@extends('master2')

@section('content')

<input type="hidden" value="{{url('api/getUnreadNotificationsCount')}}" id="notificationsUrl">

<h4>Dashboard</h4>
<hr>

@include('loader')

<div style="display: none;" id="app">

            <div class="row">
                @yield('serversPayment')
                @yield('serversExpiration')
            </div>

            <hr>

            @if(Auth::user()->isRoot())

                <div class="row">
                    <div class="col-md-4" class="h">
                        @yield('serversToReturn')
                    </div>  
                    <div class="col-md-4" class="h">
                         @yield('serversInProductionWithIssue')
                    </div>
                    <div class="col-md-4" class="h">
                        @yield('serversInstalling')
                    </div>
                </div>

                <hr>

                <div class="row">
                     <div class="col-md-6" class="h">
                        @yield('requests')
                     </div>
                     <div class="col-md-6">
                        @yield('servers')
                     </div>
                </div>

            @else

                <div class="row">
                    <div class="col-md-12" class="h">
                        @yield('requests')
                    </div>
                </div>

                <div class="row">
                     <div class="col-md-6" class="h">
                        @yield('serversToReturn')
                     </div>
                     <div class="col-md-6" class="h">
                        @yield('servers')
                     </div>
                </div>

            @endif

          @can('index',App\User::class)
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
                <h3 style="text-transform: uppercase;color: #1B8BF9">Gnerale Informations:</h3>
                <div class="row top">
                    <div class="col-md-3">
                        <p><span class="bold">Name :</span> <span>@{{selectedServer.s_name}}</span></p>
                        <p><span class="bold">Main Ip :</span> <span>@{{selectedServer.main_ip}}</span></p>
                        <p><span class="bold">Main Domain :</span> <span>@{{selectedServer.main_domain }}</span></p>
                        <p><span class="bold">Status :</span> <span>@{{selectedServer.status ? selectedServer.status.status : ''}}</span></p>
                        <p><span class="bold">Number Ips :</span> <span>@{{selectedServer.numberIps}}</span></p>
                        <p><span class="bold">Group :</span> <span>@{{selectedServer.group ? selectedServer.group.name : ''}}</span></p>
                    </div>
                    <div class="col-md-3">
                        <p><span class="bold">Provider :</span> <span>@{{selectedServer.provider ? selectedServer.provider.name : ''}}</span></p>
                        <p><span class="bold">Order N° :</span> <span>@{{selectedServer.order_number}}</span></p>
                        <p><span class="bold">Type :</span> <span>@{{selectedServer.type}}</span></p>
                        <p><span class="bold">Price :</span> <span>@{{selectedServer.price}} @{{selectedServer.currency}}</span></p>
                        <p><span class="bold">SSH User Default :</span> <span>@{{selectedServer.ssh_user_default}}</span></p>
                        <p><span class="bold">SSH Password Default :</span> <span>@{{selectedServer.ssh_pwd_default}}</span></p>
                        <p><span class="bold">Key :</span> <span>@{{selectedServer.ssh_key}}</span></p>
                    </div>
                    <div class="col-md-3">
                        <p><span class="bold" style="color: orange;">RAM :</span> <span>@{{selectedServer.ram}}</span></p>
                        <p><span class="bold" style="color: orange;">CPU :</span> <span>@{{selectedServer.cpu}}</span></p>
                        <p><span class="bold" style="color: orange;">OS :</span> <span>@{{selectedServer.os}}</span></p>
                        <p><span class="bold" style="color: orange;">Band Width :</span> <span>@{{selectedServer.band_width}}</span></p>
                        <p><span class="bold">Price :</span> <span>@{{selectedServer.price}} @{{selectedServer.currency}}</span></p>
                        <p><span class="bold">Note :</span> <span>@{{selectedServer.note}}</span></p>
                        <p><span class="bold">Description :</span> <span>@{{selectedServer.description}}</span></p>
                    </div>
                    <div class="col-md-3">
                        <p><span class="bold" style="color:green;">Created :</span> <span>@{{selectedServer.created}}</span></p>
                        <p><span class="bold" style="color:green;">Ordered :</span> <span>@{{selectedServer.date_order}}</span></p>
                        <p><span class="bold" style="color:green;">Purchased :</span> <span>@{{selectedServer.date_purchase}}</span></p>
                        <p><span class="bold" style="color:green;">Delivred :</span> <span>@{{selectedServer.date_delivred}}</span></p>
                        <p><span class="bold" style="color:green;">Expired :</span> <span>@{{selectedServer.date_expiration}}</span></p>
                        <p><span class="bold" style="color:green;">Canceled :</span> <span>@{{selectedServer.date_cancelation}}</span></p>
    
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        @endcan

            @can('index',App\User::class)
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
                        <p><span class="bold">Name :</span> <span>@{{selectedProvider.name}}</span></p>
                        <p><span class="bold">Url :</span> <span>@{{selectedProvider.url_site}}</span></p>
                        <p><span class="bold">Email :</span> <span>@{{selectedProvider.inscr_email}}</span></p>
                        <p><span class="bold">First Name :</span> <span>@{{selectedProvider.inscrfname}}</span></p>
                        <p><span class="bold">Last Name :</span> <span>@{{selectedProvider.inscrlname}}</span></p>
                        <p><span class="bold">Customer Id :</span> <span>@{{selectedProvider.id_customer}}</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><span class="bold">CPanel :</span> <span>@{{selectedProvider.cpanel}}</span></p>
                        <p><span class="bold">Login :</span> <span>@{{selectedProvider.login}}</span></p>
                        <p><span class="bold">Password :</span> <span>@{{selectedProvider.pwd}}</span></p>
                        <p><span class="bold">Created :</span> <span>@{{selectedProvider.created}}</span></p>
                        <p><span class="bold">Status :</span> <span>@{{selectedProvider.status}}</span></p>
                        <p><span class="bold">Type :</span> <span>@{{selectedProvider.type}}</span></p>
                    </div>
                    
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        @endcan

        <div id="requestDetails" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">@{{selectedRequest.subject}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped">
           <tr>
            <td style="font-weight: bold;">Status:</td>
            <td><button class="btn" :class="color(selectedRequest._status)">@{{selectedRequest._status}}</button></td>
           </tr>
            <tr>
            <td style="font-weight: bold;">Type:</td>
            <td>@{{selectedRequest.type}}</td>
           </tr>
            <tr>
            <td style="font-weight: bold;">Created:</td>
            <td>@{{selectedRequest.created_at}}</td>
           </tr>
             <tr>
            <td style="font-weight: bold;">Request:</td>
            <td>
                <textarea class="form-control" disabled="" rows="4">@{{selectedRequest.request}}</textarea>
            </td>
           </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div id="paymentDetail" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">@{{payment.groupName}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <table class="table table-sm table-bordered">
           <thead>
                <tr class="bg-primary">
                    <th>Type</th>
                    <th>Month to Date</th>
                    <th>Last Month</th>
                </tr>
           </thead>
           <tbody>
               <tr v-for="el in payment.res">
                    <td>@{{el.type}}</td>
                    <td>@{{new Intl.NumberFormat().format(el.sumMonthToDate)}} $</td>
                    <td>@{{new Intl.NumberFormat().format(el.sumLastMonth)}} $</td>
               </tr>
           </tbody>
       </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

    
</div>

@endsection

@section('javascript')
    <script src="{{asset('js/currencies.js')}}"></script>
    <script src="{{asset('js/notification.js')}}"></script>
	<script src="{{asset('js/home.js')}}"></script>
@endsection

@section('css')
	<style>
		.card-header{background-color: #1B8BF9;color:white;}
        .modal-xl{max-width:90% !important;width: 90% !important}
        p{font-size:1rem !important;line-height: 2rem}
        .bold{font-weight: bold;}
	</style>
@endsection
