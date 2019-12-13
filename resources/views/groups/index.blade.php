@extends('master2')

@section('content')

<input type="hidden" value="{{url('api/getUnreadNotificationsCount')}}" id="notificationsUrl">

@include('loader')

<div style="display: none;" id="app">

<div class="row">
	<div class="col-md-6">
		<h4>List Groups</h4>
	</div>
	<div class="col-md-6">
		<div class="flex-container">
			<button type="button" class="btn btn-primary waves-effect waves-light r" @click="refreshGroups()"><i class="icofont-refresh"></i></button>
		</div>	
	</div>
</div>

<hr>

<div class="row">
	<div class="col-md-6">
		<div class="input-group" id="dropdown2">
			 <input type="text" class="form-control" v-model="newModel.name">
			 <div class="input-group-btn">
			 	<button type="button" class="btn btn-primary shadow-none addon-btn waves-effect waves-light dropdown-toggle addon-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action </button>
			 	<div class="dropdown-menu dropdown-menu-right">
			 		<a @click="addGroup()" class="dropdown-item" href="#">Add</a>
			 		<a @click="filterGroups()" class="dropdown-item" href="#">Filter</a>
			 	</div>
			 </div>
		</div> 
	</div>
</div>

<table class="table top table-bordered table-sm">
		 					<thead>
		 						<tr class="bg-primary">
		 							<th>Name</th>
		 							<th>Created</th>
{{-- 		 							<th>Created By</th>
		 							<th>Updated By</th> --}}
		 							<th>Edit</th>
		 						</tr>
		 					</thead>
		 					<tbody>
		 						<tr v-if="groups.length>0" v-for="group in groups">
		 							<td>@{{group.name}}</td>
		 							<td>@{{group.created}}</td>
		 						{{-- 	<td>@{{group.createdBy ? group.createdBy.username : ''}}</td> --}}
		 							{{-- <td>@{{group.updatedBy ? group.updatedBy.username : ''}}</td> --}}
		 							<td>
		 								<div class="input-group">
		 									<button type="submit" class="btn btn-primary shadow-none addon-btn waves-effect waves-light" @click="editGroup(group)">Edit</button>
		 								</div>	
		 							</td>
		 						</tr>
		 						<tr id="spin" style="text-align: center;display: none;">
		 							<td colspan="4">
		 								<img src="{{asset('images/spin.svg')}}" width="400">
		 							</td>
		 						</tr>
		 						<tr v-if="groups.length==0" style="text-align: center;">
		 							<td colspan="4">
		 								No Data!
		 							</td>
		 						</tr>
		 						<tr>
		 							<td colspan="4">
		 								 <ul class="pagination">
										  	<li  class="page-item" @click="filterGroups(pagination.first)"><a class="page-link" >&laquo;</a></li>
										    <li :class="{'disabled':!pagination.prev}" class="page-item" @click="filterGroups(pagination.prev)"><a class="page-link">Previous</a></li>
										    <li class="page-item"><a class="page-link">@{{pagination.currentPage}}/@{{pagination.lastPage}}</a></li>
										    <li :class="{'disabled':!pagination.next}" class="page-item" @click="filterGroups(pagination.next)"><a class="page-link">Next</a></li>
											<li  class="page-item" @click="filterGroups(pagination.last)"><a class="page-link" >&raquo;</a></li>
										  </ul>
		 							</td>
		 							<td>
		 							</td>
		 						</tr>
		 					</tbody>
		 				</table>


<div id="editGroup" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control" v-model="selectedGroup.name">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="deleteGroup()">Delete</button>
        <button type="button" class="btn btn-primary" @click="updateGroup()">Save changes</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('css')
	<style>
		.flex-container{display: flex;justify-content:flex-end;}
		table td {font-size: 0.8rem;}
	</style>
@endsection

@section('javascript')
	<script>
		$('#dashboard').removeClass('active');
		$('#permissions').addClass('active');
	</script>
	<script src="{{asset('js/notification.js')}}"></script>
	<script src="{{asset('js/group.js')}}"></script>
@endsection



                                                  






                                                   
                                                   
           

                                                          
                                                            
                                                   