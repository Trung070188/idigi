<template>
    <div class="container-fluid">
        <ActionBar type="index" :breadcrumbs="breadcrumbs" title="Manage applications version" />
        <div class="card card-custom card-stretch gutter-b" v-if="roleName == 'Super Administrator'">
            <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="delete" tabindex="-1"
                role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered popup-main-1" role="document" style="max-width: 450px;">
                    <div class="modal-content box-shadow-main paymment-status" style="padding: 0px 0px 30px;">
                        <div class="close-popup" data-dismiss="modal"></div>
                        <div class="swal2-icon swal2-warning swal2-icon-show">
                            <div class="swal2-icon-content" style="margin: 0px 24.5px 0px ">!</div>
                        </div>
                        <div class="swal2-html-container">
                            <p>Are you sure to delete this version?</p>
                        </div>
                        <div class="swal2-actions">
                            <button type="submit" id="kt_modal_new_target_submit"
                                class="swal2-confirm btn fw-bold btn-danger" @click="remove(entry)">
                                <span class="indicator-label">Yes, delete!</span>
                            </button>
                            <button type="reset" id="kt_modal_new_target_cancel"
                                class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal"
                                style="margin: 0px 8px 0px">No, cancel
                            </button>

                        </div>

                    </div>
                </div>
            </div>

            <div class="card-header card-header-stretch border-bottom border-gray-200">
                <div class="card-title " style="margin: 36px 0px 0px;">
                    <ul class="nav nav-stretch nav-line-tabs border-transparent" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a id="kt_billing_6months_tab" class="nav-link fs-5 fw-bold me-3 active" data-bs-toggle="tab"
                                role="tab" href="billing.html#kt_billing_months">WINDOWS</a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a id="kt_billing_1year_tab" class="nav-link fs-5 fw-bold me-3" data-bs-toggle="tab" role="tab"
                                href="billing.html#kt_billing_year">MAC OS</a>
                        </li>
                    </ul>
                </div>

                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <button class="btn btn-primary button-create" style="margin:0 0 0 15px" @click="showModalUpload()"
                            v-if="appIds == '' && windowIds == '' && permissions['022'] && permissionFields['app_create_new']">
                            <i class="bi bi-upload mr-1"></i>New Application
                        </button>
                    </div>
                </div>
            </div>

            <div class="tab-content">
                <div id="kt_billing_months" class="card-body p-0 tab-pane fade show active" role="tabpanel"
                    aria-labelledby="kt_billing_months">
                    <table class="table table-hover table-row-bordered align-middle gy-4 gs-9">
                        <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                            <tr>
                                <th class="text-center">Version</th>
                                <th class="">Release notes</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="entry in entries" v-if="entry.type == 'Window'" v-on:mouseover="mouseover(entry.id)"
                                v-on:mouseleave="mouseleave()">
                                <td class="text-center">{{ entry.version }}</td>
                                <td class="cursor-pointer" @click="showReleaseNote(entry.release_note)">{{
                                    entry.release_note }}</td>
                                <td class="text-center">
                                    <span v-if="entry.is_default == 0" class="badge badge-light-warning">Draft</span>
                                    <span v-if="entry.is_default == 1" class="badge badge-light-success">Release</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-around">
                                        <i :class="'text-primary cursor-pointer action-btn action-btn-' + entry.id"
                                            v-if="entry.is_default == 0" title="Release this version"
                                            @click="showSetDefaultModal(entry.id)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-rocket-takeoff" viewBox="0 0 16 16">
                                                <path
                                                    d="M9.752 6.193c.599.6 1.73.437 2.528-.362.798-.799.96-1.932.362-2.531-.599-.6-1.73-.438-2.528.361-.798.8-.96 1.933-.362 2.532Z" />
                                                <path
                                                    d="M15.811 3.312c-.363 1.534-1.334 3.626-3.64 6.218l-.24 2.408a2.56 2.56 0 0 1-.732 1.526L8.817 15.85a.51.51 0 0 1-.867-.434l.27-1.899c.04-.28-.013-.593-.131-.956a9.42 9.42 0 0 0-.249-.657l-.082-.202c-.815-.197-1.578-.662-2.191-1.277-.614-.615-1.079-1.379-1.275-2.195l-.203-.083a9.556 9.556 0 0 0-.655-.248c-.363-.119-.675-.172-.955-.132l-1.896.27A.51.51 0 0 1 .15 7.17l2.382-2.386c.41-.41.947-.67 1.524-.734h.006l2.4-.238C9.005 1.55 11.087.582 12.623.208c.89-.217 1.59-.232 2.08-.188.244.023.435.06.57.093.067.017.12.033.16.045.184.06.279.13.351.295l.029.073a3.475 3.475 0 0 1 .157.721c.055.485.051 1.178-.159 2.065Zm-4.828 7.475.04-.04-.107 1.081a1.536 1.536 0 0 1-.44.913l-1.298 1.3.054-.38c.072-.506-.034-.993-.172-1.418a8.548 8.548 0 0 0-.164-.45c.738-.065 1.462-.38 2.087-1.006ZM5.205 5c-.625.626-.94 1.351-1.004 2.09a8.497 8.497 0 0 0-.45-.164c-.424-.138-.91-.244-1.416-.172l-.38.054 1.3-1.3c.245-.246.566-.401.91-.44l1.08-.107-.04.039Zm9.406-3.961c-.38-.034-.967-.027-1.746.163-1.558.38-3.917 1.496-6.937 4.521-.62.62-.799 1.34-.687 2.051.107.676.483 1.362 1.048 1.928.564.565 1.25.941 1.924 1.049.71.112 1.429-.067 2.048-.688 3.079-3.083 4.192-5.444 4.556-6.987.183-.771.18-1.345.138-1.713a2.835 2.835 0 0 0-.045-.283 3.078 3.078 0 0 0-.3-.041Z" />
                                                <path
                                                    d="M7.009 12.139a7.632 7.632 0 0 1-1.804-1.352A7.568 7.568 0 0 1 3.794 8.86c-1.102.992-1.965 5.054-1.839 5.18.125.126 3.936-.896 5.054-1.902Z" />
                                            </svg>
                                        </i>
                                        <a v-if="permissions['023'] && permissionFields['app_download']" :href="'/xadmin/app_versions/downloadApp/' + entry.id">
                                            <i :class="'bi bi-download text-success cursor-pointer action-btn action-btn-' + entry.id"
                                                :title="'Download version ' + entry.version"></i>
                                        </a>
                                        <i v-if="permissions['024'] && permissionFields['app_delete']"
                                            :class="'bi bi-trash text-danger cursor-pointer action-btn action-btn-' + entry.id"
                                            :title="'Delete version ' + entry.version" @click="removeApp(entry.id)">
                                        </i>
                                    </div>

                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div id="kt_billing_year" class="card-body p-0 tab-pane fade" role="tabpanel"
                    aria-labelledby="kt_billing_year">
                    <table class="table table-hover table-row-bordered align-middle gy-4 gs-9">
                        <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                            <tr>
                                <th class="text-center">Version</th>
                                <th class="">Release notes</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="entry in entries" v-if="entry.type == 'Mac'" v-on:mouseover="mouseover(entry.id)"
                                v-on:mouseleave="mouseleave()">
                                <td class="text-center">{{ entry.version }}</td>
                                <td class="cursor-pointer" @click="showReleaseNote(entry.release_note)">{{
                                    entry.release_note }}</td>
                                <td class="text-center">
                                    <span v-if="entry.is_default == 0" class="badge badge-light-warning">Draft</span>
                                    <span v-if="entry.is_default == 1" class="badge badge-light-success">Release</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-around">
                                        <i :class="'text-primary cursor-pointer action-btn action-btn-' + entry.id"
                                            v-if="entry.is_default == 0" title="Release this version"
                                            @click="showSetDefaultModal(entry.id)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-rocket-takeoff" viewBox="0 0 16 16">
                                                <path
                                                    d="M9.752 6.193c.599.6 1.73.437 2.528-.362.798-.799.96-1.932.362-2.531-.599-.6-1.73-.438-2.528.361-.798.8-.96 1.933-.362 2.532Z" />
                                                <path
                                                    d="M15.811 3.312c-.363 1.534-1.334 3.626-3.64 6.218l-.24 2.408a2.56 2.56 0 0 1-.732 1.526L8.817 15.85a.51.51 0 0 1-.867-.434l.27-1.899c.04-.28-.013-.593-.131-.956a9.42 9.42 0 0 0-.249-.657l-.082-.202c-.815-.197-1.578-.662-2.191-1.277-.614-.615-1.079-1.379-1.275-2.195l-.203-.083a9.556 9.556 0 0 0-.655-.248c-.363-.119-.675-.172-.955-.132l-1.896.27A.51.51 0 0 1 .15 7.17l2.382-2.386c.41-.41.947-.67 1.524-.734h.006l2.4-.238C9.005 1.55 11.087.582 12.623.208c.89-.217 1.59-.232 2.08-.188.244.023.435.06.57.093.067.017.12.033.16.045.184.06.279.13.351.295l.029.073a3.475 3.475 0 0 1 .157.721c.055.485.051 1.178-.159 2.065Zm-4.828 7.475.04-.04-.107 1.081a1.536 1.536 0 0 1-.44.913l-1.298 1.3.054-.38c.072-.506-.034-.993-.172-1.418a8.548 8.548 0 0 0-.164-.45c.738-.065 1.462-.38 2.087-1.006ZM5.205 5c-.625.626-.94 1.351-1.004 2.09a8.497 8.497 0 0 0-.45-.164c-.424-.138-.91-.244-1.416-.172l-.38.054 1.3-1.3c.245-.246.566-.401.91-.44l1.08-.107-.04.039Zm9.406-3.961c-.38-.034-.967-.027-1.746.163-1.558.38-3.917 1.496-6.937 4.521-.62.62-.799 1.34-.687 2.051.107.676.483 1.362 1.048 1.928.564.565 1.25.941 1.924 1.049.71.112 1.429-.067 2.048-.688 3.079-3.083 4.192-5.444 4.556-6.987.183-.771.18-1.345.138-1.713a2.835 2.835 0 0 0-.045-.283 3.078 3.078 0 0 0-.3-.041Z" />
                                                <path
                                                    d="M7.009 12.139a7.632 7.632 0 0 1-1.804-1.352A7.568 7.568 0 0 1 3.794 8.86c-1.102.992-1.965 5.054-1.839 5.18.125.126 3.936-.896 5.054-1.902Z" />
                                            </svg>
                                        </i>
                                        <a v-if="permissions['023'] && permissionFields['app_download']" :href="entry.url">
                                            <i :class="'bi bi-download text-success cursor-pointer action-btn action-btn-' + entry.id"
                                                :title="'Download version ' + entry.version"></i>
                                        </a>
                                        <i v-if="permissions['024'] && permissionFields['app_delete']"
                                            :class="'bi bi-trash text-danger cursor-pointer action-btn action-btn-' + entry.id"
                                            :title="'Delete version ' + entry.version" @click="removeApp(entry.id)">
                                        </i>
                                    </div>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex pl-9 pr-9 mb-8">
                    <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                        <div>
                            <select class="form-select form-select-sm form-select-solid">
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                        <div class="dataTables_paginate paging_simple_numbers" id="kt_customers_table_paginate">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" id="uploadApp" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered popup-main-1">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title fw-bolder text-center w-100">Create new application</h3>
                        </div>
                        <div class="modal-body px-10">
                            <div id="overlay">
                                <div class="la-3x text">
                                    <i class="la la-spinner la-spin"></i>
                                </div>
                            </div>

                            <form>
                                <div class="form-group">
                                    <label>Installation file <span class="required"></span></label>
                                    <input type="file" ref="uploader" class="form-control-file" accept=".zip,.rar,.7zip">
                                    <error-label :errors="errors.file_0"></error-label>
                                </div>
                                <div class="form-group">
                                    <label>Update OTA file </label>
                                    <input type="file" ref="uploader1" class="form-control-file" accept=".zip,.rar,.7zip">
                                    <error-label :errors="errors.file_1"></error-label>
                                </div>
                                <div class="form-group">
                                    <label>Operating System <span class="required"></span></label>
                                    <select v-model="model.type" class="form-control">
                                        <option value="">---</option>
                                        <option value="Mac">Mac OS</option>
                                        <option value="Window">Window</option>
                                    </select>
                                    <error-label :errors="errors.type"></error-label>
                                </div>
                                <div class="form-group">
                                    <label>Version <span class="required"></span></label>
                                    <input type="text" class="form-control" v-model="model.version">
                                    <error-label :errors="errors.version"></error-label>
                                </div>
                                <div class="form-group">
                                    <label>Release Note </label>
                                    <textarea type="text" class="form-control" v-model="model.release_note" />
                                    <error-label></error-label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid me-10">
                                    <input id="state" type="checkbox" v-model="model.is_default"
                                        class="form-check-input h-20px w-20px" checked>
                                    <label for="state" class="form-check-label fw-bold">Release this version</label>
                                    <error-label for="f_grade" :errors="errors.is_default"></error-label>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer" style="justify-content: center">
                            <button type="button" class="btn btn-primary mr-2" @click="save"><i
                                    class="bi bi-send mr-1"></i>Submit</button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Code updated 29/09/2022 -->
        <div class="row g-5 g-xxl-8" v-if="roleName != 'Super Administrator'">
            <!-- Windows -->
            <div class="col-xl-6" v-if="appVersionsWindow">
                <div class="card mb-5 mb-xxl-8">
                    <div class="card-header border-0 pt-5 justify-content-start">
                        <div class="card-toolbar mr-3">
                            <div class="d-block">
                                <img src="/images/windows_logo.png" height="48px" />
                            </div>

                        </div>
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-2 mb-1">Installation package for Windows</span>
                            <span class="badge badge-light-success fs-7">Version: {{ appVersionsWindow.version }}</span>
                        </h3>
                    </div>
                    <div class="card-body" style="position: relative; text-align: center;">
                        <div class="d-block mb-5">
                            <img src="/images/laptop_win.png" height="300px" />
                        </div>

                        <a :href="appVersionsWindow.url">
                            <button id="kt_widget_5_load_more_btn" class="btn btn-primary col-xl-6 text-center mb-3">
                                <span class="svg-icon"><i class="bi bi-download"></i></span>
                                <span class="indicator-label fs-6">Download Now</span>
                                <span class="indicator-progress">Loading...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </a>
                        <p class="text-gray-600 fw-bold fs-7">By downloading iSMART DIGI, you accept Terms Of Use,
                            Privacy and Cookies.</p>
                        <div class="resize-triggers">
                            <div class="expand-trigger">
                                <div style="width: 610px; height: 418px;"></div>
                            </div>
                            <div class="contract-trigger"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6" v-if="!appVersionsWindow">
                <div class="card mb-5 mb-xxl-8">
                    <div class="card-header border-0 pt-5 justify-content-start">
                        <div class="card-toolbar mr-3">
                            <div class="d-block">
                                <img src="/images/windows_logo.png" height="48px" />
                            </div>

                        </div>
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-2 mb-1">Installation package for Windows</span>
                            <span class="badge badge-light-dark fs-7">Version: N/A </span>
                        </h3>
                    </div>
                    <div class="card-body" style="position: relative; text-align: center;">
                        <div class="d-block mb-5">
                            <img src="/images/laptop_win.png" height="300px"
                                style="-webkit-filter: grayscale(100%); / Safari 6.0 - 9.0 /filter: grayscale(100%);opacity: 0.6;" />
                        </div>

                        <button id="kt_widget_5_load_more_btn" class="btn btn-light col-xl-6 text-center mb-3">
                            <span class="svg-icon"><i class="bi bi-exclamation-lg"></i></span>
                            <span class="indicator-label fs-6">Unavailable to Download</span>
                            <span class="indicator-progress">Loading...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        <p class="text-gray-600 fw-bold fs-7">By downloading iSMART DIGI, you accept Terms Of Use,
                            Privacy and Cookies.</p>
                        <div class="resize-triggers">
                            <div class="expand-trigger">
                                <div style="width: 610px; height: 418px;"></div>
                            </div>
                            <div class="contract-trigger"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Windows -->

            <!-- Mac OS -->
            <div class="col-xl-6" v-if="appVersionsOs">
                <div class="card mb-5 mb-xxl-8">
                    <div class="card-header border-0 pt-5 justify-content-start">
                        <div class="card-toolbar mr-3">
                            <div class="d-block"><img src="/images/macos_logo.png" height="48px" /></div>
                        </div>
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-2 mb-1">Installation package for Mac OS</span>
                            <span class="badge badge-light-success fs-7">Version: {{ appVersionsOs.version }}</span>
                        </h3>
                    </div>
                    <div class="card-body" style="position: relative; text-align: center;">
                        <div class="d-block mb-5">
                            <img src="/images/laptop_mac.png" height="300px" />
                        </div>
                        <a :href="appVersionsOs.url">
                            <button id="kt_widget_5_load_more_btn" class="btn btn-primary col-xl-6 text-center mb-3">
                                <span class="svg-icon"><i class="bi bi-download"></i></span>
                                <span class="indicator-label fs-6">Download Now</span>
                                <span class="indicator-progress">Loading...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </a>
                        <p class="text-gray-600 fw-bold fs-7">By downloading iSMART DIGI, you accept Terms Of Use,
                            Privacy and Cookies.</p>
                        <div class="resize-triggers">
                            <div class="expand-trigger">
                                <div style="width: 610px; height: 418px;"></div>
                            </div>
                            <div class="contract-trigger"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6" v-if="!appVersionsOs">
                <div class="card mb-5 mb-xxl-8">
                    <div class="card-header border-0 pt-5 justify-content-start">
                        <div class="card-toolbar mr-3">
                            <div class="d-block"><img src="/images/macos_logo.png" height="48px" /></div>
                        </div>
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-2 mb-1">Installation package for Mac OS</span>
                            <span class="badge badge-light-dark fs-7">Version: N/A</span>
                        </h3>
                    </div>
                    <div class="card-body" style="position: relative; text-align: center;">
                        <div class="d-block mb-5">
                            <img src="/images/laptop_mac.png" height="300px"
                                style="-webkit-filter: grayscale(100%); / Safari 6.0 - 9.0 /filter: grayscale(100%);opacity: 0.6;" />
                        </div>
                        <button id="kt_widget_5_load_more_btn" class="btn btn-light col-xl-6 text-center mb-3">
                            <span class="svg-icon"><i class="bi bi-exclamation-lg"></i></span>
                            <span class="indicator-label fs-6">Unavailable to Download</span>
                            <span class="indicator-progress">Loading...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>

                        <p class="text-gray-600 fw-bold fs-7">By downloading iSMART DIGI, you accept Terms Of Use,
                            Privacy and Cookies.</p>
                        <div class="resize-triggers">
                            <div class="expand-trigger">
                                <div style="width: 610px; height: 418px;"></div>
                            </div>
                            <div class="contract-trigger"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Mac OS -->
        </div>
        <!-- End Code updated 29/09/2022 -->

        <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="deviceConfirmLimit" tabindex="-1"
            role="dialog" aria-labelledby="deviceConfirmLimit" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document" style="max-width: 500px;">
                <div class="modal-content box-shadow-main paymment-status"
                    style="left:140px;text-align: center; padding: 27px 0px 10px;">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <div class="content">
                        <p style="word-break: break-word;margin-right: 20px;margin-left: 20px;}">{{ release_note }}</p>
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
import { $get, $post, clone, forEach, getTimeRangeAll } from "../../utils";
import $router from '../../lib/SimpleRouter';
import ActionBar from "../includes/ActionBar";

