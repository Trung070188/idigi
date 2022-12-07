<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title = "Teacher details"/>
        <div class="modal fade" style="" id="delete" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document" style="max-width: 450px;">
                <div class="modal-content box-shadow-main paymment-status" style="padding: 0px 0px 30px;">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <div class="swal2-icon swal2-warning swal2-icon-show">
                        <div class="swal2-icon-content" style="margin: 0px 25px 0px ">!</div>
                    </div>
                    <div class="swal2-html-container">
                        <p >Are you sure to delete this teacher?</p>
                    </div>
                    <div class="swal2-actions">
                        <button type="submit" id="kt_modal_new_target_submit" class="swal2-confirm btn fw-bold btn-danger" @click="remove(entry)">
                            <span class="indicator-label">Yes, delete!</span>
                        </button>
                        <button type="reset" id="kt_modal_new_target_cancel" class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal" style="margin: 0px 8px 0px">No, cancel</button>

                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" style="" id="deviceConfirm" tabindex="-1" role="dialog" aria-labelledby="deviceConfirm" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document" style="max-width: 450px;">
                <div class="modal-content box-shadow-main paymment-status" style="padding: 0px 0px 30px;">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <div class="swal2-icon swal2-warning swal2-icon-show">
                        <div class="swal2-icon-content" style="margin: 0px 25px 0px ">!</div>
                    </div>
                    <div class="swal2-html-container" >
                        <p>Are you sure to delete the device "{{deviceTeacher.device_name}}"?</p>
                    </div>
                    <div class="swal2-actions">
                        <button type="submit" class="swal2-confirm btn fw-bold btn-danger" @click="remove_device(deviceTeacher)">
                            <span class="indicator-label">Yes, delete!</span>
                        </button>
                        <button type="reset" id="kt_modal_new_target_cancel" class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal" style="margin: 0px 8px 0px">No, cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div  class="d-flex justify-content-end"
                          data-kt-customer-table-toolbar="base">
                        <button class="btn btn-danger button-create " @click="removeTeacher" style="margin: 15px 25px 0px ">
                            <i class="bi bi-person-dash mr-1"></i>Delete teacher
                        </button>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class=" card-header border-0 pt-5 row" style="margin-top:-30px;margin-left: -35px;">
                            <div class=" col-sm-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group  col-sm-4">
                                        <label>Username <span class="text-danger">*</span></label>
                                        <input class="form-control" v-model="entry.username" disabled>

                                        <error-label for="f_category_id" :errors="errors.username"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>Teacher name <span class="text-danger">*</span></label>
                                        <input class="form-control" v-model="entry.full_name">

                                        <error-label for="f_category_id" :errors="errors.full_name"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>Email </label>
                                        <input class="form-control" v-model="entry.email">

                                        <error-label for="f_category_id" :errors="errors.email"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>Phone number </label>
                                        <input class="form-control noString" v-model="entry.phone" >
                                        <error-label for="f_category_id" :errors="errors.phone"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>Class</label>
                                        <input class="form-control" v-model="entry.class">
                                        <error-label for="f_category_id" :errors="errors.class"></error-label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>School</label>
                                        <input class="form-control form-control-solid" v-model="schools.label" disabled>
                                        <error-label for="f_category_id" :errors="errors.label"></error-label>
                                    </div>
                                <div class="row">
                                    <div class="form-group col-sm-8">
                                        <label>Teacher description</label>
                                        <textarea v-model="entry.description" rows="5" class="form-control"
                                                  placeholder="Type the description here (200 characters)"></textarea>
                                        <error-label for="f_grade" :errors="errors.description"></error-label>

                                    </div>
                                </div>
                                    <div class="form-check form-check-custom form-check-solid ml-3 pb-5">
                                        <input id="state" type="checkbox" v-model="entry.state" class="form-check-input h-20px w-20px">
                                        <label for="state" class="form-check-label fw-bold">Active</label>
                                        <error-label for="f_grade" :errors="errors.state"></error-label>
                                    </div>
                                </div>
                                <hr style="margin-top:5px">
                                <h4>Resource enrollment</h4>
                                <div class="row">

                                    <div class="form-group col-sm-10"  @change="saveTeacherCourse()">
                                        <label>Course</label>
                                        <treeselect :options="allCourses" :multiple="true" @deselect="deleteCourse" v-model="courseTeachers" @input="selectTotalCourse" />
                                        <error-label  for="f_grade" :errors="errors.courseTeachers"></error-label>

                                        <table class="table table-row-bordered align-middle gy-4 gs-9" style="margin:25px 0px 0px">
                                            <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                                            <tr>
                                                <th class="">Course Name</th>
                                                <th>Unit</th>
                                            </tr>
                                            </thead>
                                            <tbody v-for="courseTeacher in courseTeachers" >
                                            <tr v-for="course in courses" v-if="courseTeacher==course.id" >
                                                <td  >
                                                    {{course.label}}
                                                </td>
                                                <td  >
                                                    <treeselect :options="course.total_unit" :multiple="true" v-model="course.courseTea" @input="selectTotalUnit(course)"/>
                                                    <error-label  :errors="errors.courseTea"></error-label>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr style="margin-top: 10px">
                        <div>
                            <button type="reset" @click="save()" class="btn btn-primary mr-2"><i class="bi bi-save2 mr-1"></i>Save</button>
                            <button type="reset" @click="backIndex()" class="btn btn-light">Cancel</button>
                        </div>
                        <hr style="margin-top: 10px">
                        <h4>Current registed devices</h4>
                        <table class=" table  table-head-custom table-head-bg table-vertical-center">
                            <thead>
                            <tr>
                                <th>Device name</th>
                                <th>Device detail</th>
                                <th>Registed date</th>
                                <th></th>
                                <th></th>


                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="device in user_device" v-if="device.user_id===entry.id">
                                <td v-text="device.device_name"></td>
                                <td v-text="device.device_name"></td>
                                <td v-text="d(device.created_at)"></td>
                                <td style="color: #f1c40f" v-if="device.delete_request!=null" v-text="device.delete_request"></td>
                                <td v-else></td>
                                <td>
                                    <a @click="modalDevice(device)" href="javascript:;" class="btn-trash deleted"><i
                                        class="fa fa-trash mr-1 deleted"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <h4>Devices activities</h4>
                        <table  class=" table  table-head-custom table-head-bg table-vertical-center">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                                <th>Device detail</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="device in user_device" v-if="device.user_id==entry.id">
                                <td v-if="device.delete_request!=null"  v-text="d(device.updated_at)"></td>
                                <td v-else v-text="d(device.created_at)"></td>
                                <td v-if="device.delete_request!=null"  >Remove device</td>
                                <td v-else >Register device</td>
                                <td v-text="device.device_name"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>

