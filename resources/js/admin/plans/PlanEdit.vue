<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title="Plan details"/>
        <div class="row">
            <!-- modal cancel package lesson -->

            <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="cancelModal" tabindex="-1" role="dialog"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                     style="max-width: 450px;">
                    <div class="modal-content box-shadow-main paymment-status" style="left:120px;text-align: center; padding: 20px 0px 55px;">
                        <div class="close-popup" data-dismiss="modal"></div>
                        <div class="swal2-icon swal2-warning swal2-icon-show">
                            <div class="swal2-icon-content" style="margin: 0px 24.5px 0px ">!</div>
                        </div>
                        <div class="swal2-html-container">
                            <p >Do you want to close ?</p>
                        </div>
                        <div class="swal2-actions">
                            <button type="submit" id="kt_modal_new_target_submit2" class="swal2-confirm btn fw-bold btn-primary" @click="cancelModalYes">
                                <span class="indicator-label">Yes</span>
                            </button>
                            <button type="reset" id="kt_modal_new_target_cancel2" class="swal2-cancel btn fw-bold btn-active-light-primary" @click="cancelModalNo" style="margin: 0px 8px 0px">No</button>

                        </div>

                    </div>
                </div>
            </div>
            <!-- end modal cancel package lesson -->

            <!-- BEGIN: MODAL ADD LESSON PACKAGE PLAN -->

            <div class="modal fade" id="kt_modal_invite" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog " style="width: 1000px">
                    <div class="modal-content" style="width: max-content;margin: 0px -150px 0px">
                        <div class="modal-header pb-0 border-0 justify-content-end">
                            <div class="btn btn-sm btn-icon btn-active-color-primary" @click="cancelModalLesson()">
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                              rx="1" transform="rotate(-45 6 17.3137)"
                                              fill="black"/>
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
                            <div class="text-center mb-13">
                                <h1 class="mb-3">Lesson package</h1>
                            </div>
                            <div class="d-flex">
                                <div class="h-15px me-3" style="width: 218px">
                                    <label>Name </label>
                                    <input v-model="filter.keyword" @keydown.enter="doFilter($event)" class="form-control" placeholder="Enter the lesson name"/>
                                </div>
                                <div class="h-15px me-3" style="width: 218px">

                                        <label>Course </label>
                                        <treeselect :options="courses" v-model="filter.courses" placeholder="Choose course" @input="changeCourse"/>


                                </div>
                                <div class="h-15px me-3" style="width: 218px">
                                    <label>Unit </label>
                                    <treeselect :options="units" v-model="filter.units" placeholder="Choose unit"/>
                                </div>

                            </div>
                            <div class="d-flex mt-20">
                                <div class="btn btn-primary" @click="doFilter">Search</div>
                            </div>
                            <div class="d-flex mb-1 mt-5">
                                <div class="mh-300px scroll-y me-n7 pe-7" style="width: 703px">
                                    <table class="table table-row-bordered align-middle gy-4 gs-9">
                                        <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                                        <tr>
                                            <td width="25">
                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox" v-model="package.allLessonSelected" @change="selectLessonAll()">
                                                </div>
                                            </td>
                                            <th class="">Name of lesson</th>
                                            <th class="">Grade</th>
                                            <th class="">Subject</th>
                                            <th></th>
                                        </tr>
                                        </thead>

                                        <tbody  v-for="lessonPackagePlan in lessonPackagePlans" v-if="lessonPackagePlan.package_id==package.package">
                                        <tr v-for="lesson in entries" >
                                            <td class="">
                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox" v-model="lessonPackagePlan.lessonIds" :value="lesson.id"  @change="updateLessonAll()">
                                                </div>
                                            </td>
                                            <td class="" v-text="lesson.name"></td>
                                            <td class="" v-text="lesson.grade"></td>
                                            <td class="" v-text="lesson.subject"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button  class="btn btn-primary" style="margin: 20px 0px 0px" @click="addLesson()">
                                    Confirm
                                </button>
                                <!-- <button  class="btn btn-primary" style="margin: 20px 0px 0px" disabled>
                                    Confirm
                                </button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- END: MODAL ADD LESSON PACKAGE PLAN -->


            <div class="col-lg-12">

                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-6" style="margin:0px 0px -35px">
                        <div class="card-title"></div>
                        <div class="card-toolbar"></div>
                    </div>
                    <div class="card-body d-flex flex-column">

                         <!-- BEGIN : THONG TIN PLAN -->

                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group col-lg-8">
                                        <label>Plan name <span class="text-danger">*</span></label>
                                        <input :disabled="permissionFields['plan_name']==false" v-model="entry.name" class="form-control" placeholder="Enter the name of plan">
                                        <error-label :errors="errors.name" for="f_school_name"></error-label>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Due date </label>
                                        <Datepicker :disabled="permissionFields['plan_due_date']==false" v-model="entry.due_at" readonly/>
                                         <span  v-if="entry.due_at!='' && permissionFields['plan_due_date']==true" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0" @click="dueAtClear">
                                            <svg type="button" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" style="float: right;margin: -32px 3px 0px;">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" style="fill:red"/>
                                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" style="fill:red"/>
                                            </svg>
                                        </span>
                                        <error-label :errors="errors.due_at" for="f_title"></error-label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-8">
                                        <label>Plan description</label>
                                        <textarea :disabled="permissionFields['plan_description']==false" class="form-control"
                                                  placeholder="Enter the description" v-model="entry.plan_description">
                                        </textarea>
                                        <error-label :errors="errors.plan_description"></error-label>
                                    </div>
                                     <div class="form-group col-lg-4">
                                        <label> Assign to IT <span class="text-danger">*</span></label>
                                        <select :disabled="permissionFields['plan_assign_to_IT']==false" class="form-control form-select" v-model="idRoleIt">
                                            <option v-for="role in roleIt" :value="role.id">{{role.full_name}}</option>
                                        </select>
                                        <error-label :errors="errors.idRoleIt"></error-label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label>Expire date <span class="text-danger">*</span></label>
                                        <Datepicker :disabled="permissionFields['plan_expire_date']==false" v-model="entry.expire_date" readonly/>
                                        <span v-if="entry.expire_date!='' && permissionFields['plan_expire_date']==true" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0" @click="expireDateClear">
                                            <svg type="button" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" style="float: right;margin: -32px 3px 0px;">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" style="fill:red"/>
                                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" style="fill:red"/>
                                            </svg>
                                        </span>
                                        <error-label :errors="errors.expire_date" for="f_title"></error-label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="card-title ">
                                        <ul class="nav nav-stretch nav-line-tabs border-transparent" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a id="kt_billing_6months_tab" class="nav-link fs-5 fw-bold me-3 active"
                                                   data-bs-toggle="tab" role="tab"
                                                   href="billing.html#kt_billing_months">Device</a>
                                            </li>
                                            <li v-for="(packageLesson,index) in lessonPackagePlans" class="nav-item" role="presentation">
                                                <a :id="'kt_billing_1year_tab' +index" class="package-lesson-link nav-link fs-5 fw-bold me-3" :class="packageLesson.className"
                                                   data-bs-toggle="tab" role="tab" href="#kt_billing_year"
                                                   @click="tabPackageLesson(packageLesson.package_id)">{{packageLesson.name}}</a>
                                            </li>
                                            <li >
                                                <a v-if="permissionFields['plan_add_package']==false" class="btn btn-primary btn-active-primary btn-sm mt-2 ml-2 isDisabled"
                                                   data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" @click="addPackageLessonModal">Add package
                                                </a>
                                                <a v-else class="btn btn-primary btn-active-primary btn-sm mt-2 ml-2 "
                                                   data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" @click="addPackageLessonModal">Add package
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- END : THONG TIN PLAN -->

                                <div class="tab-content">

                                    <!--BEGIN: LIST DEVICE PLAN-->

                                    <div id="kt_billing_months" class="card-body p-0 tab-pane fade show active" role="tabpanel" aria-labelledby="kt_billing_months">
                                        <div class="d-flex justify-content-end mb-4" >
                                            <a v-if="deviceIds!=''" class="btn btn-danger btn-sm mr-3" @click="removeDeviceAll" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Remove device</a>
                                                <a href="list.html#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                    <span class="svg-icon svg-icon-5 m-0">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black"/>
															</svg>
                                                    </span>
                                                </a>
                                            <div class="menu menu-sub menu-sub-dropdown  menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 py-4" data-kt-menu="true" style="width: 150px">
                                                <div class="menu-item px-3">
                                                    <a v-if="permissionFields['plan_add_device']==false" class="menu-link px-3 isDisabled"  @click="addDevice()">Add a device</a>
                                                    <a v-else class="menu-link px-3" @click="addDevice()">Add a device</a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a v-if="permissionFields['plan_import_device']==false" class="menu-link px-3 isDisabled" @click="importDevice()">Import devices</a>
                                                    <a class="menu-link px-3" @click="importDevice()" v-else>Import devices</a>
                                                </div>

                                                <div class="menu-item px-3">
                                                    <a class="menu-link px-3 isDisabled" @click="exportDevice" v-if="permissionFields['plan_export_device']==false">Export device list</a>
                                                    <a class="menu-link px-3 " @click="exportDevice" v-if="data.length>0 && permissionFields['plan_export_device']==true" >Export device list</a>
                                                    <a class="menu-link px-3 isDisabled" @click="exportDevice" v-if="data.length==0">Export device list</a>

                                                </div>

                                            </div>
                                        </div>
                                        <!-- BEGIN : TABLE LIST DEVICE-->

                                    <div class="mh-650px scroll-y me-n7 pe-7">
                                        <table class="table table-row-bordered align-middle gy-4 gs-9">
                                            <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                                                <tr>
                                                    <td width="25">
                                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="checkbox" :disabled="permissionFields['plan_delete_device']==false" v-model="allDeviceSelected" @change="selectDeviceAll()">
                                                        </div>
                                                    </td>
                                                    <td>No.</td>
                                                    <th class="">Device name</th>
                                                    <th class="">OS</th>
                                                    <td>Expire date</td>
                                                    <th class="">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                            <tr v-for="(device,index) in data" >
                                                <td class="">
                                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="checkbox" :disabled="permissionFields['plan_delete_device']==false"  v-model="deviceIds" :value="device.id" @change="updateDeviceCheckAll">
                                                    </div>
                                                </td>
                                                <td>{{index+1}}</td>
                                                <td>{{device.device_name}}</td>
                                                <td>{{device.type}}</td>
                                                <td>{{device.expire_date}}</td>
                                                <td class="">
                                                    <a  class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                                        <span class="svg-icon svg-icon-5 m-0">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                                <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black" />
                                                                            </svg>
                                                                        </span>
                                                    </a>
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-180px py-4" data-kt-menu="true">
                                                        <div class="menu-item px-3">
                                                            <a v-if="permissionFields['plan_edit_device_information']==false"   class="menu-link px-3 isDisabled">Edit</a>
                                                            <a v-else class="menu-link px-3" @click="modalEditDevice(device)">Edit</a>
                                                        </div>
                                                        <div class="menu-item px-3">
                                                            <a v-if="permissionFields['plan_device_get_confirm_code']==false"   class="menu-link px-3 isDisabled">Get confirmation code</a>
                                                            <a v-else class="menu-link px-3" @click="modalConfirmationCode(device)">Get confirmation code</a>
                                                        </div>
                                                        <div class="menu-item px-3">
                                                            <a v-if="permissionFields['plan_delete_device']==false" class="menu-link text-danger px-3 isDisabled" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" @click="removeDeviceModal(device.id)">Delete</a>
                                                            <a v-else class="menu-link text-danger px-3 " data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" @click="removeDeviceModal(device.id)">Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        </div>

                                        <!--END : TABLE LIST DEVICE-->
                                    </div>
                                    <!-- END: DEVICE LIST PLAN -->
                                    <!--BEGIN: PACKAGE LESSON PLAN -->


                                        <div id="kt_billing_year" class="card-body p-0 tab-pane fade"  role="tabpanel" aria-labelledby="kt_billing_year" >
                                            <div class="d-flex justify-content-end mb-4" >
                                                <a v-if="dataTableLesson.checkRemoveLesson==1 && dataZipLesson.status=='waitting' && permissionFields['plan_remove_lesson']==true" class="btn btn-danger btn-sm mr-3" @click="deleteAllLesson" >Remove lesson</a>
                                                <a v-if="dataTableLesson.checkRemoveLesson==1 && dataZipLesson.status=='waitting' && permissionFields['plan_remove_lesson']==false" class="btn btn-danger btn-sm mr-3 isDisabled" @click="deleteAllLesson" >Remove lesson</a>
                                             <a  v-if="dataZipLesson.status=='running' || dataZipLesson.status=='inprogress'"  class="mt-2 mr-5" style="color: rgb(230 180 0)"> Lesson list is packaging...</a>
                                                <a  v-if="  dataZipLesson.status=='done' && permissionFields['plan_download_package']==true" :href="dataZipLesson.url" class="btn btn-primary btn-sm mr-3 "> Download package</a>
                                                <a  v-if="  dataZipLesson.status=='done' && permissionFields['plan_download_package']==false" :href="dataZipLesson.url" class="btn btn-primary btn-sm mr-3 isDisabled"> Download package</a>
                                                <a v-if="dataZipLesson.status=='running' || dataZipLesson.status=='inprogress'" class="btn btn-light btn-active-light-primary btn-sm isDisabled" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" >Actions
                                                    <span class="svg-icon svg-icon-5 m-0">
															<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viewBox="0 0 24 24" fill="none">
																<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black"/>
															</svg>
                                                    </span>
                                                </a>
                                                <a v-else class="btn btn-light btn-active-light-primary btn-sm " data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" >Actions
                                                    <span class="svg-icon svg-icon-5 m-0">
															<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viewBox="0 0 24 24" fill="none">
																<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black"/>
															</svg>
                                                    </span>
                                                </a>
                                                <div class="menu menu-sub menu-sub-dropdown  menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 py-4" data-kt-menu="true" >
                                                    <div class="menu-item px-3" >
                                                        <a class="menu-link px-3 "  v-if="permissionFields['plan_add_lesson']==true && dataZipLesson.status=='waitting'" @click="addLessonPackage(tabLessonContent)" >Add lesson</a>
                                                        <a class="menu-link px-3 isDisabled"  v-if="permissionFields['plan_add_lesson']==false && dataZipLesson.status=='waitting'" @click="addLessonPackage(tabLessonContent)" >Add lesson</a>
                                                    </div>
                                                    <div class="menu-item px-3" v-if="dataZipLesson.status=='waitting'">
                                                        <a v-if="checkZipPackage[0].lessonIds.length>0 && permissionFields['plan_zip_package_lesson']==true" class="menu-link px-3" @click="downloadLesson(tabLessonContent)">Zip lesson package</a>
                                                        <a v-else class="menu-link px-3 isDisabled">Zip lesson package</a>
                                                    </div>
                                                    <div class="menu-item px-3" >
                                                        <a class="menu-link px-3 " v-if="permissionFields['plan_rename_lesson_package']==true" @click="renameLessonPackage(tabLessonContent)" >Rename lesson package</a>
                                                        <a class="menu-link px-3 isDisabled" v-else @click="renameLessonPackage(tabLessonContent)" >Rename lesson package</a>
                                                    </div>
                                                    <div class="menu-item px-3" v-if="permissionFields['plan_delete_lesson_package']==true && dataZipLesson.status=='done' ||  permissionFields['plan_delete_lesson_package']==true &&dataZipLesson.status=='waitting'">
                                                        <a class="menu-link px-3 text-danger "  @click="deletePackageLessonModal(tabLessonContent)" >Delete lesson package</a>
                                                    </div>
                                                    <div class="menu-item px-3" v-if="permissionFields['plan_delete_lesson_package']==false && dataZipLesson.status=='done' ||  permissionFields['plan_delete_lesson_package']==false &&dataZipLesson.status=='waitting'">
                                                        <a class="menu-link px-3 text-danger isDisabled"  @click="deletePackageLessonModal(tabLessonContent)" >Delete lesson package</a>
                                                    </div>
                                                </div>

                                            </div>
                                           <div class="d-flex flex-stack pt-4 pl-9 pr-9">

                            <div class="badge badge-lg badge-light-dark mb-15" style="margin-top:-65px;margin-left:-30px">
                                <div class="d-flex align-items-center flex-wrap">
                                   {{dataAddLessonPlan.length}} lesson
                                </div>
                            </div>
                        </div>
                                            <div class="mh-650px scroll-y me-n7 pe-7">
                                                <table class="table table-row-bordered align-middle gy-4 gs-9">
                                                    <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                                                        <tr >
                                                            <td width="25">
                                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                    <input v-if="dataZipLesson.status=='waitting'" :disabled="permissionFields['plan_remove_lesson']==false" class="form-check-input" type="checkbox" v-model="allViewLessonSelected.allViewLessonSelected" @change="selectViewLessonAll(tabLessonContent)" >
                                                                    <input v-if="dataZipLesson.status!='waitting'" disabled class="form-check-input" type="checkbox" v-model="allViewLessonSelected.allViewLessonSelected" @change="selectViewLessonAll(tabLessonContent)" >
                                                                </div>
                                                            </td>
                                                            <td>No.</td>
                                                            <th class="">Lesson name</th>
                                                            <th class="">Grade</th>
                                                            <th class="">Subject</th>
                                                            <th class="">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody >
                                                        <tr v-for="(lesson,index) in dataAddLessonPlan" >
                                                            <td class="">
                                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                    <input v-if="dataZipLesson.status=='waitting'" :disabled="permissionFields['plan_remove_lesson']==false" class="form-check-input" type="checkbox" v-model="dataTableLesson.viewLesson" :value="lesson.id" @change="updateViewLessonCheckAll()">
                                                                    <input v-if="dataZipLesson.status!='waitting'" disabled class="form-check-input" type="checkbox" v-model="dataTableLesson.viewLesson" :value="lesson.id" @change="updateViewLessonCheckAll()">
                                                                </div>
                                                            </td>
                                                            <td>{{index+1}}</td>
                                                            <td>{{lesson.name}}</td>
                                                            <td>{{lesson.grade}}</td>
                                                            <td>{{lesson.subject}}</td>
                                                            <td class="">
