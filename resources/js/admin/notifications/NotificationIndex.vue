<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                     <div class="card-header border-0 pt-5">
                        <div class="title">
                            <label>Notification</label>
                        </div>
                    </div>
                    <hr>
                    <div class="card-header border-0 pt-5">

                        <div class="row width-full">
                            <div class="col-lg-12">
                                <form class="form-inline">
                                    <div class="form-group mx-sm-3 mb-4">
                                        <input
                                            @keydown.enter="doFilter('keyword', filter.keyword, $event)"
                                            v-model="filter.keyword"
                                            type="text"
                                            class="form-control" placeholder="Search content" value="">
                                    </div>
                                    <div class="form-group mx-sm-3 mb-4">
                                        <button type="button" style="margin-left: 10px"
                                                @click="isShowFilter = !isShowFilter"
                                                class="btn btn-primary" v-if="isShowFilter"> Close Adventure search
                                            <i style="margin-left: 5px" class="fas fa-times"></i>

                                        </button>
                                        <button type="button" style="margin-left: 10px"
                                                @click="isShowFilter = !isShowFilter"
                                                class="btn btn-primary" v-if="!isShowFilter"> Adventure search
                                            <i class="fa fa-filter" v-if="!isShowFilter" aria-hidden="true"></i>

                                        </button>

                                    </div>
                                </form>
                                <form class="col-lg-12" v-if="isShowFilter">
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <label>Sender </label>
                                            <input
                                                @keydown.enter="doFilter('username', filter.username, $event)"
                                                class="form-control" placeholder="Enter the sender name"
                                                v-model="filter.username"
                                            />
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Creation time </label>
                                            <Daterangepicker placeholder="Creation time"
                                            v-model="filter.created"
                                            ></Daterangepicker>
                                        </div>

                                    </div>
                                    <div style="margin: auto 0">
                                        <button type="button" class="btn btn-primary" @click="doFilter($event)">Search
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <table class=" table  table-head-custom table-head-bg table-vertical-center">
                            <thead>
                            <tr>
                                <th>Time</th>
                                <th>Sender</th>
                                <th>Role</th>
                                <th>Notification content</th>
                                <th></th>
                                <th></th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="entry in entries">
                                <td v-text=" d(entry.created_at)"></td>
                                <td v-text="entry.username"></td>
                                <!--                            <td v-if="entry.title==='Yêu cầu xóa thiết bị'">{{entry.username}}</td>-->
                                <!--                            <td v-if="entry.title==='Yêu cầu cấp quyền'">{{entry.username}}</td>-->
                                <td v-text="entry.role"></td>
                                <td v-if="entry.title==='Yêu cầu xóa thiết bị'">Yêu cầu xóa thiết bị</td>
                                <td v-if="entry.title==='Yêu cầu cấp quyền'">Yêu cầu cấp quyền</td>
                                <td></td>
                                <td v-if="entry.title==='Yêu cầu xóa thiết bị'">
                                    <a :href="entry.url">View detail</a>
                                    <a @click="remove(entry)" href="javascript:;" class="btn-trash deleted" style="margin: 0px 10px 0px;"><i
                                        class="fa fa-trash mr-1 deleted"></i></a>
                                </td>
                                <td v-if="entry.title==='Yêu cầu cấp quyền'">
                                    <a :href="entry.url">View detail</a>
                                    <a @click="remove(entry)" href="javascript:;" class="btn-trash deleted" style="margin: 0px 10px 0px;"><i
                                        class="fa fa-trash mr-1 deleted"></i></a>
                                </td>


                            </tr>

                            </tbody>
                        </table>
                        <div style="margin-top:10px; display: flex">
                            <div class="col-4 form-group align-items-center  d-inline-flex mt-2">
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
                            <div style="float: right;margin: 10px">
                                <Paginate :value="paginate" :pagechange="onPageChange"></Paginate>
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

    let created = getTimeRangeAll();
    const $q = $router.getQuery();

    export default {
        name: "NotificationsIndex.vue",
        components: {ActionBar},
        data() {
            let isShowFilter = false;
            let filter = {
                keyword: $q.keyword || '',
                created: $q.created || '',
                username:$q.username || '',
            };
            for (var key in filter) {
                if (filter[key] != '') {
                    isShowFilter = true;
                }
            }
            return {

                unreadNotifications: {},
                entries: [],

                filter: filter,
                breadcrumbs: [
                    {
                        title: 'Notifications'
                    },
                ],
                isShowFilter: isShowFilter,
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
            doFilter() {
                $router.setQuery(this.filter)
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
