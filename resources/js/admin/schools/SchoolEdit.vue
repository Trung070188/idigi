<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title="School details"/>
        <div class="row">
            <div class="col-lg-12">
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
                                <p >Are you sure to delete this school?</p>
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
                <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="deviceConfirm" tabindex="-1" role="dialog"
                     aria-labelledby="deviceConfirm"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                         style="max-width: 500px;">
                        <div class="modal-content box-shadow-main paymment-status" style="left:140px;text-align: center; padding: 27px 0px 10px;">
                            <div class="close-popup" data-dismiss="modal"></div>
                            <h3 class="popup-title success" >Cannot delete this school</h3>
                            <div class="content">
                                <p>You can only delete this school if the list of teachers is empty.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-custom card-stretch gutter-b">

                    <div class="card-header border-0 pt-6" style="margin:0px 0px -35px">
                        <div class="card-title"></div>
                        <div class="card-toolbar">
                            <div
                                class="d-flex justify-content-end"
                                data-kt-customer-table-toolbar="base">
                                <a :href="'/xadmin/schools/teacherList?id='+entry.id" v-if="permissionFields['school_teacher_list']==true">
                                    <button style="margin: 0px 8px 25px;" v-if="title=='Edit school'"  class="btn btn-primary button-create ">
                                        <i class="bi bi-person-lines-fill mr-1"></i>Teacher list
                                    </button>
                                </a>
                                <a v-else style="cursor: pointer">
                                    <button style="margin: 0px 8px 25px;" v-if="title=='Edit school'" disabled class="btn btn-primary button-create ">
                                        <i class="bi bi-person-lines-fill mr-1"></i>Teacher list
                                    </button>
                                </a>
                                <button v-if="title=='Edit school' && teacher!=0" :disabled="permissionFields['school_delete']==false" class="btn btn-danger button-create " @click="modalDeleteSchool()">
                                    <i class="bi bi-trash mr-1"></i>Delete school
                                </button>
                                <button v-if="title=='Edit school' &&  teacher==0" :disabled="permissionFields['school_delete']==false" class="btn btn-danger button-create " @click="modalDelete">
                                    <i class="bi bi-trash mr-1"></i>Delete school
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="card-body d-flex flex-column">
                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label>School name <span class="text-danger">*</span></label>
                                        <input v-model="entry.label" class="form-control" :disabled="permissionFields['school_name']==false"
                                               placeholder="Enter the school name">
                                        <error-label for="f_school_name" :errors="errors.label"></error-label>

                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label>School address <span class="text-danger">*</span></label>
                                        <input v-model="entry.school_address" :disabled="permissionFields['school_address']==false" class="form-control"
                                               placeholder="Enter the school address">
                                        <error-label :errors="errors.school_address"></error-label>

                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>School email</label>
                                        <input v-model="entry.school_email" :disabled="permissionFields['school_email']==false" class="form-control"
                                               placeholder="Enter the email prefix">
                                        <error-label :errors="errors.school_email"></error-label>

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label>Phone number </label>
                                        <input v-model="entry.school_phone" :disabled="permissionFields['school_phone_number']==false" class="form-control noString "
                                               placeholder="Enter the phone number">
                                        <error-label for="f_school_name" :errors="errors.school_phone"></error-label>

                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label>No. of Device per user <span class="text-danger">*</span></label>
                                        <input type="number" min="1" max="200" v-model="entry.devices_per_user" :disabled="permissionFields['school_device']==false" class="form-control"
                                               placeholder="Enter number of Device per User">
                                        <error-label :errors="errors.devices_per_user"></error-label>

                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>No. of User <span class="text-danger">*</span></label>
                                        <input type="number" min="1" max="200" v-model="entry.number_of_users" :disabled="permissionFields['school_user']==false" class="form-control"
                                               placeholder="Enter number of User">
                                        <error-label :errors="errors.number_of_users"></error-label>

                                    </div>

                                </div>
                                <!--                                <div class="row">-->
                                <!--                                    <div class="form-group col-lg-4">-->
                                <!--                                        <label>License to <span class="text-danger">*</span></label>-->
                                <!--                                        <datepicker :timepicker="true" v-model="entry.license_to"></datepicker>-->
                                <!--                                        <error-label  :errors="errors.license_to"></error-label>-->
                                <!--                                    </div>-->
                                <!--                                </div>-->
                                <!--                                <div class="row">
                                <div class="form-group col-lg-8">
                                    <label>Adminitrator name</label>
                                    <QSelect v-model="entry.user_id"
                                             datasource="users"
                                             :multiple="false"></QSelect>
                                    <error-label for="f_user_id" :errors="errors.user_id"></error-label>
                                </div>
                            </div>-->
                                <div class="row">
                                    <div class="form-group col-lg-8">
                                        <label>School description</label>
                                        <textarea v-model="entry.school_description" :disabled="permissionFields['school_description']==false" rows="5" class="form-control"
                                                  placeholder="Your text here..."></textarea>
                                        <error-label for="f_grade" :errors="errors.school_description"></error-label>

                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Expired date/License <span class="text-danger">*</span></label>
                                        <datepicker  v-model="entry.license_to" rows="5" class="form-control" :disabled="permissionFields['school_expire_date']==false" readonly></datepicker>
                                        <error-label for="f_grade" :errors="errors.license_to"></error-label>
                                    </div>
                                </div>
                                <div class="form-check form-check-custom form-check-solid pb-5 ">
                                    <input id="state" type="checkbox"  class="form-check-input h-20px w-20px" v-model="entry.active_school" checked>
                                    <label for="state" class="form-check-label fw-bold" >Active school</label>
                                    <error-label for="f_grade" ></error-label>
                                </div>
                            </div>
                            <div class="col-lg-12" v-if="roleName=='School Admin' && entry.active_allocation==1 || roleName=='Super Administrator'" >
                                <div class="row" >
                                    <div class="form-group col-lg-8">
                                        <label>Resource allocation<span class="text-danger">*</span></label>

                                        <select class="form-control form-select " required v-model="allocationContentSchool" :disabled="permissionFields['school_content']==false"
                                                @change="changeAllocationContent() ">

                                            <option v-for="allocationContent in allocationContents"
                                                    :value="allocationContent.id">{{allocationContent.title}}
                                            </option>
                                        </select>

                                    </div>
                                    <div class="col-lg-4" v-if="permissionFields['school_content']==false && ['036']">
                                            <button style="margin: 20px 0px 0px" class="btn btn-primary" disabled><i class="bi bi-pencil-square mr-1"></i>Edit</button>
                                    </div>
                                    <div class="col-lg-4" v-if="permissionFields['school_content']==true && permissions['036']">
                                        <a :href="'/xadmin/allocation_contents/edit?id='+allocationContentSchool" >
                                            <button style="margin: 20px 0px 0px" class="btn btn-primary" ><i class="bi bi-pencil-square mr-1"></i>Edit</button>
                                        </a>
                                    </div>
                                </div>
                                <div class="form-check form-check-custom form-check-solid pb-5" v-if="roleName=='Super Administrator'">
                                    <input id="state1" type="checkbox"  class="form-check-input h-20px w-20px" v-model="entry.active_allocation" checked>
                                    <label for="state1" class="form-check-label fw-bold" >Active allocation</label>
                                    <error-label for="f_grade" ></error-label>
                                </div>

