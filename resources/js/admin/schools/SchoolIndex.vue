<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title="School management"/>
        <div class="row">
            <div class="col-lg-12">

                <!-- Modal Import School -->
                <div class="post d-flex flex-column-fluid" id="kt_post">
                    <div id="kt_content_container" class="container-xxl">
                        <div class="modal fade" id="kt_modal_create_app" ref="myModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered mw-900px">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2>Import School</h2>
                                        <div class="btn btn-sm btn-icon btn-active-color-primary"
                                             data-bs-dismiss="modal">
                                            <span class="svg-icon svg-icon-1">
														<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                             viewBox="0 0 24 24" fill="none">
															<rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                                                  rx="1" transform="rotate(-45 6 17.3137)"
                                                                  fill="black"/>
															<rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                                  transform="rotate(45 7.41422 6)" fill="black"/>
														</svg>
													</span>
                                        </div>
                                    </div>
                                    <div class="modal-body py-lg-10 px-lg-10">
                                        <div
                                            class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid"
                                            id="kt_modal_create_app_stepper">
                                            <div
                                                class="d-flex justify-content-center justify-content-xl-start flex-row-auto w-100 w-xl-300px">
                                                <div class="stepper-nav ps-lg-10">
                                                    <div class="stepper-item current" data-kt-stepper-element="nav">
                                                        <div class="stepper-line w-40px"></div>
                                                        <div class="stepper-icon w-40px h-40px">
                                                            <i class="stepper-check fas fa-check"></i>
                                                            <span class="stepper-number">1</span>
                                                        </div>
                                                        <div class="stepper-label">
                                                            <h3 class="stepper-title">Upload file</h3>
                                                        </div>
                                                    </div>
                                                    <div class="stepper-item" data-kt-stepper-element="nav">
                                                        <div class="stepper-line w-40px"></div>
                                                        <div class="stepper-icon w-40px h-40px">
                                                            <i class="stepper-check fas fa-check"></i>
                                                            <span class="stepper-number">2</span>
                                                        </div>
                                                        <div class="stepper-label">
                                                            <h3 class="stepper-title">Validation</h3>
                                                        </div>
                                                        <!--begin::Label-->
                                                    </div>
                                                    <!--end::Step 2-->
                                                    <!--begin::Step 3-->
                                                    <div class="stepper-item" data-kt-stepper-element="nav">
                                                        <!--begin::Line-->
                                                        <div class="stepper-line w-40px"></div>
                                                        <!--end::Line-->
                                                        <!--begin::Icon-->
                                                        <div class="stepper-icon w-40px h-40px">
                                                            <i class="stepper-check fas fa-check"></i>
                                                            <span class="stepper-number">3</span>
                                                        </div>
                                                        <!--end::Icon-->
                                                        <!--begin::Label-->
                                                        <div class="stepper-label">
                                                            <h3 class="stepper-title">Completed</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end::Nav-->
                                            </div>
                                            <!--begin::Aside-->
                                            <!--begin::Content-->
                                            <div class="flex-row-fluid py-lg-5 px-lg-15">
                                                <!--begin::Form-->
                                                <form class="form" novalidate="novalidate"
                                                      id="kt_modal_create_app_form">
                                                    <!--begin::Step 1-->
                                                    <div class="current" data-kt-stepper-element="content">
                                                        <div class="w-100">
                                                            <!--begin::Input group-->
                                                            <div class="fv-row mb-10">
                                                                <div class="dropzone dropzone-queue mb-2 ">
                                                                    <label for="file-upload"
                                                                           class="btn btn-primary btn-active-primary btn-sm">
                                                                        Upload file
                                                                    </label>
                                                                    <label>Click to select file (*.xls, *.xlsx). Max
                                                                        file size is 5Mb</label>
                                                                    <!--                                                    <button for="file-upload" class="btn btn-primary" > Upload file</button>-->
                                                                    <input type="file" id="file-upload" ref="uploader"
                                                                           class="form-control-file"
                                                                           @change="importFileSchool">
                                                                    <error-label></error-label>
                                                                    <div class="dropzone-items wm-200px"></div>

                                                                    <div class="dropzone-item p-5"
                                                                         v-if="fileUpLoad!=''">
                                                                        <!--begin::File-->
                                                                        <div class="dropzone-file">
                                                                            <div class="dropzone-filename text-dark"
                                                                                 title="some_image_file_name.jpg">
                                                                                <span
                                                                                    data-dz-name="">{{
                                                                                        fileUpLoad
                                                                                    }}</span>
                                                                                <strong>(
                                                                                    <span
                                                                                        data-dz-size="">{{
                                                                                            sizeFile
                                                                                        }}</span>)</strong>
                                                                            </div>
                                                                            <div class="dropzone-error mt-0"
                                                                                 data-dz-errormessage=""></div>
                                                                        </div>
                                                                        <div class="dropzone-toolbar">
                                                                    <span class="dropzone-delete" data-dz-remove="">
																			<i style="font-size: 15px; color: red"
                                                                               class="bi bi-trash"
                                                                               @click="removeFileSchool"></i>
																		</span>
                                                                        </div>
                                                                    </div>
                                                                    <error-label
                                                                        :errors="errors.sizeFile"></error-label>
                                                                </div>
                                                                <div v-if="valueValidateImportSchool==0"
                                                                     class="dropzone-panel mb-4  ">
                                                                    <a @click="downloadTemplate()" style="color: #696CFF;cursor:pointer">
                                                                        <i class="bi bi-download mr-2"></i>
                                                                        Download template import school here
                                                                    </a>
                                                                    <br>
                                                                    <a @click="downloadTemplateDistrict()" style="color: #696CFF;cursor:pointer;margin-top:10px;display:flex">
                                                                        <i class="bi bi-download mr-2"></i>
                                                                        Download template City/District here
                                                                    </a>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Step 1-->
                                                    <!--begin::Step 2-->
                                                    <div data-kt-stepper-element="content">
                                                        <div class="w-100">
                                                            <!--begin::Input group-->
                                                            <div class="fv-row">
                                                                <!--begin::Label-->
                                                                <label
                                                                    class="d-flex align-items-center fs-5 fw-bold mb-4">
                                                                    <span class="required">Validation result</span>
                                                                    <i class="fas fa-exclamation-circle ms-2 fs-7"
                                                                       data-bs-toggle="tooltip"
                                                                       title="validation result"></i>
                                                                </label>
                                                            </div>
                                                            <div v-if="valueValidateImportSchool!=0"
                                                                 class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="ms-6">

                                                                        <div class="fw-bold text-muted">
                                                                            {{ fileImport.length }} new
                                                                            record(s)
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex">
                                                                    <div class="text-end">
                                                                <span
                                                                    class="form-check form-check-custom form-check-solid">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="category" value="0"
                                                                           v-model="doNotImport"/>
                                                                    <label style="margin: 0px 10px 0px">Import</label>
                                                                    <input class="form-check-input" type="radio"
                                                                           name="category" value="1"
                                                                           v-model="doNotImport"/>
                                                                    <label
                                                                        style="margin: 0px 10px 0px"> Do not import </label>
                                                                </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div v-if="valueValidateImportSchool!=0"
                                                                 class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="ms-6">

                                                                        <div class="fw-bold text-muted">
                                                                            {{ fileError.length }} error
                                                                            record(s)
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex" v-if="fileError.length>0">
                                                                    <div class="text-end">
                                                                        <div class="fs-7 text-muted">
                                                                            <a @click="exportErrorImportSchool"
                                                                               type="button" class="btn btn-primary">Export</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--end::Input group-->
                                                        </div>
                                                    </div>
                                                    <!--end::Step 2-->
                                                    <!--begin::Step 3-->
                                                    <div data-kt-stepper-element="content">
                                                        <div class="w-100">
                                                            <!--begin::Input group-->
                                                            <div class="fv-row mb-10">
                                                            </div>
                                                            <!--end::Input group-->
                                                            <!--begin::Input group-->
                                                            <div class="fv-row">
                                                                <label v-if="doNotImport==0">Do you want to import
                                                                    {{ fileImport.length }} records?</label>
                                                                <label v-if="doNotImport==1"> Do you want to not import
                                                                    {{ fileImport.length }} records?</label>
                                                            </div>
                                                            <!--end::Input group-->
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-stack pt-10">
                                                        <!--begin::Wrapper-->
                                                        <div class="me-2">
                                                            <button type="button"
                                                                    class="btn btn-lg btn-light-primary me-3"
                                                                    data-kt-stepper-action="previous">
                                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr063.svg-->
                                                                <span class="svg-icon svg-icon-3 me-1">
																		<svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="24" height="24" viewBox="0 0 24 24"
                                                                             fill="none">
																			<rect opacity="0.5" x="6" y="11" width="13"
                                                                                  height="2" rx="1" fill="black"/>
																			<path
                                                                                d="M8.56569 11.4343L12.75 7.25C13.1642 6.83579 13.1642 6.16421 12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75L5.70711 11.2929C5.31658 11.6834 5.31658 12.3166 5.70711 12.7071L11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25C13.1642 17.8358 13.1642 17.1642 12.75 16.75L8.56569 12.5657C8.25327 12.2533 8.25327 11.7467 8.56569 11.4343Z"
                                                                                fill="black"/>
																		</svg>
																	</span>
                                                                <!--end::Svg Icon-->Back
                                                            </button>
                                                        </div>
                                                        <!--end::Wrapper-->
                                                        <!--begin::Wrapper-->
                                                        <div>
                                                            <button type="button" class="btn btn-lg btn-primary"
                                                                    data-kt-stepper-action="submit"
                                                                    @click="saveImport()">
																		<span class="indicator-label">Submit
                                                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
																		<span class="svg-icon svg-icon-3 ms-2 me-0">
																			<svg xmlns="http://www.w3.org/2000/svg"
                                                                                 width="24" height="24"
                                                                                 viewBox="0 0 24 24" fill="none">
																				<rect opacity="0.5" x="18" y="13"
                                                                                      width="13" height="2" rx="1"
                                                                                      transform="rotate(-180 18 13)"
                                                                                      fill="black"/>
																				<path
                                                                                    d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                                                                                    fill="black"/>
																			</svg>
																		</span>
                                                                          </span>
                                                            </button>
                                                            <button type="button" class="btn btn-lg btn-primary"
                                                                    data-kt-stepper-action="next"
                                                                    :disabled="disableContinue==false">Continue
                                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
                                                                <span class="svg-icon svg-icon-3 ms-1 me-0">
																		<svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="24" height="24" viewBox="0 0 24 24"
                                                                             fill="none">
																			<rect opacity="0.5" x="18" y="13" width="13"
                                                                                  height="2" rx="1"
                                                                                  transform="rotate(-180 18 13)"
                                                                                  fill="black"/>
																			<path
                                                                                d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                                                                                fill="black"/>
																		</svg>
																	</span>
                                                                <!--end::Svg Icon--></button>
                                                        </div>
                                                        <!--end::Wrapper-->
                                                    </div>
                                                    <!--end::Actions-->
                                                </form>
                                                <!--end::Form-->
                                            </div>
                                            <!--end::Content-->
                                        </div>
                                        <!--end::Stepper-->
                                    </div>
                                    <!--end::Modal body-->
                                </div>
                                <!--end::Modal content-->
                            </div>
                            <!--end::Modal dialog-->
                        </div>
                        <!--end::Modal - Create App-->
                    </div>
                    <!--end::Container-->
                </div>

                <!--end: Modal Import School -->

                <!-- modal xoa nhieu -->
                <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="delete1" tabindex="-1"
                     role="dialog"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                         style="max-width: 450px;">
                        <div class="modal-content box-shadow-main paymment-status"
                             style="left:120px;text-align: center; padding: 20px 0px 55px;">
                            <div class="close-popup" data-dismiss="modal"></div>
                            <div class="swal2-icon swal2-warning swal2-icon-show">
                                <div class="swal2-icon-content" style="margin: 0px 25px 0px ">!</div>
                            </div>
                            <div class="swal2-html-container">
                                <p>Are you sure to delete this school?</p>
                            </div>
                            <div class="swal2-actions">
                                <button type="submit" class="swal2-confirm btn fw-bold btn-danger" @click="removeAll">
                                    <span class="indicator-label">Yes, delete!</span>
                                </button>
                                <button type="reset" class="swal2-cancel btn fw-bold btn-active-light-primary"
                                        data-bs-dismiss="modal" style="margin: 0px 8px 0px">No, cancel
                                </button>

                            </div>

                        </div>
                    </div>
                </div>
                <!-- end modal xoa nhieu -->

                <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="delete" tabindex="-1"
                     role="dialog"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                         style="max-width: 450px;">
                        <div class="modal-content box-shadow-main paymment-status"
                             style="left:120px;text-align: center; padding: 20px 0px 55px;">
                            <div class="close-popup" data-dismiss="modal"></div>
                            <div class="swal2-icon swal2-warning swal2-icon-show">
                                <div class="swal2-icon-content" style="margin: 0px 25px 0px ">!</div>
                            </div>
                            <div class="swal2-html-container">
                                <p>Are you sure to delete this school?</p>
                            </div>
                            <div class="swal2-actions">
                                <button type="submit" id="kt_modal_new_target_submit"
                                        class="swal2-confirm btn fw-bold btn-danger" @click="remove(entry)">
                                    <span class="indicator-label">Yes, delete!</span>
                                </button>
                                <button type="reset" id="kt_modal_new_target_cancel"
                                        class="swal2-cancel btn fw-bold btn-active-light-primary"
                                        data-bs-dismiss="modal" style="margin: 0px 8px 0px">No, cancel
                                </button>

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
                                       class="form-control form-control-solid w-400px ps-15"
                                       @keydown.enter="doFilter($event)" v-model="filter.keyword"
                                       placeholder="Search ID, name, address, administrator name..." value=""/>
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
                                <button class="btn btn-primary button-create"
                                        style="margin:0 0 0 15px" @click="importSchool">
                                    <i class="bi bi-plus-lg"></i>Import School
                                </button>
                                <a :href="'/xadmin/schools/create'">
                                    <button v-if="permissions['016']" class="btn btn-primary button-create"
                                            style="margin:0 0 0 15px">
                                        <i class="bi bi-plus-lg"></i>New School
                                    </button>
                                </a>
                            </div>
                            <div class="d-flex justify-content-end align-items-center d-none"
                                 data-kt-customer-table-toolbar="selected" v-if=" schoolIds!=''">
                                <div class="fw-bolder me-5">
                                    <span class="me-2" data-kt-customer-table-select="selected_count"></span>{{
                                        schoolIds.length
                                    }} Selected
                                </div>
                                <button v-if="permissions['018']" type="button" class="btn btn-danger"
                                        data-kt-customer-table-select="delete_selected" @click="removeAllModal">Delete
                                    Selected
                                </button>
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
                                    <label>School name </label>
                                    <input class="form-control" type="text" placeholder="Enter the school name"
                                           v-model="filter.label"/>

                                </div>
                                <div class="form-group col-lg-3">
                                    <label>Administrator name </label>
                                    <input class="form-control" type="text" v-model="filter.role_name"
                                           placeholder="Enter the administrator name">

                                </div>
                                <div class="form-group col-lg-3">
                                    <label>City/ Province </label>
                                    <treeselect :options="provinces" v-model="filter.province_id"
                                                @input="selectProvince"/>

                                </div>
                                <div class="form-group col-lg-3" v-if="filter.province_id">
                                    <label>District/ Town </label>
                                    <treeselect :options="districts" v-model="filter.district_id"/>

                                </div>

                                <div class="form-group col-lg-3">
                                    <label>Street/ Ward </label>
                                    <input v-model="filter.school_address" class="form-control" type="text"
                                           placeholder="Enter the region/city">

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
                                    <span
                                        class="svg-icon svg-icon-dark mx-1"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z"
                                                fill="black"></path>
                                        </svg>
                                    </span>

                                    <div
                                        v-text=" from +'-'+ to +' of '+ paginate.totalRecord"
                                        v-if="entries.length > 0"></div>

                                </div>
                            </div>
                        </div>

                        <table class="table table-row-bordered align-middle gy-4 gs-9">
                            <thead
                                class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                            <tr>
                                <td width="25" v-if="permissions['018']">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" v-model="allSelected"
                                               @change="selectAll()">
                                    </div>
                                </td>
                                <th v-for="(header, index) in tableHeaders" :key="index" @click="sortTable(index)"
                                    :class="header.icon ? 'sort_color' : ''">
                                    {{ header.text }}<i v-html="header.icon"></i>
                                </th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <!--                            <i class="bi bi-sort-up ml-1 mt-1"></i>-->
                            <tbody>
                            <tr v-if="entries.length==0">
                                <td valign="top" colspan="10" class="text-center">No results found. Try different
                                    keywords or remove search filters.
                                </td>
                            </tr>
                            <tr v-if="entries.length!==0" v-for="(entry,index) in entries">
                                <td class="" v-if="permissions['018']">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" v-model="schoolIds"
                                               :value="entry.id" @change="updateCheckAll">
                                    </div>
                                </td>
                                <td>{{ index + 1 }}</td>
                                <td v-text="entry.label" @click="edit(entry)"></td>
                                <td v-text="entry.province" @click="edit(entry)"></td>
                                <td v-text="entry.district" @click="edit(entry)"></td>
                                <td v-text="entry.school_address" @click="edit(entry)"></td>
                                <td @click="edit(entry)" class="" data-bs-toggle="tooltip"
                                    :title="entry.nameSchoolAdmin">{{ entry.nameSchoolAdmin }}
                                </td>
                                <td v-text="entry.teacher.length" @click="edit(entry)"></td>
                                <td v-text="entry.devices_per_user" @click="edit(entry)"></td>
                                <td @click="edit(entry)">{{ d(entry.license_to) }}</td>
                                <td>
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
                                    </a>

                                    <div
                                        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                        data-kt-menu="true">

                                        <div class="menu-item px-3">
                                            <a v-if="permissions['017']" :href="'/xadmin/schools/edit?id='+entry.id"
                                               class="menu-link px-3">Edit</a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a class="menu-link text-danger px-3"
                                               v-if="permissions['018'] && entry.teacher.length==0"
                                               @click="removeSchool(entry.id)"
                                               data-kt-subscriptions-table-filter="delete_row">Delete</a>
                                            <a class="menu-link text-danger px-3"
                                               v-if="permissions['018'] && entry.teacher.length>0"
                                               @click="modalDeleteSchool()"
                                               data-kt-subscriptions-table-filter="delete_row">Delete</a>


                                        </div>
                                    </div>
                                </td>
                                <div class="modal fade" style="margin-right:50px;border:2px solid #333333  "
                                     id="deviceConfirm" tabindex="-1" role="dialog"
                                     aria-labelledby="deviceConfirm"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                                         style="max-width: 500px;">
                                        <div class="modal-content box-shadow-main paymment-status"
                                             style="left:140px;text-align: center; padding: 27px 0px 10px;">
                                            <div class="close-popup" data-dismiss="modal"></div>
                                            <h3 class="popup-title success">Cannot delete this school</h3>
                                            <div class="content">
                                                <p>You can only delete this school if the list of teachers is empty.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
