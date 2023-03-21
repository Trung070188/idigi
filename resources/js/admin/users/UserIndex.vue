<template>
    <div class="container-fluid">
        <ActionBar type="index" :breadcrumbs="breadcrumbs" title="Manage users" />
        <div class="row">
            <!-- modal xoa nhieu user -->
            <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="delete1" tabindex="-1"
                role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered popup-main-1" role="document" style="max-width: 450px;">
                    <div class="modal-content box-shadow-main paymment-status"
                        style="left:120px;text-align: center; padding: 20px 0px 55px;">
                        <div class="close-popup" data-dismiss="modal"></div>
                        <div class="swal2-icon swal2-warning swal2-icon-show">
                            <div class="swal2-icon-content" style="margin: 0px 24.5px 0px ">!</div>
                        </div>
                        <div class="swal2-html-container">
                            <p>Are you sure to delete this user?</p>
                        </div>
                        <div class="swal2-actions">
                            <button type="submit" id="kt_modal_new_target_submit1"
                                class="swal2-confirm btn fw-bold btn-danger" @click="removeAll">
                                <span class="indicator-label">Yes, delete!</span>
                            </button>
                            <button type="reset" id="kt_modal_new_target_cancel1"
                                class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal"
                                style="margin: 0px 8px 0px">No, cancel</button>

                        </div>

                    </div>
                </div>
            </div>
            <!-- end modal xoa nhieu user -->

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
                            <p>Are you sure to delete this user?</p>
                        </div>
                        <div class="swal2-actions">
                            <button type="submit" id="kt_modal_new_target_submit"
                                class="swal2-confirm btn fw-bold btn-danger" @click="remove(entry)">
                                <span class="indicator-label">Yes, delete!</span>
                            </button>
                            <button type="reset" id="kt_modal_new_target_cancel"
                                class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal"
                                style="margin: 0px 8px 0px">No, cancel</button>

                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="card-header border-0 pt-6">
                        <div class="card-title">

                            <div class="d-flex align-items-center position-relative my-1">


                                <div class="d-flex align-items-center position-relative my-1">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
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
                                    <!--end::Svg Icon-->
                                    <input type="text" data-kt-filemanager-table-filter="search"
                                        class="form-control form-control-solid w-250px ps-15"
                                        @keydown.enter="doFilter($event)" v-model="filter.keyword"
                                        placeholder="Search ID, username, email, role...." value="" />
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
                                v-if="userIds == ''">
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
                                <a v-if="permissions['001']" :href="'/xadmin/users/create'">
                                    <button class="btn btn-primary button-create" style="margin:0 0 0 15px"><i
                                            class="bi bi-person-plus mr-1"></i>New User</button>
                                </a>

                            </div>
                        </div>
                        <div class="d-flex justify-content-end align-items-center d-none"
                            data-kt-customer-table-toolbar="selected" v-if="userIds != '' && permissions['003']">
                            <div class="fw-bolder me-5">
                                <span class="me-2" data-kt-customer-table-select="selected_count"></span>{{ userIds.length
                                }} Selected
                            </div>
                            <button @click="removeAllModal" type="button" class="btn btn-danger"
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
                                <div class="form-group col-lg-3">
                                    <label style="margin-bottom: 2px">Full name </label>
                                    <input @keydown.enter="doFilter('full_name', filter.full_name, $event)"
                                        class="form-control" placeholder="Enter the full name" v-model="filter.full_name" />

                                </div>
                                <div class="form-group col-lg-3">
                                    <label>Email </label>
                                    <input @keydown.enter="doFilter('email', filter.email, $event)" class="form-control"
                                        placeholder="Enter the email" v-model="filter.email">
                                </div>
                                <div class="form-group col-lg-3">
                                    <label>Role </label>
                                    <select @keydown.enter="doFilter('role', filter.role, $event)" required
                                        class="form-control form-select" v-model="filter.role">
                                        <option value="" disabled selected>Choose role</option>
                                        <option value="0">All</option>
                                        <option v-for="role in roles " :value="role.role_name">{{ role.role_name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-3">
                                    <label>Creation date </label>
                                    <Daterangepicker v-model="filter.created" class="active" placeholder="Creation date"
                                        readonly></Daterangepicker>
                                    <span v-if="filter.created !== ''" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0"
                                        @click="filterClear">
                                        <svg type="button" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" style="float: right;margin: -32px 3px 0px;">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                                transform="rotate(-45 6 17.3137)" fill="black" style="fill:red" />
                                            <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                transform="rotate(45 7.41422 6)" fill="black" style="fill:red" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="form-group col-lg-3">
                                    <label>Active</label>
                                    <div>
                                        <switch-button v-model="filter.state"></switch-button>
                                    </div>

                                </div>
                            </div>
                            <div style="margin: auto 0">
                                <button type="button" class="btn btn-primary" @click="doFilter()">Search</button>
                            </div>

                        </form>
                    </div>

                    <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="deleteSuperAdmin"
                        tabindex="-1" role="dialog" aria-labelledby="deleteSuperAdmin" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                            style="max-width: 500px;">
                            <div class="modal-content box-shadow-main paymment-status"
                                style="left:140px;text-align: center; padding: 27px 0px 10px;">
                                <div class="close-popup" data-dismiss="modal"></div>
                                <h3 class="popup-title success">Delete user</h3>
                                <div class="content">
                                    <p>Can not delete Super Administrator account!</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="d-flex flex-stack pt-4 pl-9 pr-9">
                            <!--                           <div  @click="filterClear()">-->
                            <!--                              <span style="float: right; margin:0px 0px -20px">-->
                            <!--                               Refresh list-->
                            <!--                                  <i class="fas fa-sync"></i>-->
                            <!--                              </span>-->
                            <!--                           </div>-->
                            <!--                           <div>-->
                            <!--                              <span style="float: right;margin-bottom: -20px;margin-right: 105px">-->
                            <!--                                Last update at {{d(last_updated)}}-->
                            <!--                              </span>-->
                            <!--                           </div>-->


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
                                    <div v-text="from + '-' + to + ' of ' + userCount" v-if="entries.length > 0"></div>
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
                                    <th class="text-center">No.</th>
                                    <th class="">Username</th>
                                    <th class="">Full Name</th>
                                    <th class="">Email</th>
                                    <th class="">Role</th>
                                    <th class="text-center">Creation Date</th>
                                    <th class="text-center">Active</th>
                                    <th class="text-center" v-if="permissions['003']">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr :class="permissions['002'] ? 'cursor-pointer' : ''" v-for="(entry, index) in entries"
                                    v-on:mouseover="mouseover(entry.id)" v-on:mouseleave="mouseleave()">
                                    <td class="text-center">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" v-model="userIds"
                                                :value="entry.id" @change="updateCheckAll" />
                                        </div>
                                    </td>
                                    <td @click="edit(entry.id, permissions['002'])" :title="'Edit user ' + entry.username"
                                        class="text-center">{{ (index + 1) + (from - 1) }}</td>
                                    <td @click="edit(entry.id, permissions['002'])" :title="'Edit user ' + entry.username"
                                        class="">{{ entry.username }}</td>
                                    <td @click="edit(entry.id, permissions['002'])" :title="'Edit user ' + entry.username"
                                        class="">{{ entry.full_name }}</td>
                                    <td @click="edit(entry.id, permissions['002'])" :title="'Edit user ' + entry.username"
                                        class="">{{ entry.email }}</td>
                                    <td @click="edit(entry.id, permissions['002'])" :title="'Edit user ' + entry.username"
                                        class="">{{ entry.role }}</td>
                                    <td @click="edit(entry.id, permissions['002'])" :title="'Edit user ' + entry.username"
                                        class="text-center">{{ d(entry.created_at) }}</td>
                                    <td class="text-center">

                                        <div
                                            class="form-check form-switch form-check-custom form-check-primary justify-content-center">
                                            <input v-model="entry.state" @change="toggleStatus(entry)"
                                                class="form-check-input" type="checkbox" value=""
                                                id="flexSwitchDefault" :disabled="!permissions['002']">
                                        </div>
                                    </td>
                                    <td class="text-center" v-if="permissions['003']">
                                        <div class="d-flex justify-content-around">
                                            <i :class="'bi bi-trash text-danger cursor-pointer action-btn action-btn-' + entry.id"
                                                :title="'Delete ' + entry.username"
                                                @click="(entry.role == 'Super Administrator') ? modalDevice() : removeUser(entry.id)"></i>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex pl-9 pr-9 mb-8">
                            <div
                                class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                <!--<div class="mr-2">
                                    <label>Records per page:</label>
                                </div>-->
                                <div>
                                    <select class="form-select form-select-sm form-select-solid" v-model="limit"
                                        @change="changeLimit">
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>

                                    </select>
                                </div>
                            </div>
                            <!--<div style="float: right; margin: 10px">-->
                            <div
                                class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
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
import SwitchButton from "../../components/SwitchButton";


let created = getTimeRangeAll();
const $q = $router.getQuery();

export default {
    name: "UsersIndex.vue",
    components: { ActionBar, SwitchButton },
    data() {
        const permissions = clone(window.$permissions)
        let isShowFilter = false;
        let filter = {
            keyword: $q.keyword || '',
            created: $q.created || '',
            full_name: $q.full_name || '',
            email: $q.email || '',
            state: $q.state || '',
            role: $q.role || '',

        };
        // for (var key in filter) {
        //     if (filter[key] != '') {
        //         // isShowFilter = true;
        //     }
        // }
        return {
            userCount: '',
            user: [],
            userIds: [],
            allSelected: false,

            permissions,
            isShowFilter: isShowFilter,
            breadcrumbs: [
                {
                    title: 'Account management'
                },
                {
                    title: 'Manage users'
                },
            ],
            roles: $json.roles || [],
            last_updated: [],
            entries: [],
            filter: filter,
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
        removeAllModal() {
            $('#delete1').modal('show');
        },
        removeUser: function (deleteUser = '') {
            $('#delete').modal('show');
            this.entry = deleteUser;
        },
        modalDevice() {
            $('#deleteSuperAdmin').modal('show');
        },

        edit: function (id, permission) {
            if (permission) {
                window.location.href = '/xadmin/users/edit?id=' + id;
            }
        },

        async load() {

            let query = $router.getQuery();
            this.$loading(true);
            const res = await $get('/xadmin/users/data', query);
            this.$loading(false);
            setTimeout(function () {
                KTMenu.createInstances();
            }, 0)
            this.paginate = res.paginate;
            this.entries = res.data.data;
            this.last_updated = res.data.last_updated;
            this.userCount = res.data.userCount;
            this.from = (this.paginate.currentPage - 1) * (this.limit) + 1;
            this.to = (this.paginate.currentPage - 1) * (this.limit) + this.entries.length;
        },
        async remove() {
            const res = await $post('/xadmin/users/remove', { id: this.entry });

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
            const res = await $post('/xadmin/users/toggleStatus', {
                id: entry.id,
                state: entry.state
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
                this.userIds = selected;
                this.user = this.entries;
            } else {
                this.userIds = [];
                this.user = [];
            }
        },
        updateCheckAll() {
            this.user = [];
            if (this.userIds.length === this.entries.length) {
                this.allSelected = true;
            } else {
                this.allSelected = false;
            }
            let self = this;
            self.userIds.forEach(function (e) {
                self.entries.forEach(function (e1) {
                    if (e1.id == e) {
                        self.user.push(e1);
                    }
                });
            });
        },
        async removeAll() {
            const res = await $post('/xadmin/users/removeAll', { ids: this.userIds });

            if (res.code == 1) {
                $('#delete1').modal('hide');
                $('#deleteSuperAdmin').modal('show');
                this.userIds = [];
                this.user = [];
                this.allSelected = false;

            }
            else {
                toastr.success(res.message);
                this.userIds = [];
                this.user = [];
                this.allSelected = false;
                $('#delete1').modal('hide');
                $router.updateQuery({ page: this.paginate.currentPage, _: Date.now() });
            }
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
select:required:invalid {
    color: #adadad;
}

option[value=""][disabled] {
    display: none;
}

option {
    color: black;
}</style>