<!--                                <table class="table table-row-bordered align-middle gy-4 gs-9" v-if="courses!=null">-->
<!--                                    <thead-->
<!--                                        class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">-->
<!--                                    <tr>-->
<!--                                        <th class="">Course name</th>-->
<!--                                        <th>Unit</th>-->
<!--                                    </tr>-->
<!--                                    </thead>-->
<!--                                    <tbody>-->
<!--                                    <tr v-for="course in courses">-->
<!--                                        <td>-->
<!--                                            {{course.label}}-->
<!--                                        </td>-->
<!--                                        <td>-->
<!--                                            <treeselect :options="units" :multiple="true" v-model="course.total_unit" :disabled="true" />-->
<!--                                        </td>-->
<!--                                    </tr>-->
<!--                                    </tbody>-->
<!--                                </table>-->
<!--                                <table class="table table-row-bordered align-middle gy-4 gs-9" v-if="courses==null">-->
<!--                                    <thead-->
<!--                                        class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">-->
<!--                                    <tr>-->
<!--                                        <th class="">Course name</th>-->
<!--                                        <th>Unit</th>-->
<!--                                    </tr>-->
<!--                                    </thead>-->
<!--                                    <tbody>-->
<!--                                    <tr v-for="course in courses2">-->
<!--                                        <td>-->
<!--                                            {{course.label}}-->
<!--                                        </td>-->
<!--                                        <td>-->
<!--                                            <treeselect :options="course.units" :multiple="true" v-model="course.total_unit" :disabled="true" />-->
<!--                                        </td>-->
<!--                                    </tr>-->
<!--                                    </tbody>-->
<!--                                </table>-->
                            </div>
                            <div class="row">
                                <div class="col-lg-12" style="display: flex" v-if="courses!=null">
                                    <div style="display: flex;align-items: center;flex-basis: 10%">Course name</div>
                                    <div style="flex-basis: 90%" >Unit </div>
                                </div>
                                <div class="col-lg-12" style="display: flex ;margin: 16px 0px 0px" v-for="course in courses" v-if="courses!=null">
                                    <div   style="display: flex;align-items: center;flex-basis: 10%"> {{course.label}}</div>
                                    <div  style="flex-basis: 90%">
                                        <treeselect :options="units" :multiple="true" v-model="course.total_unit" :disabled="true" />
                                        <error-label  for="f_total_course" :errors="errors.total_unit"></error-label>
                                    </div>
                                </div>
                                <div class="col-lg-12" style="display: flex ;margin: 16px 0px 0px" v-for="course in courses2" v-if="courses==null">
                                    <div   style="display: flex;align-items: center;flex-basis: 10%"> {{course.label}}</div>
                                    <div  style="flex-basis: 90%">
                                        <treeselect :options="units" :multiple="true" v-model="course.total_unit" :disabled="true" />
                                        <error-label  for="f_total_course" :errors="errors.total_unit"></error-label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12" v-if="roleName=='School Admin' && entry.active_allocation==0">
                                <div class="row">
                                    <div class="row" >
                                            <label>Resource allocation<span class="text-danger">*</span></label>
                                            <h3 style="text-align: center; font-weight: bold;font-size: 15px">Resource allocation has been deactivated by Super admin {{entry.full_name_active_content}} </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr style="margin-top: 5px;">
                        <div>
                            <button type="reset" @click="save()" class="btn btn-primary mr-2"><i class="bi bi-save2 mr-1"></i>Save change</button>
                            <button type="reset" @click="backIndex()" class="btn btn-light">Cancel</button>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

