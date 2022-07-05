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

    <style>
        #loginForm .form-control {
            width: 100%;
        }

    </style>
    <style>
        #customBtn {
            display: inline-block;
            background: white;
            color: #444;
            width: 100%;
            border-radius: 5px;
            border: thin solid #888;
            box-shadow: 1px 1px 1px grey;
            white-space: nowrap;
        }

        #customBtn:hover {
            cursor: pointer;
        }

        span.label {
            font-family: serif;
            font-weight: normal;
        }

        span.icon {
            background: url('/images/g-normal.png') transparent 5px 50% no-repeat;
            display: inline-block;
            vertical-align: middle;
            width: 36px;
            height: 36px;
        }

        span.buttonText {
            display: inline-block;
            vertical-align: middle;
            padding-left: 42px;
            padding-right: 42px;
            font-size: 14px;
            font-weight: bold;
            /* Use the Roboto font that is loaded in the <head> */
            font-family: 'Roboto', sans-serif;
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

                <div id="gSignInWrapper" style="margin-top: 10px">
                    <div id="customBtn" class="customGPlusSignIn">
                        <span class="icon"></span>
                        <span class="buttonText">Sign in with Google</span>
                    </div>
                </div>

            </form>
            <!-- ./ form -->

        </div>
    </div>
</div>
<script src="https://apis.google.com/js/api:client.js"></script>
<script>
    var csrfToken = '{{csrf_token()}}';
    var googleUser = {};
    var startApp = function () {
        gapi.load('auth2', function () {
            // Retrieve the singleton for the GoogleAuth library and set up the client.
            auth2 = gapi.auth2.init({
                client_id: '{{googleClientId()}}',
                cookiepolicy: 'single_host_origin',
                // Request scopes in addition to 'profile' and 'email'
                //scope: 'additional_scope'
            });
            attachSignin(document.getElementById('customBtn'));
        });
    };

    function attachSignin(element) {
        console.log(element.id);
        auth2.attachClickHandler(element, {},
            function (googleUser) {
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
                            location.replace(data.redirect);
                        } else {
                            alert(data.message);
                        }
                    })

            }, function (error) {
                alert(JSON.stringify(error, undefined, 2));
            });
    }
</script>
<script>startApp();</script>

</body>
</html>
