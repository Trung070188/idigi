<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title ="School details" />
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                     <div class="card-header border-0 pt-6" style="margin:0px 0px -35px">
                         <div class="card-title"></div>
                         <div class="card-toolbar">
                             <div
                                 class="d-flex justify-content-end"
                                 data-kt-customer-table-toolbar="base">
                                 <a :href="'/xadmin/schools/teacherList?id='+entry.id">
                                     <button style="margin: 0px 8px 25px;"  v-if="title=='Edit school'" class="btn btn-primary button-create " >
                                         Teacher list <i class="fa fa-users"></i>
                                     </button>
                                 </a>
                                 <button v-if="title=='Edit school'" class="btn btn-danger button-create " @click="remove(entry)">
                                     Delete User <i class="fas fa-trash"></i>
                                 </button>
                             </div>
                         </div>

                    </div>

                    <div class="card-body d-flex flex-column" >
                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                     <div class="form-group col-lg-4">
                                        <label>School Name <span class="text-danger">*</span></label>
                                        <input  v-model="entry.label"  class="form-control"
                                               placeholder="Nhập vào tên trường" >
                                        <error-label for="f_school_name" :errors="errors.label"></error-label>

                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label>School Address <span class="text-danger">*</span></label>
                                        <input  v-model="entry.school_address" class="form-control"
                                               placeholder="Nhập vào địa chỉ của trường" >
                                        <error-label  :errors="errors.school_address"></error-label>

                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>School Email <span class="text-danger">*</span></label>
                                        <input  v-model="entry.school_email"  class="form-control"
                                                placeholder="Nhập vào email của trường" >
                                        <error-label  :errors="errors.school_email"></error-label>

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label>Phone number <span class="text-danger">*</span></label>
                                        <input  v-model="entry.school_phone"  class="form-control"
                                                placeholder="Nhập vào số điện thoại của trường" >
                                        <error-label for="f_school_name" :errors="errors.school_phone"></error-label>

                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label>Setup device per user <span class="text-danger">*</span></label>
                                        <input  v-model="entry.devices_per_user" class="form-control"
                                                placeholder="Nhập số lượng cho phép thiết bị của mỗi giáo viên" >
                                        <error-label  :errors="errors.devices_per_user"></error-label>

                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Setup no. of user <span class="text-danger">*</span></label>
                                        <input  v-model="entry.number_of_users"  class="form-control"
                                                placeholder="Nhập số lượng giáo viên" >
                                        <error-label  :errors="errors.number_of_users"></error-label>

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label>License to <span class="text-danger">*</span></label>
                                        <datepicker :timepicker="true" v-model="entry.license_to"></datepicker>
                                        <error-label  :errors="errors.license_to"></error-label>
                                    </div>
                                </div>
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
                                    <label>Licence description</label>
                                    <textarea  v-model="entry.license_info" rows="5" class="form-control" placeholder="Your text here..."></textarea>
                                    <error-label for="f_grade" :errors="errors.license_info"></error-label>

                                </div>
                                </div>
                            </div>
                             <div class="row">
                                        <div class="form-group col-lg-10">
                                        <label>Content Allocated <span class="text-danger">*</span></label>
                                       <select class="form-control form-select" required v-model="allocationContenSchool" >
                                        <option value=""  selected>{{allocationContenSchoolName}}</option>
                                           <option v-for="allocationConten in allocationContens" :value="allocationConten.id">{{allocationConten.title}}</option>
                                       </select>
                                    </div>
                            <table class="table table-row-bordered align-middle gy-4 gs-9">
                            <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                            <tr>
                                <th class="">Course Name</th>
                                <th>Unit</th>
                            </tr>
                            </thead>
                            <tbody  >
                            <tr v-for="course in courses" >
                                <td  >
                                  {{course.label}}
                                </td>
                                <td >
                                <treeselect :options="units" :multiple="true" v-model="course.total_unit" :disabled="true"/>
                                    </td>
                            </tr>
                            </tbody>
                        </table>

                </div>
                        </div>
                        <hr style="margin-top: 5px;">
                        <div >
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
        components: {ActionBar, QSelect, Datepicker,Treeselect},
        data() {
            const units=$json.units;

               let unitTreeselect = !units ? null : units.map(rec => {
                   return {
                       'id':rec.id,
                       'label': rec.unit_name,
                   }
               })

            const course=$json.courses;
               let courseTreeselect =!course ? null : course.map(rec => {
                   return {
                       'id':rec.id,
                       'label': rec.course_name,
                       'total_unit':rec.total_unit,
                   }

               })

            return {
                courses:courseTreeselect,
                units:unitTreeselect,
                allocationContenSchool:[],
                allocationContenSchoolName:$json.allocationContenSchoolName || {},
                allocationContens:$json.allocationContens || {},
                breadcrumbs: [
                    {
                        title: 'Schools',
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
            backIndex(){
                window.location.href = '/xadmin/schools/index';
            },
            async save() {
                this.$loading(true);
                const res = await $post('/xadmin/schools/save', {entry: this.entry,allocationContenSchool:this.allocationContenSchool}, false);
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

                    if (!this.entry.id) {
                        location.replace('/xadmin/schools/edit?id=' + res.id);
                    }

                }
            },
            async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return;
                }

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
