<template>
    <div class="container-fluid" >
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title ="Create new plan" />
        <div class="row">
            <div class="modal fade" id="kt_modal_invite_friends" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog ">
                    <!--begin::Modal content-->
                    <div class="modal-content" style="width: max-content;margin: 0px -150px 0px">
                        <!--begin::Modal header-->
                        <div class="modal-header pb-0 border-0 justify-content-end">
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
                        <!--begin::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15" >
                            <!--begin::Heading-->
                            <div class="text-center mb-13">
                                <!--begin::Title-->
                                <h1 class="mb-3">Device list</h1>
                            </div>

                                <button @click="exportDevice"  type="button" class="btn btn-primary" style="margin: -20px 0px 15px">
                                    Export
                                </button>

                            <div class="mb-10">

                                <div class="mh-300px scroll-y me-n7 pe-7">
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


                                                    />
                                                </div>
                                            </td>
                                            <th class="">No.</th>
                                            <th class="">Device name</th>
                                            <th class="">Type</th>
                                            <th class="">Register code</th>
                                            <th class="">Status</th>

                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody >

                                        <tr v-for="device in data" v-if="device.school_id==idListDevice && device.plan_id==entry.id">

                                            <td class="">
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid"
                                                >
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"

                                                    />
                                                </div>
                                            </td>
                                            <td class="" v-text="device.id"></td>
                                            <td class="" v-text="device.device_name"></td>
                                            <td class="" >{{device.type}}</td>
                                            <td class="" v-text="device.device_uid"></td>
                                            <td class="" ></td>
                                            <td class="">
                                                <!--<a v-if="permissions['014']" :href="'/xadmin/users/edit_teacher?id='+entry.id"><i style="font-size:1.3rem"
                                                                                                        class="fa fa-edit"></i></a>
                                                <a v-if="permissions['015']" @click="remove(entry)" href="javascript:;" class="btn-trash deleted"><i
                                                    class="fa fa-trash mr-1 deleted"></i></a>-->

