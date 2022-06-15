<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"
                   title="Notifications"/>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column" style="height: 563px" >
                        <div   class="row width-full">
                            <div  v-for="entry in entries" v-if="entry.notifiable_id==auth.id" class="col-lg-12 body " >
                                <form  class="form-inline"  >
                                    <div class="form-group mx-sm-3 mb-2" style="">
                                      giáo viên  {{(JSON.parse(entry.data)).username}} yêu cầu xóa thiết bị '{{(JSON.parse(entry.data)).device_name}}'
                                    </div>
                                </form>

                                <div  class="form-group mx-sm-3 mb-2" style="position: absolute;right:65px;margin-top: -33px;" >


                                </div>
                                <div  class="form-group mx-sm-3 mb-2" style="position: absolute;right:65px;margin-top: -33px;" >
                                    <a :href="'/xadmin/users/edit_teacher?id='+(JSON.parse(entry.data)).user_id" >
                                    <button  type="button"
                                             class="btn btn-flex btn-dark  fw-verify " style="margin-right: 5px">
                                        Xem chi tiết
                                    </button>
                                    </a>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<script>
    import {$get, $post, getTimeRangeAll} from "../../utils";
    import $router from '../../lib/SimpleRouter';
    import ActionBar from "../includes/ActionBar";
    import axios from 'axios';

    let created = getTimeRangeAll();
    const $q = $router.getQuery();

    export default {
        name: "NotificationsIndex.vue",
        components: {ActionBar},
        data() {
            return {
                unreadNotifications:{},
                entries: [],
                filter: {
                    keyword: $q.keyword || '',
                    created: $q.created || created,
                },
                breadcrumbs: [
                    {
                        title: 'Notifications'
                    },
                ],
                limit: 25,
                from: 0,
                to: 0,
                // paginate: {
                //     currentPage: 1,
                //     lastPage: 1,
                //     totalRecord: 0
                // }
            }
        },
        mounted() {
            $router.on('/', this.load).init();
        },
        methods: {
            edit: function (id, event){
                if (!$(event.target).hasClass('deleted')){
                    window.location.href='/xadmin/notifications/edit?id='+ id;
                }

            },
            getNotifications(){
                axios.get('unreadNotifications').then((response) => {
                    this.unreadnotifications = response.data
                }).catch((errors) => {
                    console.log(errors)
                });
                console.log( this.unreadnotifications);
            },
            async load() {
                let query = $router.getQuery();
                const res  = await $get('/xadmin/notifications/data', query);
                this.paginate = res.paginate;
                this.entries = res.data;



                console.log(this.entries
                );
                // this.from = (this.paginate.currentPage-1)*(this.limit) + 1;
                // this.to = (this.paginate.currentPage-1)*(this.limit) + this.entries.length;
            },
            async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return;
                }

                const res = await $post('/xadmin/notifications/remove', {id: entry.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }

                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});
            },
            filterClear() {
                for( var key in app.filter) {
                    app.filter[key] = '';
                }

                $router.setQuery({});
            },
            doFilter(field, value, event) {
                if (event) {
                    event.preventDefault();
                }

                const params = {page: 1};
                params[field] = value;
                $router.setQuery(params)
            },
            changeLimit() {
                let params = $router.getQuery();
                params['page']=1;
                params['limit'] = this.limit;
                $router.setQuery(params)
            },

            async toggleStatus(entry) {
                const res = await $post('/xadmin/notifications/toggleStatus', {
                    id: entry.id,
                    status: entry.status
                });

                if (res.code === 200) {
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message);
                }

            },
            onPageChange(page) {
                $router.updateQuery({page: page})
            }
        }
    }
</script>

<style scoped>
    .popup-title{
        margin-left: 160px;
    }
    .content{
        margin: 30px;
    }
    .form-control{
        max-width: 400px;
    }
    .btn btn-danger ito-btn-small{
        padding: 5px;
    }

    .body{
        padding: 40px;
        /*max-width: 1612px;*/
        box-sizing: border-box;
        position: static;
        width: 100%;
        left: 0px;
        top: 0px;
        background: #FFFFFF;
        border: 2px solid #333333;
        border-radius: 44px;
        margin-top: 70px;
    }


</style>
