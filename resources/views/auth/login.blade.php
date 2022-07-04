<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>idigi</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">


    <style>
        #loginForm .form-control{
            width: 100%;
        }
    </style>
</head>
<body class="form-membership">

<div class="container d-flex justify-content-center align-items-center">
    <div class="row">

        <div class="form-wrapper" style="   padding: 31px;width: 500px; border: 0; text-align: center">

            <!-- logo -->
            <div class="logo">
                <img src="{{asset('/images/imgpsh_fullsize_anim.png')}}">
            </div>
            <!-- ./ logo //// -->
            <h5 style="margin-top:20px;">Login</h5>
            <!-- form -->
            <form method="post" id="loginForm" method="post" action="/xadmin/login" style="margin-top:50px;">
                {{csrf_field()}}
                @if (config('app.env') !== 'production')
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Username or Email" name="login" value="{{ old('login') }}" required autofocus>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" required name="password">
                    </div>
                    <div class="form-group d-flex justify-content-between">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" @if(old('remember')) checked @endif name="remember"  id="customCheck1">
                            <label class="custom-control-label" for="customCheck1">Remember me</label>
                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-between">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="is_ismart" @if(old('is_ismart')) checked @endif class="custom-control-input" id="customCheck2">
                            <label class="custom-control-label" for="customCheck2">Login with iSMART account</label>
                        </div>
                    </div>
                    @error('login')
                    <div class="alert alert-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                    @error('username')
                    <div class="alert alert-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                    @error('email')
                    <div class="alert alert-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                    @error('password')
                    <div class="alert alert-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                    <button class="btn btn-primary btn-block xxx">Login</button>
                    <br>
                    <br>
                @else
                    <center>
                        <div class="g-signin2" data-onsuccess="onSignIn"></div>
                    </center>
                @endif

            </form>
            <!-- ./ form -->

        </div>
    </div>
</div>
<script>
</script>

</body>
</html>
