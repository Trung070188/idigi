<template>
    <div class="container-fluid">
        <ActionBar type="index" :breadcrumbs="breadcrumbs" title="School details" />
        <div class="row">
            <div class="col-lg-12">
                <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="delete" tabindex="-1"
                    role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered popup-main-1" role="document" style="max-width: 450px;">
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
                                    class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal"
                                    style="margin: 0px 8px 0px">No, cancel</button>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="deviceConfirm"
                    tabindex="-1" role="dialog" aria-labelledby="deviceConfirm" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered popup-main-1" role="document" style="max-width: 500px;">
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

                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title"></div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                <button v-if="title == 'Edit school'" @click="teacherList"
                                    class="btn btn-primary button-create mx-2"
                                    :disabled="!permissionFields['school_teacher_list']">
                                    <i class="bi bi-person-lines-fill mr-1"></i>Teacher list
                                </button>
                                <button v-if="title == 'Edit school' && teacher != 0"
                                    :disabled="!permissionFields['school_delete']" class="btn btn-danger button-create mx-2"
                                    @click="modalDeleteSchool()">
                                    <i class="bi bi-trash mr-1"></i>Delete school
                                </button>
                                <button v-if="title == 'Edit school' && teacher == 0"
                                    :disabled="!permissionFields['school_delete']" class="btn btn-danger button-create mx-2"
                                    @click="modalDelete">
                                    <i class="bi bi-trash mr-1"></i>Delete school
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <input v-model="entry.id" type="hidden" name="id" value="">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>School name <span class="text-danger">*</span></label>
                                <input v-model="entry.label" class="form-control" placeholder="Enter the school name"
                                    :disabled="!permissionFields['school_name']">
                                <error-label for="f_school_name" :errors="errors.label"></error-label>
                            </div>
                            <div class="form-group col-md-3">
                                <label>City/ Province <span class="text-danger">*</span></label>
                                <treeselect :options="provinces" v-model="entry.province_id" @input="selectProvince"
                                    :disabled="!permissionFields['school_address']" />
                                <error-label for="f_school_name" :errors="errors.province_id"></error-label>
                            </div>
                            <div class="form-group col-md-3" v-if="entry.province_id">
                                <label>District/ Town <span class="text-danger">*</span></label>
                                <treeselect :options="districts" v-model="entry.district_id"
                                    :disabled="!permissionFields['school_address']" />
                                <error-label for="f_school_name" :errors="errors.district_id"></error-label>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Street/ Ward </label>
                                <input v-model="entry.school_address" class="form-control"
                                    placeholder="Enter the school address" :disabled="!permissionFields['school_address']">
                                <error-label :errors="errors.school_address"></error-label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Phone number </label>
                                <input v-model="entry.school_phone" :disabled="!permissionFields['school_phone_number']"
                                    class="form-control noString " placeholder="Enter the phone number">
                                <error-label for="f_school_name" :errors="errors.school_phone"></error-label>
                            </div>
                            <div class="form-group col-md-4">
                                <label>No. of Device per user <span class="text-danger">*</span></label>
                                <input type="number" min="1" max="200" v-model="entry.devices_per_user"
                                    :disabled="!permissionFields['school_device']" class="form-control"
                                    placeholder="Enter number of Device per User">
                                <error-label :errors="errors.devices_per_user"></error-label>
                            </div>
                            <div class="form-group col-md-4">
                                <label>No. of User <span class="text-danger">*</span></label>
                                <input type="number" min="1" max="200" v-model="entry.number_of_users"
                                    :disabled="!permissionFields['school_user']" class="form-control"
                                    placeholder="Enter number of User">
                                <error-label :errors="errors.number_of_users"></error-label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>School email</label>
                                <input v-model="entry.school_email" :disabled="!permissionFields['school_email']"
                                    class="form-control" placeholder="Enter the email prefix">
                                <error-label :errors="errors.school_email"></error-label>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Expired date/License <span class="text-danger">*</span></label>
                                <datepicker v-model="entry.license_to" rows="5" class="form-control"
                                    :disabled="!permissionFields['school_expire_date']" readonly>
                                </datepicker>
                                <error-label for="f_grade" :errors="errors.license_to"></error-label>
                            </div>
                            <div class="form-group col-md-4">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label>School description</label>
                                <textarea v-model="entry.school_description"
                                    :disabled="!permissionFields['school_description']" rows="5" class="form-control"
                                    placeholder="Your text here..."></textarea>
                                <error-label for="f_grade" :errors="errors.school_description"></error-label>
                            </div>
                            <div class="form-group col-md-4">

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-check form-check-custom form-check-solid pb-5 ">
                                <input id="state" type="checkbox" class="form-check-input h-20px w-20px"
                                    v-model="entry.active_school" checked :disabled="!permissionFields['school_active']">
                                <label for="state" class="form-check-label fw-bold">Active school</label>
                                <error-label for="f_grade"></error-label>
                            </div>
                        </div>
                        <div class="form-row"
                            v-if="roleName == 'School Admin' && entry.active_allocation == 1 || roleName == 'Super Administrator'">
                            <div class="form-group col-md-8">
                                <label>Resource allocation</label>
                                <select class="form-control form-select " required v-model="allocationContentSchool"
                                    :disabled="!permissionFields['school_content']" @change="changeAllocationContent()">
                                    <option value="">---</option>
                                    <option v-for="allocationContent in allocationContents" :value="allocationContent.id">{{
                                        allocationContent.title }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-4" v-if="permissions['036'] && allocationContentSchool">
                                <button @click="resourceLocation(allocationContentSchool)" style="margin: 20px 0px 0px"
                                    class="btn btn-primary" :disabled="!permissionFields['school_content']">
                                    <i class="bi bi-pencil-square mr-1"></i>Edit</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-check form-check-custom form-check-solid pb-5"
                                v-if="roleName == 'Super Administrator'">
                                <input id="state1" type="checkbox" class="form-check-input h-20px w-20px"
                                    v-model="entry.active_allocation" checked :disabled="!permissionFields['school_active_allocation']">
                                <label for="state1" class="form-check-label fw-bold">Active allocation</label>
                                <error-label for="f_grade"></error-label>
                            </div>
                        </div>


                        <div class="form-row">
                            <div class="form-group col-sm-2">
                                <label class="col-form-label">Course name</label>
                            </div>
                            <div class="form-group col-sm-10">
                                <label class="col-form-label">Unit</label>
                            </div>
                        </div>
                        <div class="form-row" v-for="course in courses" v-if="courses != null">
                            <div class="form-group col-sm-2">
                                <label class="col-form-label">{{ course.label }}</label>
                            </div>
                            <div class="form-group col-sm-10">
                                <treeselect :options="units" :multiple="true" v-model="course.total_unit"
                                    :disabled="true" />
                                <error-label for="f_total_course" :errors="errors.total_unit"></error-label>
                            </div>
                        </div>
                        <div class="form-row" v-for="course in courses2" v-if="courses == null">
                            <div class="form-group col-sm-2">
                                <label class="col-form-label">{{ course.label }}</label>
                            </div>
                            <div class="form-group col-sm-10">
                                <treeselect :options="units" :multiple="true" v-model="course.total_unit"
                                    :disabled="true" />
                                <error-label for="f_total_course" :errors="errors.total_unit"></error-label>
                            </div>
                        </div>
                        <div class="form-row" v-if="roleName == 'School Admin' && entry.active_allocation == 0">
                            <div class="form-group col-sm-2">
                                <label class="col-form-label">Resource allocation
                                    <span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="form-group col-sm-10">
                                <h3 style="text-align: center; font-weight: bold;font-size: 15px">Resource
                                    allocation has been deactivated by Super admin
                                    {{ entry.full_name_active_content }} </h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="reset" @click="save()" class="btn btn-primary mr-2" :title="permissions['017']?'Save':'You do not have permission for this action.'" :disabled="!permissions['017']"><i
                                class="bi bi-save2 mr-1"></i>Save change</button>
                        <button type="reset" @click="backIndex()" class="btn btn-light">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { $post, clone, $get } from "../../utils";
import ActionBar from "../includes/ActionBar";
import QSelect from "../../components/QSelect";
import Datepicker from "../../components/Datepicker";
import $router from "../../lib/SimpleRouter";
import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

export default {
    name: "SchoolEdit.vue",
    components: { ActionBar, QSelect, Datepicker, Treeselect },
    data() {
        const permissions = clone(window.$permissions)
        const units = $json.units;

        let unitTreeselect = !units ? null : units.map(rec => {
            return {
                'id': rec.id,
                'label': 'Unit' + ' ' + rec.position + ' : ' + rec.unit_name,
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
                e1.label = 'Unit' + ' ' + e1.position + ' : ' + e1.unit_name;
            })

        })
        let courseTreeselect2 = !courses2 ? null : courses2.map(rec => {
            return {
                'id': rec.id,
                'label': rec.course_name,
                'total_unit': rec.total_unit,
                'units': rec.unit,
            }

        })

        return {
            provinces: [],
            districts: [],
            active_allocation: $json.active_allocation,
            roleName: $json.roleName,
            permissions,
            permissionFields: $json.permissionFields || [],
            teacher: $json.teacher,
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
        let self = this;
        $.get('/xadmin/schools/getProvince', function (res) {
            self.provinces = res;
            if (self.entry.province_id) {
                self.districts = self.provinces.filter(e => e.id == self.entry.province_id)[0]['districts'];
            }

        });

        $('.noString').keypress(function (e) {
            if (e.keyCode < 48 || e.keyCode > 57) {
                e.preventDefault();
            }
        })
    },
    methods: {
        selectProvince() {
            this.entry.district_id = null;
            this.districts = [];
            if (this.entry.province_id) {
                this.districts = this.provinces.filter(e => e.id == this.entry.province_id)[0]['districts'];
            }

        },
        modalDeleteSchool() {
            $('#deviceConfirm').modal('show');
        },
        modalDelete() {
            $('#delete').modal('show');
        },
        changeAllocationContent() {

            let curAllocationContents = this.allocationContents.filter(e => e.id == this.allocationContentSchool);
            let abc = !curAllocationContents[0].units ? null : curAllocationContents[0].units.map(rec => {
                return {
                    'id': rec.id,
                    'label': rec.unit_name,
                }
            })
            if (curAllocationContents.length > 0) {
                this.courses = curAllocationContents[0]['courses'];
                this.units = abc;
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

            const res = await $post('/xadmin/schools/remove', { id: entry.id });

            if (res.code) {
                toastr.error(res.message);
            } else {
                toastr.success(res.message);
            }
            location.replace('/xadmin/schools/index');


            $router.updateQuery({ page: this.paginate.currentPage, _: Date.now() });
        },
        teacherList() {
            window.location.href = '/xadmin/schools/teacherList?id=' + this.entry.id;
        },
        resourceLocation(id) {
            window.location.href = '/xadmin/allocation_contents/edit?id=' + id;
        }
    }
}
</script>
<style>
.vue-treeselect__control {
    height: 41px !important;
}
</style>
<style scoped>
option[value=""][disabled] {
    display: none;
}

option {
    color: black;
}

.table th,
.table td {
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    /* white-space: nowrap; */
    cursor: pointer;
    padding: 0.75rem;
    border-top: 1px solid #EBEDF3;
}
</style>
