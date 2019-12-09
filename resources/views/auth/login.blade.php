@include('header')
<body>
    <section class="login p-fixed d-flex text-center bg-primary common-img-bg">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="login-card card-block">
                        <form class="md-float-material" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="text-center">
                                <h1 style="color: white;background-color:#70C2F4;padding: 20px;border-radius: 5px ">Volazi IT Management</h1>
                            </div>
                            <h3 class="text-center txt-primary">
                                Sign In to your account
                            </h3>
                            <p style="color: red;"><?php if( $errors->any() ) { echo $errors->all()[0]; } ?></p>
                            <div class="md-input-wrapper">
                               <input id="email" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus placeholder="Username">

                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="md-input-wrapper">
                                 <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Password">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="row">
                               
                                <div class="col-sm-6 col-xs-12 forgot-phone text-right">
                                    <a href="#" class="text-right f-w-600"> Forget Password? Contact IT</a>
                                    </div>
                            </div>
                            <div class="row top">
                                <div class="col-xs-10 offset-xs-1">
                                    <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">LOGIN</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>




@include('footer')