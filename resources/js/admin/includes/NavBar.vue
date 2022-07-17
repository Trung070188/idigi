<template>

    <div id="kt_header" style="z-index: 97" class="header align-items-stretch"  >

        <div class="container-fluid d-flex align-items-stretch justify-content-between">
   

            <div class="d-flex align-items-stretch" id="kt_header_nav">
                <div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                    <div class="menu menu-lg-rounded menu-column menu-lg-row menu-state-bg menu-title-gray-700 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-400 fw-bold my-5 my-lg-0 align-items-stretch" id="#kt_header_menu" data-kt-menu="true">
                        <template v-for="menu in menus">
                            <div data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion me-lg-1" v-if="menu.subs">
                                <span class="menu-link py-3">
                                    <span class="menu-title">{{ menu.name }}</span>
                                    <span class="menu-arrow d-lg-none"></span>
                                </span>
                                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px">
                                    <div v-for="sub in menu.subs" data-kt-menu-placement="right-start" class="menu-item menu-lg-down-accordion">
                                        <a :href="sub.url" class="menu-link">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">{{ sub.name }}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>    
                            <div class="menu-item me-lg-1" v-else>
                                <a :class="menu.active?'menu-link active py-3':'menu-link py-3'" :href="menu.url">
                                    <span class="menu-title">{{ menu.name }}</span>
                                </a>
                            </div>                                                  
                        </template>
                    </div>
                </div>
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
                            <span class="symbol symbol-25" v-if="auth.image!==null">
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

                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px" data-kt-menu="true" style="">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <div class="menu-content d-flex align-items-center px-3">
                                    <!--begin::Avatar-->
                                    <div class="symbol symbol-50px me-5" v-if="auth.image!==null">
                                        <img alt="Logo" :src="auth.image" />
                                    </div>
                                    <!--end::Avatar-->
                                    <!--begin::Username-->
                                    <div class="d-flex flex-column">
                                        <div class="fw-bolder d-flex align-items-center fs-5">{{auth.username}}
                                        <span class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2">{{auth.role}}</span></div>
                                        <a href="#" class="fw-bold text-muted text-hover-primary fs-7">{{auth.email}}</a>
                                    </div>
                                    <!--end::Username-->
                                </div>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu separator-->
                            <div class="separator my-2"></div>
                            <!--end::Menu separator-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-5">
                                <a :href="'/xadmin/users/profile?id='+auth.id" class="menu-link px-5">My Profile</a>
                            </div>

                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-5">
                                <a href="/xadmin/logout" class="menu-link px-5">Sign Out</a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                                                
                        <!--<div
                            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-165px"
                            data-kt-menu="true">
                            <div class="menu-item px-5">
                                <a :href="'/xadmin/users/profile?id='+auth.id" class="menu-link px-5"><span>View Profile</span> <i class="bi bi-person-circle" style="margin-left:4px"></i></a>
                            </div>


                            <div class="menu-item px-5">
                                <a href="/xadmin/logout" class="menu-link px-5"><span>Log Out</span>   <i class="bi bi-box-arrow-right" style="margin-left:30px"></i></a>

                            </div>
                        </div>-->

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
    import {$get, $post, clone, getTimeRangeAll} from "../../utils";

    export default {
        name: "NavBar",
        components: {TopNotification, ProfileForm},
        data() {
            const menus = clone(window.$sideBarMenus);
            const pathname = location.pathname.split('?')[0];

            menus.forEach(menu => {
                menu.showSubMenu = false;
                menu.active = false;
                if (!menu.base) {
                    if (pathname.indexOf(menu.url) >= 0) {
                        menu.active = true;
                        menu.showSubMenu = true;
                    }
                }

                if (menu.base) {

                    if (menu.subs) {
                        menu.subs.forEach(sub => {
                            if (pathname.indexOf(sub.url) >= 0) {
                                sub.active = true;
                                menu.active = true;
                                menu.showSubMenu = true;
                            }
                        })
                    }
                }
            });

            return {
                menus,
            }
        },        
    }
</script>

<style scoped>

    .nva{
        position: absolute;
        width: 40px;
        height: 40px;
        margin: -22px -16px 0px;
        background: #EFF0F6;
        border-radius: 50%;
    }

</style>
