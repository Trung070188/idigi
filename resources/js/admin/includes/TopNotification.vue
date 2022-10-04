<template>
    <div class="dropdown">
        <!--begin::Toggle-->
        <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" style="margin-right: -12px;">
            <div class="btn btn-icon btn-dropdown btn-lg mr-1 pulse pulse-primary" style="margin-top: 10px;border-radius: 50%;">
											<span class="svg-icon svg-icon-xl svg-icon-primary">
												<!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                                <div class="nva" v-if="status!==1">
                                                <i  class="fa fa-bell" style="margin: 11px 0px 0px; font-size: 16px"></i></div>
                                                <div class="nva" v-if="status==1"><i  class="fa fa-bell" style="margin: 11px 0px 0px; font-size: 16px"></i>
                                                </div>
                                                <span  class="notifiy_num" v-if="notificationSuperAdmin>0 && status==2">{{notificationSuperAdmin}}</span>
                                                <span  class="notifiy_num" v-if="admin>0 && status==3">{{admin}}</span>
                                                <span  class="notifiy_num" v-if="it>0 && status==4">{{it}}</span>
											</span>

                <!--                <span v-for="pulse_ring in entries" v-if="admin>0 && pulse_ring.title=='Yêu cầu xóa thiết bị'"-->
                <!--                      class="pulse-ring"></span>-->
                <!--                <span v-for="pulse_ring in entries" v-if="notification>0 && pulse_ring.title=='Yêu cầu cấp quyền'"-->
                <!--                      class="pulse-ring"></span>-->


            </div>
        </div>
        <!--end::Toggle-->
        <!--begin::Dropdown-->
        <div  class="dropdown-menu dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
            <form v-if="status!==1">
                <!--begin::Header-->

                    <div v-if="status==2"
                         class="d-flex flex-column pt-12 bgi-size-cover bgi-no-repeat rounded-top"
                         style="background-image: url(/assets/media/misc/bg-1.jpg)">
                        <!--begin::Title-->
                        <h4 v-if="notification>0"
                            class="d-flex flex-center rounded-top">
                            <span class="text-white">User Notifications</span>
                            <span class="btn btn-text btn-success btn-sm font-weight-bold btn-font-md ml-2">{{notificationSuperAdmin}} new</span>
                        </h4>
                        <h4 v-if="notification==0"
                            class="d-flex flex-center rounded-top">
                            <span class="text-white">No Notifications</span>
                        </h4>
                    </div>
                    <div  v-if="status==3"
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
                <div  v-if="status==4"
                      class="d-flex flex-column pt-12 bgi-size-cover bgi-no-repeat rounded-top"
                      style="background-image: url(/assets/media/misc/bg-1.jpg)">
                    <!--begin::Title-->
                    <h4 v-if="it>0"
                        class="d-flex flex-center rounded-top">
                        <span class="text-white">User Notifications</span>
                        <span class="btn btn-text btn-success btn-sm font-weight-bold btn-font-md ml-2">{{it}} new</span>
                    </h4>
                    <h4 v-if="it==0"
                        class="d-flex flex-center rounded-top">
                        <span class="text-white">No Notifications</span>
                    </h4>
                </div>

                <div class="tab-content" style="overflow: auto;height: 200px;" v-if="status!==1">
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
                            <a href="#" class="navi-item" :href="'/xadmin/plans/edit?id='+ entry.plan_id" @click="abc(entry)" v-if="status==4">
                                <div class="navi-link" v-if="entry.title=='File download plan'">

                                    <div v-if="entry.status=='new'" class="navi-text">
                                        <div class="font-weight" style="font-weight: 700">  Lesson package of plan {{entry.content}} is ready to download
                                        </div>
                                    </div>
                                    <div v-if="entry.status=='unread'" style="opacity: 0.6" class="navi-text">
                                        <div class="font-weight-bold">  Lesson package of plan {{entry.content}} is ready to download</div>

                                    </div>
                                </div>
                            </a>

                            <!--end::Nav-->
                        </div>

                    </div>
                </div>


            </form>
            <form v-if="status==1">
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
            let self =this;
            let solan=0;
            let notifications= setInterval(function () {
               solan+=1
                $.get('/xadmin/notifications/notification',function (res) {
                    self.entries=res.data.entries;
                    self.status=res.data.status;
                    self.admin=res.data.admin;
                    self.notification=res.data.notification;
                    self.it=res.data.it;
                })
               if(solan===50){
                   clearInterval(notifications);
               }
        
            },15000)
        },

        data() {
            return {
                notificationSuperAdmin:'',
                it:'',
                status:'',
                admin: '',
                notification: '',
                entries: [],
            }
        },
        methods: {
            // async Notification() {
            //
            //     let query = $router.getQuery();
            //     const res = await $get('/xadmin/notifications/notification', query);
            //     this.entries = res.data.entries;
            //     this.notification = res.data.notification;
            //     this.admin = res.data.admin;
            //     this.status=res.data.status;
            //     this.it=res.data.it;
            //     this.notificationSuperAdmin=res.notificationSuperAdmin
            //
            // },
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
        padding:2px;
        position: absolute;
        margin: -20px -16px 10px;
        min-width: 17px;
        min-height: 17px;
        border-radius: 50%;
        background: red;
        color: #f1f1f1;

    }
    .nva{
        position: absolute;
        width: 40px;
        height: 40px;
        margin-top: -19px;
        margin-left: -37px;
        background: #EFF0F6;
        border-radius: 50%;
    }


</style>