<!--                                                <a  >-->
<!--                                                    <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary">-->
<!--                                                        <i class="fa fa-edit"></i>-->
<!--                                                    </button>-->
<!--                                                </a>-->
<!--                                                <a  href="javascript:;">-->
<!--                                                    <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary">-->
<!--                                                        <i class="fa fa-trash mr-1 deleted"></i>-->
<!--                                                    </button>-->
<!--                                                </a>-->

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
            <div class="modal fade" id="kt_modal_invite" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog " style="width: 1000px">
                    <!--begin::Modal content-->
                    <div class="modal-content" style="width: max-content;margin: 0px -150px 0px">
                        <!--begin::Modal header-->
                        <div class="modal-header pb-0 border-0 justify-content-end">
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
                        <!--begin::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15" >
                            <!--begin::Heading-->
                            <div class="text-center mb-13">
                                <!--begin::Title-->
                                <h1 class="mb-3">Lesson package</h1>
                            </div>
                            <div class="d-flex">

                                        <div  class="h-15px me-3">
                                            <label>Name </label>
                                            <input  v-model="filter.name" @keydown.enter="doFilter('name', filter.name, $event)"
                                                class="form-control" placeholder="Enter the lesson name"
                                            />
                                        </div>
                                        <div  class="h-15px me-3">
                                            <label>Subject </label>
                                            <select required class="form-control form-select" v-model="filter.subject" @keydown.enter="doFilter('subject', filter.subject, $event)">
                                                <option value="" disabled selected>Choose Subject</option>
                                                <option value="0">All</option>
                                                <option value="Math">Math</option>
                                                <option value="Science ">Science</option>
                                            </select>

                                        </div>
                                        <div  class="h-15px me-3">
                                            <label>Grade </label>
                                            <select required class="form-control form-select" v-model="filter.grade"  @keydown.enter="doFilter('grade', filter.grade, $event)">
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

                            <div class="d-flex" style="margin: 64px 0px 0px">

                                <div class="mh-300px scroll-y me-n7 pe-7">
                                    <table class="table table-row-bordered align-middle gy-4 gs-9">
                                        <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                                        <tr>
                                            <td width = "25">
                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox" v-model="allSelected" @change="selectAll()">
                                                </div>
                                            </td>
                                            <th class="">Name of lesson</th>
                                            <th class="">Grade</th>
                                            <th class="">Subject</th>


                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody >

                                        <tr  v-for="lesson in entries" >
                                            <td class="">
                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input   class="form-check-input" type="checkbox" v-model="lessonIds" :value="lesson.id" @change="updateCheckAll(package)">
                                                </div>
                                            </td>
                                            <td class="" v-text="lesson.name"></td>
                                            <td class="" v-text="lesson.grade" ></td>
                                            <td class="" v-text="lesson.subject"></td>

                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="d-flex pl-9 pr-9 mb-8">
                                        <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                            <!--<div class="mr-2">
                                                <label>Records per page:</label>
                                            </div>-->
                                            <div>
                                                <select class="form-select form-select-sm form-select-solid" v-model="limit" @change="changeLimit" >
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>

                                                </select>
                                            </div>
                                        </div>
                                        <!--<div style="float: right; margin: 10px">-->
                                        <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                            <div class="dataTables_paginate paging_simple_numbers" id="kt_customers_table_paginate1">
                                                <Paginate :value="paginate" :pagechange="onPageChange"></Paginate>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary" style="margin: 20px 0px 0px" @click="addLesson">Confirm</button>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <div class="modal fade" id="kt_modal" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog " style="width: 1000px">
                    <!--begin::Modal content-->
                    <div class="modal-content" style="width: max-content;margin: 0px -150px 0px">
                        <!--begin::Modal header-->
                        <div class="modal-header pb-0 border-0 justify-content-end">
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
                        <!--begin::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15" >
                            <!--begin::Heading-->
                            <div class="text-center mb-13">
                                <!--begin::Title-->
                                <h1 class="mb-3">Lesson package</h1>
                            </div>
                            <div class="d-flex">

                                <div  class="h-15px me-3">
                                    <label>Name </label>
                                    <input  v-model="filter.name" @keydown.enter="doFilter('name', filter.name, $event)"
                                            class="form-control" placeholder="Enter the lesson name"
                                    />
                                </div>
                                <div  class="h-15px me-3">
                                    <label>Subject </label>
                                    <select required class="form-control form-select" v-model="filter.subject" @keydown.enter="doFilter('subject', filter.subject, $event)">
                                        <option value="" disabled selected>Choose Subject</option>
                                        <option value="0">All</option>
                                        <option value="Math">Math</option>
                                        <option value="Science ">Science</option>
                                    </select>

                                </div>
                                <div  class="h-15px me-3">
                                    <label>Grade </label>
                                    <select required class="form-control form-select" v-model="filter.grade"  @keydown.enter="doFilter('grade', filter.grade, $event)">
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

                            <div class="d-flex" style="margin: 64px 0px 0px">

                                <div class="mh-300px scroll-y me-n7 pe-7">
                                    <table class="table table-row-bordered align-middle gy-4 gs-9">
                                        <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                                        <tr>

                                            <th class="">Name of lesson</th>
                                            <th class="">Grade</th>
                                            <th class="">Subject</th>


                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody v-for="lessonId in lessonIds">

                                        <tr  v-for="lesson in entries"  v-if="lesson.id==lessonId">

                                            <td class="" v-text="lesson.name"></td>
                                            <td class="" v-text="lesson.grade" ></td>
                                            <td class="" v-text="lesson.subject"></td>
                                            <td>
                                                <a   @click="deleteLesson(lessonId)" href="javascript:;">
                                        <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary">
                                            <i class="fa fa-trash mr-1 deleted"></i>
                                        </button>
                                    </a>

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


            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-6" style="margin:0px 0px -35px">
                        <div class="card-title"></div>
                        <div class="card-toolbar">

                        </div>

                    </div>

                    <div class="card-body d-flex flex-column" >
                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group col-lg-8">
                                        <label>Plan name <span class="text-danger">*</span></label>
                                        <input  v-model="entry.name"  class="form-control"
                                                placeholder="Enter the name of plan" >
                                        <error-label for="f_school_name" ></error-label>

                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label> Assign to IT <span class="text-danger">*</span></label>
                                        <select   class="form-control form-select" v-model="idRoleIt"
                                        >
                                            <option v-for="role in roleIt" :value="role.id">{{role.full_name}}</option>
                                        </select>
                                        <error-label  ></error-label>

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-8">
                                        <label>Plan description  <span class="text-danger">*</span> </label>
                                        <input   class="form-control"
                                                 placeholder="Enter the description" v-model="entry.plan_description">

                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label>Due date  <span class="text-danger">*</span></label>
                                        <Datepicker v-model="entry.due_at"/>
                                        <error-label for="f_title" ></error-label>


                                    </div>


                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-8">
                                        <label>School<span class="text-danger">*</span> </label>
                                        <treeselect :options="schools" :multiple="true" v-model="schoolPlan" />
                                    </div>

                                </div>
                                <div class="row" v-for="deviceSchool in nameSchool">
                                    <div class="form-group col-lg-8" >
                                        <label>Device của trường  {{deviceSchool.school_name}} </label>
                                        <div class="card-header  border border-dashed border-gray-300">
                                            <!--begin::Card title-->
                                            <div class="card-title" style="font-size: 15px">
                                                <div  class="fw-bold text-muted" >{{deviceSchool.lengthDevicePlan.length}} device(s) added</div>
                                            </div>
                                            <!--end::Card title-->
                                            <!--begin::Card toolbar-->
                                            <div class="card-toolbar">
                                                <button class="btn btn-primary" @click="viewDeviceSchoolPlan(deviceSchool.id)">View devices</button>
                                                <button class="btn btn-primary" style="margin: 0px 15px 0px" @click="addDevice(deviceSchool.id)">Add a device</button>
                                                <button class="btn btn-primary"  @click="importDevice(deviceSchool.id)">Import devices</button>
                                            </div>
                                            <!--end::Card toolbar-->
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row" >
                               <div >
                                   <button type="button" class="btn btn-sm btn-flex btn-light-primary " data-bs-toggle="modal" data-bs-target="#kt_modal_add_payment" style=" margin: 7px 0px 10px;" id="newPackage" @click="addPackageLesson" >
                                       <span class="svg-icon svg-icon-3">
																<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																	<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="black" />
																	<rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="black" />
																	<rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="black" />
																</svg>
															</span>
                                        Add lesson package
                                   </button>
                                </div>
                                <div class="form-group col-lg-8" id="clone" v-for="packageLesson in packageLessonPlan">
                                    <label>Lesson package {{packageLesson.id}}<span class="text-danger">*</span></label>
                                    <div class="card-header  border border-dashed border-gray-300">
                                        <!--begin::Card title-->
                                        <div class="card-title" style="font-size: 15px">
                                            <div  class="fw-bold text-muted" >{{lessonIds.length}} lesson(s) added</div>
                                        </div>
                                        <!--end::Card title-->
                                        <!--begin::Card toolbar-->
                                        <div class="card-toolbar">
                                            <button class="btn btn-primary"   @click="downloadLesson()">Download package</button>