</template>

<script>
    import {$post, forEach} from "../../utils";

    import ActionBar from "../includes/ActionBar";
    import SwitchButton from "../../components/SwitchButton";
    import $router from "../../lib/SimpleRouter";
    import Treeselect from '@riophae/vue-treeselect'
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'


    export default {
        name: "TeacherDetails.vue",
        components: {ActionBar, SwitchButton,Treeselect},
        data() {
            const course=$json.schoolCousers;
            console.log(course);
            !course ? null : course.forEach(function (e) {
                e.total_unit.forEach(function (e1) {
                    e1.label = 'Unit' + ' ' + e1.position +' : '+e1.unit_name;
                })

            })
            const selectAllCourses=[
                {
                    id:'all',
                    label:'All courses',
                    children:[
                    ]
                }
            ];
            const selectAllUnits=[
                {
                    id:'all',
                    label:'All units',
                    children:[
                    ]
                }
            ];
            const courseTreeselect =!course ? null :  course.map(rec => {
                let unitAll=selectAllUnits.map(res =>{
                    return {
                        id:res.id,
                        label:res.label,
                        children:rec.total_unit,

                    }
                })
                return {
                    'id':rec.id,
                    'label': rec.course_name,
                    'courseTea':rec.courseTea,
                    'total_unit':unitAll,
                }
            })
            selectAllCourses.forEach(function (e) {
                courseTreeselect.forEach(function (e1) {
                    e.children.push(e1);
                })
            })
            const allCourses= selectAllCourses.map(res => {


                return{
                    'id':'all',
                    'label':res.label,
                    'children':res.children,
                }
            })
            return {
                deviceTeacher:[],
                allCourses:allCourses,
                nameRole:5,
                courseTeachers:$json.courseTeachers || {},
                schoolId:$json.schoolId,
                showConfirm: false,
                showPass: false,
                types: [],
                currId:'',
                breadcrumbs: [
                    {
                        title:'School management',
                        url: '/xadmin/schools/index',
                    },
                    {
                        title: 'School details',
                        url: '/xadmin/schools/edit?id='+$json.schoolId
                    },
                    {
                        title: 'Teacher management',
                        url: '/xadmin/schools/teacherList?id='+$json.schoolId

                    },
                    {
                        title: 'Teacher details',
                    },
                ],
                entry: $json.entry || {
                    roles: []
                },
                user_device: $json.user_device || [],
                allocationContentId:$json.allocationContentId,
                schools:$json.schools || [],
                courses:courseTreeselect,
                isLoading: false,
                errors: {},
            }
        },
        mounted() {
            $('.noString').keypress(function (e) {
                if (e.keyCode < 48 || e.keyCode > 57) {
                    e.preventDefault();
                }
            })
        },
        methods: {
           selectTotalUnit(course)
            {
              let self=this;
              self.courses.forEach(function (e)
              {
                  if(e.id==course.id)
                  {
                      if(e.total_unit.length>0 && e.courseTea[0]=='all')
                      {
                          e.courseTea=e.total_unit[0].children.map(rec => {
                              return rec.id;
                          })
                      }
                  }
              })
            },
             selectTotalCourse()
            {
               if(this.courseTeachers.length > 0 && this.courseTeachers[0]=='all')
               {
                   this.courseTeachers=this.courses.map(res=>{
                       return res.id;
                   })
               }
            },
            removeTeacher:function()
            {
                $('#delete').modal('show');
            },
            deleteCourse: function (node, instanceId) {
                node.courseTea = [];
            },
           modalDevice:function(device=[]) {
                $('#deviceConfirm').modal('show');
                this.deviceTeacher=device;
            },


            backIndex() {

                window.location.href = '/xadmin/users/index';
            },
            async remove_device(deviceTeacher) {

                const res = await $post('/xadmin/users/removeDevice', {id: deviceTeacher.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }
                window.location.reload();

                $router.updateQuery({ _: Date.now()});

            },

            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/users/saveTeacher', {entry: this.entry, roles: this.roles,courseTeachers:this.courseTeachers,unit:this.courses,name_role:this.nameRole,schoolId:this.schools.id,allocationContentId:this.allocationContentId}, false);
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
                        location.replace('/xadmin/users/editTeacher?id=' + res.id);
                    }
                }
            },
            async remove(entry) {
                const res = await $post('/xadmin/users/remove', {id: entry.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }
                location.replace('/xadmin/users/teacher');
                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});
            },
        }
    }
</script>

<style scoped>
    .fa-eye {
        position: absolute;
        top: 40%;
        right: 5%

    }

</style>
