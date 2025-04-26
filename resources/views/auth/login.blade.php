<aside class="main-login">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gas Monitor | Login</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('reglogin/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{asset('reglogin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('reglogin/dist/css/adminlte.min.css')}}">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body class="hold-transition login-page">
    <div class="login-container">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                <h1>Silahkan Login</h1>
                </div>
                <div class="card-body">

                @if (session('failed'))
                    <div class="alert alert-danger">{{session('failed')}}</div>
                @endif

                <p class="login-box-msg">Silakan login untuk mengakses akun dan mendapatkan notifikasi.</p>

                <form action="/login" method="post">
                    @csrf
                    @error('email')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                    <div class="input-group mb-3 has-validation">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    </div>
                    @error('password')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                    <div class="input-group mb-3 has-validation">
                    <input type="password" name="password" class="form-control" placeholder="Password" id="password">
                    <div class="input-group-append show-password">
                        <div class="input-group-text">
                        <span class="fas fa-lock" id="password-lock"></span>
                        </div>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">
                            Remember Me
                        </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                    </div>
                </form>

                <p class="mb-1">
                    <a href="forgot-password.html">I forgot my password</a>
                </p>
                <p class="mb-0">
                    <a href="/register" class="text-center">Register a new membership</a>
                </p>
                </div>
                <!-- /.card-body -->
            </div>
        <!-- /.card -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{asset('reglogin/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('reglogin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('reglogin/dist/js/adminlte.min.js')}}"></script>

    <script>
    $('.show-password').on('click', function() {
            if($('#password').attr('type') == 'password') {
                $('#password').attr('type', 'text');
                $('#password-lock').attr('class', 'fas fa-unlock');
            } else {
                $('#password').attr('type', 'password');
                $('#password-lock').attr('class', 'fas fa-lock');
            }
    })
    </script>
</body>
</html>
