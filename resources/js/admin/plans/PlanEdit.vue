<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title="Create new plan"/>
        <div class="row">

            <!-- BEGIN: MODAL ADD LESSON PACKAGE PLAN -->

            <div class="modal fade" id="kt_modal_invite" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog " style="width: 1000px">
                    <div class="modal-content" style="width: max-content;margin: 0px -150px 0px">
                        <div class="modal-header pb-0 border-0 justify-content-end">
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
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
                                    <label>Subject </label>
                                    <select required class="form-control form-select" v-model="filter.subject"
                                            @keydown.enter="doFilter('subject', filter.subject, $event)">
                                        <option value="" disabled selected>Choose Subject</option>
                                        <option value="0">All</option>
                                        <option value="Math">Math</option>
                                        <option value="Science ">Science</option>
                                    </select>
                                </div>
                                <div class="h-15px me-3" style="width: 218px">
                                    <label>Grade </label>
                                    <select required class="form-control form-select" v-model="filter.grade"
                                            @keydown.enter="doFilter('grade', filter.grade, $event)">
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
                                                    <input class="form-check-input" type="checkbox" v-model="allLessonSelected" @change="selectLessonAll()">
                                                </div>
                                            </td>
                                            <th class="">Name of lesson</th>
                                            <th class="">Grade</th>
                                            <th class="">Subject</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody  v-for="lessonPackagePlan in lessonPackagePlans" v-if="lessonPackagePlan.package_id==package">
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
                                <button class="btn btn-primary" style="margin: 20px 0px 0px" @click="addLesson()">
                                    Confirm
                                </button>
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
                                        <input v-if="roleAuth=='IT'" disabled v-model="entry.name" class="form-control" placeholder="Enter the name of plan">
                                        <input v-if="roleAuth!='IT'" v-model="entry.name" class="form-control" placeholder="Enter the name of plan">
                                        <error-label :errors="errors.name" for="f_school_name"></error-label>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Due date </label>
                                        <Datepicker v-if="roleAuth=='IT'" disabled v-model="entry.due_at" readonly/>
                                         <Datepicker v-if="roleAuth!='IT'"  v-model="entry.due_at" readonly/>
                                         <span v-if="entry.due_at!='' && roleAuth!='IT'" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0" @click="dueAtClear">
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
                                        <textarea v-if="roleAuth=='IT'" disabled class="form-control"
                                                  placeholder="Enter the description" v-model="entry.plan_description">
                                        </textarea>
                                        <textarea v-if="roleAuth!='IT'" class="form-control"
                                                  placeholder="Enter the description" v-model="entry.plan_description">
                                        </textarea>
                                        <error-label :errors="errors.plan_description"></error-label>
                                    </div>
                                     <div class="form-group col-lg-4">
                                        <label> Assign to IT <span class="text-danger">*</span></label>
                                        <select disabled class="form-control form-select" v-model="idRoleIt">
                                            <option v-for="role in roleIt" :value="role.id">{{role.full_name}}</option>
                                        </select>
                                        <error-label :errors="errors.idRoleIt"></error-label>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label>Expire date <span class="text-danger">*</span></label>
                                        <Datepicker v-if="roleAuth=='IT'" v-model="entry.expire_date" disabled  readonly/>
                                        <Datepicker v-if="roleAuth!='IT'" v-model="entry.expire_date" readonly/>
                                        <span v-if="entry.expire_date!='' && roleAuth!='IT'" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0" @click="expireDateClear">
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
                                                   @click="tabPackageLesson(packageLesson.package_id)">Lesson package
                                                    {{index+1}}</a>
                                            </li>
                                            <li v-if="roleAuth=='Super Administrator'">
                                                <a class="btn btn-primary btn-active-primary btn-sm mt-2 ml-2"
                                                   data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" @click="addPackageLesson">Add package
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- END : THONG TIN PLAN -->

                                <div class="tab-content">

                                    <!--BEGIN: LIST DEVICE PLAN-->

                                    <div id="kt_billing_months" class="card-body p-0 tab-pane fade show active" role="tabpanel" aria-labelledby="kt_billing_months">
                                        <div class="d-flex justify-content-end mb-4" v-if="roleAuth=='Super Administrator'">
                                            <a v-if="deviceIds!=''" class="btn btn-danger btn-sm mr-3" @click="removeDeviceAll" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Delete</a>
                                                <a href="list.html#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                    <span class="svg-icon svg-icon-5 m-0">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black"/>
															</svg>
                                                    </span>
                                                </a>
                                            <div class="menu menu-sub menu-sub-dropdown  menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 py-4" data-kt-menu="true" style="width: 150px">
                                                <div class="menu-item px-3">
                                                    <a class="menu-link px-3" @click="addDevice()">Add a device</a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a class="menu-link px-3" @click="importDevice()">Import devices</a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a class="menu-link px-3" @click="exportDevice">Export device list</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end mb-4" v-if="roleAuth=='IT'">
                                            <a  class="btn btn-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" @click="exportDevice">Export device
                                            </a>
                                        </div>

                                        <!-- BEGIN : TABLE LIST DEVICE-->

                                    <div class="mh-650px scroll-y me-n7 pe-7">
                                        <table class="table table-row-bordered align-middle gy-4 gs-9">
                                            <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                                                <tr>
                                                    <td width="25">
                                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="checkbox"  v-model="allDeviceSelected" @change="selectDeviceAll()">
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
                                                        <input class="form-check-input" type="checkbox" v-model="deviceIds" :value="device.id" @change="updateDeviceCheckAll">
                                                    </div>
                                                </td>
                                                <td>{{index+1}}</td>
                                                <td>{{device.device_name}}</td>
                                                <td>{{device.type}}</td>
                                                <td>{{device.expire_date}}</td>
                                                <td class="">
                                                    <a v-if="roleAuth=='Super Administrator'" class="btn btn-active-danger btn-light-danger btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" @click="removeDeviceModal(device.id)">Delete</a>
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
                                                <a v-if="viewLessonIds!='' && dataZipLesson.status=='waitting' && roleAuth=='Super Administrator'" class="btn btn-danger btn-sm mr-3" @click="deleteAllLesson" >Remove lesson</a>
                                             <a  v-if="dataZipLesson.status=='inprogress'"  class="mt-2 mr-5" style="color: rgb(230 180 0)"> Lesson list is packaging...</a>
                                                <a  v-if="  dataZipLesson.status=='done'" :href="dataZipLesson.url" class="btn btn-primary btn-sm mr-3 "> Download package</a>
                                                <a class="btn btn-light btn-active-light-primary btn-sm " data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" >Actions
                                                    <span class="svg-icon svg-icon-5 m-0">
															<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viewBox="0 0 24 24" fill="none">
																<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black"/>
															</svg>
                                                    </span>
                                                </a>
                                                <div class="menu menu-sub menu-sub-dropdown  menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 py-4" data-kt-menu="true" >
                                                    <div class="menu-item px-3"  v-if="roleAuth=='Super Administrator' && dataZipLesson.status=='waitting'">
                                                        <a class="menu-link px-3 " @click="addLessonPackage(tabLessonContent)" >Add lesson</a>
                                                    </div>
                                                    <div class="menu-item px-3" v-if="dataZipLesson.status=='waitting'">
                                                        <a v-if="checkZipPackage[0].lessonIds.length>0" class="menu-link px-3" @click="downloadLesson(tabLessonContent)">Zip package lesson</a>
                                                        <a v-else class="menu-link px-3 isDisabled" @click="downloadLesson(tabLessonContent)" >Zip package lesson</a>
                                                    </div>
                                                    <div class="menu-item px-3" v-if="roleAuth=='Super Administrator' && dataZipLesson.status=='done' ||  roleAuth=='Super Administrator' &&dataZipLesson.status=='waitting'">
                                                        <a class="menu-link px-3 text-danger "  @click="deletePackageLesson(tabLessonContent)" >Delete package lesson</a>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="mh-650px scroll-y me-n7 pe-7">
                                                <table class="table table-row-bordered align-middle gy-4 gs-9">
                                                    <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                                                        <tr >
                                                            <td width="25">
                                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                    <input v-if="dataZipLesson.status=='waitting'" class="form-check-input" type="checkbox" v-model="allViewLessonSelected" @change="selectViewLessonAll(tabLessonContent)" >
                                                                    <input v-if="dataZipLesson.status!='waitting'" disabled class="form-check-input" type="checkbox" v-model="allViewLessonSelected" @change="selectViewLessonAll(tabLessonContent)" >
                                                                </div>
                                                            </td>
                                                            <td>No.</td>
                                                            <th class="">Lesson name</th>
                                                            <th class="">Grade</th>
                                                            <th class="">Subject</th>
                                                            <th class="">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody v-for="lessonPackagePlan in lessonPackagePlans" v-if="lessonPackagePlan.package_id==tabLessonContent">
                                                        <tr v-for="(lesson,index) in dataAddLessonPlan" >
                                                            <td class="">
                                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                    <input v-if="dataZipLesson.status=='waitting'" class="form-check-input" type="checkbox" v-model="viewLessonIds" :value="lesson.id" @change="updateViewLessonCheckAll()">
                                                                    <input v-if="dataZipLesson.status!='waitting'" disabled class="form-check-input" type="checkbox" v-model="viewLessonIds" :value="lesson.id" @change="updateViewLessonCheckAll()">

                                                                </div>
                                                            </td>
                                                            <td>{{index+1}}</td>
                                                            <td>{{lesson.name}}</td>
                                                            <td>{{lesson.grade}}</td>
                                                            <td>{{lesson.subject}}</td>
                                                            <td class="">
                                                                <a v-if="dataZipLesson.status=='waitting'" class="btn btn-active-danger btn-light-danger btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" @click="deleteLessonModal(lesson.id)">Delete</a>
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
                        <button type="reset" class="btn btn-primary mr-2" @click="exportPlan"><i
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

        <!-- BEGIN: MODAL IMPPORT DEVICE -->
        <div class="modal fade" id="kt_modal_create_app" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-900px">
                <div class="modal-content">
                           <div class="text-center mt-10">
                               <h2>Import devices</h2>
                           </div>
