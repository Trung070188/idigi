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
                        <button type="reset" id="kt_modal_new_target_cancel1" class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal" style="margin: 0px 8px 0px" @click="refuse(deviceTeacher)">Refuse</button>

                        <button type="submit" class="swal2-confirm btn fw-bold btn-danger" @click="remove_device(deviceTeacher)">
                            <span class="indicator-label">Yes, delete!</span>
                        </button>
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
                                    <div class="form-check form-check-custom form-check-solid ml-3 pb-5">
                                        <input id="state" type="checkbox" v-model="entry.state" class="form-check-input h-20px w-20px">
                                        <label for="state" class="form-check-label fw-bold">Active teacher</label>
                                        <error-label for="f_grade" :errors="errors.state"></error-label>
                                    </div>
                                </div>
                                <hr style="margin-top:5px">
                                <h4>Resource allocation</h4>
                                <div class="col-lg-12" v-if="entry.active_allocation==0 && school.active_allocation==0">
                                    <div class="row">
                                        <div class="row" >
                                            <label>Resource allocation<span class="text-danger">*</span></label>
                                            <h3 style="text-align: center; font-weight: bold;font-size: 15px">Resource allocation has been deactivated by Super admin </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" v-if=" school.active_allocation==1" >
                                    <div class="form-group col-sm-10">
                                        <label>Course<span class="text-danger">*</span></label>
                                        <treeselect :options="allCourses" :multiple="true" @deselect="deleteCourse" v-model="courseTeachers" @input="selectTotalCourse" />
                                        <error-label  for="f_grade" :errors="errors.courseTeachers"></error-label>

                                        <div class="container" style="display: grid;grid-template-columns: 15% 85%; margin: 16px -25px 0px" v-if="courseTeachers.length>0">
                                            <div >Course name</div>
                                            <div >Unit <span class="text-danger">*</span></div>
                                        </div>
                                        <div class="container" style="display: grid;grid-template-columns: 15% 85%; margin: 16px -25px 0px" v-for="courseTeacher in courseTeachers">
                                            <div v-for="course in courses" v-if="courseTeacher==course.id" > {{course.label}}</div>
                                            <div v-for="course in courses" v-if="courseTeacher==course.id" >
                                                <treeselect :options="course.total_unit" :multiple="true" v-model="course.courseTea" @input="selectTotalUnit(course)" :style="{ minWidth: minWidth + 'px' }"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check form-check-custom form-check-solid ml-3 pb-5" v-if="school.active_allocation==1">
                                    <input id="state1" type="checkbox" v-model="active_allocation" class="form-check-input h-20px w-20px" @change="activeAllocation">
                                    <label for="state1" class="form-check-label fw-bold">Active allocation</label>
                                    <error-label for="f_grade" :errors="active_allocation"></error-label>
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
                        <table class="table table-row-bordered align-middle gy-4 gs-9">
                            <thead  class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                            <tr>
                                <th>No.</th>
                                <th>Device name</th>
                                <th>Device detail</th>
                                <th>Registed date</th>
                                <th></th>
                                <th></th>


                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(device,index) in userDevices" >
                                <td >{{index+1}}</td>
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
                        <table  class="table table-row-bordered align-middle gy-4 gs-9">
                            <thead  class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                                <th>Device detail</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="device in deviceLog" >
                                <td v-text="d(device.time)"></td>
                                <td v-if="device.dataLog.status=='Register device'">Register device</td>
                                <td v-if="device.dataLog.status=='Approve remove device'">Remove device</td>
                                <td v-text="device.dataLog.object"></td>
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
    import {$get, $post, forEach} from "../../utils";

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
                minWidth: 940,
                school:{},
                deviceLog:[],
                active_allocation:$json.active_allocation,
                deviceTeacher:[],
                allCourses:allCourses,
                nameRole:5,
                courseTeachers:$json.courseTeachers || {},
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
                userDevices: [],
                allocationContentId:$json.allocationContentId,
                schools:$json.schools || [],
                courses:courseTreeselect,
                isLoading: false,
                errors: {},
            }
        },
        mounted() {
            $router.on('/', this.load).init();

            $('.noString').keypress(function (e) {
                if (e.keyCode < 48 || e.keyCode > 57) {
                    e.preventDefault();
                }
            })
        },
        methods: {
            async load() {
                let query = $router.getQuery();
                this.$loading(true);
                const res  = await $get('/xadmin/users/deviceTeacher?id='+this.entry.id, query);
                this.$loading(false);
                setTimeout(function (){
                    KTMenu.createInstances();
                }, 0)
                this.userDevices = res.data;
                this.school=res.school
                this.deviceLog=res.deviceLog
            },
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
            async activeAllocation()
            {
              const res=await $post('/xadmin/users/activeAllocation',{active_allocation:this.active_allocation,id:this.entry.id})
                if(res.code)
                {
                    toastr.errors(res.message)
                }
                else {
                    toastr.success(res.message)
                }
            },
            async refuse(deviceTeacher)
            {
                const res=await $post('/xadmin/users/refuseDevice',{id:deviceTeacher.id});
                if(res.code)
                {
                    toastr.error(res.message);
                }
                else {
                    toastr.success(res.message);
                }
                window.location.reload();

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
                location.replace('/xadmin/schools/teacherList?id='+this.school.id);
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