let created = getTimeRangeAll();
const $q = $router.getQuery();

export default {
    name: "App_versionsIndex.vue",
    components: { ActionBar },
    data() {
        const permissions = clone(window.$permissions);
        return {
            permissionFields: $json.permissionFields || [],
            entry: '',
            roleName: $json.roleName,
            release_note: '',
            permissions,
            window: [],
            macos: [],
            windowIds: [],
            win: [],
            appIds: [],
            app: [],
            allSelected: false,
            windowAllSelected: false,
            model: {
                type: ''
            },
            role: '',
            errors: {},
            entries: [],
            appVersionsWindow: '',
            appVersionsOs: '',
            totalVersionIos: 0,
            totalVersionWindow: 0,
            curVersion: '',
            breadcrumbs: [
                {
                    title: 'Application management'
                },
            ],
        }
    },
    mounted() {
        $router.on('/', this.load).init();
    },
    methods: {
        showReleaseNote(release_note) {
            const that = this;
            that.release_note = release_note;
            $('#deviceConfirmLimit').modal('show');
        },
        removeApp: function (deleteApp = '') {
            $('#delete').modal('show');
            this.entry = deleteApp;
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
            const files1 = this.$refs.uploader1.files;
            const formData = new FormData();
            formData.append('_token', window.$csrf)
            for (const [k, v] of Object.entries(this.model)) {
                formData.append(k, v);
            }
            for (let i = 0; i < files1.length; i++) {
                formData.append('file1_' + i, files1[i]);
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
                this.$refs.uploader1.value = null;
                $router.on('/', this.load).init();
                toastr.success(res.message);
            }

        },
        async load() {
            let query = $router.getQuery();
            const res = await $get('/xadmin/app_versions/data', query);
            console.log(res);
            this.paginate = res.paginate;
            this.entries = res.data;
            this.appVersionsWindow = res.appVersionsWindow;
            console.log(this.appVersionsWindow);
            this.appVersionsOs = res.appVersionsOs;
            console.log(this.appVersionsOs);

            setTimeout(function () {
                KTMenu.createInstances();
            }, 0)
            this.totalVersionIos = this.entries.filter(e => e.type == 'Mac').length;
            this.totalVersionWindow = this.entries.filter(e => e.type == 'Window').length;
            this.from = (this.paginate.currentPage - 1) * (this.limit) + 1;
            this.to = (this.paginate.currentPage - 1) * (this.limit) + this.entries.length;
        },
        async remove() {
            const res = await $post('/xadmin/app_versions/remove', { id: this.entry });

            if (res.code) {
                toastr.error(res.message);
            } else {
                toastr.success(res.message);
                $('#delete').modal('hide');
            }
            $router.updateQuery({ page: this.paginate.currentPage, _: Date.now() });
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
        WindowSelectAll() {
            if (this.windowAllSelected) {
                const selected = this.window.map((u) => u.id);
                this.windowIds = selected;
                this.win = this.window
            } else {
                this.windowIds = [];
                this.win = [];
            }
        },
        async removeAll() {
            if (!confirm('Xóa bản ghi: ' + JSON.stringify(this.appIds))) {
                return;
            }

            const res = await $post('/xadmin/app_versions/removeAll', { ids: this.appIds });

            if (res.code) {
                toastr.error(res.message);
            } else {
                toastr.success(res.message);
                this.appIds = [];
                this.app = [];
            }

            $router.updateQuery({ page: this.paginate.currentPage, _: Date.now() });

        },
        async windowRemoveAll() {
            if (!confirm('Xóa bản ghi: ' + JSON.stringify(this.windowIds))) {
                return;
            }

            const res = await $post('/xadmin/app_versions/windowRemoveAll', { ids: this.windowIds });

            if (res.code) {
                toastr.error(res.message);
            } else {
                toastr.success(res.message);
                this.appIds = [];
                this.app = [];
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
.status {
    color: #50cd89;
    background-color: #e8fff3;
    padding: 0.5em 0.85em;
    font-size: .85rem;
    font-weight: 600;
    border-radius: 0.475rem;

}
</style>
