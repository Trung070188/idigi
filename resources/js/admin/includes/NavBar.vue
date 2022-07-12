<template>

    <div id="kt_header" style="z-index: 97" class="header align-items-stretch"  >

        <div class="container-fluid d-flex align-items-stretch justify-content-between">
            <div class="d-flex align-items-center ms-1 ms-lg-3">
                  <a href="/">
                <img alt="Logo" src="/images/imgpsh_fullsize_anim.png" class="logo" style="height: 55px"/>
            </a>
            </div>
            <div class="d-flex align-items-center d-lg-none ms-n3 me-1" title="Show aside menu">
                <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px"
                     id="kt_aside_mobile_toggle">
                    <span class="svg-icon svg-icon-2x mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
                                fill="black"/>
                            <path opacity="0.3"
                                  d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
                                  fill="black"/>
                        </svg>
                    </span>
                </div>
            </div>
<!--            <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">-->
<!--                <a href="/" class="d-lg-none">-->
<!--                    <img alt="Logo" src="/images/imgpsh_fullsize_anim.png" class="h-30px"/>-->
<!--                </a>-->
<!--            </div>-->

            <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
                <div class="d-flex align-items-stretch" id="kt_header_nav">
                    <div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu"
                         data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
                         data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="end"
                         data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true"
                         data-kt-swapper-mode="prepend"
                         data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                    </div>
                </div>
                <div class="d-flex align-items-stretch flex-shrink-0">
                    <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_bell">


                        <!--                        <div  data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end" style="position:relative">
                                                    <i class="fas fa-bell"></i><span class="badge badge-light-danger badge-circle fw-bolder fs-7" style="position: absolute;top: -8px;right: -7px; width: 1.25rem; height: 1.25rem">3</span>
                                                </div>-->
                        <div
                            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px"
                            data-kt-menu="true">
                            <div class="menu-item px-3">
                                <div class="menu-content d-flex align-items-center px-3">
                                    <div class="d-flex flex-column">
                                        <div class="fw-bolder d-flex align-items-center fs-5">Thông báo</div>
                                    </div>
                                    <!--end::Username-->
                                </div>
                            </div>
                            <div class="separator my-2"></div>

                            <div class="menu-item px-5">
                                <a :href="'/xadmin/users/profile?id='+auth.id" class="menu-link px-5">Thông báo 1</a>

                            </div>
                            <div class="menu-item px-5">
                                <a :href="'/xadmin/users/profile?id='+auth.id" class="menu-link px-5">Thông báo 1</a>

                            </div>
                            <div class="menu-item px-5">
                                <a :href="'/xadmin/users/profile?id='+auth.id" class="menu-link px-5">Thông báo 1</a>

                            </div>
                            <div class="menu-item px-5">
                                <a :href="'/xadmin/users/profile?id='+auth.id" class="menu-link px-5">Thông báo 1</a>

                            </div>


                        </div>

                    </div>

                    <TopNotification></TopNotification>
                    <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">

                        <div data-kt-menu-trigger="click" data-kt-menu-attach="parent"
                             data-kt-menu-placement="bottom-end">
                            <span class="symbol symbol-25" v-if="auth.image!==null" >
                                <span class="symbol-label font-size-h5 font-weight-bold "><img
                                    style="max-width: 40px ;border-radius:50%;" :src="auth.image">
                                </span>
                            </span>

                            <span v-if="auth.image==null" class="symbol symbol-25 ">
                                <span class="symbol-label font-size-h5 font-weight-bold">
                                    <i style="max-width: 40px ;border-radius:50%;" class="fa fa-user"></i>
                                </span>
                            </span>
                        </div>
                        <div
                            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-165px"
                            data-kt-menu="true">

                            <div class="menu-item px-5">
                                <!--                                    <a v-for="entry in entries" :href="'/xadmin/users/profile?id='+entry.id" ><i style="font-size:1.3rem" class="fa fa-edit"></i></a>-->
                                <a :href="'/xadmin/users/profile?id='+auth.id" class="menu-link px-1">

                                    <button class="btn btn-primary" style="margin-left:10px">View Profile</button></a>
                            </div>

                            <div class="menu-item px-1">
                                <a href="/xadmin/logout" class="menu-link px-5">

                                   <button class="btn btn-primary" style="width: 109px;margin-left:10px">Log Out</button></a>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>

</template>

<script>
    import TopNotification from "./TopNotification";
    import ProfileForm from "../users/ProfileForm";
    import $router from "../../lib/SimpleRouter";
    import {$get, $post, getTimeRangeAll} from "../../utils";

    export default {
        name: "NavBar",
        components: {TopNotification, ProfileForm},
    }
</script>

<style scoped>
    .menu-sub-dropdown {
        border: 1px solid #696CFF;
    }
    .nva{
        position: absolute;
        width: 40px;
        height: 40px;
        margin: -22px -16px 0px;
        background: #EFF0F6;
        border-radius: 50%;
    }
</style>
