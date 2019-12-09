
@include('header')
<body>
    <section class="login common-img-bg">
    <!-- Container-fluid starts -->
    <div class="container-fluid">
        <div class="row bg-white">
            <div class="login-card card-block">
                <form class="md-float-material" action="{{route('unlock')}}" method="post">
                    @csrf
                    <div class="text-center">
                         <h1 style="color: white;background-color:#70C2F4;padding: 20px;border-radius: 5px ">Volazi IT Management</h1>
                    </div>
                    <h3 class="text-center txt-primary">Your session is locked</h3>
                    <div class="imgs-screen text-center">
                        <img src="assets/images/lockscreen.png" class="img-circle" alt="lock">
                    </div>
                    <div class="md-group">
                        <div class="md-input-wrapper">
                            <input type="password" class="md-form-control" name="lock"> 
                            <label>Password</label>
                        </div>
                    </div>
                    <div class="unlock">
                        <button type="submit" class="btn btn-primary btn-md waves-effect waves-light text-center m-b-20">UNLOCK
                        </button>
                      
                    </div>
                </form>
                <!-- end of form -->
            </div>
            <!-- end of login-card -->
        </div>
        <!-- end of row -->
    </div>
    <!-- end of container-fluid -->

</section>



@include('footer')