<template>
    <div class="dropdown">
        <!--begin::Toggle-->
        <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
            <div class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1 pulse pulse-primary" style="margin-top: 10px">
											<span class="svg-icon svg-icon-xl svg-icon-primary">
												<!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
												<i class="fa fa-bell"></i>
                                                <!--end::Svg Icon-->
											</span>
                <span v-if="notification>0" class="pulse-ring"></span>
            </div>
        </div>
        <!--end::Toggle-->
        <!--begin::Dropdown-->
        <div  class="dropdown-menu dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
            <form >
                <!--begin::Header-->
                <div class="d-flex flex-column pt-12 bgi-size-cover bgi-no-repeat rounded-top" style="background-image: url(/assets/media/misc/bg-1.jpg)">
                    <!--begin::Title-->
                    <h4 v-if="notification>0" class="d-flex flex-center rounded-top">
                        <span class="text-white">User Notifications</span>
                        <span   class="btn btn-text btn-success btn-sm font-weight-bold btn-font-md ml-2">{{notification}} new</span>
                    </h4>
                    <h4 v-if="notification==0" class="d-flex flex-center rounded-top">
                        <span class="text-white">No Notifications</span>
                    </h4>
                    <!--end::Title-->
                    <!--begin::Tabs-->
                    <ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-line-transparent-white nav-tabs-line-active-border-success mt-3 px-8">

                    </ul>

                </div>

                <div  class="tab-content" style="overflow: auto;height: 200px;">

                    <div v-for="entry in entries" class="tab-pane active" id="topbar_notifications_events" role="tabpanel">
                        <!--begin::Nav-->
                        <div  class="navi navi-hover scroll my-4" data-scroll="true"
                             data-height="300" data-mobile-height="200"  >
                            <!--begin::Item-->
                            <a href="#" class="navi-item" :href="entry.url" @click="abc(entry)">
                                <div class="navi-link">
                                    <div class="navi-icon mr-2">
                                        <i class="flaticon2-line-chart text-success"></i>
                                    </div>
                                    <div class="navi-text">
                                        <div class="font-weight-bold">{{entry.username}} có yêu cầu mới !</div>
                                        <div class="text-muted">{{d(entry.created_at)}}</div>
                                    </div>
                                </div>
                            </a>

                            <!--end::Nav-->
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>

    <!--    <li class="nav-item dropdown">-->
    <!--        <a id="" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
    <!--            <i class="fa fa-bell " id="notify_icon"  ></i>-->

    <!--            <span id="notifiy_num" v-show="entries.length>0">{{entries.length}}</span>-->

    <!--        </a>-->


    <!--        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="">-->
    <!--            <a class="dropdown-item" v-for="(unread,index) in entries" :key="index"  @click="abc(unread)">-->
    <!--                <p v-if="unread.title=='Yêu cầu xóa thiết bị'">{{unread.username}} yêu cầu xóa thiết bị.-->
    <!--                    {{d(unread.created_at)}} </p>-->
    <!--                <p v-if="unread.title=='Yêu cầu cấp quyền'">{{unread.username}} yêu cầu cấp quyền .-->
    <!--                    {{d(unread.created_at)}} </p>-->
    <!--            </a>-->
    <!--            <a class="dropdown-item" v-show="entries.length==0">No Notifications</a>-->

    <!--        </div>-->
    <!--    </li>-->
</template>
<script>
    import {$get, $post} from "../../utils";
    import $router from "../../lib/SimpleRouter";

    export default {
        name: "TopNotification",

        mounted() {
            this.Notification();
        },

        data() {
            return {
                notification:'',
                entries: [],
            }
        },
        methods: {
            async Notification() {

                let query = $router.getQuery();
                const res = await $get('/xadmin/notifications/notification', query);
                this.entries = res.data.entries;
                this.notification=res.data.notification;
            },
            async abc(entry) {
                const res = await $post('/xadmin/notifications/toggleStatus', {
                    id: entry.id,
                    status: entry.status
                }, false);
                toastr.success(res.message);
            },


        },
    }
</script>

<style scoped>
    #notify_icon {
        margin-right: -16px;
        line-height: 25px;

    }

    #notifiy_num {
        text-align: center;
        position: absolute;
        top: 10px;
        right: -7px;
        min-width: 16px;
        min-height: 16px;
        border-radius: 50%;
        background: red;
        color: #f1f1f1;
        font-family: Sans-Serif;
    }




</style>
