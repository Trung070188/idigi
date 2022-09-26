<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title="Application Settings"/>
        <div class="card card-custom card-stretch gutter-b" v-if="roleName=='Super Administrator'">
            <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="delete" tabindex="-1" role="dialog"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                     style="max-width: 450px;">
                    <div class="modal-content box-shadow-main paymment-status" style="left:140px;text-align: center; padding: 20px 0px 55px;">
                        <div class="close-popup" data-dismiss="modal"></div>
                        <h3 class="popup-title success" style="text-align: center">Delete version</h3>
                        <div class="content">
                            <p style="margin: 25px 0px 25px;">Are you sure to delete this version?</p>
                        </div>
                        <div class="text-center">
                            <button type="reset" id="kt_modal_new_target_cancel" class="btn btn-primary" style="margin: 0px 15px 0px;" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="kt_modal_new_target_submit" class="btn btn-light me-3" @click="remove(entry)">
                                <span class="indicator-label">Delete</span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card-header card-header-stretch border-bottom border-gray-200" >

                <div class="card-title " style="margin: 36px 0px 0px;">
                    <ul class="nav nav-stretch nav-line-tabs border-transparent" role="tablist">
                        <!--begin::Tab nav item-->
                        <li class="nav-item" role="presentation">
                            <a id="kt_billing_6months_tab" class="nav-link fs-5 fw-bold me-3 active" data-bs-toggle="tab" role="tab" href="billing.html#kt_billing_months">WINDOWS</a>
                        </li>
                        <!--end::Tab nav item-->
                        <!--begin::Tab nav item-->
                        <li class="nav-item" role="presentation">
                            <a id="kt_billing_1year_tab" class="nav-link fs-5 fw-bold me-3" data-bs-toggle="tab" role="tab" href="billing.html#kt_billing_year">MAC OS</a>
                        </li>
                        <!--end::Tab nav item-->
                        <!--begin::Tab nav item-->

                        <!--end::Tab nav item-->
                    </ul>
                    <!--end::Tab nav-->

                </div>
                <div class="card-toolbar">
                    <div
                        class="d-flex justify-content-end"
                        data-kt-customer-table-toolbar="base">
                        <a>
                            <button class="btn btn-primary button-create" style="margin:0 0 0 15px"
                                    @click="showModalUpload()" v-if=" appIds=='' && windowIds==''"> Upload Application
                            </button>
                        </a>

                    </div>
                </div>
            </div>


            <div class="tab-content" >
                <div id="kt_billing_months" class="card-body p-0 tab-pane fade show active" role="tabpanel" aria-labelledby="kt_billing_months">
                    <table class="table table-row-bordered align-middle gy-4 gs-9">
                        <thead
                            class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                        <tr>
                            <td>Name</td>
                            <th class="">Version</th>
                            <th class="">Release notes</th>
                            <th class="">Release date</th>
                            <th class="">Status</th>
                            <th class="">Actions</th>


                        </tr>
                        </thead>
                        <tbody>

                        <tr v-for="entry in entries" v-if="entry.type=='window'">
                            <td v-text="entry.name"></td>
                            <td v-text="entry.version"></td>
                            <td  class="css_test" v-text="entry.release_note" @click="showReleaseNote(entry.release_note)"></td>
                            <td> {{ d2(entry.release_date) }}</td>
                            <td style="color:#1aaf21;" class="" v-if="entry.is_default==1">Default</td>
                            <td v-if="entry.is_default==0"></td>
                            <td class="">
                                <a href="list.html#" class="btn btn-light btn-active-light-primary btn-sm"
                                   data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                    <span class="svg-icon svg-icon-5 m-0">
															<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viewBox="0 0 24 24" fill="none">
																<path
                                                                    d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                                    fill="black"/>
															</svg>
														</span>
                                    <!--end::Svg Icon--></a>
                                <!--begin::Menu-->
                                <div
                                    class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                    data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a :href="entry.url" class="menu-link px-3">Download</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a v-if="entry.is_default==0" class="menu-link px-3" @click="showSetDefaultModal(entry.id)">Set as Default</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a @click="removeApp(entry.id)"
                                           data-kt-subscriptions-table-filter="delete_row"
                                           class="menu-link text-danger px-3">Remove</a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
                <div id="kt_billing_year" class="card-body p-0 tab-pane fade" role="tabpanel" aria-labelledby="kt_billing_year">
                    <table class="table table-row-bordered align-middle gy-4 gs-9">
                        <thead
                            class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                        <tr>
                            <td>Application</td>
                            <th class="">Version</th>
                            <th class="">Release notes</th>
                            <th class="">Release date</th>
                            <th class="">Status</th>
                            <th class="">Actions</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="entry in entries" v-if="entry.type=='ios'">
                            <td v-text="entry.name"></td>
                            <td v-text="entry.version"></td>
                            <td class="css_test"  v-text="entry.release_note" @click="showReleaseNote(entry.release_note)"></td>
                            <td> {{ d2(entry.release_date) }}</td>
                            <td style="color:#1aaf21;" v-if="entry.is_default==1">Default</td>
                            <td v-if="entry.is_default==0"></td>
                            <td class="">
                                <a href="list.html#" class="btn btn-light btn-active-light-primary btn-sm"
                                   data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                    <span class="svg-icon svg-icon-5 m-0">
															<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viewBox="0 0 24 24" fill="none">
																<path
                                                                    d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                                    fill="black"/>
															</svg>
														</span>
                                    <!--end::Svg Icon--></a>
                                <!--begin::Menu-->
                                <div
                                    class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                    data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a :href="entry.url" class="menu-link px-3">Download</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a v-if="entry.is_default==0" @click="showSetDefaultModal(entry.id)"
                                           class="menu-link px-3">Set as Default</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a @click="remove(entry)"
                                           data-kt-subscriptions-table-filter="delete_row"
                                           class="menu-link text-danger px-3">Remove</a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>


                <div class="d-flex pl-9 pr-9 mb-8">
                    <div
                        class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                        <!--<div class="mr-2">
                            <label>Records per page:</label>
                        </div>-->
                        <div>
                            <select class="form-select form-select-sm form-select-solid">
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
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal" id="uploadApp" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload App</h5>
                            <button type="button" class="close" data-bs-dismiss="modal">
                                &times;
                            </button>
                        </div>
                        <div class="modal-body">

                            <div id="overlay">
                                <div class="la-3x text">
                                    <i class="la la-spinner la-spin"></i>
                                </div>
                            </div>

                            <form>
                                <div class="form-group">
                                    <label>File <span class="required"></span></label>
                                    <input type="file" ref="uploader" class="form-control-file"
                                           accept=".zip,.rar,.7zip">
                                    <error-label :errors="errors.file_0"></error-label>
                                </div>
                                <div class="form-group">
                                    <label>Update file <span class="required"></span></label>
                                    <input type="file" ref="uploader" class="form-control-file"
                                           accept=".zip,.rar,.7zip">
                                    <error-label :errors="errors.file_0"></error-label>
                                </div>
                                <div class="form-group">
                                    <label>Name <span class="required"></span></label>
                                    <input type="text" v-model="model.name" class="form-control">
                                    <error-label :errors="errors.name"></error-label>
                                </div>

                                <div class="form-group">
                                    <label>Type <span class="required"></span></label>
                                    <select v-model="model.type" class="form-control">
                                        <option value="">---</option>
                                        <option value="ios">Mac OS</option>
                                        <option value="window">window</option>
                                    </select>
                                    <error-label :errors="errors.type"></error-label>
                                </div>
                                <div class="form-group">
                                    <label>Version <span class="required"></span></label>
                                    <input type="text" class="form-control" v-model="model.version">
                                    <error-label></error-label>
                                </div>
                                <div class="form-group">
                                    <label>Release date <span class="required"></span></label>
                                    <Datepicker v-model="model.release_date"/>
                                    <error-label for="f_title" :errors="errors.release_date"></error-label>

                                </div>
                                <div class="form-group">
                                    <label>Release Note <span class="required"></span></label>
                                    <textarea type="text" class="form-control" v-model="model.release_note"/>
                                    <error-label></error-label>
                                </div>
                            </form>

                        </div>
                        <div class="modal-footer" style="justify-content: center">
                            <button type="button" class="btn btn-primary" @click="save">Save</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card card-custom card-stretch gutter-b" v-if="roleName!='Super Administrator'">
            <div class="card-body d-flex flex-column" style="height: 563px">
                <div class="" style="margin-top: 65px; margin-bottom: 50px">
                    <h2 style="text-align: center;font-size: 30px">Tải iDIGI PC cho máy tính</h2>
                    <h5 style="text-align: center;font-size:20px">Ứng dụng đã có mặt trên Windows và MacOS.</h5>
                    <br>
                    <div style="text-align: center;font-size: 14px;">
                        <label>Cài đặt bài giảng số iDIGI thuận lợi và giảng dạy nhanh chóng.
                        </label>
                        <br>
                        <label>Sử dụng trực tuyến (online) và ngoại tuyến (offline) mà không gặp gián
                            đoạn.</label>
                        <br>
                        <label>Bảo mật bài giảng riêng cho thiết bị được đăng ký trước.</label>
                    </div>
                </div>
                <div class="col-lg-12" style="text-align: center;padding: 0 114px;">
                    <div v-for="entry in entries" v-if="entry.type=='window'&& entry.is_default==1" style="">

                        <a :href="entry.url">
                            <button class="btn btn-primary">Download for Windows
                                <i class="bi bi-windows"></i>
                            </button>
                        </a>
                        <br>
                        <label style="margin: 3px 34px 20px;">{{entry.name}}</label>
                    </div>
                    <div v-for="entry in entries" v-if="entry.type=='ios'&& entry.is_default==1" style="">

                        <a :href="entry.url">
                            <button class="btn btn-primary">Download for MacOS
                                <i style="margin:-3px 0px 0px" class="bi bi-apple"></i>
                            </button>
                        </a>
                        <br>
                        <label style="margin: 4px 34px 0px;">{{entry.name}}</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="deviceConfirmLimit" tabindex="-1" role="dialog"
             aria-labelledby="deviceConfirmLimit"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                 style="max-width: 500px;">
                <div class="modal-content box-shadow-main paymment-status" style="left:140px;text-align: center; padding: 27px 0px 10px;">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <div class="content">
                        <p  style="word-break: break-word;margin-right: 20px;margin-left: 20px;}">{{release_note}}</p>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="setDefault" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">Set as Default</h2>
                        <button type="button" class="close" data-bs-dismiss="modal">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure want to set this version as default?
                    </div>
                    <div class="modal-footer" style="justify-content: center">
                        <button type="button" class="btn btn-primary" @click="setDefaultVersion(1)">Yes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<script>
    import {$get, $post, clone, forEach, getTimeRangeAll} from "../../utils";
    import $router from '../../lib/SimpleRouter';
    import ActionBar from "../includes/ActionBar";

    let created = getTimeRangeAll();
    const $q = $router.getQuery();

    export default {
        name: "App_versionsIndex.vue",
        components: {ActionBar},
        data() {
            const permissions = clone(window.$permissions);
            return {
                entry:'',
                roleName:$json.roleName,
                release_note:'',
                permissions,
                window:[],
                macos:[],
                windowIds:[],
                win:[],
                appIds: [],
                app: [],
                allSelected: false,
                windowAllSelected:false,
                model: {
                    type: ''
                },
                role: '',
                errors: {},
                entries: [],
                totalVersionIos: 0,
                totalVersionWindow: 0,
                curVersion: '',
                breadcrumbs: [
                    {
                        title: 'Download Application'
                    },
                ],
            }
        },
        mounted() {
            $router.on('/', this.load).init();
        },
        methods: {
            showReleaseNote(release_note)
            {
                const that=this;
                that.release_note=release_note;
                $('#deviceConfirmLimit').modal('show');
            },
            removeApp:function(deleteApp='')
            {
                $('#delete').modal('show');
                this.entry=deleteApp;
            },

            showSetDefaultModal: function (id) {
                this.curVersion = id;
                $('#setDefault').modal('show');
            },
            showUnsetDefaultModal: function (id) {
                this.curVersion = id;
                $('#unsetDefault').modal('show');
            },

            showModalUpload() {
                $('#uploadApp').modal('show');
            },

            async setDefaultVersion(isDefault) {

                const res = await $post('/xadmin/app_versions/setDefaultVersion', {
                    id: this.curVersion,
                    is_default: isDefault
                });

                $('#setDefault').modal('hide');
                $('#unsetDefault').modal('hide');

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    this.errors = {};
                    $router.on('/', this.load).init();
                    toastr.success(res.message);
                }

            },

            async save() {
                this.errors = {};
                const files = this.$refs.uploader.files;
                const formData = new FormData();
                formData.append('_token', window.$csrf)
                forEach(files, (v, k) => {
                    formData.append(k, v);
                });
                for (const [k, v] of Object.entries(this.model)) {
                    formData.append(k, v);
                }

                for (let i = 0; i < files.length; i++) {
                    formData.append('file_' + i, files[i]);
                }

                $('#overlay').show();
                let res = await fetch('/xadmin/app_versions/save', {
                    method: 'POST',
                    body: formData
                })
                    .then((response) => response.json())
                    .catch((error) => {
                        console.error('Error:', error);
                    });

                $('#overlay').hide();
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
            async load() {
                let query = $router.getQuery();
                const res = await $get('/xadmin/app_versions/data', query);
                this.paginate = res.paginate;
                this.entries = res.data;
                setTimeout(function () {
                    KTMenu.createInstances();
                }, 0)
                this.totalVersionIos = this.entries.filter(e => e.type == 'ios').length;
                this.totalVersionWindow = this.entries.filter(e => e.type == 'window').length;
                this.from = (this.paginate.currentPage - 1) * (this.limit) + 1;
                this.to = (this.paginate.currentPage - 1) * (this.limit) + this.entries.length;
            },
            async remove() {
                const res = await $post('/xadmin/app_versions/remove', {id: this.entry});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                    $('#delete').modal('hide');
                }
                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});
            },
            selectAll() {
                if (this.allSelected) {
                    const selected = this.macos.map((u) => u.id);
                    this.appIds = selected;
                    this.app = this.macos
                } else {
                    this.appIds = [];
                    this.app = [];
                }

            },
            updateCheckAll() {
                this.app = [];
                if (this.appIds.length === this.macos.length) {
                    this.allSelected = true;
                } else {
                    this.allSelected = false;
                }
                let self = this;
                self.appIds.forEach(function (e) {
                    self.macos.forEach(function (e1) {
                        if (e1.id == e) {
                            self.app.push(e1);
                        }
                    })
                })
            },
            WindowSelectAll()
            {
                if (this.windowAllSelected) {
                    const selected = this.window.map((u) => u.id);
                    this.windowIds = selected;
                    this.win = this.window
                } else {
                    this.windowIds = [];
                    this.win = [];
                }
            },
            async removeAll()
            {
                if (!confirm('Xóa bản ghi: ' + JSON.stringify(this.appIds))) {
                    return;
                }

                const res = await $post('/xadmin/app_versions/removeAll', {ids: this.appIds});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                    this.appIds = [];
                    this.app = [];
                }

                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});

            },
            async windowRemoveAll()
            {
                if (!confirm('Xóa bản ghi: ' + JSON.stringify(this.windowIds))) {
                    return;
                }

                const res = await $post('/xadmin/app_versions/windowRemoveAll', {ids: this.windowIds});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                    this.appIds = [];
                    this.app = [];
                }

                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});

            }
        }
    }
</script>

<style scoped>
    .status {
        color: #50cd89;
        background-color: #e8fff3;
        padding: 0.5em 0.85em;
        font-size: .85rem;
        font-weight: 600;
        border-radius: 0.475rem;

    }
    .css_test{
        max-width: 0px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
