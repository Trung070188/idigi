<!DOCTYPE html>

<?php
    $pageTitle = isset($title) ? $title . ' - idgi' : 'idgi';
    $title = isset($title) ? $title : '';
?>

<html lang="en">
	<head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>{{$pageTitle}}</title>

		<link rel="shortcut icon" href="/themes/admin/assets/media/logos/favicon.ico" />

        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&amp;display=swap" rel="stylesheet">
        <link href="/assets/js/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
        <link href="/assets/js/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
        <link href="/assets/plugins/ckeditor/plugins/codesnippet/lib/highlight/styles/default.css" rel="stylesheet"/>
		<link href="/themes/admin/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="/themes/admin/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
        <link href="/assets/plugins/ion-rangeslider/css/ion.rangeSlider.css" rel="stylesheet">
        <link href="/assets/plugins/ion-rangeslider/css/ion.rangeSlider.skinFlat.css" rel="stylesheet">
        <link href="{{ asset('/assets/css/viewer.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="/assets/css/jquery-ui.css">
        <link rel="stylesheet" href="/assets/css/style.css">
        <style>
            .btn-trash i{
                font-size: 1.3rem;
            }
            .table tr{
                border-top:1px solid lightgrey;
            }
        </style>

        <!--end::Layout Themes-->
        <script>
            window.$json = JSON.parse('{!! addslashes(json_encode($jsonData?? new stdClass())) !!}');
            window.$componentName = '{{$component}}';
            window.$sideBarMenus = JSON.parse('{!! addslashes(json_encode(config('menu'))) !!}');
            window.$csrf = '{{csrf_token()}}';
            window.$pageTitle = '{{$title}}';
            <?php
                $user = auth_user();
                $auth = [
                    //'id' => $user->id,
                    'email' => $user->email,
                    'username' => $user->username
                ];
            ?>
            window.$auth =  JSON.parse('{!! addslashes(json_encode($auth)) !!}');

            function autoGrow(element) {
                element.style.height = "5px";
                element.style.height = (element.scrollHeight)+"px";
            }
        </script>

	</head>
	<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">

        <div id="root-app"></div>

		<script>var hostUrl = "/";</script>
		<script src="/themes/admin/assets/plugins/global/plugins.bundle.js"></script>
		<script src="/themes/admin/assets/js/scripts.bundle.js"></script>
		<script src="/themes/admin/assets/js/custom/widgets.js"></script>
		<script src="/themes/admin/assets/js/custom/apps/chat/chat.js"></script>
		<script src="/themes/admin/assets/js/custom/modals/create-app.js"></script>
		<script src="/themes/admin/assets/js/custom/modals/upgrade-plan.js"></script>
		<script src="/themes/admin/assets/js/custom/intro.js"></script>
        <script src="/assets/js/moment.min.js"></script>
        <script src="/assets/js/daterangepicker/daterangepicker.js"></script>
        <script src="/assets/plugins/ckeditor/ckeditor.js"></script>
        <script src="/assets/plugins/ckeditor/plugins/colorbutton/plugin.js"></script>
        <script src="/assets/plugins/ckeditor/plugins/colordialog/plugin.js"></script>
        <script src="/assets/plugins/ckeditor/plugins/codesnippet/lib/highlight/highlight.pack.js"></script>
        <script src="/assets/plugins/ion-rangeslider/js/ion.rangeSlider.js"></script>
        <script src="/assets/js/jquery-ui.js"></script>
        <script type="text/javascript" src="{{ asset('packages/barryvdh/elfinder/js/elfinder.min.js') }}"></script>
        <link rel="stylesheet" type="text/css" href="{{ asset('packages/barryvdh/elfinder/css/elfinder.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('packages/barryvdh/elfinder/css/theme.css') }}">

        <script src="/assets/js/app.js"></script>

	</body>
</html>
