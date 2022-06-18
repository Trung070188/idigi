
    <template>
        <li class="nav-item dropdown">
            <a id=""  href="#"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-bell" id="notify_icon"></i>
                <span id="notifiy_num"  v-show="unreadnotifications.length>0" >{{unreadnotifications.length}}</span>

            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="">
                <a class="dropdown-item" v-for="(unread,index) in unreadnotifications" :key="index" @click="show()">
                    <p v-if="unread.type=='App\\Notifications\\InvoicePaid'">yêu cầu xóa thiết bị của {{unread.data.username}}. {{d(unread.created_at)}} </p>
                    <p v-if="unread.type=='App\\Notifications\\RequestRoleNotification'">yêu cầu cấp quyền {{unread.data.user_name}}. {{d(unread.created_at)}} </p>


                </a>
                <a class="dropdown-item" v-show="unreadnotifications.length==0">No Notifications</a>
            </div>
        </li>
    </template>


<script>
    import {$get, $post} from "../../utils";
    import axios from 'axios';
    import $router from "../../lib/SimpleRouter";

    export default {
        name: "TopNotification",
        mounted() {
            this.getNotifications();
            // this.interval = setInterval(function() {
            //     this.getNotifications()
            // }.bind(this), 500);
        },
        data()
        {
          return {
              unreadnotifications:{},


          }

        },
        methods:{

            getNotifications(){
                axios.get('/xadmin/unreadNotifications').then((response) => {
                    this.unreadnotifications = response.data
                }).catch((errors) => {
                    console.log(errors)
                });
                console.log( this.unreadnotifications);
            },
            markAsRead(){
                axios.get('/xadmin/markAsRead').then((response) => {

                }).catch((errors) => {
                    console.log(errors)
                });
            },
            show()
            {
                axios.get('/xadmin/show').then((response) => {

                }).catch((errors) => {
                    console.log(errors)
                });

            }
        },
    }
</script>

<style scoped>
    #notify_icon{
        margin-right: -16px;
        line-height: 25px;

    }
    #notifiy_num{
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
