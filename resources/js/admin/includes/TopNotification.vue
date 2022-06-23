<template>

    <li class="nav-item dropdown">
        <a id="" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-bell " id="notify_icon"  ></i>

            <span id="notifiy_num" v-show="entries.length>0">{{entries.length}}</span>

        </a>


        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="">
            <a class="dropdown-item" v-for="(unread,index) in entries" :key="index"  @click="abc(unread)">
                <p v-if="unread.title=='Yêu cầu xóa thiết bị'">{{unread.username}} yêu cầu xóa thiết bị.
                    {{d(unread.created_at)}} </p>
                <p v-if="unread.title=='Yêu cầu cấp quyền'">{{unread.username}} yêu cầu cấp quyền .
                    {{d(unread.created_at)}} </p>
            </a>
            <a class="dropdown-item" v-show="entries.length==0">No Notifications</a>

        </div>
    </li>
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
                status:'',
                entries:[],
            }
        },
        methods: {
            async Notification() {

                let query = $router.getQuery();
                this.$loading(true);
                const res = await $get('/xadmin/notifications/notification', query);
                this.$loading(false);
                this.entries = res.data.entries;
                this.status=res.data.status;
                console.log(this.entries);
            },
            async abc(unread) {
                const res = await $post('/xadmin/notifications/toggleStatus', {
                    id: unread.id,
                    status: unread.status}, false);
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
