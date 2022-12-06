<template>
    <div class="container-fluid">

        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title = "Lessons"/>
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
                            <li v-for="_device in devices"><input type="radio" v-model="device" :value="_device.id"/>
                                <strong>{{ _device.device_name }}</strong></li>
                        </ul>
                    </div>
                    <div class="modal-footer" style="justify-content: center">
                        <button type="button" class="btn btn-primary"
                                @click="downloadLesson" :disabled="lessons.length == 0 || !device || isConfirm == 0"> Download for Windows  <i class="bi bi-windows"></i>
                        </button>
                        <button type="button" class="btn btn-primary"  @click="downloadLesson" :disabled="lessons.length == 0 || !device || isConfirm == 0"
                              >
                            Download for MacOS <i style="margin:-3px 0px 0px" class="bi bi-apple"></i>
                        </button>
                    </div>
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
                                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base" v-if="lessonIds==''">

                                        <button type="button" style="margin-left: 10px" @click="isShowFilter = !isShowFilter" class="btn btn-light" v-if="isShowFilter">
                                            <i style="margin-left: 5px" class="fas fa-times"></i>
                                            Close Advanced Search
                                        </button>
                                        <button type="button" style="margin-left: 10px" @click="isShowFilter = !isShowFilter" class="btn btn-light" v-if="!isShowFilter">
                                            <i class="bi bi-funnel"></i>
                                            Advanced Search
                                        </button>
                                    </div>
                                     <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected" v-if="permissions['011'] && lessonIds!=''">
                                         <span v-if="lessons.length>3" style="color:#f1416c;margin: 0px 10px 0px ">Chỉ được chọn tối đa 3 lesson để tải xuống</span>
												<div class="fw-bolder me-5" v-if="lessons.length<=3">
												<span class="me-2" data-kt-customer-table-select="selected_count"></span>{{ lessonIds.length }} Selected</div>

												<button @click="openModal()" :disabled="lessons.length>3" type="button" class="btn btn-danger" data-kt-customer-table-select="delete_selected">Download Selected</button>

											</div>

                                </div>


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
                                                <switch-button v-model="filter.enabled"></switch-button>
                                            </div>

                                        </div>
                                    </div>
                                    <div style="margin: auto 0">
                                        <button type="button" class="btn btn-primary" @click="doFilter($event)">Search</button>
                                    </div>
                                </form>
                            </div>


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

                                    <div v-text=" from +'-'+ to +' of '+ paginate.totalRecord" v-if="entries.length > 0"></div>

                                    <template v-if="lessonIds.length > 0">
                                        <!-- <span class="svg-icon svg-icon-2x svg-icon-primary mx-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z" fill="black"></path>
                                            </svg>
                                        </span> -->

                                        <div>
                                            <!-- {{ lessonIds.length }} lesson selected -->
                                             <a href="javascript:;" @click="removeAll" style="color: red; margin-left: 10px">clear all</a>
                                        </div>
                                    </template>

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
                                <th>Lesson</th>
                                <th class="">Grade</th>
                                <th class="">Subject</th>
                                <th class="">Active</th>
                                <th class="">Creation Date</th>
                                <th class="">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr  v-for="entry in entries">
                                <td class="">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" v-model="lessonIds" :value="entry.id" @change="updateCheckAll">
                                    </div>
                                </td>
                                <td class="" v-text="entry.id"></td>
                                <td v-text="entry.name"></td>
                                <td class="" v-text="entry.grade"></td>
                                <td class="" v-text="entry.subject"></td>
                                <td class="" v-text="entry.enabled == 0 ? 'No' : 'Yes'"></td>
                                <td class="" v-text=" d(entry.created_at)"></td>
                                <td class="">
                                    <a href="list.html#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                        <span class="svg-icon svg-icon-5 m-0">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black" />
															</svg>
														</span>
                                       </a>

                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-auto py-4" data-kt-menu="true">

                                        <div class="menu-item px-3">
                                            <a @click="openModalEntry(entry)" class="menu-link px-3">Download</a>
                                        </div>
                                        <div class="menu-item px-3" >
                                            <a class="menu-link text-danger px-3" v-if="permissions['012']" @click="remove(entry)" data-kt-subscriptions-table-filter="delete_row">Remove</a>

                                        </div>
                                    </div>
                                </td>
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
    import {$get, $post, getTimeRangeAll, clone} from "../../utils";
    import $router from '../../lib/SimpleRouter';
    import ActionBar from "../includes/ActionBar";

    let created = getTimeRangeAll();
    const $q = $router.getQuery();

    export default {
        name: "LessonsIndex.vue",
        components: {ActionBar},
        data() {
            const permissions = clone(window.$permissions)

            let isShowFilter = false;
            let filter = {
                keyword: $q.keyword || '',
                created: $q.created || '',
                name:$q.name||'',
                subject: $q.subject || '',
                grade: $q.grade || '',
                enabled: $q.enabled || ''
            };
            for (var key in filter) {
                if (filter[key] != '') {
                    isShowFilter = true;
                }
            }
            return {
                permissions,
                device: '',
                devices: [],
                lessonIds: [],
                lessons: [],
                allSelected: false,
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
        },
        methods: {
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
            edit: function (id, event) {
                if (!$(event.target).hasClass('deleted')) {
                    window.location.href = '/xadmin/lessons/edit?id=' + id;
                }

            },
            async load() {
                let query = $router.getQuery();
                this.$loading(true);
                const res = await $get('/xadmin/lessons/data', query);
                this.$loading(false);
                setTimeout(function (){
                    KTMenu.createInstances();
                }, 0)
                this.paginate = res.paginate;
                this.entries = res.data;
                this.from = (this.paginate.currentPage - 1) * (this.limit) + 1;
                this.to = (this.paginate.currentPage - 1) * (this.limit) + this.entries.length;
            },
            async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return;
                }

                const res = await $post('/xadmin/lessons/remove', {id: entry.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }

                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});
            },

            async removeAll() {
                this.lessonIds = [];
                $('input:checkbox').each(function() { this.checked = false; });
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
                $router.updateQuery({page: page})
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
    }

</style>