<!--                                            <button class="btn btn-primary"  v-if="url.status=='waitting'" @click="downloadLesson()">Download package</button>-->
                                            <span   v-if="url.status=='inprogress'">inprogress</span>
                                            <a v-if="url.status=='done'" :href="url.url" type="button" class="btn btn-primary">Dowload Package</a>

                                            <button class="btn btn-primary" style="margin: 0px 15px 0px" data-bs-toggle="modal" data-bs-target="#kt_modal" >View lessons</button>
                                            <button class="btn btn-primary" @click="addLessonPackage(packageLesson.id)" >Add lesson</button>
                                        </div>
                                        <!--end::Card toolbar-->
                                    </div>
<!--                                    <div class="d-flex align-items-center"  style="margin-top: 20px" >-->
<!--                                        &lt;!&ndash;begin::Checkbox&ndash;&gt;-->
<!--                                        <label class="form-check form-check-custom form-check-solid me-10">-->
<!--                                            <input class="form-check-input h-20px w-20px" type="checkbox" name="communication[]" value="email"  />-->
<!--                                            <span class="form-check-label fw-bold">For Windows</span>-->
<!--                                        </label>-->
<!--                                        &lt;!&ndash;end::Checkbox&ndash;&gt;-->
<!--                                        &lt;!&ndash;begin::Checkbox&ndash;&gt;-->
<!--                                        <label class="form-check form-check-custom form-check-solid">-->
<!--                                            <input class="form-check-input h-20px w-20px" type="checkbox" name="communication[]" value="phone" />-->
<!--                                            <span class="form-check-label fw-bold">For MacOS</span>-->
<!--                                        </label>-->
<!--                                        &lt;!&ndash;end::Checkbox&ndash;&gt;-->
<!--                                    </div>-->

                                </div>
                            </div>
                        </div>




                    </div>
                    <hr style="margin-top: 5px;">
                    <div style="margin: 13px 25px 13px">
                        <button type="reset" @click="save()" class="btn btn-primary mr-2">Save</button>
                        <button type="reset" @click="backIndex()" class="btn btn-secondary">Cancel</button>
                    </div>
                </div>
            </div>


        </div>
        <div class="modal fade" style="margin-right:50px " id="deviceConfirm" tabindex="-1" role="dialog"
             aria-labelledby="deviceConfirm"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                 style="max-width: 500px;">
                <div class="modal-content box-shadow-main paymment-status" style="margin-right:20px; left:140px">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 style="margin:20px auto;font-weight: 500;" class="popup-title success">Add more device</h3>
                    <div class="content" style="margin: -30px 20px 20px">
                        <p>Bước 1 :Sử dụng máy tính mà bạn muốn thêm thiết bị mở ứng dụng IDIGI trên Desktop</p>
                        <p>Bước 2:Nhấn vào nút "Get device information" và copy đoạn mã thông tin thiết bị </p>
                        <p>Bước 3:Dán đoạn mã vào ô phía dưới</p>
                        <input type="text" class="form-control " placeholder="Enter the device name" aria-label="" style="margin-bottom: 10px" aria-describedby="basic-addon1" v-model="deviceName" >