<!--                            <span class="svg-icon svg-icon-1">-->
<!--								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"-->
<!--                                     fill="none">-->
<!--									<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"-->
<!--                                          transform="rotate(-45 6 17.3137)" fill="black"/>-->
<!--									<rect x="7.41422" y="6" width="16" height="2" rx="1"-->
<!--                                          transform="rotate(45 7.41422 6)" fill="black"/>-->
<!--								</svg>-->
<!--							</span>-->
<!--                        </div>-->


                    <div class="modal-body py-lg-10 px-lg-10">
                        <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid"
                             id="kt_modal_create_app_stepper">
                            <div
                                class="d-flex justify-content-center justify-content-xl-start flex-row-auto w-100 w-xl-300px">
                                <div class="stepper-nav ps-lg-10">
                                    <div class="stepper-item " data-kt-stepper-element="nav">
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
                                    </div>
                                    <div class="stepper-item" data-kt-stepper-element="nav">
                                        <div class="stepper-line w-40px"></div>
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="stepper-check fas fa-check"></i>
                                            <span class="stepper-number">3</span>
                                        </div>
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">Import process</h3>
                                        </div>
                                    </div>
                                    <div class="stepper-item" data-kt-stepper-element="nav">
                                        <div class="stepper-line w-40px"></div>
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="stepper-check fas fa-check"></i>
                                            <span class="stepper-number">4</span>
                                        </div>
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">Completed</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-row-fluid py-lg-5 px-lg-15">
                                <form class="form" novalidate="novalidate" id="kt_modal_create_app_form">
                                    <div class="current" data-kt-stepper-element="content">
                                        <div class="w-100">
                                            <div class="fv-row mb-10">
                                                <div  class="dropzone dropzone-queue mb-2 ">
                                                    <label v-if="valueValidateImportDevice==0"  for="file-upload" class="btn btn-primary btn-active-primary btn-sm">
                                                        Upload file
                                                    </label>
                                                    <label v-if="valueValidateImportDevice==0">Click to select file (*.xls, *.xlsx). Max file size is 5Mb</label>
                                                    <label v-if="valueValidateImportDevice!=0"  >
                                                        Validation result
                                                    </label>
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
                                                        <!--end::File-->
                                                        <!--begin::Progress-->
