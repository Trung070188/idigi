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
                                        <label>School Email </label>
                                        <input  v-model="entry.school_email"  class="form-control"
                                                placeholder="Nhập vào email của trường" >
                                        <error-label  :errors="errors.school_email"></error-label>

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label>Phone number </label>
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
<!--                                <div class="row">-->
<!--                                    <div class="form-group col-lg-4">-->
<!--                                        <label>License to </label>-->
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
                                    <textarea  v-model="entry.school_description" rows="5" class="form-control" placeholder="Your text here..."></textarea>
                                    <error-label for="f_grade" :errors="errors.school_description"></error-label>

                                </div>
                                    <div class="form-group col-lg-4">
                                        <label>Expired date/License <span class="text-danger">*</span></label>
                                        <datepicker  v-model="entry.license_to" rows="5" class="form-control" ></datepicker>
                                        <error-label for="f_grade" :errors="errors.license_to"></error-label>
                                    </div>
                                </div>
                            </div>
                             <div class="row">
                                    <div class="form-group col-lg-10">
                                        <label>Content Allocated </label>
                                       <select class="form-control form-select" v-model="allocationContenSchool" required @change="changeAllocationContent()">
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
                            <tbody v-if="allocationContenSchool!=null" >
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
    import Datepicker from "../../components/Datepicker";
    import $router from "../../lib/SimpleRouter";
    import Treeselect from '@riophae/vue-treeselect'
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'

    export default {
        name: "SchoolsForm.vue",
        components: {ActionBar, Datepicker,Treeselect},
        data() {
            const units=$json.units;
            const unitTreeselect = units.map(rec => {
                return {
                    'id':rec.id,
                    'label': rec.unit_name,
                }
            })

            const course = $json.arrayCourse;
            let courseTreeselect = !course ? null : course.map(rec => {
                return {
                    'id': rec.id,
                    'label': rec.course_name,
                    'total_unit': rec.total_unit,
                }

            })
            return {
                courses: courseTreeselect,
                units:unitTreeselect,
                allocationContenSchool:null,
                allocationContens:$json.newAllocationContents,
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
            changeAllocationContent() {

                let curAllocationContents = this.allocationContens.filter(e => e.id == this.allocationContenSchool);
                if(curAllocationContents.length > 0){
                    let abc = ! curAllocationContents[0].units ? null : curAllocationContents[0].units.map(rec => {
                        return {
                            'id': rec.id,
                            'label': rec.unit_name,
                        }
                    })
                    this.courses = curAllocationContents[0]['courses'];
                    this.units=abc;
                    this.courses.forEach(function (e) {
                        e.label = e.course_name;
                    })
                }
                else {
                    this.courses=[];
                    this.units=[];
                }


            },
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
                    location.replace('/xadmin/schools/index');

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

</style>
