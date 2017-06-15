@extends('main_layout')

@section('content')
    <div id="sign-wrapper">

        <!-- Brand -->
        <div class="brand">
            <img width='100%' src='{{url('img/logo_usu.png')}}'>
        </div>
        <!--/ Brand -->

        <!-- Login form -->
        <form class="sign-in form-horizontal shadow rounded no-overflow" action="{{url('user/login')}}" method="post">
            <div class="sign-header">
                <div class="form-group">
                    <div class="sign-text">
                        <span>LOGIN</span>
                    </div>
                </div><!-- /.form-group -->
            </div><!-- /.sign-header -->
            <div class="sign-body">
                <div class="form-group">
                    <div class="input-group input-group-lg rounded no-overflow">
                        <input type="text" class="form-control input-sm" placeholder="Username" name="username" id='username'>
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    </div>
                </div><!-- /.form-group -->
                <div class="form-group">
                    <div class="input-group input-group-lg rounded no-overflow">
                        <input type="password" class="form-control input-sm" placeholder="Password" name="password">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    </div>
                </div><!-- /.form-group -->
            </div><!-- /.sign-body -->
            <div class="sign-footer">
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="ckbox ckbox-theme">
                                <input id="rememberme" type="checkbox">
                                <label for="rememberme" class="rounded">Ingat Saya</label>
                            </div>
                        </div>
                        <div class="col-xs-6 text-right">
                            <a href="mods/lost_password.html" title="Lupa password">Lupa password?</a>
                        </div>
                    </div>
                </div><!-- /.form-group -->
                {{csrf_field()}}
                <div class="form-group">
                    <button type="submit" class="btn btn-theme btn-lg btn-block no-margin rounded" id="login-btn">Sign In</button>
                </div><!-- /.form-group -->
            </div><!-- /.sign-footer -->
        </form><!-- /.form-horizontal -->
        <!--/ Login form -->

        <!-- Content text -->
        <p class="text-muted text-center sign-link">Ingin daftar akun, silahkan hubungi PSI</p>
        <!--/ Content text -->

    </div><!-- /#sign-wrapper -->
    <!--/ END SIGN WRAPPER -->
@endsection