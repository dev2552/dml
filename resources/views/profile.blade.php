@extends('master2')

@section('content')

@section('breadcrumb')
	<ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
         <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="icofont icofont-home"></i></a></li>
         <li class="breadcrumb-item"><a href="{{route('profile')}}">Profile</a></li>
    </ol>	
@endsection

<h4>Profile</h4>
<hr>


<div class="container">
	<div class="row">
		<div class="col-md-3 col-xs-12">
			<div class="social-profile">
                 <img class="img-fluid" src="assets/images/social/profile.jpg" alt="">
            </div>
		</div>
		<div class="col-md-9">
			@if ($errors->any())
			    <div class="alert alert-danger">
			        <ul>
			            @foreach ($errors->all() as $error)
			                <li>{{ $error }}</li>
			            @endforeach
			        </ul>
			    </div>
			@endif
			@if(session()->has('notUserPassword'))
				 <div class="alert alert-danger">
			        <ul>
			             <li>{{ session()->get('notUserPassword') }}</li>
			        </ul>
			    </div>
			@endif
			@if(session()->has('sucess'))
				 <div class="alert alert-success">
			        <ul>
			             <li>{{ session()->get('sucess') }}</li>
			        </ul>
			    </div>
			@endif
			<div class="card">
                                <div class="card-header"><h5 class="card-header-text">About Me</h5>
                                    <button onclick="$('#editUser').modal();" id="edit-btn" type="button" class="btn btn-primary waves-effect waves-light f-right">
                                        <i class="icofont icofont-edit"></i>
                                    </button>
                                </div>
                                <div class="card-block">
                                    <div class="view-info">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="general-info">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-xl-6">
                                                            <table class="table m-0">
                                                                <tbody>
                                                                <tr>
                                                                    <th scope="row">Username</th>
                                                                    <td>{{Auth::user()->username}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Role</th>
                                                                    <td>{{Auth::user()->roule_id}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">First Name</th>
                                                                    <td>{{Auth::user()->f_name}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Last Name</th>
                                                                    <td>{{Auth::user()->l_name}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Group</th>
                                                                    <td>{{Auth::user()->group->name}}</td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- end of table col-lg-6 -->

                                                        <div class="col-lg-12 col-xl-6">
                                                            <table class="table">
                                                                <tbody>
                                                                <tr>
                                                                    <th scope="row">Email</th>
                                                                    <td><a href="javascript:void(0)">{{Auth::user()->email}}</a></td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Active</th>
                                                                    <td>{{Auth::user()->active ? 'Yes' : 'No'}}</td>
                                                                </tr>
                                                               
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- end of table col-lg-6 -->
                                                    </div>
                                                    <!-- end of row -->
                                                </div>
                                                <!-- end of general info -->
                                            </div>
                                            <!-- end of col-lg-12 -->
                                        </div>
                                        <!-- end of row -->
                                    </div>
                                </div>
                                <!-- end of card-block -->
                            </div>



                            <div id="editUser" class="modal fade" tabindex="-1" role="dialog">
							  <div class="modal-dialog" role="document">
							    <div class="modal-content">
							      <div class="modal-header">
							        <h5 class="modal-title">Edit Your Information</h5>
							        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							          <span aria-hidden="true">&times;</span>
							        </button>
							      </div>
							      <div class="modal-body">
							        <form id="f" action="{{route('users.update',['user'=>Auth::user()->username])}}" method="post">
							        	@csrf
							        	@method('put')
										<div class="form-group">
											<label>Current Password</label>
											<input required="" type="password" name="currentPassword" class="form-control">
										</div>
										<div class="form-group">
											<label>New Password</label>
											<input required="" type="password" name="newPassword" class="form-control">
										</div>
										<div class="form-group">
											<label>Confirm New Password</label>
											<input required="" type="password" name="newPassword_confirmation" class="form-control">
										</div>
							        </form>
							      </div>
							      <div class="modal-footer">
							        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							        <button onclick="$('#f').submit();"  type="button" class="btn btn-primary">Save changes</button>
							      </div>
							    </div>
							  </div>
							</div>
		</div>
	</div>
</div>


@endsection