<!--                                                        <div class="dropzone-progress">-->
<!--                                                            <div class="progress bg-light-primary">-->
<!--                                                                <div class="progress-bar bg-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress=""></div>-->
<!--                                                            </div>-->
<!--                                                        </div>-->
                                                        <!--end::Progress-->
                                                        <!--begin::Toolbar-->
                                                        <div class="dropzone-toolbar">
																		<span class="dropzone-start">
																			<i class="bi bi-play-fill fs-3" @click="saveValidateImportDevice"></i>
																		</span>
<!--                                                            <span class="dropzone-cancel" data-dz-remove="" style="display: none;">-->
<!--																			<i class="bi bi-x fs-3"></i>-->
<!--																		</span>-->
                                                            <span class="dropzone-delete" data-dz-remove="">
																			<i class="bi bi-x fs-1" @click="removeFileDevice"></i>
																		</span>
                                                        </div>
                                                        <!--end::Toolbar-->
                                                    </div>
                                                </div>
                                                <div v-if="valueValidateImportDevice==0" class="dropzone-panel mb-4  ">
                                                   <a class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" @click="downloadTemplate()" ><i class="bi bi-download mr-2"></i>Download template here</a>

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
                                                        <a :href="validateFile" type="button" class="btn btn-primary">Export</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="d-flex flex-stack pt-10" v-if="valueValidateImportDevice!=0">
                                        <div class="me-2">
                                            <button type="button" class="btn btn-lg btn-light-primary me-3"
                                                    data-kt-stepper-action="previous">
                                                <span class="svg-icon svg-icon-3 me-1">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none">
													<rect opacity="0.5" x="6" y="11" width="13" height="2" rx="1"
                                                          fill="black"/>
													<path
                                                        d="M8.56569 11.4343L12.75 7.25C13.1642 6.83579 13.1642 6.16421 12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75L5.70711 11.2929C5.31658 11.6834 5.31658 12.3166 5.70711 12.7071L11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25C13.1642 17.8358 13.1642 17.1642 12.75 16.75L8.56569 12.5657C8.25327 12.2533 8.25327 11.7467 8.56569 11.4343Z"
                                                        fill="black"/>
												</svg>
											</span>
                                                Back
                                            </button>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-lg btn-primary"
                                                    data-kt-stepper-action="submit">
												<span class="indicator-label">Submit
												<span class="svg-icon svg-icon-3 ms-2 me-0">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none">
														<rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1"
                                                              transform="rotate(-180 18 13)" fill="black"/>
														<path
                                                            d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                                                            fill="black"/>
													</svg>
												</span>
                                                  </span>
                                                <span class="indicator-progress">Please wait...
												<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                            </button>
                                            <button type="button" class="btn btn-lg btn-primary"
                                                    data-kt-stepper-action="next" @click="saveImport">Continue
                                                <span class="svg-icon svg-icon-3 ms-1 me-0">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none">
													<rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1"
                                                          transform="rotate(-180 18 13)" fill="black"/>
													<path
                                                        d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                                                        fill="black"/>
												</svg>
											</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
            };

            return {
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
                package: '',
                idListDevice: [],
                nameSchool: $json.nameSchool || [],
                schoolPlan: $json.schoolPlan || [],
                schoolId: '',
                schools: $json.schools || [],
                exportDevicePlan: '',
                lessonPackagePlans: $json.lessonPackagePlans,
                data: $json.data || [],
                allLessonSelected: false,
                allDeviceSelected:false,
                allViewLessonSelected:false,
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
                        title: 'Manage plans',
                        url: '/xadmin/plans/index'
                    },
                    {
                        title: 'Create new plan'
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

            }
        },

        mounted() {
            $router.on('/', this.load).init();
        },

        methods: {
            deviceExpireDateClear()
            {
                this.deviceExpireDate='';
            },
            expireDateClear()
            {
                this.entry.expire_date='';
            },
            dueAtClear()
            {
                this.entry.due_at='';
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
                let self = this;
                self.checkZipPackage=[];
                let dataPackage=self.lessonPackagePlans.filter(item =>item.package_id==tabPackage);
               self.checkZipPackage.push(dataPackage[0]);
                for(const e of self.lessonPackagePlans)
                {
                    if(e.package_id==tabPackage)
                    {
                          self.dataAddLessonPlan=[];
                        for(const e1 of e.lessonIds )
                        {
                          let data= self.entries.filter(item =>item.id==e1) ;
                          for(const e2 of data)
                        {
                             self.dataAddLessonPlan.push(e2);
                        }
                        }
                    }
                }

                //call lại bảng ZipPlanLesson

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
                this.package = tabLessonContent;
            },
        // xoa lesson theo từng id
            async deleteLesson(deleteLessons) {
                let self = this;
                for (const e of self.lessonPackagePlans) {
                    if (e.package_id == self.tabLessonContent) {
                        let array = e.lessonIds.filter(item => item !== deleteLessons);
                        e.lessonIds = array;
                        let packageLesson = e.lessonIds
                        const res = await $post('/xadmin/plans/deleteLesson', {
                            packageLesson,
                            entry: self.entry,
                            viewPackage: self.tabLessonContent
                        });
                        if (res.code) {
                            toastr.error(res.message);
                        } else {
                            toastr.success(res.message);
                            self.dataAddLessonPlan= self.dataAddLessonPlan.filter(item => item.id !==deleteLessons);
                            $('#deleteLesson').modal('hide');
                        }
                    }
                }
            },
            // dung select để xóa lesson view
            async deleteAllLesson()
            {
                        let self=this;
                        self.abc=[];
                        // lấy Id của lesson cập nhật lại vào bảng package lesson
                        let array=self.lessonPackagePlans.filter(item => item.package_id==self.tabLessonContent);
                                self.viewLessonIds.forEach(function (e1) {

                                array[0].lessonIds= array[0].lessonIds.filter(item => item!==e1);
                                })
                                self.abc=array[0].lessonIds

                          const res = await $post('/xadmin/plans/removeAllLesson', {
                              ids:self.abc,
                              entry: self.entry,
                              viewPackage: self.tabLessonContent
                          });
                          if (res.code) {
                              toastr.error(res.message);
                          } else {
                              toastr.success(res.message);
                              self.viewLessonIds=[];
                              self.allViewLessonSelected=false;

                                    //view lesson theo packagelesson khi delete all
                                  self.lessonPackagePlans.forEach(function (e) {

                                      if(e.package_id==self.tabLessonContent)
                                      {
                                          if(e.lessonIds.length==0)
                                          {
                                              self.dataAddLessonPlan=[];
                                          }
                                          else {
                                      let dataLesson=[];
                                      self.abc.forEach(function(e1)
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
                                      }
                                  })

                                //call lại bảng PackageLesson
                              setTimeout(function ()
                              {
                                  $.get('/xadmin/plans/dataPackage',function (res) {

                                      let dataPackage= res.data.filter(item => item.plan_id==self.entry.id)
                                      let data= dataPackage.map(res =>{
                                          return{
                                              'package_id':res.id,
                                              'plan_id':res.plan_id,
                                              'lessonIds':res.lesson_ids
                                          }
                                      })
                                    return self.lessonPackagePlans=data;
                                  })
                              },0);

                          }
            },
            async deletePackageLesson(tabLessonContent)
            {
                const res = await $post('/xadmin/plans/deletePackageLesson', {id: tabLessonContent,entry:this.entry});
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
                                    'package_id':res.id,
                                    'plan_id':res.plan_id,
                                    'lessonIds':res.lesson_ids
                                }
                            })
                            return self.lessonPackagePlans=data;
                        })
                    },0);

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
                console.log(this.$refs.uploader.files)
                if (this.$refs.uploader.files) {
                   let fileSize=(this.$refs.uploader.files[0].size.toString());
                    if(fileSize.length < 7)
                    {
                        let size= `${Math.round(+fileSize/1024).toFixed(2)}kb`
                        this.sizeFile=size;
                    }
                    else {
                       let size= `${(Math.round(+fileSize/1024)/1000).toFixed(2)}MB`
                        this.sizeFile=size;
                    }

                    this.fileUpLoad=this.$refs.uploader.files[0].name
                    const files = this.$refs.uploader.files;
                }
            },
            removeFileDevice(){
            if(this.$refs.uploader.files)
            {
                this.fileUpLoad='';
                this.$refs.uploader.value = null;
                this.valueValidateImportDevice=0;

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
                                $.get('/xadmin/plans/dataDevice',function (res) {
                                    console.log(res)
                                    let dataDevicePlan=res.data.filter(item => item.plan_id==self.entry.id)
                                    self.data=dataDevicePlan;
                                    $('#kt_modal_create_app').modal('hide');
                                    self.$refs.uploader.value = null;
                                    self.fileUpLoad='';
                                    self.valueValidateImportDevice=0;
                                    self.fileImport.length=0;
                                    self.deviceError.length=0;


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
                }
            },

            // export devive
            async exportDevice() {
                window.location.href= '/xadmin/plans/exportDevice?entry=' + JSON.stringify(this.entry)+
                '&idRoleIt=' + JSON.stringify(this.idRoleIt)+
                '&dataDevice=' + JSON.stringify(this.data);
            },

            changeLimit() {
                let params = $router.getQuery();
                params['page'] = 1;
                params['limit'] = this.limit;
                $router.setQuery(params)
            },

            selectLessonAll() {
                if (this.allLessonSelected)
                {
                    const selected=this.entries.map((u)=>u.id);
                    let self=this;
                    self.lessonPackagePlans.forEach(function (e){
                        e.lessonIds=selected;
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
                        self.allLessonSelected = true;
                    } else {
                        self.allLessonSelected = false;
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

                if (this.allViewLessonSelected ) {
                    const selected = this.dataAddLessonPlan.map((u) => u.id);
                    this.viewLessonIds = selected;
                }

                else {
                    let self = this;
                    self.viewLessonIds=[];
                    self.lessons = [];
                }
            },
            updateViewLessonCheckAll()
            {
                if (this.viewLessonIds.length === this.dataAddLessonPlan.length ) {
                    this.allViewLessonSelected = true;
                } else {
                    this.allViewLessonSelected = false;
                }
                this.lessons=[];
                let self=this;
                self.lessonPackagePlans.forEach(function (e3) {
                    self.entries.forEach(function (e4) {
                        if (e4.id == e3) {
                            self.lessons.push(e3);
                        }
                    })
                })

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
                        $.get('/xadmin/plans/dataDevice',function (res) {
                            console.log(res)
                            let dataDevicePlan=res.data.filter(item => item.plan_id==self.entry.id)
                            self.data=dataDevicePlan;
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
                    this.allLessonSelected=false;
                    for (var key in this.filter) {
                        this.filter[key] = '';
                    }
                    $router.setQuery({});
                    $('#kt_modal_invite').modal('hide');
                    let self =this;

                    setTimeout(function ()
                    {
                        $.get('/xadmin/plans/dataPackage',function (res) {
                            let dataPackage= res.data.filter(item => item.plan_id==self.entry.id)
                            // return self.lessonPackagePlans=dataPackage;
                            let data= dataPackage.map(res =>{
                                return{
                                    'package_id':res.id,
                                    'plan_id':res.plan_id,
                                    'lessonIds':res.lesson_ids
                                }
                            })
                            self.lessonPackagePlans.forEach(function (e) {
                                if(e.package_id==self.tabLessonContent)
                                {
                                    self.dataAddLessonPlan=[];
                                   e.lessonIds.forEach(function (e1) {
                                      let array=self.entries.filter(item => item.id==e1)
                                      self.dataAddLessonPlan.push(array[0]);

                                   })
                                }
                            })
                        })
                    },0);
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

                $router.setQuery(this.filter)
            },

            // data all lesson

            async load() {
                let query = $router.getQuery();
                this.$loading(true);
                setTimeout(function (){
                    KTMenu.createInstances();
                }, 0)
                const res = await $get('/xadmin/plans/dataLesson', query);
                this.$loading(false);
                this.entries = res.data;
            },
            onPageChange(page) {
                $router.updateQuery({page: page})
            },

            // add packageLesson
            async addPackageLesson() {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/addPackageLesson', {
                    entry: this.entry,
                    lessonIds: this.lessonIds
                }, false);
                this.isLoading = false;
                if (res.code) {
                    toastr.error(res.message);
                } else {
                    this.errors = {};
                    toastr.success(res.message);
                    // location.replace('/xadmin/plans/edit?id=' + this.entry.id);
                    // $('.package-lesson-link').removeClass('active');

                    let self=this;

                   setTimeout(function ()
                    {
                        $.get('/xadmin/plans/dataPackage',function (res) {
                       let dataPackage=   res.data.filter(item => item.plan_id==self.entry.id)
                            // self.lessonPackagePlans.forEach(function (e){
                            //     e.className = '';
                            // })
                            $('.package-lesson-link').removeClass('active');
                            // dataPackage.className = 'active';
                          // self.lessonPackagePlans.push(dataPackage);
                          //  self.tabPackageLesson(self.tabLessonContent);
                            let data= dataPackage.map(rec =>{
                                return{
                                    'package_id':rec.id,
                                    'plan_id':rec.plan_id,
                                    'lessonIds':rec.lesson_ids
                                }
                            })
                            self.lessonPackagePlans=data;
                            // self.lessonPackagePlans.className='active';
                            return self.tabLessonContent=data[data.length-1].package_id

                        })
                    },0);

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
                    lessonPackagePlans: this.lessonPackagePlans,
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
                this.lessonPackagePlans.forEach(function (e) {
                    packageIds.push(e.lesson_ids);
                })
                window.location.href= '/xadmin/plans/exportPlan?entry=' + JSON.stringify(this.entry)+
                '&packageLessonPlan=' + JSON.stringify(this.lessonPackagePlans)+
                '&dataDevice=' + JSON.stringify(this.data);
            },

            //download mẫu template excel add device
            downloadTemplate()
            {
                window.location.href= '/xadmin/plans/downloadTemplate';
            },
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
