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
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
        <link href="{{ asset('/assets/css/viewer.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="/assets/css/jquery-ui.css">
        <link rel="stylesheet" href="/assets/css/style.css">
        <style>
            .btn-trash i{
                font-size: 1.3rem;
            }
            .breadcrumb{
                background:unset;
            }
            .table-head-bg{
                border-top: 1px solid #d5cfcf;
                border-bottom: 1px solid #d5cfcf;
            }
            .table td:first-child, .table th:first-child, .table tr:first-child{
                padding-left: 0.75rem;
            }
            .form-group label{
                font-weight: 700;
            }
            @media (max-width: 991.98px){
                .toolbar .page-title[data-kt-swapper=true] {
                    display: block!important;
                }
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

        <!--end::Layout Themes-->
        <script>
            window.$json = JSON.parse('{!! addslashes(json_encode($jsonData?? new stdClass())) !!}');
            window.$componentName = '{{$component}}';
            window.$csrf = '{{csrf_token()}}';
            window.$pageTitle = '{{$title}}';
            <?php
                $user = auth_user();
                $auth = [
                    'id' => $user->id,
                    'email' => $user->email,
                    'username' => $user->username,
                    'full_name'=>$user->full_name,
                    'image'=>$user->image,
                ];

                //menu
                $groupPermissions = \App\Models\GroupPermission::with(['permissions.roles', 'permissions', 'childs'])
                    ->where('parent_id', NULL)
                    ->get();
                $roles = $user->roles;
                $menus = [];
                $isAdmin = 0;

                foreach ($roles as $role) {
                    if ($role->role_name == 'Super Administrator') {
                        $isAdmin = 1;
                    }
                }

                if($isAdmin == 1){
                    foreach ($groupPermissions as $groupPermission) {

                        if ($groupPermission->childs->count() > 0) {

                            $menu = [
                                "name" => $groupPermission->name,
                                "icon" => $groupPermission->icon,
                                'url' => $groupPermission->path,
                                'base' => $groupPermission->base,
                                'subs' => [],
                            ];
                            foreach ($groupPermission->childs as $child) {
                                $menu['subs'][] = [
                                    "name" => $child->name,
                                    "icon" => $child->icon,
                                    'url' => $child->path,
                                ];
                            }
                        }else{
                            $menu = [
                                "name" => $groupPermission->name,
                                "icon" => $groupPermission->icon,
                                'url' => $groupPermission->path
                            ];
                        }

                        $menus[] = $menu;

                    }
                }else{
                    foreach ($groupPermissions as $groupPermission) {
                        $check = 0;
                        $permissionRoles = explode(';', $groupPermission->role_id);
                        foreach ($permissionRoles as $permissionRole){
                            foreach ($roles as $role){
                                if($permissionRole == $role->id){
                                    $check = 1;
                                }
                            }
                        }

                        if($check == 1){
                            if ($groupPermission->childs->count() > 0) {

                                $menu = [
                                    "name" => $groupPermission->name,
                                    "icon" => $groupPermission->icon,
                                    'url' => $groupPermission->path,
                                    'subs' => [],
                                ];

                                foreach ($groupPermission->childs as $child) {
                                    $menu['subs'][] = [
                                        "name" => $child->name,
                                        "icon" => $child->icon,
                                        'url' => $child->path,
                                    ];
                                }
                            }else{
                                $menu = [
                                    "name" => $groupPermission->name,
                                    "icon" => $groupPermission->icon,
                                    'url' => $groupPermission->path
                                ];
                            }

                            $menus[] = $menu;
                        }

                    }
                }
                //end menu

                //Permission
                $userPermissions = [];
                $roleIds = [];
                foreach ($roles as $role){
                    $roleIds[] = $role->id;
                }

                if($isAdmin == 1){
                    $permissions = \App\Models\Permission::get();

                    foreach ($permissions as $permission){
                        $userPermissions[$permission->code] = 1;
                    }
                }else{
                    $rolePermissions = \App\Models\RoleHasPermission::whereIn('role_id', $roleIds)
                        ->with(['permission'])->get();

                    foreach ($rolePermissions as $rolePermission){
                        $userPermissions[$rolePermission->permission->code] = 1;
                    }
                }

            ?>
            window.$sideBarMenus = JSON.parse('{!! addslashes(json_encode($menus)) !!}');
            window.$permissions = JSON.parse('{!! addslashes(json_encode($userPermissions)) !!}');
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

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" ></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" ></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" ></script>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="/assets/js/app.js"></script>

	</body>
</html>
