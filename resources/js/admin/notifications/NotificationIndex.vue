<template>
    <div class="container-fluid">
        <ActionBar type="index" :breadcrumbs="breadcrumbs" title="Notifications" />
        <div class="row">
            <div class="col-lg-12">
                <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="delete" tabindex="-1"
                    role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered popup-main-1" role="document" style="max-width: 450px;">
                        <div class="modal-content box-shadow-main paymment-status"
                            style="left:120px;text-align: center; padding: 20px 0px 55px;">
                            <div class="close-popup" data-dismiss="modal"></div>
                            <div class="swal2-icon swal2-warning swal2-icon-show">
                                <div class="swal2-icon-content" style="margin: 0px 24.5px 0px ">!</div>
                            </div>
                            <div class="swal2-html-container">
                                <p>Are you sure to delete this notification?</p>
                            </div>
                            <div class="swal2-actions">
                                <button type="button" id="kt_modal_new_target_submit" class="swal2-confirm btn fw-bold btn-danger" @click="remove(entry)" :disabled="!permissions['025']">
                                    <span class="indicator-label">Yes, delete!</span>
                                </button>
                                <button type="reset" id="kt_modal_new_target_cancel"
                                    class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal"
                                    style="margin: 0px 8px 0px">No, cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none">
                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                                transform="rotate(45 17.0365 15.1223)" fill="black"></rect>
                                            <path
                                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                fill="black"></path>
                                        </svg>
                                    </span>
                                    <input type="text" data-kt-filemanager-table-filter="search"
                                        class="form-control form-control-solid w-250px ps-15"
                                        @keydown.enter="doFilter($event)" v-model="filter.keyword" placeholder="Search..."
                                        value="" />
                                    <span v-if="filter.keyword !== ''" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0"
                                        @click="filterClear">
                                        <svg type="button" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" style="margin: 3px -25px 0px;">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                                transform="rotate(-45 6 17.3137)" fill="black" style="fill:red" />
                                            <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                transform="rotate(45 7.41422 6)" fill="black" style="fill:red" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base"
                                v-if="notificationIds == ''">
                                <button type="button" style="margin-left: 10px" @click="isShowFilter = !isShowFilter"
                                    class="btn btn-light" v-if="isShowFilter">
                                    <i style="margin-left: 5px" class="fas fa-times"></i>
                                    Close Advanced Search
                                </button>
                                <button type="button" style="margin-left: 10px" @click="isShowFilter = !isShowFilter"
                                    class="btn btn-light" v-if="!isShowFilter">
                                    <i class="bi bi-funnel"></i>
                                    Advanced Search
                                </button>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end align-items-center d-none"
                            data-kt-customer-table-toolbar="selected" v-if="notificationIds != ''">
                            <div class="fw-bolder me-5">
                                <span class="me-2" data-kt-customer-table-select="selected_count"></span>{{
                                    notificationIds.length }} Selected
                            </div>
                            <button v-if="permissions['025']" @click="removeAll" type="button" class="btn btn-danger"
                                data-kt-customer-table-select="delete_selected">
                                Delete Selected
                            </button>
                        </div>

                        <form class="col-lg-12" v-if="!isShowFilter">
                            <div class="row">
                                <div style="margin:7px 3px 0px">
                                    <button type="button" class="btn btn-primary" @click="doFilter()">Search</button>
                                </div>
                            </div>
                        </form>

                        <form class="col-lg-12" v-if="isShowFilter">
                            <div class="row">
                                <div class="form-group col-lg-4">
                                    <label>Sender </label>
                                    <input @keydown.enter="doFilter('username', filter.username, $event)"
                                        class="form-control" placeholder="Enter the sender name"
                                        v-model="filter.username" />
                                </div>
                                <div class="form-group col-lg-2">
                                    <label>Creation time </label>
                                    <Daterangepicker placeholder="Creation time" v-model="filter.created"></Daterangepicker>
                                </div>

                            </div>
                            <div style="margin: auto 0">
                                <button type="button" class="btn btn-primary" @click="doFilter($event)">Search
                                </button>
                            </div>
                        </form>

                    </div>
                    <div class="tab-content">
                        <div class="d-flex flex-stack pt-4 pl-9 pr-9">
                            <div class="badge badge-lg badge-light-dark mb-15">
                                <div class="d-flex align-items-center flex-wrap">
                                    <span class="svg-icon svg-icon-dark mx-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none">
                                            <path
                                                d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z"
                                                fill="black"></path>
                                        </svg>
                                    </span>
                                    <div v-text="from + '-' + to + ' of ' + paginate.totalRecord" v-if="entries.length > 0"></div>
                                </div>
                            </div>
                        </div>

                        <table class="table table-hover table-row-bordered align-middle gy-4 gs-9">
                            <thead
                                class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                                <tr>
                                    <td class="text-center" width="25">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" v-model="allSelected"
                                                @change="selectAll()" />
                                        </div>
                                    </td>
                                    <th class="text-center">Time</th>
                                    <th class="">Account</th>
                                    <th class="">Role</th>
                                    <th class="">Content</th>
                                    <th class="text-center" v-if="permissions['025'] && permissionFields['notification_delete']">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr :class="permissions['025'] && permissionFields['notification_view_detail'] ? 'cursor-pointer' : ''" v-for="entry in entries"
                                    v-on:mouseover="mouseover(entry.id)" v-on:mouseleave="mouseleave()">
                                    <td class="text-center">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" v-model="notificationIds"
                                                :value="entry.id" @change="updateCheckAll" />
                                        </div>
                                    </td>
                                    <td @click="detail(entry.url, permissions['025'] && permissionFields['notification_view_detail'])" class="text-center">{{
                                        d(entry.created_at) }}</td>
                                    <td @click="detail(entry.url, permissions['025'] && permissionFields['notification_view_detail'])" class="">{{ entry.username }}</td>
                                    <td @click="detail(entry.url, permissions['025'] && permissionFields['notification_view_detail'])" class="">{{ entry.role }}</td>
                                    <td @click="detail(entry.url, permissions['025'] && permissionFields['notification_view_detail'])" class="">{{ entry.title }}</td>
                                    <td class="text-center" v-if="permissions['025'] && permissionFields['notification_delete']">
                                        <div class="d-flex justify-content-around">
                                            <i :class="'bi bi-trash text-danger cursor-pointer action-btn action-btn-' + entry.id"
                                                :title="'Delete ' + entry.id" @click="removeNotification(entry.id)"></i>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <div class="d-flex pl-9 pr-9 mb-8">
                            <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                <div>
                                    <select class="form-select form-select-sm form-select-solid" v-model="limit"
                                        @change="changeLimit">
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                <div class="dataTables_paginate paging_simple_numbers" id="kt_customers_table_paginate">
                                    <Paginate :value="paginate" :pagechange="onPageChange"></Paginate>
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
import { $get, $post, getTimeRangeAll, clone } from "../../utils";
import $router from '../../lib/SimpleRouter';
import ActionBar from "../includes/ActionBar";