import {$get, $post, clone, getTimeRangeAll} from "../../utils";
import $router from '../../lib/SimpleRouter';
import ActionBar from "../includes/ActionBar";
import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

let created = getTimeRangeAll();
const $q = $router.getQuery();

export default {
    name: "SchoolsIndex.vue",
    components: {ActionBar, Treeselect},
    data() {
        let self = this;
        const permissions = clone(window.$permissions)
        let isShowFilter = false;

        let filter = {
            keyword: $q.keyword || '',
            label: $q.label || '',
            school_address: $q.school_address || '',
            role_name: $q.role_name || '',
            district_id: $q.district_id != 'undefined' && $q.district_id != 'null' ? $q.district_id : null,
            province_id: $q.province_id != 'undefined' && $q.province_id != 'null' ? $q.province_id : null,
            sortBy: $q.sortBy || '',
            sortDirection: $q.sortDirection || '',
        };

        for (var key in filter) {
            if (filter[key] != '' && filter[key] != 'undefined' && filter[key] != null && key != 'sortBy' && key != 'sortDirection') {
                isShowFilter = true;
            }
        }
        let tableHeaders = [
            {text: 'No.', icon: ''},
            {text: 'Name', icon: '', name: 'label'},
            {text: 'City/ Province', icon: '', name: 'province'},
            {text: 'District/ Town', icon: '', name: 'district'},
            {text: 'Street/ Ward', icon: '', name: 'school_address'},
            {text: 'Administrator name', icon: ''},
            {text: 'Teacher', icon: ''},
            {text: 'Devices per user', icon: '', name: 'devices_per_user'},
            {text: 'License', icon: '', name: 'license_to'},
        ];


        tableHeaders.forEach(function (e) {
            if (e.name == filter['sortBy']) {

                self.sortDirection = filter['sortDirection'];
                let icon = self.sortDirection > 0 ? '<i class="bi bi-sort-up ml-1 mt-1"></i>' : '<i class="bi bi-sort-down-alt ml-1 mt-1"></i>';
                e.icon = icon;
            }
        })

        return {
            errors: {},
            disableContinue: false,
            doNotImport: 0,
            fileError: [],
            fileImport: [],
            valueValidateImportSchool: 0,
            sizeFile: '',
            fileUpLoad: '',
            tableHeaders,
            sortDirection: 1,
            provinces: [],
            districts: [],
            permissions,
            schoolIds: [],
            school: [],
            allSelected: false,
            breadcrumbs: [
                {
                    title: 'School management'
                },
            ],
            entry: '',
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
        let self = this;
        $.get('/xadmin/schools/getProvince', function (res) {
            self.provinces = res;
            if (self.filter.province_id) {
                self.districts = self.provinces.filter(e => e.id == self.filter.province_id)[0]['districts'];
            }

        });
        $router.on('/', this.load).init();


    },
    methods: {
        removeFileSchool() {
            if (this.$refs.uploader.files) {
                this.fileUpLoad = '';
                this.$refs.uploader.value = null;
                this.valueValidateImportTeacher = 0;
                this.fileImport.length = 0;
                this.fileError.length = 0;
                this.disableContinue = false;

            }
        },
        importSchool() {
            $('#kt_modal_create_app').modal('show');
        },
        async validateImportSchool() {
            console.log(1);
            this.errors = {};
            this.valueValidateImportSchool = 1;
            const files = this.$refs.uploader.files;
            const formData = new FormData();
            formData.append('_token', window.$csrf);
            console.log(files);
            files.forEach(function (e, index) {
                formData.append(index, e);
            })
            // forEach(files, (v, k) => {
            //     formData.append(k, v);
            // });

            for (let i = 0; i < files.length; i++) {
                formData.append('file_' + i, files[i]);
                formData.append('school_id', this.entry.id);
            }

            $('#overlay').show();
            let res = await fetch('/xadmin/schools/validateImportSchool', {
                method: 'POST',
                body: formData
            })
                .then((response) => response.json())
                .catch((error) => {
                    console.error('Error:', error);
                });
            if (res.code == 2) {
                this.code = res.code;
                this.fileError = res.fileError;
                this.fileImport = res.fileImport;
                this.errorName = res.errorFileName;
            }
            if (res.code == 0) {
                this.fileImport = res.fileImport;
            }
        },
        async saveImport() {
            if (this.doNotImport == 0) {
                {
                    this.$loading(true);
                    const res = await $post('/xadmin/schools/import', {
                        fileImport: this.fileImport,
                        school_id: this.entry.id,
                    }, false);
                    this.$loading(false);
                    if (res.code) {
                        toastr.error(res.message);
                    } else {
                        this.errors = {};
                        toastr.success(res.message);
                        let self = this;
                        $('#kt_modal_create_app').modal('hide');
                        self.$refs.uploader.value = null;
                        self.fileUpLoad = '';
                        self.valueValidateImportSchool = 0;
                        self.fileImport.length = 0;
                        self.fileError.length = 0;
                        location.reload();
                    }
                }
            }
            if (this.doNotImport == 1) {
                $('#kt_modal_create_app').modal('hide');
                this.$refs.uploader.value = null;
                this.fileUpLoad = '';
                this.valueValidateImportSchool = 0;
                this.fileImport.length = 0;
                this.fileError.length = 0;
                location.reload();
            }

        },
        exportErrorImportSchool() {
            window.location.href = '/xadmin/schools/exportErrorSchool?fileError=' + this.errorName;
        },
        downloadTemplate() {
            window.location.href = '/xadmin/schools/downloadTemplate';
        },
        downloadTemplateDistrict()
        {
            window.location.href = '/xadmin/schools/downloadTemplateDistrict';

        },
        async importFileSchool() {
            const fileExtension = this.$refs.uploader.files[0].name.split('.').pop();
            if (fileExtension !== 'xlsx') {
                return this.errors = {
                    'sizeFile': ['The file is not in the correct format']
                };
            } else {
                this.errors = {};
                let fileSize = (this.$refs.uploader.files[0].size.toString());
                if (fileSize.length < 7) {
                    let size = `${Math.round(+fileSize / 1024).toFixed(2)}kb`
                    this.sizeFile = size;

                } else {
                    let size = `${(Math.round(+fileSize / 1024) / 1000).toFixed(2)}MB`;
                    let numberSize = (Math.round(+fileSize / 1024) / 1000).toFixed(2)
                    this.sizeFile = size;
                    if (numberSize > 5) {
                        return this.errors = {
                            'sizeFile': ['Max file size is 5Mb']
                        };

                    }

                }
                this.fileUpLoad = this.$refs.uploader.files[0].name;
                this.disableContinue = true;
                this.validateImportSchool();
            }
        },

        sortTable(index) {
            let self = this;
            let check = 0;
            this.tableHeaders.forEach((header, i) => {
                if (header.name) {
                    if (index === i) {
                        self.sortDirection = -this.sortDirection;
                        self.filter.sortDirection = this.sortDirection;
                        const sortIcon = this.sortDirection > 0 ? '<i class="bi bi-sort-up ml-1 mt-1"></i>' : '<i class="bi bi-sort-down-alt ml-1 mt-1"></i>';
                        header.icon = sortIcon;
                        self.filter.sortBy = header.name;
                        $router.setQuery(this.filter);
                        self.filter.sortBy = header.name;
                        check = 1;
                        $router.setQuery(this.filter);
                    }
                }
            });

            this.tableHeaders.forEach((header, i) => {
                if (header.name) {
                    if (index != i && check == 1) {
                        header.icon = '';
                    }
                }
            });
        },
        selectProvince() {
            this.filter.district_id = null;
            this.districts = [];
            if (this.filter.province_id) {
                this.districts = this.provinces.filter(e => e.id == this.filter.province_id)[0]['districts'];
            }

        },
        removeSchool: function (deleteSchool = '') {
            $('#delete').modal('show');
            this.entry = deleteSchool;
        },
        modalDeleteSchool() {
            $('#deviceConfirm').modal('show');
        },
        removeAllModal() {
            $('#delete1').modal('show');
        },
        edit(entry) {
            window.location.href = '/xadmin/schools/edit?id=' + entry.id;
        },
        async load() {
            let query = $router.getQuery();
            this.$loading(true);
            const res = await $get('/xadmin/schools/data', query);
            this.$loading(false);
            setTimeout(function () {
                KTMenu.createInstances();
            }, 0)
            this.paginate = res.paginate;
            this.entries = res.data;
            this.user = res.users;
            this.from = (this.paginate.currentPage - 1) * (this.limit) + 1;
            this.to = (this.paginate.currentPage - 1) * (this.limit) + this.entries.length;
        },
        async remove() {
            const res = await $post('/xadmin/schools/remove', {id: this.entry});

            if (res.code) {
                toastr.error(res.message);
            } else {
                toastr.success(res.message);
                $('#delete').modal('hide');

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
        async removeAll() {

            const res = await $post('/xadmin/schools/removeAll', {ids: this.schoolIds});
            if (res.code == 1) {
                $('#delete1').modal('hide');
                $('#deviceConfirm').modal('show');
                this.schoolIds = [];
                this.school = [];
                this.allSelected = false;
            } else {
                toastr.success(res.message);
                this.schoolIds = [];
                this.school = [];
                $('#delete1').modal('hide');
                this.allSelected = false;
                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});

            }


        }
    }
}
</script>

<style>
.loading-screen{
    z-index: 9999 !important;
}
</style>

<style scoped>
input[type="file"] {
    display: none;
}

td {
    cursor: pointer;
}

.table th, .table td {
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    cursor: pointer;
    padding: 0.75rem;
    vertical-align: top;
}

tr:hover {
    background-color: #f2f2f2;
}

.sort_color {
    color: black;
}

</style>
