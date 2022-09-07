<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title="School details"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="delete" tabindex="-1" role="dialog"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                         style="max-width: 500px;">
                        <div class="modal-content box-shadow-main paymment-status" style="left:140px;text-align: center; padding: 27px 0px 10px;">
                            <div class="close-popup" data-dismiss="modal"></div>
                            <h3 class="popup-title success" style="text-align: center">Delete school</h3>
                            <div class="content">
                                <p>Are you sure to delete this school?</p>
                            </div>
                            <div class="text-center">
                                <button type="reset" id="kt_modal_new_target_cancel" class="btn btn-primary" style="margin: 0px 15px 0px;" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" id="kt_modal_new_target_submit" class="btn btn-light me-3" @click="remove(entry)">
                                    <span class="indicator-label">Delete</span>
                                </button>
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
                                <a :href="'/xadmin/schools/teacherList?id='+entry.id">
                                    <button style="margin: 0px 8px 25px;" v-if="title=='Edit school'"
                                            class="btn btn-primary button-create ">
                                        Teacher list <i class="fa fa-users"></i>
                                    </button>
                                </a>
                                <button v-if="title=='Edit school' && teacher!=0" class="btn btn-danger button-create "
                                        @click="modalDeleteSchool()">
                                    Delete School <i class="fas fa-trash"></i>
                                </button>
                                <button v-if="title=='Edit school' &&  teacher==0" class="btn btn-danger button-create "@click="modalDelete">
                                    Delete School <i class="fas fa-trash"></i>
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
                                        <label>School Name <span class="text-danger">*</span></label>
                                        <input v-model="entry.label" class="form-control"
                                               placeholder="Nhập vào tên trường">
                                        <error-label for="f_school_name" :errors="errors.label"></error-label>

                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label>School Address <span class="text-danger">*</span></label>
                                        <input v-model="entry.school_address" class="form-control"
                                               placeholder="Nhập vào địa chỉ của trường">
                                        <error-label :errors="errors.school_address"></error-label>

                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>School Email</label>
                                        <input v-model="entry.school_email" class="form-control"
                                               placeholder="Nhập vào email của trường">
                                        <error-label :errors="errors.school_email"></error-label>

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label>Phone number </label>
                                        <input v-model="entry.school_phone" class="form-control"
                                               placeholder="Nhập vào số điện thoại của trường">
                                        <error-label for="f_school_name" :errors="errors.school_phone"></error-label>

                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label>Setup device per user <span class="text-danger">*</span></label>
                                        <input v-model="entry.devices_per_user" class="form-control"
                                               placeholder="Nhập số lượng cho phép thiết bị của mỗi giáo viên">
                                        <error-label :errors="errors.devices_per_user"></error-label>

                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Setup no. of user <span class="text-danger">*</span></label>
                                        <input v-model="entry.number_of_users" class="form-control"
                                               placeholder="Nhập số lượng giáo viên">
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
                                        <textarea v-model="entry.school_description" rows="5" class="form-control"
                                                  placeholder="Your text here..."></textarea>
                                        <error-label for="f_grade" :errors="errors.school_description"></error-label>

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                            <div class="row">
                                    <div class="form-group col-lg-10">
                                        <label>Content Allocated <span class="text-danger">*</span></label>

                                        <select class="form-control form-select " required v-model="allocationContentSchool"
                                                @change="changeAllocationContent() ">

                                            <option v-for="allocationContent in allocationContents"
                                                    :value="allocationContent.id">{{allocationContent.title}}
                                            </option>
                                        </select>

                                    </div>
                                    <div class="col-lg-2">
                                        <a :href="'/xadmin/allocation_contents/edit?id='+allocationContentSchool">
                                            <button style="margin: 20px 0px 0px" class="btn btn-primary">Edit allocation</button>
                                        </a>
                                    </div>
                                </div>

                                <table class="table table-row-bordered align-middle gy-4 gs-9" v-if="courses!=null">
                                    <thead
                                        class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                                    <tr>
                                        <th class="">Course Name</th>
                                        <th>Unit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="course in courses">
                                        <td>
                                            {{course.label}}
                                        </td>
                                        <td>
                                            <treeselect :options="units" :multiple="true" v-model="course.total_unit" :disabled="true" />
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table class="table table-row-bordered align-middle gy-4 gs-9" v-if="courses==null">
                                    <thead
                                        class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                                    <tr>
                                        <th class="">Course Name</th>
                                        <th>Unit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="course in courses2">
                                        <td>
                                            {{course.label}}
                                        </td>
                                        <td>
                                            <treeselect :options="course.units" :multiple="true" v-model="course.total_unit" :disabled="true" />
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>


                            </div>
                        </div>
                        <hr style="margin-top: 5px;">
                        <div>
                            <button type="reset" @click="save()" class="btn btn-primary mr-2">Save</button>
                            <button type="reset" @click="backIndex()" class="btn btn-secondary">Cancel</button>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

</template>

<script>
    import {$post} from "../../utils";
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
            const units = $json.units;

            let unitTreeselect = !units ? null : units.map(rec => {
                return {
                    'id': rec.id,
                    'label': rec.unit_name,
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
                    e1.label = e1.unit_name;
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
                teacher:$json.teacher,
                courses: courseTreeselect,
                units: unitTreeselect,
                allocationContentSchool: $json.entry.allocationContentId,
                allocationContenSchoolName: $json.allocationContenSchoolName || {},
                allocationContents: $json.allocationContents || {},
                courses2: courseTreeselect2,
                breadcrumbs: [
                    {
                        title: 'School & teacher',

                    },
                    {
                        title: 'Manage school',
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
                    location.replace('/xadmin/schools/edit?id=' + res.id);
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


</style>
