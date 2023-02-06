<template>
    <div class="container-fluid">

        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title = "Course"/>
        <div class="modal" id="download-lesson" tabindex="-1">
            <div id="overlay">
                <div class="la-3x text">
                    <i class="la la-spinner la-spin"></i>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="card-header border-0 pt-6">


                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"></rect>
                                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black"></path>
                                                </svg>
                                            </span>
                                <!--end::Svg Icon-->
                                <input type="text" data-kt-filemanager-table-filter = "search" class="form-control form-control-solid w-250px ps-15" @keydown.enter="doFilter($event)" v-model="filter.keyword" placeholder="Search..." value="" />
                                <span v-if="filter.keyword!==''" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0" @click="filterClear">
                                            <svg type="button" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin: 3px -25px 0px;">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" style="fill:red" />
                                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" style="fill:red" />
                                            </svg>
                                        </span>

                            </div>

                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base" >

                                <button type="button" style="margin-left: 10px" @click="isShowFilter = !isShowFilter" class="btn btn-light" v-if="isShowFilter">
                                    <i style="margin-left: 5px" class="fas fa-times"></i>
                                    Close Advanced Search
                                </button>
                                <button type="button" style="margin-left: 10px" @click="isShowFilter = !isShowFilter" class="btn btn-light" v-if="!isShowFilter">
                                    <i class="bi bi-funnel"></i>
                                    Advanced Search
                                </button>
                                <a :href="'/xadmin/courses/create'" v-if="permissions['051']">
                                    <button class="btn btn-primary button-create" style="margin:0 0 0 15px"><i class="bi bi-plus-lg"></i>Create new course</button>
                                </a>
                            </div>

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
                                    <label>Name </label>
                                    <input  @keydown.enter="doFilter('name', filter.name, $event)"
                                            class="form-control" placeholder="Enter the lesson name"
                                            v-model="filter.name"/>
                                </div>
                                <div class="form-group col-lg-2">
                                    <label>Subject </label>
                                    <select required class="form-control form-select" v-model="filter.subject" @keydown.enter="doFilter('subject', filter.subject, $event)">
                                        <option value="" disabled selected>Choose Subject</option>
                                        <option value="0">All</option>
                                        <option value="Math">Math</option>
                                        <option value="Science ">Science</option>
                                    </select>

                                </div>
                                <div class="form-group col-lg-2">
                                    <label>Grade </label>
                                    <select required class="form-control form-select" v-model="filter.grade" @keydown.enter="doFilter('grade', filter.grade, $event)">
                                        <option value="" disabled selected>Choose Grade</option>
                                        <option value="0">All</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3">
                                    <label>Creation date </label>
                                    <Daterangepicker v-model="filter.created" class="active"
                                                     placeholder="Creation date" readonly></Daterangepicker>
                                    <span v-if="filter.created!==''" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0" @click="filterClear">
                                            <svg type="button" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" style="float: right;margin: -32px 3px 0px;">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" style="fill:red" />
                                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" style="fill:red" />
                                            </svg>
                                            </span>
                                </div>
                                <div class="form-group col-lg-2">
                                    <label>Active</label>
                                    <div>
                                        <switch-button v-model="filter.active"></switch-button>
                                    </div>

                                </div>
                                <div style="margin: auto 0">
                                    <button type="button" class="btn btn-primary" @click="doFilter()">Search</button>
                                </div>
                            </div>
                        </form>

                    </div>
                    <!--                    <div class="row">-->
                    <!--                        <div class="d-flex align-items-center position-absolute my-1 col-lg-12 ml-10">-->
                    <!--                            <select class="form-control col-lg-2"><option>1</option></select>-->
                    <!--                        </div>-->

                    <!--                    </div>-->



                    <!--<div class="card-body d-flex flex-column">-->
                    <div class="tab-content">
                        <div class="d-flex flex-stack pt-4 pl-9 pr-9">
                            <!--<div class="d-flex flex-stack">-->

                            <div class="badge badge-lg badge-light-dark mb-15">
                                <div class="d-flex align-items-center flex-wrap">
                                    <span
                                        class="svg-icon svg-icon-dark mx-1"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z" fill="black" ></path>
                                        </svg>
                                    </span>

<!--                                    <div v-text=" from +'-'+ to +' of '+ countLesson" v-if="entries.length > 0"></div>-->
                                </div>
                            </div>
                        </div>

                        <!--<table class=" table  table-head-custom table-head-bg table-vertical-center">-->
                        <table class="table table-row-bordered align-middle gy-4 gs-9">
                            <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                            <tr>
                                <td width = "25">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" v-model="allSelected" @change="selectAll()">
                                    </div>
                                </td>
                                <th class="">No.</th>
                                <th>Course name</th>
                                <th class="">Course ID</th>
                                <th class="">Grade</th>
                                <th class="">Subject</th>
                                <th class="">Creation Date</th>
                                <th>Delete</th>
                                <th>Active</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr  v-for="(entry,index) in entries" @click="edit(entry.id)">
                                <td class="">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox"  :value="entry.id" >
                                    </div>
                                </td>
                                <td >{{((index+1)+(from+1))-2}}</td>
                                <td v-text="entry.course_name"></td>
                                <td v-text="entry.id"></td>
                                <td class="" v-text="entry.grade"></td>
                                <td class="" v-text="entry.subject"></td>
                                <td class="" v-text=" d(entry.created_at)"></td>
                                <td>
                                    <i class="bi bi-trash"></i>
                                </td>
                                <td class="" v-text="entry.enabled == 0 ? 'No' : 'Yes'"></td>
                            </tr>
                            </tbody>
                        </table>
                        <!--<div style="margin-top:10px; display: flex">-->
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
    import {$get, $post, getTimeRangeAll,clone} from "../../utils";
    import $router from '../../lib/SimpleRouter';
    import ActionBar from "../includes/ActionBar";
    let created = getTimeRangeAll();
    const $q = $router.getQuery();
    export default {
        name: "CoursesIndex.vue",
        components: {ActionBar},
        data() {
            const permissions = clone(window.$permissions);
            let isShowFilter = false;
            let filter = {
                keyword: $q.keyword || '',
                created: $q.created || '',
            };
            for (var key in filter) {
                if (filter[key] != '') {
                    isShowFilter = true;
                }
            }

            return {
                permissions,
                allSelected: false,
                entries: [],
                filter: filter,
                isShowFilter: isShowFilter,
                breadcrumbs: [
                    {
                        title: 'Resource management'
                    },
                    {
                        title: 'Course'
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

            edit: function (id){
                if(this.permissions['054'])
                {
                    window.location.href='/xadmin/courses/edit?id='+ id;

                }

            },
            async load() {
                let query = $router.getQuery();
                const res  = await $get('/xadmin/courses/data', query);
                this.paginate = res.paginate;
                this.entries = res.data;
                this.from = (this.paginate.currentPage-1)*(this.limit) + 1;
                this.to = (this.paginate.currentPage-1)*(this.limit) + this.entries.length;
            },
            async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return;
                }

                const res = await $post('/xadmin/courses/remove', {id: entry.id});

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
                const res = await $post('/xadmin/courses/toggleStatus', {
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

</style>
