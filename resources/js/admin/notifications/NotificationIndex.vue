<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"
                   title="Notification"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <table class=" table  table-head-custom table-head-bg table-vertical-center">
                        <thead>
                        <tr>
                            <th>Username</th>
                            <th>Content</th>
                            <th>Status</th>
                            <th>Creation Date</th>
                            <th>Action</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="entry in entries">
                            <td v-if="entry.title==='Yêu cầu xóa thiết bị'">{{entry.username}}</td>
                            <td v-if="entry.title==='Yêu cầu cấp quyền'">{{entry.username}}</td>
                            <td v-if="entry.title==='Yêu cầu xóa thiết bị'">{{entry.content}}</td>
                            <td v-if="entry.title='Yêu cầu cấp quyền'">{{entry.content}}</td>
                            <td v-if="entry.title==='Yêu cầu xóa thiết bị'">Cấp quyền</td>
                            <td v-if="entry.title==='Yêu cầu cấp quyền'">Xóa thiết bị</td>
                            <td v-text=" d(entry.created_at)"></td>

                            <td v-if="entry.title==='Yêu cầu xóa thiết bị'">
                                <a :href="entry.url"><i style="font-size:1.3rem"
                                                        class="fa fa-trash mr-1 deleted"></i></a>
                            </td>
                            <td v-if="entry.title==='Yêu cầu cấp quyền'">
                                <a :href="entry.url"><i style="font-size:1.3rem"
                                                        class="fa fa-trash mr-1 deleted"></i></a>
                            </td>

                        </tr>

                        </tbody>
                    </table>
                    <div style="margin-top:10px; display: flex">
                        <div class="col-4 form-group d-inline-flex mt-2">
                            <div class="mr-2">
                                <label>Records per page:</label>
                            </div>
                            <div>
                                <select class="form-select form-select-sm " v-model="limit" @change="changeLimit">
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>

                                </select>
                            </div>
                        </div>
                        <div style="float: right">
                            <Paginate :value="paginate" :pagechange="onPageChange"></Paginate>
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

    let created = getTimeRangeAll();
    const $q = $router.getQuery();

    export default {
        name: "NotificationsIndex.vue",
        components: {ActionBar},
        data() {
            return {
                unreadNotifications: {},
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
                limit: $q.limit || 25,
                from: 0,
                to: 0,
                paginate: {
                    currentPage: 1,
                    lastPage: 1,
                    totalRecord: 0
                }


            }
        },
        mounted() {
            $router.on('/', this.load).init();
        },
        methods: {
            edit: function (id, event) {
                if (!$(event.target).hasClass('deleted')) {
                    window.location.href = '/xadmin/notifications/edit?id=' + id;
                }

            },

            async load() {
                let query = $router.getQuery();
                const res = await $get('/xadmin/notifications/data', query);
                this.paginate = res.paginate;
                this.entries = res.data.entries;
                console.log(this.entries);
                this.from = (this.paginate.currentPage - 1) * (this.limit) + 1;
                console.log(this.paginate.currentPage);
                this.to = (this.paginate.currentPage - 1) * (this.limit) + this.entries.length;

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
                for (var key in app.filter) {
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
                params['page'] = 1;
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
    .btn btn-danger ito-btn-small {
        padding: 5px;
    }

    .body {
        padding: 40px;
        /*max-width: 1612px;*/
        box-sizing: border-box;
        position: static;
        width: 100%;
        left: 0px;
        top: 0px;
        background: #FFFFFF;
        border: 1px solid #333333;
        border-radius: 44px;
        margin-top: 70px;
    }


</style>
