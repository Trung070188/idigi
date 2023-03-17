<template>
    <div class="container-fluid">

        <ActionBar type="index" :breadcrumbs="breadcrumbs" title="Lessons" />
        <div class="modal" id="download-lesson" tabindex="-1">
            <div id="overlay">
                <div class="la-3x text">
                    <i class="la la-spinner la-spin"></i>
                </div>
            </div>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Download Lesson</h5>
                        <button type="button" class="close" data-bs-dismiss="modal">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn đang lựa chọn để tải về các bài học:</p>
                        <ul>
                            <li v-for="lesson in lessons"><strong style="word-break: break-word;">{{ lesson.name
                            }}</strong></li>

                        </ul>
                        <p>Hãy chọn thiết bị để tải về các bài học này:</p>
                        <ul class="device">
                            <li v-for="_device in devices"><input type="radio" v-model="device" :value="_device.id" />
                                <strong>{{ _device.device_name }}</strong>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer" style="justify-content: center">
                        <button type="button" class="btn btn-primary" @click="downloadLesson"
                            :disabled="lessons.length == 0 || !device || isConfirm == 0"> Download <i
                                class="bi bi-download mb-1 ml-1"></i>
                        </button>
                        <!--                        <button type="button" class="btn btn-primary"  @click="downloadLesson" :disabled="lessons.length == 0 || !device || isConfirm == 0"-->
                        <!--                              >-->
                        <!--                            Download for MacOS <i style="margin:-3px 0px 0px" class="bi bi-apple"></i>-->
                        <!--                        </button>-->
                    </div>
                </div>
            </div>
        </div>

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
                                <p>Are you sure to delete this lesson?</p>
                            </div>
                            <div class="swal2-actions">
                                <button type="submit" id="kt_modal_new_target_submit"
                                    class="swal2-confirm btn fw-bold btn-danger" @click="remove(lessonToDelete)">
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
                                    class="form-control form-control-solid w-250px ps-15" v-model="filter.keyword"
                                    placeholder="Search..." value="" />
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
                                <select v-if="roleName == 'School Admin'"
                                    class="form-control form-select w-250px ps-15 ml-5" v-model="schoolLesson"
                                    @change="checkSchool">
                                    <option value="null">All school</option>
                                    <option v-for="school in schools" :value="school.id">{{ school.label }}</option>
                                </select>
                            </div>

                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base"
                                v-if="lessonIds == ''">

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
                            <div class="d-flex justify-content-end align-items-center d-none"
                                data-kt-customer-table-toolbar="selected" v-if="permissions['011'] && lessonIds != ''">
                                <span v-if="lessons.length > 3" style="color:#f1416c;margin: 0px 10px 0px ">Chỉ được chọn
                                    tối
                                    đa 3 lesson để tải xuống</span>
                                <div class="fw-bolder me-5" v-if="lessons.length <= 3">
                                    <span class="me-2" data-kt-customer-table-select="selected_count"></span>{{
                                        lessonIds.length }} lesson
                                </div>

                                <button @click="openModal()" :disabled="lessons.length > 3" type="button"
                                    class="btn btn-danger" data-kt-customer-table-select="delete_selected">Download
                                    lesson</button>

                            </div>
                            <a :href="'/xadmin/lessons/create'">
                                <button class="btn btn-primary button-create" style="margin:0 0 0 15px"><i
                                        class="bi bi-plus-lg"></i>Create new lesson</button>
                            </a>
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
                                    <input class="form-control" placeholder="Enter the lesson name" v-model="filter.name" />
                                </div>
                                <div class="form-group col-lg-2">
                                    <label>Subject </label>
                                    <select required class="form-control form-select" v-model="filter.subject"
                                        @change="changeSubject">
                                        <option value="" disabled selected>Choose Subject</option>
                                        <option value="0">All</option>
                                        <option value="Math">Math</option>
                                        <option value="Science">Science</option>
                                    </select>

                                </div>
                                <div class="form-group col-lg-2">
                                    <label>Unit </label>
                                    <select required class="form-control form-select" v-model="filter.unit">
                                        <option value="" disabled selected>Choose unit</option>
                                        <option value="">All</option>
                                        <option :value="unit.id" v-for="unit in units" v-text="unit.unit_name"></option>

                                    </select>
                                </div>
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
                                <div class="form-group col-lg-2">
                                    <label>Active</label>
                                    <div>
                                        <switch-button v-model="filter.enabled"></switch-button>
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
                                    <span class="svg-icon svg-icon-dark mx-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none">
                                            <path
                                                d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z"
                                                fill="black"></path>
                                        </svg>
                                    </span>

                                    <div v-text="from + '-' + to + ' of ' + countLesson" v-if="entries.length > 0"></div>

                                    <template v-if="lessonIds.length > 0">
                                        <!-- <span class="svg-icon svg-icon-2x svg-icon-primary mx-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z" fill="black"></path>
                                            </svg>
                                        </span> -->

                                        <div>
                                            <!-- {{ lessonIds.length }} lesson selected -->
                                            <a href="javascript:;" @click="removeAll"
                                                style="color: red; margin-left: 10px">clear all</a>
                                        </div>
                                    </template>

                                </div>
                            </div>
                        </div>

                        <!--<table class=" table  table-head-custom table-head-bg table-vertical-center">-->
                        <table class="table table-hover table-row-bordered align-middle gy-4 gs-9">
                            <thead
                                class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                                <tr>
                                    <td width="25" class="text-center">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" v-model="allSelected"
                                                @change="selectAll()">
                                        </div>
                                    </td>
                                    <th class="text-center">No.</th>
                                    <th class="">Lesson name</th>
                                    <th class="text-center">Lesson ID</th>
                                    <th class="">Unit</th>
                                    <th class="">Subject</th>
                                    <th class="text-center">Downloaded</th>
                                    <th class="text-center">Creation Date</th>
                                    <th class="text-center" v-if="permissions['043']">Active</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="user.active_allocation == 0">
                                    <td valign="top" colspan="10" class="text-center"
                                        style="font-size: 15px;font-weight: bold">Resource allocation has been deactivated
                                        by {{ user.full_name_active_content }}.</td>
                                </tr>
                                <tr v-if="user.active_allocation == 1 && entries.length == 0">
                                    <td valign="top" colspan="10" class="text-center">No results found. Try different
                                        keywords or remove search filters.</td>
                                </tr>

                                <tr class="cursor-pointer" v-for="(entry, index) in entries"
                                    v-if="user.active_allocation == 1 && entries.length > 0"
                                    v-on:mouseover="mouseover(entry.id)"
                                    v-on:mouseleave="mouseleave()">
                                    <td class="text-center">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" v-model="lessonIds"
                                                :value="entry.id" @change="updateCheckAll">
                                        </div>
                                    </td>
                                    <td class="text-center" @click="edit(entry.id)">{{ ((index + 1) + (from +
                                        1)) - 2 }}</td>
                                    <td class="" @click="edit(entry.id)" v-text="entry.name"></td>
                                    <td class="text-center" @click="edit(entry.id)" v-text="entry.id"></td>
                                    <td class="" @click="edit(entry.id)"
                                        v-text="entry.unit ? entry.unit.unit_name : ''"></td>
                                    <td class="" @click="edit(entry.id)" v-text="entry.subject"></td>
                                    <td class="text-center" @click="edit(entry.id)"
                                        v-text="entry.total_download"></td>
                                    <td class="text-center" @click="edit(entry.id)"
                                        v-text="d(entry.created_at)"></td>
                                    <td class="text-center" v-if="permissions['043']">
                                        <div
                                            class="form-check form-switch form-check-custom form-check-primary justify-content-center">
                                            <input v-model="entry.enabled" @change="toggleStatus(entry)"
                                                class="form-check-input" type="checkbox" value="" id="flexSwitchDefault">
                                        </div>
                                    </td>
                                    <td class="text-center ">
                                        <div class="d-flex justify-content-around">
                                            <i :class="'bi bi-download text-success cursor-pointer action-btn action-btn-' + entry.id" :title="'Download ' + entry.name"
                                                @click="openModalEntry(entry)"></i>
                                            <i :class="'bi bi-trash text-danger cursor-pointer action-btn action-btn-' + entry.id" v-if="permissions['012']"
                                                :title="'Delete ' + entry.name" @click="deleteLession(entry)"></i>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!--<div style="margin-top:10px; display: flex">-->
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

