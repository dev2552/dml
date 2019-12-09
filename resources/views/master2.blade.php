@include('header')
<body class="sidebar-mini fixed">
 <div class="wrapper">
     <div class="loader-bg">
    <div class="loader-bar">
    </div>
  </div>
<!-- Navbar-->
        <header class="main-header-top hidden-print">
      <!--   <a href="{{route('home')}}" class="logo"><img class="img-fluid able-logo" src="assets/images/logo.png" alt="Theme-logo"></a> -->
      <p class="logo">Volazi IT Management</p>
        <nav class="navbar navbar-static-top">
          <!-- Sidebar toggle button--><a href="#!" data-toggle="offcanvas" class="sidebar-toggle"></a>
          <!-- Navbar Right Menu-->
          <div class="navbar-custom-menu f-right">
             
            <ul class="top-nav">
              <!--Notification Menu-->

             
         
             
              <!-- window screen -->
              <li class="pc-rheader-submenu">
                        <a href="#!" class="drop icon-circle" onclick="javascript:toggleFullScreen()">
                            <i class="icon-size-fullscreen"></i>
                        </a>

                    </li>
              <!-- User Menu-->
              <li class="dropdown">
                <a href="#!" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle drop icon-circle drop-image">
                              <span><img class="img-circle " src="{{Avatar::create(Auth::user()->username)->toBase64()}}" style="width:40px;" alt="User Image"></span>
                              <span>{{Auth::user()->firstName}} {{Auth::user()->lastName}} <i class=" icofont icofont-simple-down"></i></span>

              </a>
                <ul class="dropdown-menu settings-menu">
               
                  <li><a href="{{route('profile')}}"><i class="icon-user"></i> Profile</a></li>
                
                  <li class="p-0">
                                  <div class="dropdown-divider m-0"></div>
                                </li>
                                <li><a href="{{route('lock')}}"><i class="icon-lock"></i> Lock Screen</a></li>
                  <li><a href="{{route('logout')}}"><i class="icon-logout"></i> Logout</a></li>

                </ul>
              </li>
            </ul>

         
          </div>
        </nav>
      </header>
      <!-- Side-Nav-->
      <aside class="main-sidebar hidden-print " >
<section class="sidebar" id="sidebar-scroll">
            
          <div class="user-panel">
            <div class="f-left image"><img src="{{Avatar::create(Auth::user()->username)->toBase64()}}" alt="User Image" class="img-circle"></div>
            <div class="f-left info">
              <p> {{Auth::user()->username}}</p>
              <p class="designation" style="text-transform: uppercase;"> {{Auth::user()->roule_id}} <i class="icofont icofont-caret-down m-l-5"></i></p>
            </div>
          </div>
          <!-- sidebar profile Menu-->
          <ul class="nav sidebar-menu extra-profile-list">
            <li>
   <a class="waves-effect waves-dark" href="{{route('profile')}}">
                     <i class="icon-user"></i>
                     <span class="menu-text">View Profile</span>
                     <span class="selected"></span>
                 </a>

            </li>
            
            <li>
                <a class="waves-effect waves-dark" href="{{route('logout')}}">
                    <i class="icon-logout"></i>
                    <span class="menu-text">Logout</span>
                    <span class="selected"></span>
                </a>
            </li>
        </ul>
        <!-- Sidebar Menu-->
            <ul class="sidebar-menu">
                <li class="nav-level">Navigation</li>

                <li id="dashboard" class="active treeview">
                    <a class="waves-effect waves-dark" href="{{route('home')}}">
                        <i class="icon-speedometer"></i><span> Dashboard</span>
                    </a>                
                </li>

               {{--  <li id="dashboard" class=" treeview">
                    <a class="waves-effect waves-dark" href="{{route('notifications.index')}}">
                        <i class="icofont-notification"></i><span> Notifications <span class="badge badge-primary">{{Auth::user()->unreadNotifications ->count()}}</span></span>
                    </a>                
                </li> --}}

                @include('notification')

               @if(Auth::user()->isRoot())
                <li id="configuration" class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="icofont-gear-alt"></i></i><span>Configuration</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('registrars.index')}}"><i class="icon-arrow-right"></i>Registrars</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('domains.index')}}"><i class="icon-arrow-right"></i> Domains</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('subDomains.index')}}"><i class="icon-arrow-right"></i>Sub Domains</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('configureDNS')}}"><i class="icon-arrow-right"></i>Configure DNS</a></li>
                    </ul>
                </li>
                @endif

                 <li id="serverManagement" class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="icofont-server"></i><span>Server Management</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('servers.index')}}"><i class="icon-arrow-right"></i>Servers</a></li>
                        @can('index',App\Models\ProviderModel::class)
                        <li><a class="waves-effect waves-dark" href="{{route('providers.index')}}"><i class="icon-arrow-right"></i> Providers</a></li>
                        @endcan
                        <li><a class="waves-effect waves-dark" href="{{route('ips.index')}}"><i class="icon-arrow-right"></i>Ips</a></li>
                    </ul>
                </li>

                <li id="requests" class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="icofont-info"></i><span>Requests</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('requests.index')}}"><i class="icon-arrow-right"></i>Requests</a></li>
                       {{--  @can('index',App\Models\RequestServerModel::class)
                        <li><a class="waves-effect waves-dark" href="{{route('linkRS')}}"><i class="icon-arrow-right"></i> Link R/S</a></li>
                        @endcan --}}
                    </ul>
                </li>

                @if(Auth::user()->isRoot())
                 <li id="payments" class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="icofont-credit-card"></i><span>Payments</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('payments.index')}}"><i class="icon-arrow-right"></i>Payments</a></li>
                    </ul>
                </li>
                @endif


                  @if(Auth::user()->isRoot())
                  <li id="permissions" class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="icofont-safety"></i><span>Permissions</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('users.index')}}"><i class="icon-arrow-right"></i>Users</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('groups.index')}}"><i class="icon-arrow-right"></i> Groups</a></li>
                    </ul>
                </li>
                @endif

            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <!-- Container-fluid starts -->
       <div class="container-fluid">
        <!-- Row Starts -->
        <div class="row">
            <div class="col-sm-12 p-0">
                <div class="main-header">
                    @yield('breadcrumb')
                </div>
            </div>
        </div>
        <!-- Row end -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="md-card-block">
                           
                           @yield('content')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- Container-fluid ends -->
     </div>
</div>




@include('footer');