<!--                                                                <a v-if="dataZipLesson.status=='waitting' && permissionFields['plan_remove_lesson']==true"  class="btn btn-active-danger btn-light-danger btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" @click="deleteLessonModal(lesson.id)">Delete</a>-->
<!--                                                                <a v-if="dataZipLesson.status=='waitting' && permissionFields['plan_remove_lesson']==false"  class="btn btn-active-danger btn-light-danger btn-sm isDisabled" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" @click="deleteLessonModal(lesson.id)">Delete</a>-->
                                                                <button v-if="dataZipLesson.status=='waitting'" :disabled="permissionFields['plan_remove_lesson']==false"  class="btn btn-active-danger btn-light-danger btn-sm"  @click="deleteLessonModal(lesson.id)">Delete</button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr style="margin-top: 5px;">

                    <div class="mt-3 mb-5" style="margin-left: 18px">
                        <button type="reset" @click="save()" class="btn btn-primary mr-2">Save plan</button>
                        <button :disabled="permissionFields['plan_export_plan']==false" type="reset" class="btn btn-primary mr-2" @click="exportPlan"><i
                            class="bi bi-arrow-up-square-fill"></i>Export plan
                        </button>
                        <button type="reset" @click="backIndex()" class="btn btn-secondary"
                                style="margin:0px 12px 0px">Cancel
                        </button>

                    </div>
                </div>
            </div>

            <!-- END : PACKAGE LESSON PLAN -->

        </div>

        <!-- Begin:modal add device-->
         <div class="modal fade" style="margin-right:50px " id="deviceConfirm" tabindex="-1" role="dialog"
             aria-labelledby="deviceConfirm"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                 style="max-width: 500px;">
                <div class="modal-content box-shadow-main paymment-status" style="margin-right:20px; left:140px">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 style="text-align: center;" class="pt-7 fs-1 fw-bolder">Register New Device</h3>
                    <div class="px-10 py-5 text-left">
                        <div class="d-flex align-items-start justify-content-start mb-5">
                            <span class="svg svg-icon mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-1-circle" viewBox="0 0 16 16">
                                    <path d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8Zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0ZM9.283 4.002V12H7.971V5.338h-.065L6.072 6.656V5.385l1.899-1.383h1.312Z"/>
                                </svg>
                            </span>
                            <span>Open iDIGI application on your device.</span>
                        </div>
                        <div class="d-flex align-items-start justify-content-start mb-5">
                            <span class="svg svg-icon mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-2-circle" viewBox="0 0 16 16">
                                  <path d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8Zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0ZM6.646 6.24v.07H5.375v-.064c0-1.213.879-2.402 2.637-2.402 1.582 0 2.613.949 2.613 2.215 0 1.002-.6 1.667-1.287 2.43l-.096.107-1.974 2.22v.077h3.498V12H5.422v-.832l2.97-3.293c.434-.475.903-1.008.903-1.705 0-.744-.557-1.236-1.313-1.236-.843 0-1.336.615-1.336 1.306Z"/>
                                </svg>
                            </span>
                            <span>Click on button "Get device information" and copy "Register Code".</span>
                        </div>
                        <div class="d-flex align-items-start justify-content-start mb-7">
                            <span class="svg svg-icon mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-3-circle" viewBox="0 0 16 16">
                                  <path d="M7.918 8.414h-.879V7.342h.838c.78 0 1.348-.522 1.342-1.237 0-.709-.563-1.195-1.348-1.195-.79 0-1.312.498-1.348 1.055H5.275c.036-1.137.95-2.115 2.625-2.121 1.594-.012 2.608.885 2.637 2.062.023 1.137-.885 1.776-1.482 1.875v.07c.703.07 1.71.64 1.734 1.917.024 1.459-1.277 2.396-2.93 2.396-1.705 0-2.707-.967-2.754-2.144H6.33c.059.597.68 1.06 1.541 1.066.973.006 1.6-.563 1.588-1.354-.006-.779-.621-1.318-1.541-1.318Z"/>
                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0ZM1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8Z"/>
                                </svg>
                            </span>
                            <span>Paste it to the following input field.</span>
                        </div>
                         <datepicker readonly v-model="deviceExpireDate" class="form-control mb-4" ></datepicker>
                         <span v-if="deviceExpireDate!=''" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0" @click="deviceExpireDateClear">
                                            <svg type="button" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" style="float: right;margin: -45px 3px 0px;">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" style="fill:red"/>
                                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" style="fill:red"/>
                                            </svg>
                                        </span>
                        <error-label for="f_category_id" :errors="errors.deviceExpireDate"></error-label>

                        <input type="text" class="form-control " placeholder="Enter the device name" aria-label="" style="margin-bottom: 10px" aria-describedby="basic-addon1" v-model="deviceName">
                        <error-label for="f_category_id" :errors="errors.deviceName"></error-label>
                        <input type="text" class="form-control " placeholder="Enter the register code" aria-label="" aria-describedby="basic-addon1" v-model="deviceUid">
                        <error-label for="f_category_id" :errors="errors.deviceUid"></error-label>
                        <error-label for="f_category_id" :errors="errors.device_uid"></error-label>
                    </div>
                    <div class="form-group d-flex justify-content-center">
                        <!--                        <button  class="btn btn-danger ito-btn-small" data-dismiss="modal" @click="save()">Add now</button>-->
                        <button class="btn btn-primary ito-btn-add mr-3" data-dismiss="modal" @click="saveDevice()">
                            <i class="bi bi-send mr-1"></i>Add Device
                        </button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- end: modal add device-->

        <!-- begin :modal edit device -->
        <div class="modal fade" style="margin-right:50px " id="deviceConfirm1" tabindex="-1" role="dialog"
             aria-labelledby="deviceConfirm"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                 style="max-width: 500px;">
                <div class="modal-content box-shadow-main paymment-status" style="margin-right:20px; left:140px;" >

                        <div class="btn btn-sm btn-icon btn-active-color-primary " data-bs-dismiss="modal" style="margin-left: 440px;cursor: pointer;width: 50px;height: 50px" >
                                <span class="svg-icon svg-icon-1" data-bs-dismiss="modal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                              rx="1" transform="rotate(-45 6 17.3137)"
                                              fill="black"/>
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"/>
                                    </svg>
                                </span>
                        </div>
                    <label style="text-align: center; margin-top: -20px" class="pt-7 fs-1 ">Edit device information </label>
                    <div class="px-10 py-5 text-left">
                        <label style="margin-bottom: 0px">Device name</label>
                        <input type="text" class="form-control " placeholder="Enter the device name" aria-label="" style="margin-bottom: 10px" aria-describedby="basic-addon1" v-model="dataDeviceEdit.name">
                        <error-label for="f_category_id" :errors="errors.edit_name_device"></error-label>
                        <span>OS</span>
                       <select class="form-control " style="margin: 0px 0px 15px" v-model="dataDeviceEdit.os">
                           <option value="Windows">Windows</option>
                           <option value="MacOs">MacOs</option>
                       </select>
                        <span>Expire date</span>

                        <Datepicker readonly   class="form-control "  v-model="dataDeviceEdit.expired" ></Datepicker>
                        <error-label for="f_category_id" :errors="errors.edit_device_date"></error-label>
                    </div>
                    <div class="form-group d-flex justify-content-center">
                        <!--                        <button  class="btn btn-danger ito-btn-small" data-dismiss="modal" @click="save()">Add now</button>-->
                        <button class="btn btn-primary ito-btn-add mr-3" data-dismiss="modal" @click="saveEditDevice(dataDeviceEdit)">Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end :modal edit device -->

        <!-- BEGIN: MODAL IMPPORT DEVICE -->

        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <div class="modal fade" id="kt_modal_create_app" tabindex="-1" aria-hidden="true">
                    <!--begin::Modal dialog-->
                    <div class="modal-dialog modal-dialog-centered mw-900px">
                        <!--begin::Modal content-->
                        <div class="modal-content">
                            <!--begin::Modal header-->
                            <div class="modal-header">
                                <!--begin::Modal title-->
                                <h2>Import device</h2>
                                <!--end::Modal title-->
                                <!--begin::Close-->
                                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
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
                            <div class="modal-body py-lg-10 px-lg-10">
                                <!--begin::Stepper-->
                                <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid" id="kt_modal_create_app_stepper">
                                    <!--begin::Aside-->
                                    <div class="d-flex justify-content-center justify-content-xl-start flex-row-auto w-100 w-xl-300px">
                                        <!--begin::Nav-->
                                        <div class="stepper-nav ps-lg-10">
                                            <!--begin::Step 1-->
                                            <div class="stepper-item current" data-kt-stepper-element="nav">
                                                <!--begin::Line-->
                                                <div class="stepper-line w-40px"></div>
                                                <!--end::Line-->
                                                <!--begin::Icon-->
                                                <div class="stepper-icon w-40px h-40px">
                                                    <i class="stepper-check fas fa-check"></i>
                                                    <span class="stepper-number">1</span>
                                                </div>
                                                <!--end::Icon-->
                                                <!--begin::Label-->
                                                <div class="stepper-label">
                                                    <h3 class="stepper-title">Upload file</h3>
                                                </div>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Step 1-->
                                            <!--begin::Step 2-->
                                            <div class="stepper-item" data-kt-stepper-element="nav">
                                                <!--begin::Line-->
                                                <div class="stepper-line w-40px"></div>
                                                <!--end::Line-->
                                                <!--begin::Icon-->
                                                <div class="stepper-icon w-40px h-40px">
                                                    <i class="stepper-check fas fa-check"></i>
                                                    <span class="stepper-number">2</span>
                                                </div>
                                                <!--begin::Icon-->
                                                <!--begin::Label-->
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
                                        <form class="form" novalidate="novalidate" id="kt_modal_create_app_form">
                                            <!--begin::Step 1-->
                                            <div class="current" data-kt-stepper-element="content">
                                                <div class="w-100">
                                                    <!--begin::Input group-->
                                                    <div class="fv-row mb-10">
                                                        <div  class="dropzone dropzone-queue mb-2 ">
                                                            <label   for="file-upload" class="btn btn-primary btn-active-primary btn-sm">
                                                                Upload file
                                                            </label>
                                                            <label >Click to select file (*.xls, *.xlsx). Max file size is 5Mb</label>
                                                            <!--                                                    <button for="file-upload" class="btn btn-primary" > Upload file</button>-->
                                                            <input type="file" id="file-upload" ref="uploader" class="form-control-file" @change="importFileDevice">
                                                            <error-label></error-label>
                                                            <div class="dropzone-items wm-200px"></div>

                                                            <div class="dropzone-item p-5" v-if="fileUpLoad!=''">
                                                                <!--begin::File-->
                                                                <div class="dropzone-file">
                                                                    <div class="dropzone-filename text-dark" title="some_image_file_name.jpg">
                                                                        <span data-dz-name="">{{fileUpLoad}}</span>
                                                                        <strong>(
                                                                            <span data-dz-size="">{{sizeFile}}</span>)</strong>
                                                                    </div>
                                                                    <div class="dropzone-error mt-0" data-dz-errormessage=""></div>
                                                                </div>
                                                                <div class="dropzone-toolbar">
                                                                    <span class="dropzone-delete" data-dz-remove="">
																			<i style="font-size: 15px; color: red" class="bi bi-trash" @click="removeFileDevice"></i>
																		</span>
                                                                </div>
                                                            </div>
                                                            <error-label :errors="errors.sizeFile"></error-label>
                                                        </div>
                                                        <div v-if="valueValidateImportDevice==0" class="dropzone-panel mb-4  ">
                                                            <a class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" @click="downloadTemplate()" ><i class="bi bi-download mr-2"></i>Download template here</a>

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
                                                        <label class="d-flex align-items-center fs-5 fw-bold mb-4">
                                                            <span class="required">Validation result</span>
                                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="validation result"></i>
                                                        </label>
                                                    </div>
                                                    <div v-if="valueValidateImportDevice!=0" class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                                                        <div class="d-flex align-items-center">
                                                            <div class="ms-6">

                                                                <div class="fw-bold text-muted">{{fileImport.length}} new
                                                                    record(s)
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex">
                                                            <div class="text-end">
                                                                <span class="form-check form-check-custom form-check-solid">
                                                                    <input class="form-check-input" type="radio" name="category" value="0" v-model="doNotImport"/>
                                                                    <label style="margin: 0px 10px 0px">Import</label>
                                                                    <input class="form-check-input" type="radio"
                                                                           name="category" value="1" v-model="doNotImport"/>
                                                                    <label style="margin: 0px 10px 0px"> Do not import </label>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-if="valueValidateImportDevice!=0"
                                                         class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                                                        <div class="d-flex align-items-center">
                                                            <div class="ms-6">

                                                                <div class="fw-bold text-muted">{{deviceError.length}} error
                                                                    record(s)
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex" v-if="deviceError.length>0">
                                                            <div class="text-end">
                                                                <div class="fs-7 text-muted">
                                                                    <a @click="exportDeviceError" type="button" class="btn btn-primary">Export</a>
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
                                                    <label v-if="doNotImport==0">Do you want to import {{fileImport.length}} records?</label>
                                                        <label v-if="doNotImport==1"> Do you want to not import {{fileImport.length}} records?</label>
                                                    </div>
                                                    <!--end::Input group-->
                                                </div>
                                            </div>
                                            <div class="d-flex flex-stack pt-10">
                                                <!--begin::Wrapper-->
                                                <div class="me-2">
                                                    <button type="button" class="btn btn-lg btn-light-primary me-3" data-kt-stepper-action="previous">
                                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr063.svg-->
                                                        <span class="svg-icon svg-icon-3 me-1">
																		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																			<rect opacity="0.5" x="6" y="11" width="13" height="2" rx="1" fill="black" />
																			<path d="M8.56569 11.4343L12.75 7.25C13.1642 6.83579 13.1642 6.16421 12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75L5.70711 11.2929C5.31658 11.6834 5.31658 12.3166 5.70711 12.7071L11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25C13.1642 17.8358 13.1642 17.1642 12.75 16.75L8.56569 12.5657C8.25327 12.2533 8.25327 11.7467 8.56569 11.4343Z" fill="black" />
																		</svg>
																	</span>
                                                        <!--end::Svg Icon-->Back</button>
                                                </div>
                                                <!--end::Wrapper-->
                                                <!--begin::Wrapper-->
                                                <div>
                                                    <button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="submit" @click="saveImport()">
																		<span class="indicator-label">Submit
                                                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
																		<span class="svg-icon svg-icon-3 ms-2 me-0">
																			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																				<rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black" />
																				<path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black" />
																			</svg>
																		</span>
                                                                          </span>
                                                    </button>
                                                    <button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="next"  :disabled="disableContinue==false">Continue
                                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
                                                        <span class="svg-icon svg-icon-3 ms-1 me-0">
																		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																			<rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black" />
																			<path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black" />
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

        <!-- END : MOADAL IMPORT DEVIVE -->

        <!--BEGIN : MODAL DELETE DEVICE PLAN -->
                <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="deleteDevice" tabindex="-1" role="dialog"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                         style="max-width: 450px;">
                        <div class="modal-content box-shadow-main paymment-status" style="left:120px;text-align: center; padding: 20px 0px 55px;">
                            <div class="close-popup" data-dismiss="modal"></div>
                            <div class="swal2-icon swal2-warning swal2-icon-show">
                                <div class="swal2-icon-content" style="margin: 0px 25px 0px ">!</div>
                            </div>
                            <div class="swal2-html-container">
                                <p >Are you sure to delete this device?</p>
                            </div>
                            <div class="swal2-actions">
                                <button type="submit" id="kt_modal_new_target_submit" class="swal2-confirm btn fw-bold btn-danger" @click="removeDevice(deleteDevice)">
                                    <span class="indicator-label">Yes, delete!</span>
                                </button>
                                <button type="reset" id="kt_modal_new_target_cancel" class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal" style="margin: 0px 8px 0px">No, cancel</button>

                            </div>

                        </div>
                    </div>
                </div>

        <!-- END : MODAL DELETE DEVICE PLAN -->

        <!-- BEGIN : MODAL DELETE LESSON PLAN -->

                <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="deleteLesson" tabindex="-1" role="dialog"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                         style="max-width: 450px;">
                        <div class="modal-content box-shadow-main paymment-status" style="left:120px;text-align: center; padding: 20px 0px 55px;">
                            <div class="close-popup" data-dismiss="modal"></div>
                            <div class="swal2-icon swal2-warning swal2-icon-show">
                                <div class="swal2-icon-content" style="margin: 0px 25px 0px ">!</div>
                            </div>
                            <div class="swal2-html-container">
                                <p >Are you sure to delete this lesson?</p>
                            </div>
                            <div class="swal2-actions">
                                <button type="submit" id="kt_modal_new_target_submit" class="swal2-confirm btn fw-bold btn-danger" @click="deleteLesson(deleteLessons)">
                                    <span class="indicator-label">Yes, delete!</span>
                                </button>
                                <button type="reset" id="kt_modal_new_target_cancel" class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal" style="margin: 0px 8px 0px">No, cancel</button>

                            </div>

                        </div>
                    </div>
                </div>

        <!-- END : MODAL DELETE LESSON PLAN -->

        <!-- BEGIN: MODAL ADD NAME PACKAGE LESSON -->

        <div class="modal fade" style="margin-right:50px " id="addNamePackageLesson" tabindex="-1" role="dialog"
             aria-labelledby="addNamePackageLesson"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                 style="max-width: 500px;">
                <div  class="modal-content box-shadow-main paymment-status" style="margin-right:20px; left:140px">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 style="text-align: center;" class="pt-7 fs-1 fw-bolder">Lesson package name</h3>
                    <div class="px-10 py-5 text-left">
                        <label>Lesson package <span class="text-danger">*</span></label>
                        <input type="text" class="form-control " placeholder="Enter the lesson package name" aria-label="" style="margin-bottom: 10px" aria-describedby="basic-addon1" v-model="packageLessonName">
                        <error-label for="f_category_id" :errors="errors.packageLessonName"></error-label>
                    </div>
                    <div class="form-group d-flex justify-content-center">
                        <button class="btn btn-primary ito-btn-add mr-3" data-dismiss="modal" @click="addPackageLesson(tabLessonContent)">
                           Save
                        </button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- END :MODAL ADD NAME PACKAGE LESSON -->

        <!--BEGIN: MODAL DELETE PACKAGE LESSON -->

        <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="delete" tabindex="-1" role="dialog"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                 style="max-width: 450px;">
                <div class="modal-content box-shadow-main paymment-status" style="left:120px;text-align: center; padding: 20px 0px 55px;">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <div class="swal2-icon swal2-warning swal2-icon-show">
                        <div class="swal2-icon-content" style="margin: 0px 25px 0px ">!</div>
                    </div>
                    <div class="swal2-html-container">
                        <p >Are you sure to delete this package lesson?</p>
                    </div>
                    <div class="swal2-actions">
                        <button type="submit" id="kt_modal_new_target_submit1" class="swal2-confirm btn fw-bold btn-danger" @click="deletePackageLesson(deletePack)">
                            <span class="indicator-label">Yes, delete!</span>
                        </button>
                        <button type="reset" id="kt_modal_new_target_cancel1" class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal" style="margin: 0px 8px 0px">No, cancel</button>

                    </div>

                </div>
            </div>
        </div>

        <!--END :MODAL DELETE PACKAGE LESSON -->

        <!-- BEGIN:MODAL GET CONFIRMATION CODE -->
         <div class="modal fade" id="editdeviceConfirm" tabindex="-1" role="dialog"
             aria-labelledby="editdeviceConfirm"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document">
                <div class="modal-content" style="margin-right:20px; left:140px">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 style="margin:20px auto;font-weight: 500;" class="popup-title success">  Get confirmation code</h3>
                    <div class="content" style="margin: -10px 39px 14px">
                        <label>Device Name</label>
                        <input type="text" class="form-control " placeholder="Device name" aria-label=""
                               style="margin-bottom: 10px" aria-describedby="basic-addon1" v-model="deviceGetCode.device_name"
                               disabled>
                        <div>
                            <button  type="button" class="btn btn-primary" v-on:click="genToken"> Generate Key</button>
                        </div>
                        <div style="text-align:right"><button type="button" v-if="token" class="btn btn-primary" v-on:click="copyTextToken" title="Copy Token" style="padding: 5px 20px;margin:0px 7px 10px"> Copy</button></div>

                        <div v-if="token" style="font-size: 14px; word-wrap: break-word;white-space: pre-wrap;word-break: normal;background-color: #f7f7f9;">{{token}}</div>
                    </div>
                </div>
            </div>
        </div>
        <!--END:MODAL GET CONFIRMATION CODE -->
    </div>