let created = getTimeRangeAll();
const $q = $router.getQuery();

export default {
    name: "LessonsIndex.vue",
    components: { ActionBar },
    data() {
        const permissions = clone(window.$permissions)

        let isShowFilter = false;
        let filter = {
            keyword: $q.keyword || '',
            created: $q.created || '',
            name: $q.name || '',
            subject: $q.subject || '',
            unit: $q.unit || '',
            enabled: $q.enabled || '',
        };
        for (var key in filter) {
            if (filter[key] != '') {
                isShowFilter = true;
            }
        }
        return {
            countLesson: '',
            user: {},
            roleName: '',
            idSchool: null,
            schoolLesson: null,
            schools: [],
            permissions,
            device: '',
            devices: [],
            lessonIds: [],
            lessons: [],
            allUnit: [],
            units: [],
            allSelected: false,
            lessonToDelete: '',
            breadcrumbs: [
                {
                    title: 'Resource management'
                },
                {
                    title: 'Lessons'
                },
            ],
            entries: [],
            filter: filter,
            isShowFilter: isShowFilter,
            limit: $q.limit || 25,
            from: 0,
            to: 0,
            paginate: {
                currentPage: 1,
                lastPage: 1,
                totalRecord: 0
            },
            isConfirm: 0,
        }
    },
    mounted() {
        $router.on('/', this.load).init();
        let self = this;
        $.get('/xadmin/user_devices/getDeviceByUser', function (res) {
            self.devices = res;
        });
        $.get('/xadmin/units/getUnits', function (res) {
            self.allUnit = res;
            if (self.filter.subject && self.filter.subject != 0) {
                self.units = self.allUnit.filter(e => e.subject == self.filter.subject);
            } else {
                self.units = self.allUnit;
            }
        });

    },
    methods: {
        changeSubject() {
            if (this.filter.subject && this.filter.subject != 0) {
                this.units = this.allUnit.filter(e => e.subject == this.filter.subject);
                this.filter.unit = '';
            } else {
                this.units = this.allUnit;
                console.log(this.filter.unit)
            }

        },
        edit: function (id) {
            window.location.href = '/xadmin/lessons/edit?id=' + id;
        },
        checkSchool() {
            this.idSchool = this.schoolLesson;

            this.load()
        },
        openModalEntry(entry) {
            this.isConfirm = 1;
            this.lessons = [entry];
            this.lessonIds = [entry.id];
            $('#download-lesson').modal('show');
        },

        async downloadLesson() {
            this.isConfirm = 0;
            $('#overlay').show();
            const res = await $post('/xadmin/lessons/downloadLesson', {
                csrf: window.$csrf,
                lessonIds: this.lessonIds,
                device: this.device
            });

            window.location.href = res.url;
            $('#overlay').hide();
            $('#download-lesson').modal('hide');

        },

        openModal() {
            this.isConfirm = 1;
            if (this.lessons.length > 3) {
                alert('Bạn chỉ được chọn tối đa 3 lesson');
                return false;
            }
            $('#download-lesson').modal('show');
        },
        selectAll() {
            if (this.allSelected) {
                const selected = this.entries.map((u) => u.id);
                this.lessonIds = selected;
                this.lessons = this.entries
            } else {
                this.lessonIds = [];
                this.lessons = [];
            }

        },
        updateCheckAll() {
            this.lessons = [];
            if (this.lessonIds.length === this.entries.length) {
                this.allSelected = true;
            } else {
                this.allSelected = false;
            }
            let self = this;
            self.lessonIds.forEach(function (e) {
                self.entries.forEach(function (e1) {
                    if (e1.id == e) {
                        self.lessons.push(e1);
                    }
                })
            })
        },
        async load() {
            let query = $router.getQuery();
            this.$loading(true);
            const res = await $get('/xadmin/lessons/data?schoolLesson=' + this.idSchool, query);
            this.$loading(false);
            setTimeout(function () {
                KTMenu.createInstances();
            }, 0)
            this.paginate = res.paginate;
            // this.entries=res.data;
            this.entries = [...(new Set(res.data))];
            this.schools = res.schools;
            this.roleName = res.roleName;
            this.user = res.user;
            this.countLesson = res.countLesson;
            this.from = (this.paginate.currentPage - 1) * (this.limit) + 1;
            this.to = (this.paginate.currentPage - 1) * (this.limit) + this.entries.length;
        },

        async remove(entry) {
            const res = await $post('/xadmin/lessons/remove', { id: entry.id });

            if (res.code) {
                toastr.error(res.message);
            } else {
                toastr.success(res.message);
                $('#delete').modal('hide');
            }

            $router.updateQuery({ page: this.paginate.currentPage, _: Date.now() });
        },

        async removeAll() {
            this.lessonIds = [];
            $('input:checkbox').each(function () { this.checked = false; });
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
            const res = await $post('/xadmin/lessons/toggleStatus', {
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
        deleteLession(entry) {
            $('#delete').modal('show');
            this.lessonToDelete = entry;
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
ul.device {
    list-style-type: none;
}

select:required:invalid {
    color: #adadad;
}

option[value=""][disabled] {
    display: none;
}

option {
    color: black;
}</style>
