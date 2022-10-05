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
                                        <input v-model="entry.name" class="form-control" placeholder="Enter the name of plan">
                                        <error-label :errors="errors.name" for="f_school_name"></error-label>
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
                                    <div class="form-group col-lg-8">
                                        <label>Plan description <span class="text-danger">*</span> </label>
                                        <textarea class="form-control"
                                                  placeholder="Enter the description" v-model="entry.plan_description">
                                        </textarea>
                                        <error-label :errors="errors.plan_description"></error-label>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Due date <span class="text-danger">*</span></label>
                                        <Datepicker disabled v-model="entry.due_at"/>
                                        <error-label :errors="errors.due_at" for="f_title"></error-label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label>Expire date <span class="text-danger">*</span></label>
                                        <Datepicker v-if="roleAuth=='IT'" v-model="entry.expire_date" disabled/>
                                        <Datepicker v-if="roleAuth!='IT'" v-model="entry.expire_date" />
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
                                                   @click="tabPackageLesson(packageLesson.package_id)">Package lesson
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
                                                    <th class="">Register code</th>
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
                                                <td>{{device.device_uid}}</td>
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
                                            <div class="d-flex justify-content-end mb-4" v-if="!dataZipLesson">
                                                <a v-if="viewLessonIds!=''" class="btn btn-danger btn-sm mr-3" @click="deleteAllLesson" >Delete</a>
                                                <a class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" >Actions
                                                    <span class="svg-icon svg-icon-5 m-0">
															<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viewBox="0 0 24 24" fill="none">
																<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black"/>
															</svg>
                                                    </span>
                                                </a>
                                                <div class="menu menu-sub menu-sub-dropdown  menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 py-4" data-kt-menu="true" style="width: 150px" >
                                                    <div class="menu-item px-3" v-if="roleAuth=='Super Administrator'">
                                                        <a class="menu-link px-3" @click="addLessonPackage(tabLessonContent)">Add lesson</a>
                                                    </div>
                                                    <div class="menu-item px-3" v-if="roleAuth=='Super Administrator'">
                                                        <a class="menu-link px-3 text-danger " @click="deletePackageLesson(tabLessonContent)" >Delete package lesson</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end mb-4" v-if="dataZipLesson">
                                                <a v-if="viewLessonIds!='' && dataZipLesson.status=='waitting' && roleAuth=='Super Administrator'" class="btn btn-danger btn-sm mr-3" @click="deleteAllLesson" >Delete</a>
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
                                                <div class="menu menu-sub menu-sub-dropdown  menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 py-4" data-kt-menu="true" style="width: 150px" >
                                                    <div class="menu-item px-3"  v-if="roleAuth=='Super Administrator' && dataZipLesson.status=='waitting'">
                                                        <a class="menu-link px-3 " @click="addLessonPackage(tabLessonContent)" >Add lesson</a>
                                                    </div>
                                                    <div class="menu-item px-3" v-if="dataZipLesson.status=='waitting'">
                                                        <a class="menu-link px-3 " @click="downloadLesson(tabLessonContent)"  >Zip package lesson</a>
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
                                                                    <input class="form-check-input" type="checkbox" v-model="allViewLessonSelected" @change="selectViewLessonAll(tabLessonContent)" >
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
                                                                    <input class="form-check-input" type="checkbox" v-model="viewLessonIds" :value="lesson.id" @change="updateViewLessonCheckAll(lessonPackagePlan.id)">
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
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document" style="max-width: 500px;">
                <div class="modal-content box-shadow-main paymment-status" style="margin-right:20px; left:140px">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 style="margin:20px auto;font-weight: 500;" class="popup-title success">Add more device</h3>
                    <div class="content" style="margin: -30px 20px 20px">
                        <p>Bước 1 :Sử dụng máy tính mà bạn muốn thêm thiết bị mở ứng dụng IDIGI trên Desktop</p>
                        <p>Bước 2:Nhấn vào nút "Get device information" và copy đoạn mã thông tin thiết bị </p>
                        <p>Bước 3:Dán đoạn mã vào ô phía dưới</p>
                        <datepicker v-model="deviceExpireDate" class="form-control mb-4" ></datepicker>
                        <input type="text" class="form-control " placeholder="Enter the device name" aria-label="" style="margin-bottom: 10px" aria-describedby="basic-addon1" v-model="deviceName">
                        <!--                        <error-label for="f_category_id" :errors="errors.device_name"></error-label>-->

                        <input type="text" class="form-control " placeholder="Enter the register code" aria-label=""
                               aria-describedby="basic-addon1" v-model="deviceUid">
                        <!--                        <error-label for="f_category_id" :errors="errors.device_uid"></error-label>-->
                    </div>
                    <div class="form-group d-flex justify-content-between">
                        <button class="btn btn-primary ito-btn-add" data-dismiss="modal" @click="saveDevice()" style="margin:0 auto">
                            Add now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- end: modal add device-->

        <!-- BEGIN: MODAL IMPPORT DEVICE -->
        <div class="modal fade" id="kt_modal_create_app" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-900px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Import devices</h2>
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <span class="svg-icon svg-icon-1">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none">
									<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                          transform="rotate(-45 6 17.3137)" fill="black"/>
									<rect x="7.41422" y="6" width="16" height="2" rx="1"
                                          transform="rotate(45 7.41422 6)" fill="black"/>
								</svg>
							</span>
                        </div>
                    </div>
                    <div class="modal-body py-lg-10 px-lg-10">
                        <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid"
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
                                                <div class="dropzone-panel mb-4">
                                                    <label>File <span class="required"></span></label>
                                                    <input type="file" ref="uploader" class="form-control-file"
                                                           @change="saveValidateImportDevice">
                                                    <error-label></error-label>
                                                </div>

                                                <div
                                                    class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
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
                                                                <input class="form-check-input" type="radio" name="category" :value="fileImport" v-model="fileImport"/>
                                                                <label style="margin: 0px 10px 0px">Import</label>
                                                                <input class="form-check-input" type="radio"
                                                                       name="category" value="1" v-model="doNotImport"/>
                                                                <label style="margin: 0px 10px 0px"> Do not import </label>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div
                                                class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-6">

                                                        <div class="fw-bold text-muted">{{deviceError.length}} error
                                                            record(s)
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="text-end">
                                                        <div class="fs-7 text-muted">
                                                        <a :href="validateFile" type="button" class="btn btn-primary">Export</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="d-flex flex-stack pt-10">
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
                doNotImport: '',
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
            }
        },

        mounted() {
            $router.on('/', this.load).init();
        },

        methods: {
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
                console.log(this.tabLessonContent);
                let self = this;
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
                };
                // let interval = setInterval(function () {
                //     $.get('/xadmin/plans/dataZipLessonPlan', function (res) {
                //         setTimeout(function (){
                //             KTMenu.createInstances();
                //         }, 0)
                //         let array = res.data.filter(item => item.plan_id ==self.entry.id);
                //
                //         array.forEach(function (e) {
                //
                //
                //             if(e.package_id==tabPackage)
                //             {
                //                 let dataZip=[];
                //                 dataZip.push(e);
                //                 self.improgress=dataZip[0].status;
                //                 self.dataZipLesson=dataZip[0];
                //             }
                //
                //         })
                //         let done =res.data.filter(item => item.status=='done');
                //         if(array.length==done.length)
                //         {
                //             clearInterval(interval);
                //         }
                //         console.log('1');
                //     })
                //
                // }, 1000);
                // console.log(self.dataZipLesson);



                if(self.urls)
                {
                    self.dataZipLesson = self.urls.filter( item => item.package_id==tabPackage);
                    setTimeout(function (){
                        KTMenu.createInstances();
                    }, 0)
                    return   self.dataZipLesson=self.dataZipLesson[0];
                }
            },
            importDevice(){
                $('#kt_modal_create_app').modal('show');
            },
            addDevice: function (addDevice = '') {
                $('#deviceConfirm').modal('show');
            },
            addLessonPackage: function (tabLessonContent = '') {
                $('#kt_modal_invite').modal('show');
                console.log(tabLessonContent);
                this.package = tabLessonContent;
            },
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
            async deleteAllLesson()
            {
              let self=this;
              for(const e of self.lessonPackagePlans)
              {
                  if(e.package_id==self.tabLessonContent)
                  {
                      for( const e1 of self.viewLessonIds)
                      {
                          let array=e.lessonIds.filter(item=>item !==e1);
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
                              self.viewLessonIds=[];
                              self.allViewLessonSelected=false;
                              self.dataAddLessonPlan= self.dataAddLessonPlan.filter(item => item.id !==e1);
                          }

                      }
                  }
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
                          console.log(res.data);

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
                    location.replace('/xadmin/plans/edit?id=' + this.entry.id);


                    if (!this.entry.id) {
                        location.replace('/xadmin/plans/edit?id=' + this.entry.id);
                    }

                }
            },
            async saveValidateImportDevice() {
                // this.errors = {};
                if (this.$refs.uploader.files) {
                    const files = this.$refs.uploader.files;
                    const formData = new FormData();
                    formData.append('_token', window.$csrf)
                    forEach(files, (v, k) => {
                        formData.append(k, v);
                    });

                    for (let i = 0; i < files.length; i++) {
                        formData.append('file_' + i, files[i]);
                        formData.append('expire_date', this.entry.expire_date);
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
            async saveImport() {
                if (this.doNotImport == '') {
                    {
                        this.$loading(true);
                        const res = await $post('/xadmin/plans/import', {
                            fileImport: this.fileImport,
                            entry: this.entry,
                            schoolId: this.schoolId,
                            idRoleIt: this.idRoleIt,
                            doNotImport: this.doNotImport,
                        }, false);
                        this.$loading(false);
                        if (res.code) {
                            console.log('1')
                            toastr.error(res.message);
                        } else {
                            this.errors = {};
                            this.exportDevicePlan = res.url;
                            toastr.success(res.message);
                            location.replace('/xadmin/plans/index');
                        }
                    }
                }
                if (this.doNotImport == 1) {
                    location.replace('/xadmin/plans/index');
                }
            },
            async exportDevice() {
                const res = await $post('/xadmin/plans/exportDevice', {
                    csrf: window.$csrf,
                    dataDevice: this.data,
                    entry: this.entry,
                    idRoleIt: this.idRoleIt,
                });
                window.location.href = res.url;
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
                } else {
                    this.viewLessonIds = [];
                }
            },
            updateViewLessonCheckAll()
            {
                if (this.viewLessonIds.length === this.dataAddLessonPlan.length ) {
                    this.allViewLessonSelected = true;
                } else {
                    this.allViewLessonSelected = false;
                }

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
                    location.replace('/xadmin/plans/edit?id=' + this.entry.id);


                    if (!this.entry.id) {
                        location.replace('/xadmin/plans/edit?id=' + entry.id);
                    }

                }
            },


            async addLesson() {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/planLesson', {
                    lessonPackagePlans: this.lessonPackagePlans,
                    entry: this.entry,
                    package: this.package
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
                    location.replace('/xadmin/plans/edit?id=' + this.entry.id);


                    if (!this.entry.id) {
                        location.replace('/xadmin/plans/edit?id=' + this.entry.id);
                    }

                }

            },
            async sentAdmin() {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/sentAdmin', {entry: this.entry}, false);
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
                    location.replace('/xadmin/plans/edit?id=' + this.entry.id);


                    if (!this.entry.id) {
                        location.replace('/xadmin/plans/edit?id=' + this.entry.id);
                    }

                }

            },
            doFilter() {

                $router.setQuery(this.filter)
            },
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
                            console.log(self.tabLessonContent);
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
                    location.replace('/xadmin/plans/edit?id=' + this.entry.id);


                    if (!this.entry.id) {
                        location.replace('/xadmin/plans/edit?id=' + this.entry.id);
                    }

                }
            },
            async sentSale() {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/sentSale', {entry: this.entry}, false);
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
        }
    }
</script>

<style scoped>
.isDisabled {
    color: currentColor;
    cursor: not-allowed;
    opacity: 0.5;
    text-decoration: none;
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

</style>
