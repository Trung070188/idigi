<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title = "Teacher Management"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="card-header border-0 pt-6">

                            <div class="card-title">

                               <div
                                class="d-flex align-items-center position-relative my-1"
                            >
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


                                </div>
                                <div class="card-toolbar">
                            <div
                                class="d-flex justify-content-end"
                                data-kt-customer-table-toolbar="base"
                                v-if="teacherIds == ''"
                            >
                                        <button type="button" style="margin:0px 15px 0px"
                                                @click="isShowFilter = !isShowFilter"
                                                class="btn btn-primary" v-if="isShowFilter"> Close Advanced Search
                                            <i style="margin-left: 5px" class="fas fa-times"></i>
                                        </button>
                                        <button type="button" style="margin:0px 15px 0px"
                                                @click="isShowFilter = !isShowFilter"
                                                class="btn btn-primary" v-if="!isShowFilter"> Advanced Search
                                            <i class="fa fa-filter" v-if="!isShowFilter" aria-hidden="true"></i>
                                        </button>
                                <div class="d-flex justify-content-end" data-kt-filemanager-table-toolbar="base">


                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_upload">
                                        <span class="svg-icon svg-icon-2">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
														<path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z" fill="black" />
														<path d="M10.4 3.60001L12 6H21C21.6 6 22 6.4 22 7V19C22 19.6 21.6 20 21 20H3C2.4 20 2 19.6 2 19V4C2 3.4 2.4 3 3 3H9.20001C9.70001 3 10.2 3.20001 10.4 3.60001ZM16 11.6L12.7 8.29999C12.3 7.89999 11.7 7.89999 11.3 8.29999L8 11.6H11V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V11.6H16Z" fill="black" />
														<path opacity="0.3" d="M11 11.6V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V11.6H11Z" fill="black" />
													</svg>
												</span>
                                     Upload Files</button>

                                </div>

                                        <a v-if="permissions['013']" :href="'/xadmin/users/create_teacher'">
                                            <button class="btn btn-primary button-create" style="margin:0 0 0 15px"> Create New</button>
                                        </a>

                                    </div>
                                </div>
                                  <div
                            class="d-flex justify-content-end align-items-center d-none"
                            data-kt-customer-table-toolbar="selected"
                            v-if="teacherIds != ''"
                        >
                            <div class="fw-bolder me-5">
                                <span
                                    class="me-2"
                                    data-kt-customer-table-select="selected_count"
                                ></span
                                >{{ teacherIds.length }} Selected
                            </div>
                            <button
                                v-if="permissions['015']"
                                @click="removeAll"
                                type="button"
                                class="btn btn-danger"
                                data-kt-customer-table-select="delete_selected"
                            >
                                Delete Selected
                            </button>
                        </div>

                                <form class="col-lg-12" v-if="isShowFilter">
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Teacher name </label>
                                            <input @keydown.enter="doFilter('username', filter.full_name, $event)"
                                                   class="form-control" placeholder="Enter the teacher’s name"
                                                   v-model="filter.username"/>

                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Teacher email </label>
                                            <input @keydown.enter="doFilter('email', filter.email, $event)"
                                                   class="form-control" placeholder="Enter the teacher’s email"
                                                   v-model="filter.email">
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Teacher phone number </label>
                                            <input @keydown.enter="doFilter('phone', filter.email, $event)"
                                                   class="form-control" placeholder="Enter the teacher’s phone number"
                                                   v-model="filter.phone">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Creation time </label>
                                            <Daterangepicker v-model="filter.created"
                                                             placeholder="Creation date" readonly></Daterangepicker>
                                            <span v-if="filter.created!==''" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0" @click="filterClear">
                                            <svg type="button" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" style="float: right;margin: -32px 3px 0px;">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" style="fill:red"/>
                                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" style="fill:red" />
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


                    <div class="tab-content">
                        <div class="d-flex flex-stack pt-4 pl-9 pr-9">

                            <div class="badge badge-lg badge-light-primary mb-15">
                                <div class="d-flex align-items-center flex-wrap">

                                    <span class="svg-icon svg-icon-2x svg-icon-primary mx-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z" fill="black"></path>
                                        </svg>
                                    </span>

                                    <div v-text="from +'-'+ to +' of '+ paginate.totalRecord " v-if="entries.length > 0"></div>

                                </div>
                            </div>
                        </div>

                        <table class="table table-row-bordered align-middle gy-4 gs-9">
                            <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                            <tr>
                                 <td width="25">
                                        <div
                                            class="form-check form-check-sm form-check-custom form-check-solid"
                                        >
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                v-model="allSelected"
                                                @change="selectAll()"
                                            />
                                        </div>
                                    </td>
                                <th class="">No.</th>
                                <th class="">Teacher's name</th>
                                <th class="">Email</th>
                                <th class="">Class</th>
                                <th class="">Phone number</th>
                                <th class="">Registered devices</th>
                                <th class="">Creation Date</th>
                                <th class="">Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody v-for="entry in entries">

                            <tr v-for="teacher in entry.roles" >
                                 <td class="">
                                        <div
                                            class="form-check form-check-sm form-check-custom form-check-solid"
                                        >
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                v-model="teacherIds"
                                                :value="entry.id"
                                                @change="updateCheckAll"
                                            />
                                        </div>
                                    </td>
                                <td class="" v-text="entry.id"></td>
                                <td class="" v-text="entry.full_name"></td>
                                <td class="" v-text="entry.email"></td>
                                <td class="" v-text="entry.class"></td>
                                <td class="" v-text="entry.phone"></td>
                                <td class="">{{entry.user_devices.length}} / 3</td>
                                <td class="" v-text=" d(entry.created_at)"></td>
                                <td class="" v-text="entry.state===0 ? 'No' : 'Yes'"></td>
                                <td class="">
                                    <!--<a v-if="permissions['014']" :href="'/xadmin/users/edit_teacher?id='+entry.id"><i style="font-size:1.3rem"
                                                                                            class="fa fa-edit"></i></a>
                                    <a v-if="permissions['015']" @click="remove(entry)" href="javascript:;" class="btn-trash deleted"><i
                                        class="fa fa-trash mr-1 deleted"></i></a>-->

                                    <a v-if="permissions['014']" :href="'/xadmin/users/editTeacher?id='+entry.id">
                                        <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </a>
                                    <a v-if="permissions['015']" @click="remove(entry)" href="javascript:;">
                                        <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary">
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
        <div class="modal fade" id="kt_modal_upload" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Form-->
                    <form class="form" action="https://preview.keenthemes.com/metronic8/demo1/apps/file-manager/none" id="kt_modal_upload_form">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">Upload files</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                <span class="svg-icon svg-icon-1">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
																<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
															</svg>
														</span>
                                <!--end::Svg Icon-->
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body pt-10 pb-15 px-lg-17">
                            <!--begin::Input group-->
                            <div class="form-group">
                                <!--begin::Dropzone-->
                                <div class="dropzone dropzone-queue mb-2" id="kt_modal_upload_dropzone">
                                    <!--begin::Controls-->
                                    <div class="dropzone-panel mb-4">
                                        <label>File <span class="required"></span></label>
                                        <input type="file" ref="uploader" class="form-control-file">
                                        <error-label ></error-label>
                                    </div>
                                    <!--end::Controls-->
                                    <!--begin::Items-->
                                    <div class="dropzone-items wm-200px">
                                        <div class="dropzone-item p-5" style="display:none">
                                            <!--begin::File-->
                                            <div class="dropzone-file">
                                                <div class="dropzone-filename text-dark" title="some_image_file_name.jpg">
                                                    <span data-dz-name="">some_image_file_name.jpg</span>
                                                    <strong>(
                                                        <span data-dz-size="">340kb</span>)</strong>
                                                </div>
                                                <div class="dropzone-error mt-0" data-dz-errormessage=""></div>
                                            </div>
                                        </div>
                                    </div>

                                   <div v-if="code==2" >
                                       <div style="color: #f1416c">File error : <a :href="validateFile">exports</a> </div>

                                   </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="justify-content: center">
                                <button type="button" class="btn btn-primary" @click="saveImportTeacher">Save</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                        <!--end::Modal body-->
                    </form>
                    <!--end::Form-->
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import {$get, $post, getTimeRangeAll, clone, forEach} from "../../utils";
    import $router from '../../lib/SimpleRouter';
    import ActionBar from "../includes/ActionBar";
    import SwitchButton from "../../components/SwitchButton";


    let created = getTimeRangeAll();
    const $q = $router.getQuery();

    export default {
        name: "TeacherIndex.vue",
        components: {ActionBar, SwitchButton},
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
            for (var key in filter) {
                if (filter[key] != '') {
                    isShowFilter = false;
                }
            }
            return {
                code:0,
                validateFile:'',
                allSelected:false,
                teacherIds:[],
                teacher:[],
                permissions,
                isShowFilter: isShowFilter,
                breadcrumbs: [
                    {
                        title: 'Teachers'
                    },
                ],
                roles: $json.roles || [],
                entries: [],
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

            // edit: function (id, event){
            //     if (!$(event.target).hasClass('deleted')) {
            //         window.location.href = '/xadmin/users/edit?id=' + id;
            //     }
            // },
            async load() {
                let query = $router.getQuery();
                this.$loading(true);
                const res = await $get('/xadmin/users/dataTeacher', query);
                this.$loading(false);
                this.entries = res.data;
                this.paginate = res.paginate;
                this.from = (this.paginate.currentPage - 1) * (this.limit) + 1;
                this.to = (this.paginate.currentPage - 1) * (this.limit) + this.entries.length;
            },
            async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return false;
                }

                const res = await $post('/xadmin/users/remove', {id: entry.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }

                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});
            },
            async saveImportTeacher()
            {
                this.errors = {};
                const files = this.$refs.uploader.files;
                const formData = new FormData();
                formData.append('_token', window.$csrf)
                forEach(files, (v, k) => {
                    formData.append(k, v);
                });

                for (let i = 0; i < files.length; i++) {
                    formData.append('file_' + i, files[i]);
                }

                $('#overlay').show();
                let res = await fetch('/xadmin/users/saveImportTeacher', {
                    method: 'POST',
                    body: formData
                })

                    .then((response) => response.json())
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                if(res.code==2)
                {
                    this.code=res.code;
                    this.validateFile=res.file;

                }


                if (res.code) {
                    this.errors = res.errors;
                } else {
                    $('#uploadApp').modal('hide');
                    this.model = {
                        type: ''
                    }
                    this.$refs.uploader.value = null;
                    $router.on('/', this.load).init();
                    toastr.success(res.message);
                }
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
                const selected = this.entries.map(u => u.id);
                this.teacherIds = selected;
                this.teacher = this.entries;
            } else {
                this.teacherIds = [];
                this.teacher = [];
            }
        },
        updateCheckAll() {
            this.teacher = [];
            if (this.teacherIds.length === this.entries.length) {
                this.allSelected = true;
            } else {
                this.allSelected = false;
            }
            let self = this;
            self.teacherIds.forEach(function(e) {
                self.entries.forEach(function(e1) {
                    if (e1.id == e) {
                        self.teacher.push(e1);
                    }
                });
            });
        },
         async removeAll()
            {
                if (!confirm('Xóa bản ghi: ' + JSON.stringify(this.teacherIds))) {
                    return;
                }

                const res = await $post('/xadmin/users/removeAll', {ids: this.teacherIds});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }
                this.teacherIds = [];
                this.teacher = [];

                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});

            }
        }
    }
</script>

<style scoped>

</style>
