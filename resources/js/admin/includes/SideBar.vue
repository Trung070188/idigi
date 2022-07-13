<template>

    <div style="background:#1e1e2d;" id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true"
         data-kt-drawer-name="aside"
         data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
         data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
         data-kt-drawer-toggle="#kt_aside_mobile_toggle">

        <div style="background: #1e1e2d" class="aside-logo flex-column-auto" id="kt_aside_logo">
            <!-- <a href="/">
                <img alt="Logo" src="/images/imgpsh_fullsize_anim.png" class="logo" style="height: 55px"/>
            </a> -->

            <div class="d-flex align-items-center mb-10 " style="margin: 45px -10px 0px;">
                <!--begin::Symbol-->
                <div class="symbol symbol-40  mr-5 logo ">
                        <span v-if="image!==null" class="symbol ">
                            <img :src="image" class="h-75 align-self-end " style="border-radius: 50%" alt=""/>
                        </span>
                    <span v-if="image==null" class="symbol symbol-lg-35 symbol-25 symbol-light-success"
                    >
                            </span>
                </div>
                <!--end::Symbol-->
                <!--begin::Text-->
                <div class="d-flex flex-column flex-grow-1 font-weight-bold ">
                    <span style="color:#fff" class="logo ">{{entries}}</span>

                    <span style="color:#fff" class="logo">{{role}}</span>


                </div>

                <div style="margin:0px 15px 0px" id="kt_aside_toggle"
                     class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle"
                     data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
                     data-kt-toggle-name="aside-minimize">
                         	<span class="svg-icon svg-icon-1 rotate-180">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none">
									<path opacity="0.5"
                                          d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
                                          fill="black"/>
									<path
                                        d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
                                        fill="black"/>
								</svg>
							</span>

                </div>
            </div>

        </div>
        <br>
        <br>
        <div class="aside-menu flex-column-fluid">
            <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
                 data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
                 data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu"
                 data-kt-scroll-offset="0">
                <div
                    class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 "
                    id="#kt_aside_menu" data-kt-menu="true">
                    <hr>

                    <div class="menu-item">

                    </div>

                    <template v-for="menu in menus">
                        <div data-kt-menu-trigger="click" :class="{'show': menu.active ,'here': menu.active}"
                             class="menu-item menu-accordion" v-if="menu.subs">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i :class="menu.icon" style="width: 20px"></i>
                                </span>
                                <span class="menu-title">{{ menu.name }}</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item" v-for="sub in menu.subs">
                                    <a class="menu-link" :href="sub.url" :class="{'active': sub.active}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">{{ sub.name }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="menu-item" v-else>
                            <a class="menu-link" :href="menu.url" :class="{'active': menu.active}">
                                <span class="menu-icon">
                                    <i :class="menu.icon" style="width: 20px"></i>
                                </span>
                                <span class="menu-title">{{ menu.name }}</span>
                            </a>
                        </div>
                    </template>

                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import {$get, clone} from "../../utils";
    import $router from "../../lib/SimpleRouter";

    export default {
        name: "SideBar.vue",
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
                entries: '',
                role: '',
                image: ''

            }
        },
        mounted() {
            this.load();
        },

        methods: {
            toggleMenu(menu) {
                if (!menu.subs) {
                    location.href = menu.url;
                } else {
                    menu.showSubMenu = !menu.showSubMenu;
                }
            },
            async load() {
                let query = $router.getQuery();
                const res = await $get('/xadmin/users/name_sideBar', query);
                this.entries = res.username;
                this.role = res.role;
                this.image = res.image;
            },
        }
    }
</script>

<style scoped>

</style>
