<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title ="Create new school" />
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
                                     Delete user <i class="fas fa-trash"></i>
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
                                        <label>School name <span class="text-danger">*</span></label>
                                        <input  v-model="entry.label"  class="form-control "
                                               placeholder="Enter the school name" >
                                        <error-label for="f_school_name" :errors="errors.label"></error-label>

                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label>School address <span class="text-danger">*</span></label>
                                        <input  v-model="entry.school_address" class="form-control"
                                               placeholder="Enter the school address">
                                        <error-label  :errors="errors.school_address"></error-label>

                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>School email</label>
                                        <input  v-model="entry.school_email"  class="form-control"
                                                placeholder="Enter the email prefix" >
                                        <error-label  :errors="errors.school_email"></error-label>

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label>Phone number </label>
                                        <input  v-model="entry.school_phone"  class="form-control noString"
                                                placeholder="Enter the phone number" >
                                        <error-label for="f_school_name" :errors="errors.school_phone"></error-label>

                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label>No. of Device per user <span class="text-danger">*</span></label>
                                        <input type="number" min="1" max="200" v-model="entry.devices_per_user" class="form-control noString"
                                                placeholder="Enter number of Device per User" >
                                        <error-label  :errors="errors.devices_per_user"></error-label>

                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>No. of User <span class="text-danger">*</span></label>
                                        <input type="number" min="1" max="10000" v-model="entry.number_of_users"  class="form-control noString"
                                                placeholder="Enter number of User" >
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
                                        <datepicker  v-model="entry.license_to" rows="5" class="form-control" readonly></datepicker>
                                        <error-label for="f_grade" :errors="errors.license_to"></error-label>
                                    </div>
                                </div>
                                <div class="form-check form-check-custom form-check-solid pb-5 ">
                                    <input id="state" type="checkbox"  class="form-check-input h-20px w-20px" checked>
                                    <label for="state" class="form-check-label fw-bold">Active school</label>
                                    <error-label for="f_grade" ></error-label>
                                </div>
                            </div>
                             <div class="row">
                                    <div class="form-group col-lg-8">
                                        <label>Resource allocation </label>
                                       <select required class="form-control form-select" v-model="allocationContentSchool" @change="changeAllocationContent()">
                                           <option value="" disabled selected>Choose resource allocation</option>
                                           <option v-for="allocationConten in allocationContens" :value="allocationConten.id">{{allocationConten.title}}</option>
                                       </select>
                                        <error-label for="f_grade" :errors="errors.allocationContentSchool"></error-label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid pb-5 ml-3">
                                <input id="state1" type="checkbox"  class="form-check-input h-20px w-20px" checked>
                                <label for="state1" class="form-check-label fw-bold">Active allocation </label>
                                <error-label for="f_grade" ></error-label>
                            </div>
                            </div>
                        </div>
                        <div class="row" v-if="allocationContentSchool!=''">
                            <div class="col-lg-12" style="display: flex">
                                <div style="display: flex;align-items: center;flex-basis: 10%">Course name</div>
                                <div style="flex-basis: 90%" >Unit </div>
                            </div>
                            <div class="col-lg-12" style="display: flex ;margin: 16px 0px 0px" v-for="course in courses">
                                <div   style="display: flex;align-items: center;flex-basis: 10%"> {{course.label}}</div>
                                <div  style="flex-basis: 90%">
                                    <treeselect :options="units" :multiple="true" v-model="course.total_unit" :disabled="true"/>
                                </div>
                            </div>
                        </div>
                        <hr style="margin-top: 5px;">
                        <div >
                            <button type="reset" @click="save()" class="btn btn-primary mr-2"><i class="bi bi-send"></i>Save</button>
                            <button type="reset" @click="backIndex()" class="btn btn-light">Cancel</button>
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
                allocationContentSchool:'',
                allocationContens:$json.newAllocationContents,
                breadcrumbs: [

                    {
                        title: 'School management',
                        url: '/xadmin/schools/index',
                    },

                    {
                        title: $json.entry ? 'School details' : 'Create new school',
                    },
                ],
                title: $json.entry ? 'Edit school' : 'Create new school',

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
            changeAllocationContent() {

                let curAllocationContents = this.allocationContens.filter(e => e.id == this.allocationContentSchool);
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
                const res = await $post('/xadmin/schools/save', {entry: this.entry,allocationContentSchool:this.allocationContentSchool}, false);
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
  .table th, .table td
    {
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
    padding: 0.75rem;
    border-top: 1px solid #EBEDF3;
    }
  select:required:invalid {
      color: #adadad;
  }
  option {
      color: black;
  }
  option[value=""][disabled] {
      display: none;
  }


</style>