</template>

<script>
    import {$get, $post, forEach, getTimeRangeAll} from "../../utils";
    import ActionBar from "../includes/ActionBar";
    import $router from "../../lib/SimpleRouter";
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'
    import Treeselect from "@riophae/vue-treeselect";
    import _ from "lodash";

    let created = getTimeRangeAll();
    const $q = $router.getQuery();

    export default {
        name: "PlanEdit.vue",
        components: {ActionBar, Treeselect},
        data() {
            let filter = {
                keyword: $q.keyword || '',
                name: $q.name || '',
                subject: $q.subject || '',
                grade: $q.grade || '',
                units: $q.units != 'undefined' && $q.units != 'null' ? $q.units : null,
                courses: $q.courses != 'undefined' && $q.courses != 'null' ? $q.courses : null,

            };

            return {
                allUnits:$json.units || [],
                courses:$json.courses || [],
                units:$json.units || [],
                dataDeviceEdit:[],
                disableContinue:false,
                cachePlan:$json.cachePlan,
                cacheLesson:$json.cacheLesson,
                permissionFields:$json.permissionFields || [],
                deviceGetCode:[],
                token:'',
                deletePack:'',
                idRenamePackageLesson:'',
                packageLessonName:'',
                valueValidateImportDevice:0,
                sizeFile:'',
                fileUpLoad:'',
                deleteLessons:'',
                deleteDevice:'',
                viewLessonIds:[],
                lessonIds:[],
                device:[],
                deviceIds:[],
                tabLessonContent: '',
                roleAuth: $json.roleAuth,
                lessons: [],
                viewPackage: this.viewPackage,
                packagePlan: $json.packagePlan,
                package: [],
                idListDevice: [],
                nameSchool: $json.nameSchool || [],
                schoolPlan: $json.schoolPlan || [],
                schoolId: '',
                schools: $json.schools || [],
                exportDevicePlan: '',
                lessonPackagePlans: $json.lessonPackagePlans,
                dataTableLesson:[],
                // data: $json.data || [],
                data:[],
                allDeviceSelected:false,
                allViewLessonSelected:[],
                filter: filter,
                entries: [],
                doNotImport:0,
                deviceError: [],
                code: 0,
                validateFile: '',
                fileImport: [],
                deviceExpireDate:'',
                deviceName: '',
                deviceUid: '',
                urls: $json.urls || [],
                idRoleIt: $json.idRoleIt,
                roleIt: $json.roleIt || [],
                breadcrumbs: [
                    {
                        title: 'Plan management',
                        url: '/xadmin/plans/index'
                    },
                    {
                        title: 'Plan details'
                    },
                ],
                entry: $json.entry || {},
                isLoading: false,
                errors: {},
                limit: $q.limit || 25,
                from: 0,
                to: 0,
                paginate: {
                    currentPage: 1,
                    lastPage: 1,
                    totalRecord: 0
                },
                dataAddLessonPlan:[],
                dataZipLesson:[],
                improgress:'',
                abc:'',
                checkZipPackage:[],
                errorDeviceName:'',
                exportDeviceName:'',

            }
        },

        mounted() {
            $router.on('/', this.load).init();
        },
        methods: {
            changeCourse(){
                this.filter.units = null;
              if(this.filter.courses){
                  this.units = this.allUnits.filter(e=>e.course_id == this.filter.courses);
              }else{
                  this.units = this.allUnits;
              }
            },
            selectProvince() {
                this.filter.district_id = null;
                this.districts = [];
                if (this.filter.province_id) {
                    this.districts = this.provinces.filter(e => e.id == this.filter.province_id)[0]['districts'];
                }

            },
            cancelModalLesson()
            {
                $('#kt_modal_invite').modal('hide');

                $('#cancelModal').modal('show');
            },
            cancelModalYes()
            {
                $('#kt_modal_invite').modal('hide');
                $('#cancelModal').modal('hide');
            },
            cancelModalNo()
            {
                $('#cancelModal').modal('hide');
                $('#kt_modal_invite').modal('show');
            },
            modalConfirmationCode:function(device=[])
            {
                $('#editdeviceConfirm').modal('show');
                this.deviceGetCode=device;

            },
            deviceExpireDateClear()
            {
                this.deviceExpireDate='';
            },
            EditDeviceExpireDateClear(dataDeviceEdit)
            {
                dataDeviceEdit.expire_date='';
            },
            expireDateClear()
            {
                this.entry.expire_date='';
            },
            dueAtClear()
            {
                this.entry.due_at='';
            },
            deletePackageLessonModal:function(deletePackage='')
            {
              $('#delete').modal('show');
              this.deletePack=deletePackage;
            },
            addPackageLessonModal:function(addPackage='')
            {
                $('#addNamePackageLesson').modal('show');
                this.packageLessonName='';
                this.tabLessonContent='';
            },
            modalEditDevice:function(device={})
            {
                $('#deviceConfirm1').modal('show');
                this.dataDeviceEdit=device;
                const originalDate = device.expire_date;
                console.log(originalDate);
                const [day, month, year] = originalDate.split("/");
                const newDate = `${year}-${month.padStart(2, "0")}-${day.padStart(2, "0")}`;
              return   this.dataDeviceEdit = {
                   'name' : device.device_name,
                    'id' :device.id,
                    'uid':device.device_uid,
                    'os':device.type,
                    'expired':newDate
                }



            },
            renameLessonPackage:function(rename='')
            {
                $('#addNamePackageLesson').modal('show');
                let self=this;
                let pack= self.lessonPackagePlans.filter(item =>item.package_id==rename);
                self.packageLessonName=pack[0].name;
            },
            deleteLessonModal:function(deleteIdLesson='')
            {
                $('#deleteLesson').modal('show');
                this.deleteLessons=deleteIdLesson;
            },
            removeDeviceModal:function(deleteIdDevice='')
            {
                $('#deleteDevice').modal('show');
                this.deleteDevice=deleteIdDevice;

            },
            tabPackageLesson: function (tabPackage = '') {
                $('#kt_billing_year').show();
                this.tabLessonContent = tabPackage;
                this.load();
                let self = this;
                self.checkZipPackage=[];
                let dataPackage=self.lessonPackagePlans.filter(item =>item.package_id==tabPackage);
               self.checkZipPackage.push(dataPackage[0]);

               // code select lesson
                this.allViewLessonSelected={
                    'allViewLessonSelected':false,
                    'tabPackage':tabPackage
                }

               setTimeout(function(){
                   $.get('/xadmin/plans/dataPackage',function(res)
                   {
                    self.dataTableLesson=res.data.filter(item => item.id==tabPackage);
                       let viewLesson=[];
                       let checkRemoveLesson=0;
                       self.dataTableLesson=self.dataTableLesson.map(abc => {

                       return {
                           'id':abc.id,
                           'name':abc.name,
                           'plan_id':abc.plan_id,
                           'lesson_ids':abc.lesson_ids,
                           'viewLesson':viewLesson,
                           'checkRemoveLesson':checkRemoveLesson,
                       }
                    })
                       self.dataTableLesson= self.dataTableLesson[0];
                   })
               },0)
               setTimeout(function () {
                    $.get('/xadmin/plans/dataZipLessonPlan',function (res) {
                        let array=res.data.filter(item => item.plan_id==self.entry.id);
                      let arrZipPlan= array.filter(item =>item.package_id==tabPackage);
                        self.dataZipLesson=arrZipPlan[0];
                    })

                },0)
            },
            importDevice(){
                $('#kt_modal_create_app').modal('show');
            },
            addDevice: function (addDevice = '') {
                $('#deviceConfirm').modal('show');
            },
            addLessonPackage: function (tabLessonContent = '') {
                $('#kt_modal_invite').modal('show');
                this.package=
                    {
                        allLessonSelected:false,
                        package:tabLessonContent
                    };

            },
            continueImportDevice()
            {
            },
        // xoa lesson theo từng id
            async deleteLesson(deleteLessons) {
                let self = this;
               let packageLesson= self.dataTableLesson.lesson_ids.filter(item => item !==deleteLessons)

                const res = await $post('/xadmin/plans/deleteLesson', {
                    packageLesson,
                    entry: self.entry,
                    viewPackage: self.tabLessonContent
                });

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                    // code return lai o checkbox trong add lessonPackage khi xoa 1 lesson o tabLesson

                    let datalessonPackagePlan=self.lessonPackagePlans.filter(item => item.package_id==self.tabLessonContent);
                    datalessonPackagePlan=datalessonPackagePlan[0];
                    let concat=(packageLesson.concat(datalessonPackagePlan.lessonIds));
                    let dataUpdate=concat.filter((a,index)=> concat.indexOf(a)==concat.lastIndexOf(a));
                    dataUpdate=dataUpdate[0];
                    datalessonPackagePlan.lessonIds=datalessonPackagePlan.lessonIds.filter(item => item !=dataUpdate);

                    // end

                    self.dataAddLessonPlan= self.dataAddLessonPlan.filter(item => item.id !==deleteLessons);
                    $('#deleteLesson').modal('hide');
                    $.get('/xadmin/plans/dataPackage',function(res)
                    {
                        self.dataTableLesson=res.data.filter(item => item.id==self.tabLessonContent);
                        let viewLesson=[];
                        let checkRemoveLesson=0;
                        self.dataTableLesson=self.dataTableLesson.map(abc => {

                            return {
                                'id':abc.id,
                                'name':abc.name,
                                'plan_id':abc.plan_id,
                                'lesson_ids':abc.lesson_ids,
                                'viewLesson':viewLesson,
                                'checkRemoveLesson':checkRemoveLesson,
                            }
                        })
                        self.dataTableLesson= self.dataTableLesson[0];
                    })
                }

            },
            // dung select để xóa lesson view
            async deleteAllLesson()
            {
                        let self=this;

                        // lấy Id của lesson cập nhật lại vào bảng package lesson
               let concatArr=self.dataTableLesson.lesson_ids.concat(self.dataTableLesson.viewLesson)
                let dupChars = concatArr.filter((c, index) => concatArr.indexOf(c) == concatArr.lastIndexOf(c));


               const res = await $post('/xadmin/plans/removeAllLesson', {
                              ids:dupChars,
                              entry: self.entry,
                              viewPackage:self.dataTableLesson.id
                          });


                          if (res.code) {
                              toastr.error(res.message);
                          } else {
                              toastr.success(res.message);

                              // code update checkbox trong modal addPackageLesson
                                let distinctive = concatArr.filter((c, index) => concatArr.indexOf(c) != concatArr.lastIndexOf(c));
                                distinctive=[...new Set(distinctive)];
                                let datalessonPackagePlan=self.lessonPackagePlans.filter(item => item.package_id==self.tabLessonContent);
                                datalessonPackagePlan=datalessonPackagePlan[0];
                                let concat=datalessonPackagePlan.lessonIds.concat(distinctive);
                                datalessonPackagePlan.lessonIds=concat.filter((a,index)=> concat.indexOf(a)==concat.lastIndexOf(a));
                              //end


                              self.dataTableLesson.viewLesson=[];
                              self.allViewLessonSelected.allViewLessonSelected=false;

                                    //view lesson theo packagelesson khi delete all
                                          if(self.dataTableLesson.viewLesson.length==self.dataTableLesson.lesson_ids.length)
                                          {
                                              self.dataAddLessonPlan=[];
                                          }
                                          else {
                                      let dataLesson=[];
                                      dupChars.forEach(function(e1)
                                      {
                                          self.dataAddLessonPlan.forEach(function(e2)
                                          {
                                              if(e1==e2.id)
                                              {
                                                  dataLesson.push(e2);
                                              }
                                          })
                                      })
                                       self.dataAddLessonPlan=dataLesson;
                                        }
                                          self.dataTableLesson.checkRemoveLesson=0;
                              $.get('/xadmin/plans/dataPackage',function(res)
                              {
                                  self.dataTableLesson=res.data.filter(item => item.id==self.tabLessonContent);
                                  let viewLesson=[];
                                  let checkRemoveLesson=0;
                                  self.dataTableLesson=self.dataTableLesson.map(abc => {

                                      return {
                                          'id':abc.id,
                                          'name':abc.name,
                                          'plan_id':abc.plan_id,
                                          'lesson_ids':abc.lesson_ids,
                                          'viewLesson':viewLesson,
                                          'checkRemoveLesson':checkRemoveLesson,
                                      }
                                  })
                                  self.dataTableLesson= self.dataTableLesson[0];

                              })


                          }
            },
            async deletePackageLesson(deletePack)
            {
                const res = await $post('/xadmin/plans/deletePackageLesson', {id: deletePack,entry:this.entry});
                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                    let self =this;

                    setTimeout(function ()
                    {
                        $.get('/xadmin/plans/dataPackage',function (res) {
                            let dataPackage= res.data.filter(item => item.plan_id==self.entry.id)
                           // return self.lessonPackagePlans=dataPackage;
                           let data= dataPackage.map(res =>{
                                return{
                                    'name':res.name,
                                    'package_id':res.id,
                                    'plan_id':res.plan_id,
                                    'lessonIds':res.lesson_ids
                                }
                            })
                            return self.lessonPackagePlans=data;
                        })
                        $.get('/xadmin/plans/getPlan?id='+self.entry.id,function(res)
                        {
                            self.entry=res.data;
                        })
                    },0);
                    $('#delete').modal('hide');
                }
            },
            backIndex() {
                window.location.href = '/xadmin/plans/index';
            },

            // luu plan
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/save', {
                    entry: this.entry,
                    idRoleIt: this.idRoleIt,
                    schoolPlan: this.schoolPlan,
                    schoolId: this.schoolId,
                    idListDevice: this.idListDevice
                }, false);
                this.isLoading = false;
                if (res.errors) {
                    this.errors = res.errors;
                    return;
                }
                if (res.code) {
                    toastr.error(res.message);
                } else {
                    this.errors = {};
                    toastr.success(res.message);
                    location.replace('/xadmin/plans/index');


                    if (!this.entry.id) {
                        location.replace('/xadmin/plans/edit?id=' + this.entry.id);
                    }

                }
            },

            // validate device khi import
            async importFileDevice() {
                const fileExtension =this.$refs.uploader.files[0].name.split('.').pop();
                if(fileExtension!=='xlsx')
                {
                    return  this.errors={
                        'sizeFile':['The file is not in the correct format']
                    };
                }
                else{
                    this.errors={};
                   let fileSize=(this.$refs.uploader.files[0].size.toString());
                    if(fileSize.length < 7)
                    {
                        let size= `${Math.round(+fileSize/1024).toFixed(2)}kb`
                        this.sizeFile=size;

                    }
                    else {
                       let size= `${(Math.round(+fileSize/1024)/1000).toFixed(2)}MB`;
                       let numberSize=(Math.round(+fileSize/1024)/1000).toFixed(2)
                        this.sizeFile=size;
                       if(numberSize>5)
                       {
                          return  this.errors={
                               'sizeFile':['Max file size is 5Mb']
                           };

                       }

                    }
                    this.errors={};
                    this.fileUpLoad=this.$refs.uploader.files[0].name
                    const files = this.$refs.uploader.files;
                    this.disableContinue=true;
                    this.saveValidateImportDevice();
                }
            },
            removeFileDevice(){
            if(this.$refs.uploader.files)
            {
                this.fileUpLoad='';
                this.$refs.uploader.value = null;
                this.valueValidateImportDevice=0;
                this.disableContinue=false;

            }

            },

            async saveValidateImportDevice() {
                if (this.$refs.uploader.files) {
                    this.valueValidateImportDevice=1;
                    const files = this.$refs.uploader.files;
                    const formData = new FormData();
                    formData.append('_token', window.$csrf)
                    forEach(files, (v, k) => {
                        formData.append(k, v);
                    });

                    for (let i = 0; i < files.length; i++) {
                        formData.append('file_' + i, files[i]);
                        formData.append('expire_date', this.entry.expire_date);
                        formData.append('plan_id', this.entry.id);
                    }

                    $('#overlay').show();
                    let res = await fetch('/xadmin/plans/validateImportDevice', {
                        method: 'POST',
                        body: formData,

                    })

                        .then((response) => response.json())
                        .catch((error) => {
                            console.error('Error:', error);
                        });
                    if (res.code == 2) {
                        this.deviceError = res.deviceError;
                        this.errorDeviceName=res.errorDeviceName;
                        this.code = res.code;
                        this.validateFile = res.fileError;
                        this.fileImport = res.fileImport;
                    }
                    if (res.code == 1) {
                        this.code = res.code;
                        this.fileImport = res.fileImport;
                    }
                    if (res.code) {
                        // this.errors = res.errors;
                    } else {
                        $('#uploadApp').modal('hide');
                        this.model = {
                            type: ''
                        }
                        this.$refs.uploader.value = null;
                        $router.on('/', this.load).init();
                        toastr.success(res.message);
                    }
                }
            },
            // save device vào db khi khi các device qua validate
            async saveImport() {
                if (this.doNotImport ==0) {
                    {
                        this.$loading(true);
                        const res = await $post('/xadmin/plans/import', {
                            fileImport: this.fileImport,
                            entry: this.entry,
                            schoolId: this.schoolId,
                            idRoleIt: this.idRoleIt,
                        }, false);
                        this.$loading(false);
                        if (res.code) {
                            toastr.error(res.message);
                        } else {
                            this.errors = {};
                            this.exportDevicePlan = res.url;
                            toastr.success(res.message);
                            let self=this;
                            setTimeout(function () {
                                $.get('/xadmin/plans/dataDevice?plan_id='+self.entry.id,function (res) {
                                    self.data=res.data;
                                    $('#kt_modal_create_app').modal('hide');
                                    self.$refs.uploader.value = null;
                                    setTimeout(function (){
                                        KTMenu.createInstances();
                                    }, 0)
                                    self.fileUpLoad='';
                                    self.valueValidateImportDevice=0;
                                    self.fileImport.length=0;
                                    self.deviceError.length=0;
                                    location.reload();


                                })
                            },0)
                        }
                    }
                }
                if (this.doNotImport ==1) {
                    let self=this;
                    $('#kt_modal_create_app').modal('hide');
                    self.$refs.uploader.value = null;
                    self.fileUpLoad='';
                    self.valueValidateImportDevice=0;
                    self.fileImport.length=0;
                    self.deviceError.length=0;
                    location.reload();
                }
            },

            // export devive
            async exportDevice() {
                console.log(this.exportDeviceName);
                window.location.href= '/xadmin/plans/exportDevice?idPlan=' +JSON.stringify(this.entry.id)+
                '&idRoleIt=' + JSON.stringify(this.idRoleIt)+
                '&dataDevice=' + this.exportDeviceName;
            },

            changeLimit() {
                let params = $router.getQuery();
                params['page'] = 1;
                params['limit'] = this.limit;
                $router.setQuery(params)
            },

            selectLessonAll() {
                if (this.package.allLessonSelected )
                {
                    const selected=this.entries.map((u)=>u.id);
                    let self=this;
                    self.lessonPackagePlans.forEach(function (e){

                        if(self.package.package==e.package_id)
                        {

                            e.lessonIds=e.lessonIds.concat(selected);

                        }
                    })
                }
                else {
                        let self = this;
                        self.lessonPackagePlans.forEach(function (e1) {
                            e1.lessonIds = [];
                        })
                        self.lessons = [];
                    }

            },
            updateLessonAll()
            {
                this.lessons = [];
                let self = this;
                self.lessonPackagePlans.forEach(function (e) {
                    if (e.lessonIds.length == self.entries.length) {
                        self.package.allLessonSelected = true;
                    } else {
                        self.package.allLessonSelected = false;
                    }

                })
                self.lessonPackagePlans.forEach(function (e3) {
                    self.entries.forEach(function (e4) {
                        if (e4.id == e3) {
                            self.lessons.push(e3);
                        }
                    })
                })
            },
            selectViewLessonAll()
            {

                if (this.allViewLessonSelected.allViewLessonSelected ) {
                    const selected = this.dataAddLessonPlan.map((u) => u.id);
                    let self=this;
                   self.dataTableLesson.checkRemoveLesson=1;
                    if(self.allViewLessonSelected.tabPackage== self.dataTableLesson.id)
                    {
                        self.dataTableLesson.viewLesson=selected;
                    }
                }

                else {
                    let self = this;
                    self.dataTableLesson.checkRemoveLesson=0;
                    if(self.allViewLessonSelected.tabPackage==self.dataTableLesson.id)
                    {
                        self.dataTableLesson.viewLesson=[];
                    }

                }
            },
            updateViewLessonCheckAll()
            {
                let self = this;
                if(self.dataTableLesson.viewLesson.length>0)
                {
                   self.dataTableLesson.checkRemoveLesson=1;
                }
                else {
                    self.dataTableLesson.checkRemoveLesson=0;
                }
                if (self.dataTableLesson.viewLesson.length=== self.dataAddLessonPlan.length ) {
                    self.allViewLessonSelected.allViewLessonSelected = true;

                } else {
                    self.allViewLessonSelected.allViewLessonSelected = false;
                }
                // self.lessons=[];
                // self.lessonPackagePlans.forEach(function (e3) {
                //     self.entries.forEach(function (e4) {
                //         if (e4.id == e3) {
                //             self.lessons.push(e3);
                //         }
                //     })
                // })

            },

            selectDeviceAll() {
                if (this.allDeviceSelected) {
                    const selected = this.data.map(u => u.id);
                    this.deviceIds = selected;
                } else {
                    this.deviceIds = [];
                    this.device = [];
                }

            },
            updateDeviceCheckAll() {
                this.device = [];
                if (this.deviceIds.length === this.data.length) {
                    this.allDeviceSelected = true;
                } else {
                    this.allDeviceSelected = false;
                }
                let self = this;
                self.deviceIds.forEach(function (e) {
                    self.data.forEach(function (e1) {
                        if (e1.id == e) {
                            self.device.push(e1);
                        }
                    });
                });
            },
            async removeDeviceAll() {
                let self = this;
                for(const e of self.deviceIds)
                {
                   self.data= self.data.filter(item => item.id !==e);
                }
                const res = await $post('/xadmin/plans/removeDeviceAll', {ids: this.deviceIds,entry:this.entry});
                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                    this.deviceIds = [];
                    this.device = [];
                    this.allDeviceSelected=false;
                }
            },
            async removeDevice(deleteDevice)
            {
                let self = this;
                    self.data= self.data.filter(item => item.id !==deleteDevice);
                const res = await $post('/xadmin/plans/removeDevice', {id: deleteDevice,entry:this.entry});
                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                    $('#deleteDevice').modal('hide');
                    this.deviceIds = [];
                    this.device = [];
                }
            },

           async saveDevice() {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/saveDevice', {
                    idRoleIt: this.idRoleIt,
                    deviceName: this.deviceName,
                    deviceUid: this.deviceUid,
                    deviceExpireDate:this.deviceExpireDate,
                    entry: this.entry
                }, false);
                this.isLoading = false;
                if (res.errors) {
                    this.errors = res.errors;
                    return;
                }
                if (res.code) {
                    toastr.error(res.message);
                } else {
                    this.errors = {};
                    toastr.success(res.message);
                    let self=this
                    setTimeout(function () {
                        $.get('/xadmin/plans/dataDevice?plan_id='+self.entry.id,function (res) {
                            self.data=res.data.map(rec =>{
                                const [year, month, day] = rec.expire_date.split("-");
                                const newDate = `${day.padStart(2, "0")}/${month.padStart(2, "0")}/${year}`;
                                return {
                                    'id':rec.id,
                                    'device_uid':rec.device_uid,
                                    'device_name':rec.device_name,
                                    'user_id':rec.user_id,
                                    'plan_id':rec.plan_id,
                                    'type':rec.type,
                                    'status':rec.status,
                                    'secret_key':rec.secret_key,
                                    'expire_date':newDate,
                                    'created_at':rec.created_at,
                                    'updated_at':rec.updated_at
                                }
                            })
                            setTimeout(function (){
                                KTMenu.createInstances();
                            }, 0)
                            $('#deviceConfirm').modal('hide');
                           self.deviceExpireDate='';
                           self.deviceName='';
                           self.deviceUid='';

                        })

                    },0)

                    if (!this.entry.id) {
                        location.replace('/xadmin/plans/edit?id=' + entry.id);
                    }

                }
            },

            // save lesson: thêm lesson_ids vào bảng PackageLesson
            async addLesson() {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/planLesson', {
                    lessonPackagePlans: this.lessonPackagePlans,
                    entry: this.entry,
                    package: this.package
                }, false);
                this.isLoading = false;
                if (res.code) {
                    toastr.error(res.message);
                } else {
                    this.errors = {};
                    toastr.success(res.message);
                    this.package.allLessonSelected=false;
                    $('#kt_modal_invite').modal('hide');
                    let self =this;

                    setTimeout(function ()
                    {
                        $.get('/xadmin/plans/dataPackage',function (res) {
                            self.dataTableLesson= res.data.filter(item => item.id==self.tabLessonContent);

                            let viewLesson=[];
                            let checkRemoveLesson=0;
                            self.dataTableLesson=self.dataTableLesson.map(abc => {

                                return {
                                    'id':abc.id,
                                    'name':abc.name,
                                    'plan_id':abc.plan_id,
                                    'lesson_ids':abc.lesson_ids,
                                    'viewLesson':viewLesson,
                                    'checkRemoveLesson':checkRemoveLesson,
                                }
                            })
                            self.dataTableLesson= self.dataTableLesson[0];
                            // self.dataAddLessonPlan=[];

                            // self.dataTableLesson.lesson_ids.forEach(function(e)
                            // {
                            //     let dataLesson=self.entries.filter(item => item.id==e)
                            //     dataLesson.forEach(function(e2)
                            //     {
                            //         self.dataAddLessonPlan.push(e2);
                            //     })
                            // })
                        })


                    },1000);

                       /*setTimeout(function () {
                           for (var key in self.filter) {
                               self.filter[key] = '';
                           }
                           $router.setQuery({});

                       },0)*/
                       this.load();


                }

            },
            // async sentAdmin() {
            //     this.isLoading = true;
            //     const res = await $post('/xadmin/plans/sentAdmin', {entry: this.entry}, false);
            //     this.isLoading = false;
            //     if (res.errors) {
            //         this.errors = res.errors;
            //         return;
            //     }
            //     if (res.code) {
            //         toastr.error(res.message);
            //     } else {
            //         this.errors = {};
            //         toastr.success(res.message);
            //         location.replace('/xadmin/plans/edit?id=' + this.entry.id);


            //         if (!this.entry.id) {
            //             location.replace('/xadmin/plans/edit?id=' + this.entry.id);
            //         }

            //     }

            // },
            doFilter() {

                this.load();
                //$router.setQuery(this.filter)
            },

            // data all lesson

            async load() {
                let query = this.filter;
                this.$loading(true);
                setTimeout(function (){
                    KTMenu.createInstances();
                }, 0)
                const res = await $get('/xadmin/plans/dataLesson?idPlan='+this.entry.id + '&packageLessonId='+this.tabLessonContent, query);
                this.$loading(false);
                this.entries = res.data;
                this.data=res.devices.map(rec => {
                    setTimeout(function (){
                        KTMenu.createInstances();
                    }, 0)
                    const [year, month, day] = rec.expire_date.split("-");
                    const newDate = `${day.padStart(2, "0")}/${month.padStart(2, "0")}/${year}`;
                    return {
                        'id':rec.id,
                        'device_uid':rec.device_uid,
                        'device_name':rec.device_name,
                        'user_id':rec.user_id,
                        'plan_id':rec.plan_id,
                        'type':rec.type,
                        'status':rec.status,
                        'secret_key':rec.secret_key,
                        'expire_date':newDate,
                        'created_at':rec.created_at,
                        'updated_at':rec.updated_at
                    }
                })
                this.exportDeviceName=res.export_device_name;
                this.dataAddLessonPlan=res.dataAddLessonPlan;
            },
            onPageChange(page) {
                $router.updateQuery({page: page})
            },

            // add packageLesson
            async addPackageLesson(tabLessonContent) {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/addPackageLesson', {
                    tabLessonContent:tabLessonContent,
                     packageLessonName:this.packageLessonName,
                    entry: this.entry,
                    lessonIds: this.lessonIds
                }, false);
                this.isLoading = false;
                if (res.code) {
                    this.errors=res.errors;
                } else {
                    this.errors = {};
                    toastr.success(res.message);
                    // location.replace('/xadmin/plans/edit?id=' + this.entry.id);
                    // $('.package-lesson-link').removeClass('active');

                    let self=this;

                   setTimeout(function ()
                    {
                        $.get('/xadmin/plans/dataPackage',function (res) {
                            let dataPackage=res.data.filter(item => item.plan_id==self.entry.id)
                            // self.lessonPackagePlans.forEach(function (e){
                            //     e.className = '';
                            // })
                            $('.package-lesson-link').removeClass('active');
                            // dataPackage.className = 'active';
                          // self.lessonPackagePlans.push(dataPackage);
                          //  self.tabPackageLesson(self.tabLessonContent);
                            let data= dataPackage.map(rec =>{
                                return{
                                    'name':rec.name,
                                    'package_id':rec.id,
                                    'plan_id':rec.plan_id,
                                    'lessonIds':rec.lesson_ids
                                }
                            })
                            self.lessonPackagePlans=data;
                            // self.lessonPackagePlans.className='active';
                            return self.tabLessonContent=data[data.length-1].package_id

                        })
                        $.get('/xadmin/plans/getPlan?id='+self.entry.id,function(res)
                        {
                            self.entry=res.data;
                        })
                    },0);
                    $('#addNamePackageLesson').modal('hide');
                    self.packageLessonName='';
                    self.tabLessonContent='';
                    if (!this.entry.id) {
                        location.replace('/xadmin/plans/edit?id=' + this.entry.id);
                    }
                }
            },

            //download lesson khi da zip xong: link url trong bảng zip_plan_lesson
            async downloadLesson(tabLessonContent) {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/downloadLesson', {
                    entry: this.entry,
                    dataTableLesson: this.dataTableLesson,
                    package: tabLessonContent,
                    idRoleIt: this.idRoleIt
                }, false);
                this.isLoading = false;
                if (res.errors) {
                    this.errors = res.errors;
                    return;
                }
                if (res.code) {
                    toastr.error(res.message);
                } else {
                    this.errors = {};
                    toastr.success(res.message);
                    let self=this;
                    setTimeout(function () {
                        $.get('/xadmin/plans/dataZipLessonPlan',function (res) {
                            let arrZipPackage= res.data.filter(item => item.plan_id==self.entry.id)
                            let dataPackage=arrZipPackage.filter(item => item.package_id==self.tabLessonContent)
                            self.dataZipLesson=dataPackage[0];
                        })
                    },0)
                    if (!this.entry.id) {
                        location.replace('/xadmin/plans/edit?id=' + this.entry.id);
                    }

                }
            },
            // async sentSale() {
            //     this.isLoading = true;
            //     const res = await $post('/xadmin/plans/sentSale', {entry: this.entry}, false);
            //     this.isLoading = false;
            //     if (res.errors) {
            //         this.errors = res.errors;
            //         return;
            //     }
            //     if (res.code) {
            //         toastr.error(res.message);
            //     } else {
            //         this.errors = {};
            //         toastr.success(res.message);
            //         location.replace('/xadmin/plans/index');


            //         if (!this.entry.id) {
            //             location.replace('/xadmin/plans/edit?id=' + this.entry.id);
            //         }

            //     }

            // },

            //export kế hoạch triển khai cho IT

             exportPlan()
            {
                let packageIds = [];
                if(this.lessonPackagePlans!=null)
                {
                    this.lessonPackagePlans.forEach(function (e) {
                        packageIds.push(e.lesson_ids);
                    })
                    window.location.href= '/xadmin/plans/exportPlan?entry=' +this.cachePlan+
                        '&packageLessonPlan=' + this.cacheLesson+
                        '&dataDevice=' + this.exportDeviceName;
                }
                else {
                    window.location.href= '/xadmin/plans/exportPlan?entry=' + JSON.stringify(this.entry)+
                        '&packageLessonPlan=' + this.cacheLesson+
                        '&dataDevice=' + this.exportDeviceName;
                }

            },

            //download mẫu template excel add device
            downloadTemplate()
            {
                window.location.href= '/xadmin/plans/downloadTemplate';
            },

            async genToken(){
                const res  = await $post('/xadmin/plans/generateToken', {device_id: this.deviceGetCode.id,entry:this.entry});
                this.token = res.token;
            },
            copyTextToken() {
                navigator.clipboard.writeText(this.token);
                this.token='';
            },
            exportDeviceError()
            {
                window.location.href='/xadmin/plans/exportDeviceError?deviceError='+this.errorDeviceName;
            },
            async saveEditDevice(dataDeviceEdit)
            {
                const res=await $post('/xadmin/plans/editDevice',{device:dataDeviceEdit,plan:this.entry})

                if (res.code) {
                    this.errors=res.errors;
                } else {
                    let self=this;
                    setTimeout(function () {
                        $.get('/xadmin/plans/dataDevice?plan_id='+self.entry.id,function (res) {
                            self.data=res.data.map(rec =>{
                                const [year, month, day] = rec.expire_date.split("-");
                                const newDate = `${day.padStart(2, "0")}/${month.padStart(2, "0")}/${year}`;
                                return {
                                    'id':rec.id,
                                    'device_uid':rec.device_uid,
                                    'device_name':rec.device_name,
                                    'user_id':rec.user_id,
                                    'plan_id':rec.plan_id,
                                    'type':rec.type,
                                    'status':rec.status,
                                    'secret_key':rec.secret_key,
                                    'expire_date':newDate,
                                    'created_at':rec.created_at,
                                    'updated_at':rec.updated_at
                                }
                            });
                            setTimeout(function (){
                                KTMenu.createInstances();
                            }, 0)
                        })
                        $('#deviceConfirm1').modal('hide');


                    },0)

                }
            }
        }
    }
</script>

<style scoped>
.isDisabled {
    color: currentColor;
    cursor: not-allowed;
    opacity: 0.5;
    text-decoration: none;
    pointer-events: none
}
.lds-ring {
    display: inline-block;
    position: relative;
    width: 80px;
    height: 80px;
}
.lds-ring div {
    box-sizing: border-box;
    display: block;
    position: absolute;
    width: 64px;
    height: 64px;
    margin: 8px;
    border: 8px solid #fff;
    border-radius: 50%;
    animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    border-color: #fff transparent transparent transparent;
}
.lds-ring div:nth-child(1) {
    animation-delay: -0.45s;
}
.lds-ring div:nth-child(2) {
    animation-delay: -0.3s;
}
.lds-ring div:nth-child(3) {
    animation-delay: -0.15s;
}
@keyframes lds-ring {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
input[type="file"] {
    display: none;
}
</style>
