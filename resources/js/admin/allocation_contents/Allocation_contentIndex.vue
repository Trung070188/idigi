<template>
    <div class="container-fluid">
        <ActionBar type="index" :breadcrumbs="breadcrumbs" title="Resource allocation" />
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
                                <p>Are you sure to delete this resource allocation?</p>
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
                <div class="card card-custom card-stretch gutter-b">

                    <div class="card-header border-0 pt-6">

                        <div class="card-title">
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
                                <input @keydown.enter="doFilter($event)" v-model="filter.keyword" type="text"
                                    data-kt-filemanager-table-filter="search"
                                    class="form-control form-control-solid w-250px ps-15" placeholder="Search..."
                                    value="" />
                                <span class="svg-icon svg-icon-2 svg-icon-lg-1 me-0">
                                </span>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                <a :href="'/xadmin/allocation_contents/create'">
                                    <button v-if="permissions['035']" type="button" class="btn btn-primary"
                                        data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer">
                                        <i class="bi bi-plus-lg"></i>New Allocation
                                    </button>
                                </a>

                            </div>

                        </div>
                        <form class="col-lg-12">
                            <div class="row">
                                <div style="margin:7px 3px 0px">
                                    <button type="button" class="btn btn-primary" @click="doFilter()">Search</button>
                                </div>
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

                                    <div v-text="from + '-' + to + ' of ' + countContent" v-if="entries.length > 0"></div>

                                </div>
                            </div>
                        </div>
                        <table class="table table-hover table-row-bordered align-middle gy-4 gs-9">
                            <thead
                                class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                                <tr>
                                    <th class="">Title</th>
                                    <th class="text-center">No. of schools</th>
                                    <th class="text-center">No. of courses</th>
                                    <th class="text-center">No. of units</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="cursor-pointer" v-for="entry in entries" v-on:mouseover="mouseover(entry.id)"
                                    v-on:mouseleave="mouseleave()">
                                    <td @click="edit(entry.id, permissions['036'])">{{ entry.title }}</td>
                                    <td class="text-center" @click="edit(entry.id, permissions['036'])">{{
                                        entry.schools.length }}</td>
                                    <td class="text-center" @click="edit(entry.id, permissions['036'])">{{
                                        entry.courses.length }}</td>
                                    <td class="text-center" @click="edit(entry.id, permissions['036'])">{{
                                        entry.units.length }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-around">
                                            <i :class="'bi bi-trash text-danger cursor-pointer action-btn action-btn-' + entry.id"
                                                v-if="permissions['037']" :title="'Delete ' + entry.title"
                                                @click="removeContent(entry.id)"></i>
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
import { $get, $post, clone, getTimeRangeAll } from "../../utils";
import $router from '../../lib/SimpleRouter';
import ActionBar from "../includes/ActionBar";

let created = getTimeRangeAll();
const $q = $router.getQuery();

export default {
    name: "Allocation_contentIndex.vue",
    components: { ActionBar },
    data() {
        const permissions = clone(window.$permissions)
        return {
            countContent: '',
            permissions,
            entry: '',
            entries: [],
            filter: {
                keyword: $q.keyword || '',
                created: $q.created || created,
            },
            breadcrumbs: [
                {
                    title: 'Resource management'
                },
                {
                    title: 'Resource allocation'
                },
            ],
            limit: 25,
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
        removeContent: function (deleteContent = '') {
            $('#delete').modal('show');
            this.entry = deleteContent;
        },
        edit: function (id, permission) {
            if (permission) {
                window.location.href = "/xadmin/allocation_contents/edit?id=" + id;
            }
        },
        async load() {
            let query = $router.getQuery();
            this.$loading(true);
            const res = await $get('/xadmin/allocation_contents/data', query);
            this.$loading(false);
            this.paginate = res.paginate;
            this.entries = res.data;
            this.countContent = res.countContent;
            setTimeout(function () {
                KTMenu.createInstances();
            }, 0)
            this.from = (this.paginate.currentPage - 1) * (this.limit) + 1;
            this.to = (this.paginate.currentPage - 1) * (this.limit) + this.entries.length;
        },


        async remove() {
            const res = await $post('/xadmin/allocation_contents/remove', { id: this.entry });

            if (res.code) {
                toastr.error(res.message);
            } else {
                toastr.success(res.message);
                $('#delete').modal('hide');
            }

            $router.updateQuery({ page: this.paginate.currentPage, _: Date.now() });
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


        onPageChange(page) {
            $router.updateQuery({ page: page })
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

<style scoped></style>