</template>

<script>
    import {$post, clone} from "../../utils";
    import ActionBar from "../includes/ActionBar";
    import QSelect from "../../components/QSelect";
    import Datepicker from "../../components/Datepicker";
    import $router from "../../lib/SimpleRouter";
    import Treeselect from '@riophae/vue-treeselect'
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'

    export default {
        name: "SchoolEdit.vue",
        components: {ActionBar, QSelect, Datepicker, Treeselect},
        data() {
            const permissions = clone(window.$permissions)
            const units = $json.units;

            let unitTreeselect = !units ? null : units.map(rec => {
                return {
                    'id': rec.id,
                    'label': 'Unit' + ' ' + rec.position +' : '+rec.unit_name,
                }
            })

            const course = $json.courses;
            let courseTreeselect = !course ? null : course.map(rec => {
                return {
                    'id': rec.id,
                    'label': rec.course_name,
                    'total_unit': rec.total_unit,
                }

            })
            const courses2 = $json.courses2;
            courses2.forEach(function (e) {
                e.unit.forEach(function (e1) {
                    e1.label = 'Unit' + ' ' + e1.position +' : '+e1.unit_name;
                })

            })
            let courseTreeselect2 = !courses2 ? null : courses2.map(rec => {
                return {
                    'id': rec.id,
                    'label': rec.course_name,
                    'total_unit': rec.total_unit,
                    'units':rec.unit,
                }

            })

            return {
                active_allocation:$json.active_allocation,
                roleName:$json.roleName,
                permissions,
                permissionFields:$json.permissionFields || [],
                teacher:$json.teacher,
                courses: courseTreeselect,
                units: unitTreeselect,
                allocationContentSchool: $json.entry.allocationContentId,
                allocationContenSchoolName: $json.allocationContenSchoolName || {},
                allocationContents: $json.allocationContents || {},
                courses2: courseTreeselect2,
                breadcrumbs: [
                    {
                        title: 'School management',
                        url: '/xadmin/schools/index',

                    },
                    {
                        title: $json.entry ? 'School details' : 'Create New school',
                    },
                ],
                title: $json.entry ? 'Edit school' : 'Create New school',

                entry: $json.entry || {},
                isLoading: false,
                errors: {}
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
            modalDeleteSchool() {
                $('#deviceConfirm').modal('show');
            },
                modalDelete()
            {
                $('#delete').modal('show');
            },
            changeAllocationContent() {

                let curAllocationContents = this.allocationContents.filter(e => e.id == this.allocationContentSchool);
                let abc = ! curAllocationContents[0].units ? null : curAllocationContents[0].units.map(rec => {
                    return {
                        'id': rec.id,
                        'label': rec.unit_name,
                    }
                })
                if(curAllocationContents.length > 0){
                    this.courses = curAllocationContents[0]['courses'];
                    this.units=abc;
                    this.courses.forEach(function (e) {
                        e.label = e.course_name;
                    })
                }


            },
            backIndex() {
                window.location.href = '/xadmin/schools/index';
            },
            async save() {
                this.$loading(true);
                const res = await $post('/xadmin/schools/save', {
                    entry: this.entry,
                    allocationContentSchool: this.allocationContentSchool
                }, false);
                this.$loading(false);
                if (res.errors) {
                    this.errors = res.errors;
                    return;
                }
                if (res.code) {
                    toastr.error(res.message);
                } else {
                    this.errors = {};
                    toastr.success(res.message);
                    location.replace('/xadmin/schools/index');
                    if (!this.entry.id) {
                        location.replace('/xadmin/schools/edit?id=' + res.id);
                    }

                }
            },
            async remove(entry) {

                const res = await $post('/xadmin/schools/remove', {id: entry.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }
                location.replace('/xadmin/schools/index');


                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});
            },

        }
    }
</script>

<style scoped>
    option[value=""][disabled] {
        display: none;
    }

    option {
        color: black;
    }
    .table th, .table td
    {
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    /* white-space: nowrap; */
    cursor: pointer;
    padding: 0.75rem;
    border-top: 1px solid #EBEDF3;
    }


</style>
