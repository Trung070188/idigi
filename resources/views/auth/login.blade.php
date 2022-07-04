<?php
$googleSignEnabled = config('services.google.enabled')
?>
    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>idigi</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    @if ($googleSignEnabled)
        <meta name="google-signin-client_id"
              content="{{googleClientId()}}">
    @endif

    <style>
        #loginForm .form-control {
            width: 100%;
        }

        .btn-google {
            color: #545454;
            background-color: #ffffff;

            box-shadow: 0 1px 2px 1px #ddd;
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

                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Username or Email" name="login"
                           value="{{ old('login') }}" required autofocus>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" required name="password">
                </div>
                <div class="form-group d-flex justify-content-between">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" @if(old('remember')) checked
                               @endif name="remember" id="customCheck1">
                        <label class="custom-control-label" for="customCheck1">Remember me</label>
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
                <button class="btn btn-primary btn-block xxx">Sign in</button>

<!--                <div style="margin-top: 10px">
                    <div class="g-signin2" data-onsuccess="onSignIn"></div>
                </div>-->

            </form>
            <!-- ./ form -->

        </div>
    </div>
</div>
@if ($googleSignEnabled)

    <script>
        var continueUrl = '{{@$_GET['c']}}';
        var csrfToken = '{{csrf_token()}}';

        function onGoogleLoaded() {
            console.log('onGoogleLoaded')
            gapi.load('auth2', function () {
                gapi.auth2.init();
            });
        }

        function onSignIn(googleUser) {

            var profile = googleUser.getBasicProfile();


            var params = {
                email: profile.getEmail(),
                id: profile.getId(),
                imageUrl: profile.getImageUrl(),
                token: gapi.auth2.getAuthInstance().currentUser.get().getAuthResponse().id_token
            };

            fetch('/auth/google-sign', {
                method: 'POST', // or 'PUT'
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify(params),
            }).then((response) => response.json())
                .then((data) => {
                    if (data.code === 200) {
                        location.replace(continueUrl ? continueUrl : data.redirect);
                    } else {
                        alert(data.message);
                    }
                })


        }

    </script>

    <script src="https://apis.google.com/js/platform.js?onload=onGoogleLoaded" async defer></script>
@endif

</body>
</html>
