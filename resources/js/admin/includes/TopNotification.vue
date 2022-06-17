
<!--    <template>-->
<!--        <li class="nav-item dropdown">-->
<!--            <a id=""  href="#"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                <i class="fa fa-bell" id="notify_icon"></i>-->
<!--                <span id="notifiy_num"  ></span>-->
<!--            </a>-->
<!--            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="">-->
<!--                <a class="dropdown-item" v-for="(unread,index) in entries" :key="index">-->
<!--                    <p v-if="unread.type=='App\\Notifications\\InvoicePaid'">yêu cầu xóa thiết bị của {{unread.data.username}}. {{d(unread.created_at)}} </p>-->
<!--                    <p v-if="unread.type=='App\\Notifications\\RequestRoleNotification'">yêu cầu cấp quyền {{unread.data.user_name}}. {{d(unread.created_at)}} </p>-->


<!--                </a>-->
<!--&lt;!&ndash;                <a class="dropdown-item" v-show="unreadnotifications.length==0">No Notifications</a>&ndash;&gt;-->
<!--            </div>-->
<!--        </li>-->
<!--    </template>-->


<!--<script>-->
<!--    import {$get, $post} from "../../utils";-->
<!--    import axios from 'axios';-->
<!--    import $router from "../../lib/SimpleRouter";-->

<!--    export default {-->
<!--        name: "TopNotification",-->
<!--        mounted() {-->
<!--            $router.on('/', this.load).init();-->
<!--        },-->

<!--        data()-->
<!--        {-->
<!--          return {-->
<!--              entries: [],-->

<!--          }-->

<!--        },-->
<!--        methods:{-->
<!--            async load() {-->
<!--                let query = $router.getQuery();-->
<!--                const res  = await $get('/xadmin/notifications/data_notification', query);-->
<!--                this.paginate = res.paginate;-->
<!--                this.entries = res.data.entries;-->
<!--                console.log(this.entries);-->
<!--                this.from = (this.paginate.currentPage - 1) * (this.limit) + 1;-->
<!--                console.log(this.paginate.currentPage);-->
<!--                this.to = (this.paginate.currentPage - 1) * (this.limit) + this.entries.length;-->

<!--            },-->
<!--            // getNotifications(){-->
<!--            //     axios.get('/xadmin/unreadNotifications').then((response) => {-->
<!--            //         this.unreadnotifications = response.data-->
<!--            //     }).catch((errors) => {-->
<!--            //         console.log(errors)-->
<!--            //     });-->
<!--            //     console.log( this.unreadnotifications);-->
<!--            // },-->
<!--            // markAsRead(){-->
<!--            //     axios.get('/xadmin/markAsRead').then((response) => {-->
<!--            //-->
<!--            //     }).catch((errors) => {-->
<!--            //         console.log(errors)-->
<!--            //     });-->
<!--            // },-->
<!--            // show(index)-->
<!--            // {-->
<!--            //-->
<!--            //     var notification_id=this.unreadnotifications[index].id;-->
<!--            //     location.href='/xadmin/notifications/show?id='+notification_id;-->
<!--            // }-->
<!--        },-->
<!--    }-->
<!--</script>-->

<!--<style scoped>-->
<!--    #notify_icon{-->
<!--        margin-right: -16px;-->
<!--        line-height: 25px;-->

<!--    }-->
<!--    #notifiy_num{-->
<!--        text-align: center;-->
<!--        position: absolute;-->
<!--        top: 10px;-->
<!--        right: -7px;-->
<!--        min-width: 16px;-->
<!--        min-height: 16px;-->
<!--        border-radius: 50%;-->
<!--        background: red;-->
<!--        color: #f1f1f1;-->

<!--        font-family: Sans-Serif;-->
<!--    }-->

<!--</style>-->
