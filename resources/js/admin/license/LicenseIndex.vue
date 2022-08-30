<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title="License Management"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="card-header border-0 pt-6">

                        <div class="card-title">

                            <div class="d-flex align-items-center position-relative my-1">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546"
                                                          height="2" rx="1" transform="rotate(45 17.0365 15.1223)"
                                                          fill="black"></rect>
                                                    <path
                                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                        fill="black"></path>
                                                </svg>
                                            </span>
                                <!--end::Svg Icon-->
                                <input type="text" data-kt-filemanager-table-filter="search"
                                       class="form-control form-control-solid w-250px ps-15"
                                       @keydown.enter="doFilter($event)" v-model="filter.keyword"
                                       placeholder="Search..." value=""/>
                                <span v-if="filter.keyword!==''" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0"
                                      @click="filterClear">
                                            <svg type="button" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" style="margin: 3px -25px 0px;">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                                  transform="rotate(-45 6 17.3137)" fill="black"/>
                                                        <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                              transform="rotate(45 7.41422 6)" fill="black"/>
                                            </svg>
                                        </span>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base"
                                 v-if="schoolIds==''">
                                <button type="button"
                                        @click="isShowFilter = !isShowFilter"
                                        class="btn btn-primary" v-if="isShowFilter"> Close Advanced Search
                                    <i style="margin-left: 5px" class="fas fa-times"></i>

                                </button>
                                <button type="button"
                                        @click="isShowFilter = !isShowFilter"
                                        class="btn btn-primary" v-if="!isShowFilter"> Advanced Search
                                    <i class="fa fa-filter" v-if="!isShowFilter" aria-hidden="true"></i>
                                </button>
                                <a :href="'/xadmin/schools/createLicense'">
                                    <button v-if="permissions['016']" class="btn btn-primary button-create" style="margin:0 0 0 15px"> Create New
                                    </button>
                                </a>
                            </div>
                            <div class="d-flex justify-content-end align-items-center d-none"
                                 data-kt-customer-table-toolbar="selected" v-if=" schoolIds!=''">
                                <div class="fw-bolder me-5">
                                    <span class="me-2" data-kt-customer-table-select="selected_count"></span>{{
                                    schoolIds.length }} Selected
                                </div>
                                <button v-if="permissions['018']"  type="button" class="btn btn-danger"
                                        data-kt-customer-table-select="delete_selected" @click="removeAll">Delete Selected
                                </button>
                            </div>

                        </div>
                        <form class="col-lg-12" v-if="isShowFilter">
                            <div class="row">
                                <div class="form-group col-lg-3">
                                    <label>School name </label>
                                    <input class="form-control" type="text" placeholder="Enter your school name"
                                           v-model="filter.label"/>

                                </div>
                                <div class="form-group col-lg-3">
                                    <label>Administrator name </label>
                                    <input class="form-control" type="text"
                                           placeholder="Enter the administrator name">

                                </div>
                                <div class="form-group col-lg-3">
                                    <label>Region/City </label>
                                    <input class="form-control" type="text" placeholder="Enter the region/city">

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

                            <div class="badge badge-lg badge-light-primary mb-15">
                                <div class="d-flex align-items-center flex-wrap">

                                    <span class="svg-icon svg-icon-2x svg-icon-primary mx-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z"
                                                fill="black"></path>
                                        </svg>
                                    </span>

                                    <div
                                        v-text=" from +'-'+ to +' of '+ paginate.totalRecord "
                                        v-if="entries.length > 0"></div>

                                </div>
                            </div>
                        </div>

                        <table class="table table-row-bordered align-middle gy-4 gs-9">
                            <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                            <tr>
                                <td width="25">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" v-model="allSelected"
                                               @change="selectAll()">
                                    </div>
                                </td>
                                <th class="">ID</th>
                                <th class="">School name</th>
                                <th class="">School address</th>
                                <th class="">Administrator name</th>
                                <th class="">Expire date</th>
                                <th></th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="entry in entries">
                                <td class="">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" v-model="schoolIds"
                                               :value="entry.id" @change="updateCheckAll">
                                    </div>
                                </td>
                                <td class="" v-text="entry.id"></td>
                                <td class="" v-text="entry.label"></td>
                                <td class="" v-text="entry.school_address"></td>
                                <td class="" ></td>
                                <td class="" >{{d(entry.license_to)}}</td>
                                <td class="">
                                    <!--<a :href="'/xadmin/schools/edit?id='+entry.id" style="margin-right: 10px"><i style="font-size:1.3rem" class="fa fa-edit"></i></a>
                                    <a @click="remove(entry)" href="javascript:;" class="btn-trash deleted"><i  class="fa fa-trash mr-1"></i></a>-->

                                    <a v-if="permissions['017']" :href="'/xadmin/schools/editLicense?id='+entry.id">
                                        <button type="button"
                                                class="btn btn-sm btn-icon btn-light btn-active-light-primary">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </a>
                                    <a v-if="permissions['018']" @click="remove(entry)" href="javascript:;">
                                        <button type="button"
                                                class="btn btn-sm btn-icon btn-light btn-active-light-primary">
                                            <i class="fa fa-trash mr-1 deleted"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="d-flex pl-9 pr-9 mb-8">
                            <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                <!--<div class="mr-2">
                                    <label>Records per page:</label>
                                </div>-->
                                <div>
                                    <select class="form-select form-select-sm form-select-solid" v-model="limit" @change="changeLimit">
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>

                                    </select>
                                </div>
                            </div>
                            <!--<div style="float: right; margin: 10px">-->
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
    import {$get, $post, clone, getTimeRangeAll} from "../../utils";
    import $router from '../../lib/SimpleRouter';
    import ActionBar from "../includes/ActionBar";

    let created = getTimeRangeAll();
    const $q = $router.getQuery();

    export default {
        name: "LicenseIndex.vue",
        components: {ActionBar},
        data() {
            const permissions = clone(window.$permissions)
            let isShowFilter = false;
            let filter = {
                keyword: $q.keyword || '',
                label: $q.label || ''
            };
            for (var key in filter) {
                if (filter[key] != '') {
                    isShowFilter = true;
                }
            }
            return {
                permissions,
                schoolIds: [],
                school: [],
                allSelected: false,
                breadcrumbs: [
                    {
                        title: 'License'
                    },
                ],
                entries: [],
                isShowFilter: isShowFilter,
                filter: filter,
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
            edit: function (id, event) {
                if (!$(event.target).hasClass('deleted')) {
                    window.location.href = '/xadmin/schools/edit?id=' + id;
                }

            },
            async load() {
                let query = $router.getQuery();
                this.$loading(true);
                const res = await $get('/xadmin/schools/dataLicense', query);
                this.$loading(false);
                this.paginate = res.paginate;
                this.entries = res.data;
                this.user=res.users;
                console.log(this.user);
                console.log(this.entries);
                this.from = (this.paginate.currentPage - 1) * (this.limit) + 1;
                this.to = (this.paginate.currentPage - 1) * (this.limit) + this.entries.length;
            },
            async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return;
                }

                const res = await $post('/xadmin/schools/removeLicense', {id: entry.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }

                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});
            },
            filterClear() {
                for (var key in this.filter) {
                    this.filter[key] = '';
                }

                $router.setQuery({});
            },
            doFilter(event) {
                if (event) {
                    event.preventDefault();
                }
                $router.setQuery(this.filter)
            },
            changeLimit() {
                let params = $router.getQuery();
                params['page'] = 1;
                params['limit'] = this.limit;
                $router.setQuery(params)
            },

            async toggleStatus(entry) {
                const res = await $post('/xadmin/schools/toggleStatus', {
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
            },
            selectAll() {
                if (this.allSelected) {
                    const selected = this.entries.map((u) => u.id);
                    this.schoolIds = selected;
                    this.school = this.entries
                } else {
                    this.schoolIds = [];
                    this.school = [];
                }

            },
            updateCheckAll() {
                this.school = [];
                if (this.schoolIds.length === this.entries.length) {
                    this.allSelected = true;
                } else {
                    this.allSelected = false;
                }
                let self = this;
                self.schoolIds.forEach(function (e) {
                    self.entries.forEach(function (e1) {
                        if (e1.id == e) {
                            self.school.push(e1);
                        }
                    })
                })
            },
            async removeAll()
            {
                if (!confirm('Xóa bản ghi: ' + JSON.stringify(this.schoolIds))) {
                    return;
                }

                const res = await $post('/xadmin/schools/removeAll', {ids: this.schoolIds});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                    this.schoolIds = [];
                    this.school = [];
                }

                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});

            }
        }
    }
</script>

<style scoped>

</style>
