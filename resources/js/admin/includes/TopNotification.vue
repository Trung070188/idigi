<template>
    <div class="dropdown">
        <!--begin::Toggle-->
        <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" style="margin-right: -12px;">
            <div class="btn btn-icon btn-dropdown btn-lg mr-1 pulse pulse-primary" style="margin-top: 10px;border-radius: 50%;">
											<span class="svg-icon svg-icon-xl svg-icon-primary">
												<!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                                <div class="nva">
                                                <i class="fa fa-bell" style="margin: 11px 0px 0px"></i>
                                                </div>
                                                   <span v-for="notify in entries" class="notifiy_num"
                                                         v-if="notification>0 && notify.title=='Yêu cầu cấp quyền'">{{notification}}</span>
                                                <span v-for="notify in entries" class="notifiy_num"
                                                      v-if="admin>0 && notify.title=='Yêu cầu xóa thiết bị'">{{admin}}</span>


                                                <!--end::Svg Icon-->
											</span>

<!--                <span v-for="pulse_ring in entries" v-if="admin>0 && pulse_ring.title=='Yêu cầu xóa thiết bị'"-->
<!--                      class="pulse-ring"></span>-->
<!--                <span v-for="pulse_ring in entries" v-if="notification>0 && pulse_ring.title=='Yêu cầu cấp quyền'"-->
<!--                      class="pulse-ring"></span>-->


            </div>
        </div>
        <!--end::Toggle-->
        <!--begin::Dropdown-->
        <div  v-for="entry in entries" class="dropdown-menu dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
            <form v-if="entries.length>0">
                <!--begin::Header-->
                    <div v-if="entry.title=='Yêu cầu cấp quyền'"
                         class="d-flex flex-column pt-12 bgi-size-cover bgi-no-repeat rounded-top"
                         style="background-image: url(/assets/media/misc/bg-1.jpg)">
                        <!--begin::Title-->
                        <h4 v-if="notification>0"
                            class="d-flex flex-center rounded-top">
                            <span class="text-white">User Notifications</span>
                            <span class="btn btn-text btn-success btn-sm font-weight-bold btn-font-md ml-2">{{notification}} new</span>
                        </h4>
                        <h4 v-if="notification==0"
                            class="d-flex flex-center rounded-top">
                            <span class="text-white">No Notifications</span>
                        </h4>
                    </div>
                <div  v-if="entry.title=='Yêu cầu xóa thiết bị'"
                     class="d-flex flex-column pt-12 bgi-size-cover bgi-no-repeat rounded-top"
                     style="background-image: url(/assets/media/misc/bg-1.jpg)">
                    <!--begin::Title-->
                    <h4 v-if="admin>0"
                        class="d-flex flex-center rounded-top">
                        <span class="text-white">User Notifications</span>
                        <span class="btn btn-text btn-success btn-sm font-weight-bold btn-font-md ml-2">{{admin}} new</span>
                    </h4>
                    <h4 v-if="admin==0"
                        class="d-flex flex-center rounded-top">
                        <span class="text-white">No Notifications</span>
                    </h4>
                </div>
                <div class="tab-content" style="overflow: auto;height: 200px;">
                    <div v-for="entry in entries" class="tab-pane active" id="topbar_notifications_events"
                         role="tabpanel">
                        <div class="navi navi-hover scroll my-4" data-scroll="true"
                             data-height="300" data-mobile-height="200">
                            <!--begin::Item-->
                            <a href="#" class="navi-item" :href="entry.url" @click="abc(entry)">
                                <div class="navi-link" v-if="entry.title=='Yêu cầu cấp quyền'">

                                    <div v-if="entry.status=='new' " class="navi-text">
                                        <div class="font-weight" style="font-weight: 700">{{entry.username}} có yêu cầu
                                            mới !
                                        </div>
                                        <div class="text-muted" style="font-weight: 500">{{d(entry.created_at)}}</div>
                                    </div>
                                    <div v-if="entry.status=='unread'" style="opacity: 0.6" class="navi-text">
                                        <div class="font-weight-bold">{{entry.username}} có yêu cầu mới !</div>
                                        <div class="text-muted">{{d(entry.created_at)}}</div>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="navi-item" :href="entry.url" @click="abc(entry)">
                                <div class="navi-link" v-if="entry.title=='Yêu cầu xóa thiết bị'">

                                    <div v-if="entry.status=='new'" class="navi-text">
                                        <div class="font-weight" style="font-weight: 700">{{entry.username}} có yêu cầu
                                            mới !
                                        </div>
                                        <div class="text-muted" style="font-weight: 500">{{d(entry.created_at)}}</div>
                                    </div>
                                    <div v-if="entry.status=='unread'" style="opacity: 0.6" class="navi-text">
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
            <form v-if="entries.length==0">
                <div class="d-flex flex-column pt-12 bgi-size-cover bgi-no-repeat rounded-top"
                     style="background-image: url(/assets/media/misc/bg-1.jpg)">
                    <h4 class="d-flex flex-center rounded-top">
                        <span class="text-white">No Notifications</span>
                    </h4>


                </div>
                <div class="tab-content" style="overflow: auto;height: 200px;">
                    <!--begin::Nav-->
                    <div class="navi navi-hover scroll my-4"
                         data-height="300" data-mobile-height="200">
                        <!--begin::Item-->
                        <div class="d-flex flex-center text-center text-muted min-h-200px">
                            <br/>No new notifications.
                        </div>
                        <!--end::Nav-->
                    </div>
                </div>
            </form>

        </div>
    </div>

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
                admin: '',
                notification: '',
                entries: [],
            }
        },
        methods: {
            async Notification() {

                let query = $router.getQuery();
                const res = await $get('/xadmin/notifications/notification', query);
                this.entries = res.data.entries;
                this.notification = res.data.notification;
                this.admin = res.data.admin;

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
    .notifiy_num {
        text-align: center;
        position: absolute;
        margin: -20px -15px 10px;
        min-width: 15px;
        min-height: 17px;
        border-radius: 50%;
        background: red;
        color: #f1f1f1;
        font-family: Sans-Serif;
    }
    .nva{
        position: absolute;
        width: 40px;
        height: 40px;
        margin: -19px -35px 0px;
        background: #EFF0F6;
        border-radius: 50%;
    }


</style>
