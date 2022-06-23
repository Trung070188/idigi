<template>

    <li class="nav-item dropdown">
        <a id="" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-bell " id="notify_icon"></i>

            <span id="notifiy_num" v-show="unreadnotifications.length>0">{{unreadnotifications.length}}</span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="">
            <a class="dropdown-item" v-for="(unread,index) in unreadnotifications" :key="index" :href="unread.url">
                <p v-if="unread.title=='Yêu cầu xóa thiết bị'">{{unread.username}} yêu cầu xóa thiết bị.
                    {{d(unread.created_at)}} </p>
                <p v-if="unread.title=='Yêu cầu cấp quyền'">{{unread.username}} yêu cầu cấp quyền .
                    {{d(unread.created_at)}} </p>
            </a>
            <a class="dropdown-item" v-show="unreadnotifications.length==0">No Notifications</a>

        </div>
    </li>
</template>
<script>
    import axios from 'axios';

    export default {
        name: "TopNotification",

        mounted() {
            this.Notification();
        },

        data() {
            return {
                unreadnotifications: {},
            }
        },
        methods: {
            Notification() {
                console.log(this.entry);
                axios.get('/xadmin/notification').then((response) => {
                    this.unreadnotifications = response.data
                }).catch((errors) => {
                    console.log(errors)
                });
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