let created = getTimeRangeAll();
const $q = $router.getQuery();

export default {
    name: "NotificationsIndex.vue",
    components: { ActionBar },
    data() {
        const permissions = clone(window.$permissions)
        let permissionFields = $json.permissionFields
        let isShowFilter = false;
        let filter = {
            keyword: $q.keyword || '',
            created: $q.created || '',
            username: $q.username || '',
        };
        for (var key in filter) {
            if (filter[key] != '') {
                isShowFilter = true;
            }
        }
        return {
            entry: '',
            notificationIds: [],
            permissionFields: permissionFields || [],
            notification: [],
            allSelected: false,
            permissions,

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
        removeNotification: function (deleteNotification = '') {
            $('#delete').modal('show');
            this.entry = deleteNotification;
        },
        edit: function (id, event) {
            if (!$(event.target).hasClass('deleted')) {
                window.location.href = '/xadmin/notifications/edit?id=' + id;
            }

        },
        detail: function (url, permission) {
            if (permission) {
                window.location.href = url;
            }
        },
        async load() {
            let query = $router.getQuery();
            const res = await $get('/xadmin/notifications/data', query);
            this.paginate = res.paginate;
            this.entries = res.data.entries;
            setTimeout(function () {
                KTMenu.createInstances();
            }, 0)
            this.from = (this.paginate.currentPage - 1) * (this.limit) + 1;
            console.log(this.paginate.currentPage);
            this.to = (this.paginate.currentPage - 1) * (this.limit) + this.entries.length;

        },
        async remove() {


            const res = await $post('/xadmin/notifications/remove', { id: this.entry });

            if (res.code) {
                toastr.error(res.message);
            } else {
                toastr.success(res.message);
                $('#delete').modal('hide');
            }

            $router.updateQuery({ page: this.paginate.currentPage, _: Date.now() });
        },
        filterClear() {
            for (var key in this.filter) {
                this.filter[key] = '';
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
            $router.updateQuery({ page: page })
        },
        selectAll() {
            if (this.allSelected) {
                const selected = this.entries.map(u => u.id);
                this.notificationIds = selected;
                this.notification = this.entries;
            } else {
                this.notificationIds = [];
                this.notification = [];
            }
        },
        updateCheckAll() {
            this.inventory = [];
            if (this.notificationIds.length === this.entries.length) {
                this.allSelected = true;
            } else {
                this.allSelected = false;
            }
            let self = this;
            self.notificationIds.forEach(function (e) {
                self.entries.forEach(function (e1) {
                    if (e1.id == e) {
                        self.notification.push(e1);
                    }
                });
            });
        },
        async removeAll() {
            if (!confirm('Xóa bản ghi: ' + JSON.stringify(this.notificationIds))) {
                return;
            }

            const res = await $post('/xadmin/notifications/removeAll', { ids: this.notificationIds });

            if (res.code) {
                toastr.error(res.message);
            } else {
                toastr.success(res.message);
                this.notificationIds = [];
                this.notification = [];
            }

            $router.updateQuery({ page: this.paginate.currentPage, _: Date.now() });

        },
        mouseover(id) {

            $('.action-btn-' + id).show();
        },
        mouseleave() {
            $('.action-btn').hide();
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