<!--                        <error-label for="f_category_id" :errors="errors.device_name"></error-label>-->

                        <input type="text" class="form-control " placeholder="Enter the register code" aria-label="" aria-describedby="basic-addon1" v-model="deviceUid" >
<!--                        <error-label for="f_category_id" :errors="errors.device_uid"></error-label>-->
                    </div>
                    <div class="form-group d-flex justify-content-between">
                        <!--                        <button  class="btn btn-danger ito-btn-small" data-dismiss="modal" @click="save()">Add now</button>-->
                        <button class="btn btn-primary ito-btn-add" data-dismiss="modal" @click="saveDevice()" style="margin:0 auto">
                            Add now
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="kt_modal_create_app" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-900px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <h2>Import devices</h2>
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
                                            <h3 class="stepper-title">Import process</h3>
                                        </div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Step 3-->
                                    <!--begin::Step 4-->
                                    <div class="stepper-item" data-kt-stepper-element="nav">
                                        <!--begin::Line-->
                                        <div class="stepper-line w-40px"></div>
                                        <!--end::Line-->
                                        <!--begin::Icon-->
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="stepper-check fas fa-check"></i>
                                            <span class="stepper-number">4</span>
                                        </div>
                                        <!--end::Icon-->
                                        <!--begin::Label-->
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">Completed</h3>
                                        </div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Step 4-->
                                    <!--begin::Step 5-->

                                    <!--end::Step 5-->
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
                                                <!--begin::Label-->
                                                <div class="dropzone-panel mb-4">
                                                    <label>File <span class="required"></span></label>
                                                    <input type="file" ref="uploader" class="form-control-file" @change="saveValidateImportDevice">
                                                    <error-label ></error-label>
                                                </div>

                                                <!--                                                <input type="text" class="form-control form-control-lg form-control-solid" name="name" placeholder="" value="" style="font-size: 22px" />-->
                                                <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                                                    <!--begin::Details-->
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Avatar-->

                                                        <!--end::Avatar-->
                                                        <!--begin::Details-->
                                                        <div class="ms-6">

                                                            <div class="fw-bold text-muted">{{fileImport.length}} new record(s)</div>
                                                            <!--end::Email-->
                                                        </div>
                                                        <!--end::Details-->
                                                    </div>
                                                    <!--end::Details-->
                                                    <!--begin::Stats-->
                                                    <div class="d-flex">
                                                        <!--begin::Sales-->
                                                        <div class="text-end">
                                                          	<span class="form-check form-check-custom form-check-solid">
																					<input class="form-check-input" type="radio" name="category" :value="fileImport" v-model="fileImport"/>
                                                                <label style="margin: 0px 10px 0px">Import</label>
                                                                <input class="form-check-input" type="radio" name="category" value="1" v-model="doNotImport"/>
                                                                <label style="margin: 0px 10px 0px">Do not import</label>
																				</span>

                                                        </div>
                                                        <!--end::Sales-->
                                                        <!--end::Stats-->
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                                                <!--begin::Details-->
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Avatar-->

                                                    <!--end::Avatar-->
                                                    <!--begin::Details-->
                                                    <div class="ms-6">

                                                        <div class="fw-bold text-muted">{{deviceError.length}} error record(s)</div>
                                                        <!--end::Email-->
                                                    </div>
                                                    <!--end::Details-->
                                                </div>
                                                <!--end::Details-->
                                                <!--begin::Stats-->
                                                <div class="d-flex">
                                                    <!--begin::Sales-->
                                                    <div class="text-end">
                                                        <div class="fs-7 text-muted"><a :href="validateFile" type="button" class="btn btn-primary">Export</a></div>
                                                    </div>
                                                    <!--end::Sales-->
                                                </div>
                                                <!--end::Stats-->
                                            </div>
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
                                            <button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="submit">
												<span class="indicator-label">Submit
                                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
												<span class="svg-icon svg-icon-3 ms-2 me-0">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
														<rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black" />
														<path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black" />
													</svg>
												</span>
                                                    <!--end::Svg Icon--></span>
                                                <span class="indicator-progress">Please wait...
												<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                            </button>
                                            <button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="next" @click="saveImport">Continue
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
    </div>

