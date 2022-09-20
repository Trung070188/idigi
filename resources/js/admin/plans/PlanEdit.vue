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
                                        <tbody v-for="lessonId in lessonPackagePlans" v-if="lessonId.package_id==package ">
                                        <tr v-for="lesson in entries"  >
                                            <td class="">
                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input   class="form-check-input" type="checkbox" v-model="lessonId.lessonIds"  :value="lesson.id" @change="updateCheckAll(package)">

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
                                <button class="btn btn-primary" style="margin: 20px 0px 0px" @click="addLesson()">Confirm</button>
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
                                    <table class="table table-row-bordered align-middle gy-4 gs-9" v-for="lessonId in lessonPackagePlans" v-if="lessonId.package_id==viewPackage">
                                        <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                                        <tr>
                                            <th class="">Name of lesson</th>
                                            <th class="">Grade</th>
                                            <th class="">Subject</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody v-for="packageLesson in lessonId.lessonIds" >
                                            <tr  v-for="lesson in entries"  v-if="packageLesson==lesson.id">
                                                <td class="" v-text="lesson.name"></td>
                                                <td class="" v-text="lesson.grade" ></td>
                                                <td class="" v-text="lesson.subject"></td>
                                                <td>
                                                    <a    @click="deleteLesson(lesson)" href="javascript:;">
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
                                   <button type="button" class="btn btn-sm btn-flex btn-light-primary " data-bs-toggle="modal" data-bs-target="#kt_modal_add_payment" style=" margin: 7px 0px 10px;" id="newPackage" @click="addPackageLesson()" >
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
                                <div class="form-group col-lg-8" id="clone" v-for="(packageLesson,index) in packageLessonPlan" >
                                    <label>Lesson package <svg width="20pt" height="20pt" version="1.1" viewBox="0 0 700 700" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path d="m541.29 284.29c-1.5859 0-3.125 0.30469-4.6914 0.53516-0.98047-2.7773-2.0312-5.5312-3.125-8.2617 4.457 0.1875 7.5586 0.30469 11.199-0.55859 10.035-2.4023 17.035-11.27 17.035-21.582v-51.145c0-12.227-9.9648-22.191-22.191-22.191h-10.035c-4.1758-2.4258-4.293-3.7109-5.0391-12.832-5.1328-62.953-45.852-111.79-114.89-138.06l-0.27734-7.5156c-0.48828-11.922-10.219-21.258-22.145-21.258h-74.223c-11.922 0-21.676 9.332-22.168 21.258l-0.32422 7.5352c-42.535 16.266-108.08 54.672-114.89 138.09-0.72266 9.1016-0.83984 10.383-5.0156 12.809h-10.055c-12.227 0-22.191 9.9414-22.191 22.191v51.148c0 10.312 7.0234 19.203 17.078 21.582 3.6406 0.88672 6.7891 0.74609 11.199 0.55859-1.1211 2.7305-2.1914 5.4844-3.1719 8.2383-1.5391-0.23438-3.0781-0.53516-4.6445-0.53516-24.406 0-43.516 26.203-43.516 59.664 0 32.715 18.316 58.309 41.93 59.453 21.398 88.918 99.637 155.16 192.88 155.16 93.215 0 171.48-66.219 192.85-155.14 23.613-1.1445 41.93-26.738 41.93-59.453 0.023438-33.484-19.09-59.688-43.496-59.688zm-390.13 70.398c0 9.9648 0.93359 19.672 2.2852 29.238-11.035-4.1289-19.555-20.719-19.555-39.969 0-21.699 11.152-39.715 23.918-40.672-4.2422 16.68-6.6484 33.832-6.6484 51.402zm14.957-96.766c-3.9219 0.16406-5.3438 0.21094-6.4414-0.070312-1.5859-0.375-2.707-1.7969-2.707-3.4297l0.003906-51.145c0-1.9375 1.5625-3.5234 3.5-3.5234h58.309c5.1562 0 9.332-4.1758 9.332-9.332s-4.1992-9.332-9.332-9.332l-26.227-0.003906c0.88672-3.5 1.2383-7.2578 1.5625-11.293 5.9727-73.035 62.301-107.82 108.48-124.16 3.5703-1.2617 6.043-4.5977 6.207-8.3984l0.55859-13.742c0.09375-1.8906 1.6328-3.3828 3.5234-3.3828h74.223c1.8906 0 3.4062 1.4688 3.5 3.3828l0.58203 13.766c0.16406 3.8047 2.6133 7.1406 6.207 8.3984 46.176 16.332 102.53 51.148 108.45 124.23 0.32812 4.0352 0.67578 7.7461 1.5859 11.246h-31.312c-5.1562 0-9.332 4.1758-9.332 9.332s4.1992 9.332 9.332 9.332h63.398c1.9375 0 3.5 1.5859 3.5 3.5234v51.148c0 1.6328-1.0977 3.0586-2.6602 3.4297-1.1914 0.25781-2.5898 0.21094-6.5117 0.046875-2.3555-0.09375-5.2969-0.1875-9.9648-0.16406l-29.516 3.0781c-3.125 0.72266-6.3008 1.5391-9.4023 2.6133-48.535 16.566-80.148 17.547-110.79 18.504-7.1641 0.21094-14.305 0.44141-21.91 0.86328l-2.332-0.21094c-0.55859 0-1.8906 0.09375-2.4492 0.21094-7.3516-0.42188-14.512-0.62891-21.77-0.86328-30.59-0.95703-62.184-1.9375-110.71-18.504-3.1484-1.0742-6.3008-1.9141-10.547-2.8242l-29.375-2.9414c-3.4805 0.003906-6.5625 0.12109-8.9648 0.21484zm183.89 281.98c-99.352 0-180.18-83.09-180.18-185.22 0-26.902 5.5078-52.875 16.355-77.188l15.238 1.5156c2.5195 0.58203 5.0391 1.2383 7.5586 2.1016 51.172 17.5 84.211 18.504 116.32 19.508 6.9531 0.21094 13.953 0.42188 22.398 0.86328 0.79297 0 1.5625-0.046876 2.3555-0.11719 0.88672 0.070312 1.8438 0.046875 3.4766 0.09375 7.2109-0.42188 14.211-0.60547 21.305-0.83984 31.941-0.98047 64.984-2.0078 116.18-19.508 2.4961-0.86328 5.0391-1.5156 6.418-1.9141l16.613-1.7266c0.11719 0.35156 0.070312 0.69922 0.23438 1.0508 10.547 24.012 15.891 49.629 15.891 76.184 0 102.11-80.805 185.2-180.16 185.2zm196.56-155.96c1.3516-9.5664 2.2852-19.297 2.2852-29.238 0-17.57-2.4023-34.695-6.625-51.379 12.625 1.0742 23.918 18.969 23.918 40.648-0.023438 19.227-8.5391 35.84-19.578 39.969z"/>
                                            <path d="m408.26 460.79c-10.5 13.066-32.738 21.488-56.629 21.488-25.082 0-47.762-9.0312-57.773-22.984-3.0117-4.1992-8.8203-5.1094-13.043-2.1484-4.1758 3.0117-5.1562 8.8203-2.1484 13.02 13.602 18.992 41.559 30.777 72.941 30.777 29.75 0 57.027-10.898 71.168-28.441 3.2422-4.0117 2.5898-9.8945-1.4219-13.113-4.0156-3.2383-9.8711-2.6328-13.094 1.4023z"/>
                                            <path d="m303.1 350c0-16.965-13.812-30.754-30.754-30.754-16.988 0-30.777 13.789-30.777 30.754s13.812 30.754 30.777 30.754c16.938 0 30.754-13.789 30.754-30.754zm-30.777 12.086c-6.6719 0-12.109-5.4375-12.109-12.109 0-6.6719 5.4141-12.109 12.109-12.109 6.6484 0 12.086 5.4375 12.086 12.109 0 6.6719-5.4141 12.109-12.086 12.109z"/>
                                            <path d="m427.68 319.22c-16.988 0-30.777 13.789-30.777 30.754 0 16.965 13.812 30.754 30.777 30.754 16.965 0 30.777-13.789 30.777-30.754 0-16.965-13.812-30.754-30.777-30.754zm0 42.863c-6.6719 0-12.109-5.4375-12.109-12.109 0-6.6719 5.4141-12.109 12.109-12.109 6.6953 0 12.109 5.4375 12.109 12.109 0 6.6719-5.4336 12.109-12.109 12.109z"/>
                                        </g>
                                    </svg> {{index+1}}<span class="text-danger">*</span></label>
                                    <div class="card-header  border border-dashed border-gray-300">
                                        <!--begin::Card title-->
                                        <div class="card-title" style="font-size: 15px">
                                            <div v-for="lesson in lessonPackagePlans" v-if="packageLesson.id==lesson.package_id" class="fw-bold text-muted" >{{lesson.lessonIds.length}} lesson(s) added</div>
                                        </div>
                                        <!--end::Card title-->
                                        <!--begin::Card toolbar-->
                                        <div class="card-toolbar"  v-if="packageLesson.status=='waitting'">
                                            <button class="btn btn-primary" @click="addLessonPackage(packageLesson.id)" >Add lesson</button>
                                        </div>
                                        <div class="card-toolbar" v-for="urls in url" v-if="urls.package_id==packageLesson.id && packageLesson.status=='done'">
                                            <button class="btn btn-primary"  @click="downloadLesson(packageLesson)" v-if="urls.status=='waitting'  ">Download package</button>
                                            <span   v-if="urls.status=='inprogress' && entry.status=='Packaging'" style="color:#ffc700 ">inprogress</span>
                                            <a v-if="urls.status=='done' && entry.status=='Packaging'" :href="urls.url" type="button" class="btn btn-primary">Dowload Package</a>
                                            <button class="btn btn-primary" style="margin: 0px 15px 0px" @click="viewPackageLesson(packageLesson.id)">View lessons</button>
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
                                                    <input type="file" ref="uploader" class="form-control-file" @change="saveValidateImportDevice">
                                                    <error-label ></error-label>
                                                </div>

                                                <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                                                    <div class="d-flex align-items-center">
                                                        <div class="ms-6">

                                                            <div class="fw-bold text-muted">{{fileImport.length}} new record(s)</div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="text-end">
                                                          	<span class="form-check form-check-custom form-check-solid">
																					<input class="form-check-input" type="radio" name="category" :value="fileImport" v-model="fileImport"/>
                                                                <label style="margin: 0px 10px 0px">Import</label>
                                                                <input class="form-check-input" type="radio" name="category" value="1" v-model="doNotImport"/>
                                                                <label style="margin: 0px 10px 0px">Do not import</label>
																				</span>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-6">

                                                        <div class="fw-bold text-muted">{{deviceError.length}} error record(s)</div>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="text-end">
                                                        <div class="fs-7 text-muted"><a :href="validateFile" type="button" class="btn btn-primary">Export</a></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="d-flex flex-stack pt-10">
                                        <div class="me-2">
                                            <button type="button" class="btn btn-lg btn-light-primary me-3" data-kt-stepper-action="previous">
                                                <span class="svg-icon svg-icon-3 me-1">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<rect opacity="0.5" x="6" y="11" width="13" height="2" rx="1" fill="black" />
													<path d="M8.56569 11.4343L12.75 7.25C13.1642 6.83579 13.1642 6.16421 12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75L5.70711 11.2929C5.31658 11.6834 5.31658 12.3166 5.70711 12.7071L11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25C13.1642 17.8358 13.1642 17.1642 12.75 16.75L8.56569 12.5657C8.25327 12.2533 8.25327 11.7467 8.56569 11.4343Z" fill="black" />
												</svg>
											</span>
                                              Back</button>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="submit">
												<span class="indicator-label">Submit
												<span class="svg-icon svg-icon-3 ms-2 me-0">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
														<rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black" />
														<path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black" />
													</svg>
												</span>
                                                  </span>
                                                <span class="indicator-progress">Please wait...
												<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                            </button>
                                            <button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="next" @click="saveImport">Continue
                                                <span class="svg-icon svg-icon-3 ms-1 me-0">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black" />
													<path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black" />
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
            let filter = {
                keyword: $q.keyword || '',
                name:$q.name||'',
                subject: $q.subject || '',
                grade: $q.grade || '',
            };

            return {
                lessons:[],
                viewPackage:this.viewPackage,
                packagePlan:$json.packagePlan,
                package:'',
                idListDevice:[],
                nameSchool:$json.nameSchool || [],
                schoolPlan:$json.schoolPlan || [],
                schoolId:'',
                schools:$json.schools || [],
                exportDevicePlan:'',
                lessonPackagePlans: $json.lessonPackagePlans,
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
            viewPackageLesson:function(viewLesson='')
            {
                $('#kt_modal').modal('show');
                this.viewPackage=viewLesson;
            },
          async  deleteLesson(lesson)
            {

                let self = this;
                for (const e of self.lessonPackagePlans) {
                    if(e.package_id==self.viewPackage)
                    {
                        let array=e.lessonIds.filter(item => item !== lesson.id);
                        e.lessonIds=array;
                        let packageLesson=e.lessonIds
                        const res =await $post('/xadmin/plans/deleteLesson', {packageLesson,entry:self.entry,viewPackage:self.viewPackage});
                        if (res.code) {
                            toastr.error(res.message);
                        } else {
                            toastr.success(res.message);
                        }
                    }
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
                    location.replace('/xadmin/plans/edit?id=' + this.entry.id);


                    if (!this.entry.id) {
                        location.replace('/xadmin/plans/edit?id=' + this.entry.id);
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
                     idRoleIt:this.idRoleIt,
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
                    let self=this;
                    self.lessonPackagePlans.forEach(function (e) {
                        e.lessonIds=selected;
                        self.lessons = self.entries;
                    })
                } else {
                    let self=this;
                    self.lessonPackagePlans.forEach(function (e1) {
                        e1.lessonIds=[];
                    })
                    self.lessons = [];
                }

            },
            updateCheckAll() {
                this.lessons = [];
                let self = this;
                self.lessonPackagePlans.forEach(function (e) {
                    if(e.lessonIds.length==self.entries.length)
                    {
                        self.allSelected = true;
                    }
                    else {
                        self.allSelected = false;
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
                    location.replace('/xadmin/plans/edit?id=' + this.entry.id);


                    if (!this.entry.id) {
                        location.replace('/xadmin/plans/edit?id=' + entry.id);
                    }

                }
            },
            async addLesson()
            {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/planLesson', {lessonPackagePlans:this.lessonPackagePlans,entry:this.entry,package:this.package}, false);
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
                const res = await $post('/xadmin/plans/addPackageLesson', {entry:this.entry,lessonIds:this.lessonIds}, false);
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
            async downloadLesson(packageLesson)
            {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/downloadLesson', {entry:this.entry,lessonPackagePlans:this.lessonPackagePlans,package:packageLesson.id,idRoleIt:this.idRoleIt}, false);
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
            }

        }
    }
</script>

<style scoped>

</style>
