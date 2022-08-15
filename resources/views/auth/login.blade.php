<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: Metronic - Bootstrap 5 HTML, VueJS, React, Angular & Laravel Admin Dashboard Theme
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>iDIGI</title>
		<meta charset="utf-8" />
		<!--begin::Fonts-->
        <link rel="shortcut icon" href="/themes/admin/assets/media/logos/favicon.ico" />

		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="/themes/admin/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="/themes/admin/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
		<!--end::Global Stylesheets Bundle-->

        <style>
            #loginForm .form-control {
                width: 100%;
            }

            #overlay {
                position: fixed; /* Sit on top of the page content */
                display: none;
                width: 100%; /* Full width (cover the whole page) */
                height: 100%; /* Full height (cover the whole page) */
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0,0,0,0.5); /* Black background with opacity */
                z-index: 200000; /* Specify a stack order in case you're using a different order for other elements */
                cursor: pointer; /* Add a pointer on hover */
            }
            #overlay  .text {
                position: absolute;
                top: 50%;
                left: 50%;
                font-size: 50px;
                color: white;
                user-select: none;
                transform: translate(-50%,-50%);
                -ms-transform: translate(-50%,-50%);
            }
            .la-spin{
                font-size:8rem!important;
            }
            .btn-action{
                margin-right: 10px;
            }

        </style>

	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="bg-body">
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed">
				<!--begin::Content-->
				<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
					<!--begin::Logo-->
					<a href="/" class="mb-12">
						<!--<img alt="Logo" src="/images/Logo_iDIGI.png" class="h-40px" />-->
                        <img src="{{asset('/images/imgpsh_fullsize_anim.png')}}" style="height: 100px;"/>
					</a>
					<!--end::Logo-->
					<!--begin::Wrapper-->
					<div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
						<!--begin::Form-->
						<form class="form w-100" id="loginForm" method="post" action="/xadmin/login">
                            @csrf

							<!--begin::Heading-->
							<div class="text-center mb-10">
								<!--begin::Title-->
								<h1 class="text-dark mb-3">Sign In</h1>
								<!--end::Title-->
							</div>

							<!--begin::Heading-->
							<!--begin::Input group-->
							<div class="fv-row mb-10">
								<!--begin::Label-->
								<label class="form-label fs-6 fw-bolder text-dark">Username or Email</label>
								<!--end::Label-->
								<!--begin::Input-->
								<input class="form-control form-control-lg form-control-solid" type="text" name="login" autocomplete="off" placeholder="Username or Email" value="{{ old('login') }}" />
								<!--end::Input-->
                                @error('login')
                                <div class="fv-plugins-message-container invalid-feedback"><div data-field="email" data-validator="notEmpty">{{ $message }}</div></div>
                                @enderror
                                @error('username')
                                <div class="fv-plugins-message-container invalid-feedback"><div data-field="email" data-validator="notEmpty">{{ $message }}</div></div>
                                @enderror
							</div>
							<!--end::Input group-->
							<!--begin::Input group-->
							<div class="fv-row mb-10">
								<!--begin::Wrapper-->
								<div class="d-flex flex-stack mb-2">
									<!--begin::Label-->
									<label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
									<!--end::Label-->
								</div>
								<!--end::Wrapper-->
								<!--begin::Input-->
								<input class="form-control form-control-lg form-control-solid" type="password" name="password" placeholder="Password" autocomplete="off" />
								<!--end::Input-->
                                @error('password')
                                <div class="fv-plugins-message-container invalid-feedback"><div data-field="email" data-validator="notEmpty">{{ $message }}</div></div>
                                @enderror
							</div>

                            <div class="fv-row mb-10 fv-plugins-icon-container fv-plugins-bootstrap5-row-invalid">
								<label class="form-check form-check-custom form-check-solid form-check-inline mb-5">
									<input class="form-check-input" type="checkbox" name="remember" id="customCheck1" @if(old('remember')) checked @endif>
                                    <span class="form-check-label fw-bold text-gray-700">Remember me</span>
								</label>
                            </div>

							<!--end::Input group-->
							<!--begin::Actions-->
							<div class="text-center">
								<!--begin::Submit button-->
								<button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
									<span class="indicator-label">Continue</span>
									<span class="indicator-progress">Please wait...
									<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
								</button>
								<!--end::Submit button-->
								<!--begin::Separator-->
<!--								<div class="text-center text-muted text-uppercase fw-bolder mb-5">or</div>
								&lt;!&ndash;end::Separator&ndash;&gt;
								&lt;!&ndash;begin::Google link&ndash;&gt;
								<div id="gSignInWrapper">
                                    <button id="customBtn" type="button" class="btn btn-flex flex-center btn-light btn-lg w-100 mb-5">
                                        <img alt="Logo" src="/themes/admin/assets/media/svg/brand-logos/google-icon.svg" class="h-20px me-3" />Continue with Google
                                    </button>
                                </div>-->
								<!--end::Google link-->
							</div>
							<!--end::Actions-->
						</form>
						<!--end::Form-->
					</div>
					<!--end::Wrapper-->
				</div>
				<!--end::Content-->

			</div>
			<!--end::Authentication - Sign-in-->
		</div>
		<!--end::Main-->

        <div id="overlay">
            <div class="la-3x text">
                <i class="la la-spinner la-spin"></i>
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

                auth2.attachClickHandler(element, {},
                    function (googleUser) {
                        $('#overlay').show();
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

                                if (data.code === 200 || data.code === 0) {
                                    location.replace(data.redirect);
                                } else {
                                    $('#overlay').hide();
                                    alert(data.message);
                                }
                            })

                    }, function (error) {
                        console.log(error)
                        $('#overlay').hide();
                    });
            }
        </script>
        <script>startApp();</script>

	</body>
	<!--end::Body-->
</html>
