<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title = "Teacher Details"/>

        <div class="modal fade" style="margin-right:50px " id="deviceConfirm" tabindex="-1" role="dialog"
             aria-labelledby="deviceConfirm"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                 style="max-width: 500px;">

                <div  class="modal-content box-shadow-main paymment-status" style="margin-right:20px; left:140px">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 class="popup-title success" style="text-align: center;margin:15px 0px 0px" >Delete device </h3>
                    <div class="content" style="text-align: center;" v-for="device in user_device" v-if="device.id==currId">
                        <p>Bạn có chắc chắn muốn xóa thiết bị “{{device.device_name}}”
                            của giáo viên “{{entry.full_name}}”??</p>
                    </div>
                    <div class="form-group d-flex justify-content-between" style="margin: auto;margin-bottom: 20px">
                        <button class="btn btn-primary ito-btn-add" data-dismiss="modal" style="margin-right: 5px" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button v-for="device in user_device" v-if="device.id==currId" class="btn btn-danger ito-btn-small" data-dismiss="modal" @click="remove_device(device)" >Yes</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div  class="d-flex justify-content-end"
                          data-kt-customer-table-toolbar="base">
                        <button class="btn btn-danger button-create " @click="remove(entry)" style="margin: 15px 25px 0px ">
                            Delete User <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class=" card-header border-0 pt-5 row" style="margin-top:-30px;margin-left: -35px;">
                            <div class=" col-sm-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group  col-sm-4">
                                        <label>Teacher name <span class="text-danger">*</span></label>
                                        <input class="form-control" v-model="entry.username">

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
                                        <input class="form-control" v-model="entry.phone">
                                        <error-label for="f_category_id" :errors="errors.phone"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>Class</label>
                                        <input class="form-control" v-model="entry.class">
                                        <error-label for="f_category_id" :errors="errors.class"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>School</label>
                                        <input class="form-control" v-model="schools.label" disabled>
                                        <error-label for="f_category_id" :errors="errors.label"></error-label>
                                    </div>


                                </div>
                                <h4>Content Allocated</h4>
                                <div class="row">

                                    <div class="form-group col-sm-10"  @change="saveTeacherCourse()">
                                        <label>Course</label>
                                        <treeselect :options="courses" :multiple="true" @deselect="deleteCourse" v-model="courseTeachers"   />
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
                                                    <treeselect :options="course.total_unit" :multiple="true" v-model="course.courseTea" />
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
                            <button type="reset" @click="save()" class="btn btn-primary mr-2">Save change</button>
                            <button type="reset" @click="backIndex()" class="btn btn-secondary">Cancel</button>
                        </div>
                        <hr style="margin-top: 10px">
                        <h2>Current registed devices</h2>
                        <table class=" table  table-head-custom table-head-bg table-vertical-center">
                            <thead>
                            <tr>
                                <th>Device name</th>
                                <th>Device detail</th>
                                <th>Registed date</th>
                                <th></th>


                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="device in user_device" v-if="device.user_id===entry.id && device.status===2  ">
                                <td v-text="device.device_name"></td>
                                <td v-text="device.device_name"></td>
                                <td v-text="d(device.created_at)"></td>
                                <td>
                                    <a @click="modalDevice(device.id)" href="javascript:;" class="btn-trash deleted"><i
                                        class="fa fa-trash mr-1 deleted"></i></a>
                                </td>
                            </tr>
                            <tr v-for="device in user_device" v-if=" device.user_id===entry.id && device.status===1 ">
                                <td v-text="device.device_name"></td>
                                <td v-text="device.device_name" ></td>
                                <td v-text="d(device.created_at)"></td>
                                <td style="color: #f1c40f">Deleting request</td>
                                <td>
                                    <a @click="modalDevice(device.id)" href="javascript:;" class="btn-trash deleted"><i
                                        class="fa fa-trash mr-1 deleted"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <h2>Devices activities</h2>
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
                                <td v-if="device.status==2" v-text="d(device.created_at)"></td>
                                <td v-if="device.status==1" v-text="d(device.updated_at)"></td>
                                <td v-if="device.status==2" >Register device</td>
                                <td v-if="device.status==1" >Remove device</td>
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
    import {$post} from "../../utils";

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
            !course ? null : course.forEach(function (e) {
                e.total_unit.forEach(function (e1) {
                    e1.label = e1.unit_name;
                })

            })
            let courseTreeselect =!course ? null : course.map(rec => {
                return {
                    'id':rec.id,
                    'label': rec.course_name,
                    'total_unit':rec.total_unit,
                    'courseTea':rec.courseTea,
                }
            })
            return {
                nameRole:5,
                courseTeachers:$json.courseTeachers || {},
                schoolId:$json.schoolId,
                showConfirm: false,
                showPass: false,
                types: [],
                currId:'',
                breadcrumbs: [
                    {
                        title:'School & teacher',
                    },
                    {
                        title: ' Manage school',
                        url: '/xadmin/schools/index',
                    },
                    {
                        title: 'School details',
                        url: '/xadmin/schools/edit?id='+$json.schoolId
                    },
                    {
                        title: 'Teacher list',
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
                schools:$json.schools || [],
                courses:courseTreeselect,
                isLoading: false,
                errors: {}
            }
        },
        methods: {
            deleteCourse: function (node, instanceId) {
                node.courseTea = [];
            },
            modalDevice(id) {
                const that=this;
                that.currId = id;
                console.log(that.currId);
                $('#deviceConfirm').modal('show');

            },

            backIndex() {

                window.location.href = '/xadmin/users/index';
            },
            async remove_device(entry) {

                const res = await $post('/xadmin/users/removeDevice', {id: entry.id});

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
                const res = await $post('/xadmin/users/saveTeacher', {entry: this.entry, roles: this.roles,courseTeachers:this.courseTeachers,unit:this.courses,name_role:this.nameRole,schoolId:this.schools.id}, false);
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
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return false;
                }

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