</template>

<script>
    import {$get, $post, forEach, getTimeRangeAll} from "../../utils";
    import ActionBar from "../includes/ActionBar";
    import $router from "../../lib/SimpleRouter";
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'
    import Treeselect from "@riophae/vue-treeselect";

    let created = getTimeRangeAll();
    const $q = $router.getQuery();

    export default {
        name: "PlanEdit.vue",
        components: {ActionBar,Treeselect},
        data() {


            // $(document).ready(function() {
            //    $("#newPackage").click(function ()
            //    {
            //        $("#clone").append("<li></li>")
            //    });
            // });
            let filter = {
                keyword: $q.keyword || '',
                name:$q.name||'',
                subject: $q.subject || '',
                grade: $q.grade || '',
            };

            return {
                packagePlan:$json.packagePlan,
                package:'',
                idListDevice:[],
                nameSchool:$json.nameSchool || [],
                schoolPlan:$json.schoolPlan || [],
                trung:$json.trung || [],
                schoolId:'',
                schools:$json.schools || [],
                exportDevicePlan:'',
                lessonIds: $json.lessonIds || [],
                data:$json.data || [],
                allSelected: false,
                filter: filter,
                entries:[],
                doNotImport:'',
                deviceError:[],
                code:0,
                validateFile:'',
                fileImport:[],
                deviceName:'',
                deviceUid:'',
                url:$json.url || [],
                idRoleIt:$json.idRoleIt,
                roleIt:$json.roleIt || [],
                breadcrumbs: [
                    {
                        title: 'Manage plans',
                        url:'/xadmin/plans/index'
                    },
                    {
                        title: 'Create new plan'
                    },
                ],
                entry: $json.entry || {},
                data:$json.data || [],
                packageLessonPlan:$json.packageLessonPlan || [],
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
             importDevice:function(addevicePlan=''){
                $('#kt_modal_create_app').modal('show');
                this.schoolId=addevicePlan;
            },
             addDevice:function(addDevice=''){
                $('#deviceConfirm').modal('show');
                this.schoolId=addDevice;
            },
             viewDeviceSchoolPlan:function(viewDevice=''){
                $('#kt_modal_invite_friends').modal('show');
                this.idListDevice=viewDevice;
            },
            addLessonPackage:function(addLesson='')
            {
                $('#kt_modal_invite').modal('show');
                this.package=addLesson;
            },
            // async downloadLesson() {


            //     const res = await $post('/xadmin/plans/downloadLesson', {
            //         entry:this.entry,
            //         lessonIds: this.lessonIds,
            //         device: this.device
            //     });
            //     window.location.href = res.url;

            // },
            async deleteLesson(lesson)
            {
                let new_arr = this.lessonIds.filter(item => item !== lesson);
                this.lessonIds=new_arr
                let lessons=this.lessonIds
                const res = await $post('/xadmin/plans/deleteLesson', {lessons,entry:this.entry});
                if (res.code) {
                    toastr.error(res.message);
                } else {
                        toastr.success(res.message);

                }
            },
            backIndex(){
                window.location.href = '/xadmin/plans/index';
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/save', {entry: this.entry,idRoleIt:this.idRoleIt,schoolPlan:this.schoolPlan, schoolId:this.schoolId,idListDevice:this.idListDevice}, false);
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

                    if (!this.entry.id) {
                        location.replace('/xadmin/plans/edit?id=' + res.id);
                    }

                }
            },
            async saveValidateImportDevice()
            {
                this.errors = {};
                if(this.$refs.uploader.files)
                {
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
                    let res = await fetch('/xadmin/plans/validateImportDevice', {
                        method: 'POST',
                        body: formData
                    })

                        .then((response) => response.json())
                        .catch((error) => {
                            console.error('Error:', error);
                        });
                    if(res.code==2)
                    {
                        this.deviceError=res.deviceError;
                        this.code=res.code;
                        this.validateFile=res.fileError;
                        this.fileImport=res.fileImport;
                    }
                    if(res.code==1)
                    {
                        this.code=res.code;
                        this.fileImport=res.fileImport;
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
                }
            },
            async saveImport() {
                if(this.doNotImport=='')
                {
                    {
                        this.$loading(true);
                        const res = await $post('/xadmin/plans/import', {
                            fileImport:this.fileImport,
                            entry: this.entry,
                            schoolId:this.schoolId,
                            idRoleIt:this.idRoleIt,
                            doNotImport:this.doNotImport,
                        }, false);
                        this.$loading(false);
                        if (res.code) {
                            console.log('1')
                            toastr.error(res.message);
                        } else {
                            this.errors = {};
                            this.exportDevicePlan=res.url;
                            toastr.success(res.message);
                            location.replace('/xadmin/plans/index');
                        }
                    }
                }
                if(this.doNotImport==1)
                {
                    location.replace('/xadmin/plans/index');
                }
            },
            async exportDevice()
            {
                 const res = await $post('/xadmin/plans/exportDevice', {
                    csrf: window.$csrf,
                    dataDevice: this.data,
                    entry: this.entry,
                    idListDevice:this.idListDevice,
                    schoolId:this.idListDevice,
                });
                 window.location.href = res.url;
            },

            changeLimit() {
                let params = $router.getQuery();
                params['page'] = 1;
                params['limit'] = this.limit;
                $router.setQuery(params)
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
                let self = this;


                self.packagePlan.forEach(function(e2){
                   if(e2.package_id==self.package)
                   {
                    if (self.lessonIds.length === self.entries.length) {
                        self.allSelected = true;
                    }
                    else {
                        self.allSelected = false;
                    }
                   }

                })


                self.lessonIds.forEach(function (e) {
                    self.entries.forEach(function (e1) {
                        if (e1.id == e) {
                            self.lessons.push(e1);
                        }
                    })
                })
            },
            async saveDevice() {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/saveDevice', {idRoleIt:this.idRoleIt,deviceName:this.deviceName,deviceUid:this.deviceUid,entry:this.entry,schoolId:this.schoolId}, false);
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

                    if (!this.entry.id) {
                        // location.replace('/xadmin/plans/edit?id=' + res.id);
                    }

                }
            },
            async addLesson()
            {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/planLesson', {lessonIds:this.lessonIds,entry:this.entry,package:this.package}, false);
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

                    if (!this.entry.id) {
                        // location.replace('/xadmin/plans/edit?id=' + res.id);
                    }

                }

            },
            doFilter() {

                $router.setQuery(this.filter)
            },
            async load() {
                let query = $router.getQuery();
                this.$loading(true);
                const res = await $get('/xadmin/plans/dataLesson', query);
                this.$loading(false);
                this.paginate = res.paginate;
                this.entries = res.data;
                this.from = (this.paginate.currentPage - 1) * (this.limit) + 1;
                this.to = (this.paginate.currentPage - 1) * (this.limit) + this.entries.length;
            },
            onPageChange(page) {
                $router.updateQuery({page: page})
            },
           async addPackageLesson()
            {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/addPackageLesson', {entry:this.entry,lessonIds:this.lessonIds,package:this.package}, false);
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

                    if (!this.entry.id) {
                        // location.replace('/xadmin/plans/edit?id=' + res.id);
                    }

                }
            },
            async downloadLesson()
            {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/downloadLesson', {entry:this.entry,lessonIds:this.lessonIds,package:this.package}, false);
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

                    if (!this.entry.id) {
                        // location.replace('/xadmin/plans/edit?id=' + res.id);
                    }

                }
            }

        }
    }
</script>

<style scoped>

</style>
