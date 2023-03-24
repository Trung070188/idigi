<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title = "Create new teacher"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column">
                        <div class="row">
                            <div class=" col-sm-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group  col-sm-4">
                                        <label>Username <span class="text-danger">*</span></label>
                                        <input class="form-control nospace" placeholder="Enter the username" v-model="entry.username">

                                        <error-label for="f_category_id" :errors="errors.username"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>Teacher name <span class="text-danger">*</span></label>
                                        <input class="form-control" placeholder="Enter the full name" v-model="entry.full_name">

                                        <error-label for="f_category_id" :errors="errors.full_name"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>Email </label>
                                        <input class="form-control" placeholder="Enter the email address" v-model="entry.email" >
                                        <error-label for="f_category_id" :errors="errors.email"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>Phone number </label>
                                        <input class="form-control noString" placeholder="Enter the phone number" v-model="entry.phone">
                                        <error-label for="f_category_id" :errors="errors.phone"></error-label>
                                    </div>
                                     <div class="form-group  col-sm-4">
                                        <label>Class</label>
                                        <input class="form-control" placeholder="Enter the class" v-model="entry.class">
                                        <error-label for="f_category_id" :errors="errors.class"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>School<span class="text-danger">*</span></label>
                                        <input class="form-control" v-model="schoolName" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div v-if="entry.id==null" class="form-group  col-sm-4">
                                        <label>Password <span class="text-danger">*</span></label>
                                        <input v-if="auto_gen==true" disabled :type="showPass ? 'text' : 'password'" class="form-control"
                                           placeholder="Enter the password"  ref="password" v-model="entry.password">
                                        <input v-if="auto_gen==false"  :type="showPass ? 'text' : 'password'" class="form-control"
                                            placeholder="Enter the password"   ref="password" v-model="entry.password">
                                        <!-- <i @click="showPass = !showPass" class="fa fa-eye"></i> -->
                                        <error-label for="f_category_id" :errors="errors.password"></error-label>
                                    </div>

                                    <div v-if="entry.id==null" class="form-group  col-sm-4">
                                        <label>Confirm your password <span class="text-danger">*</span></label>
                                        <input v-if="auto_gen==true" disabled class="form-control" :type="showConfirm ? 'text' : 'password'" @input="inputPasswordConfirm"
                                            placeholder="Re-enter to confirm the password"   v-model="entry.password_confirmation">
                                        <input v-if="auto_gen==false"  class="form-control" :type="showConfirm ? 'text' : 'password'" @input="inputPasswordConfirm"
                                             placeholder="Re-enter to confirm the password"  v-model="entry.password_confirmation">
                                        <!-- <i @click="showConfirm = !showConfirm" class="fa fa-eye"></i> -->
                                        <error-label for="f_category_id" :errors="errors.password_confirmation"></error-label>
                                    </div>
                                </div>

                                <div class="form-check form-check-custom form-check-solid pb-5">
                                    <input  type="checkbox" class="form-check-input h-20px w-20px" v-model="auto_gen">
                                    <label class="form-check-label fw-bold">Auto password</label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid pb-5">
                                    <input id="state1" type="checkbox" v-model="entry.state" class="form-check-input h-20px w-20px"  checked>
                                    <label for="state1" class="form-check-label fw-bold">Active teacher</label>
                                    <error-label for="f_grade" :errors="errors.state"></error-label>
                                </div>
                                <div class="row"  >
                                    <div class="form-group col-sm-12" >
                                        <label>Course<span class="text-danger">*</span></label>
                                        <treeselect :options="courses" :multiple="true" v-model="courseTeacherTmp" @input="selectTotalCourse"/>
                                        <error-label  for="f_grade" :errors="errors.courseTeachers"></error-label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12" style="display: flex" v-if="courseTeachers.length>0" >
                                        <div style="display: flex;align-items: center;flex-basis: 10%">Course name</div>
                                        <div style="flex-basis: 90%"  >Unit <span class="text-danger">*</span></div>
                                    </div>
                                    <div class="col-lg-12" style="display: flex ;margin: 16px 0px 0px" v-for="courseTeacher in courseTeachers">
                                        <div v-for="course in courses[0].children" v-if="courseTeacher==course.id" style="display: flex;align-items: center;flex-basis: 10%"> {{course.label}}</div>
                                        <div  style="flex-basis: 90%" v-for="course in courses[0].children" v-if="courseTeacher==course.id">
                                            <treeselect :options="course.unit" :multiple="true" v-model="course.teacher_unit_tmp" @input="selectTotalUnit(course)" />
                                            <error-label :errors="errors.teacher_unit"></error-label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check form-check-custom form-check-solid pb-5 mt-3">
                                    <input id="state2" type="checkbox" v-model="entry.active_allocation" class="form-check-input h-20px w-20px"  checked>
                                    <label for="state2" class="form-check-label fw-bold">Active allocation </label>
                                    <error-label for="f_grade" :errors="errors.active_allocation"></error-label>
                                </div>
                            </div>

                        </div>
                        <hr style="margin-top: 5px;">
                        <div>
                            <button type="reset" @click="save()" class="btn btn-primary mr-2">Create new teacher</button>
                            <button type="reset" @click="backIndex()" class="btn btn-secondary">Cancel</button>
                             <label style="margin-left: 20px">Thông tin đăng nhập và mật khẩu sẽ được gửi tới người dùng
                            qua email.</label>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
</template>

<script>
    import {$get,$post} from "../../utils";

    import ActionBar from "../includes/ActionBar";
    import SwitchButton from "../../components/SwitchButton";
    import Treeselect from '@riophae/vue-treeselect'
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'
    import $router from "../../lib/SimpleRouter";


    export default {
        name: "TeacherCreated.vue",
        components: {ActionBar, SwitchButton,Treeselect},

        data() {

            return {
                allocationContent:'',
                courseTeacherTmp:[],
                courseTeachers:[],
                units:[],
                courses:[],
                auto_gen:false,
                showConfirm: false,
                showPass: false,
                types: [],
                breadcrumbs: [
                    {
                      title:'School management',
                        url: '/xadmin/schools/index',
                    },

                    {
                        title: 'School details',
                        url: '/xadmin/schools/edit?id='+$json.school.id
                    },
                    {
                        title: 'Teacher management',
                        url: '/xadmin/schools/teacherList?id='+$json.school.id


                    },
                    {
                        title: $json.entry ? 'Edit User' : 'Create new teacher',
                    },
                ],
                school:$json.school,
                schoolName:$json.schoolName,
                entry: $json.entry || {
                    roles: []
                },
                roles: $json.roles || [],
                isLoading: false,
                errors: {}
            }
        },
        mounted()
        {
            $router.on('/', this.load).init();

            $('.nospace').keypress(function (e) {
                if (e.keyCode == 32 ) {
                    e.preventDefault();
                }
            })

            $('.noString').keypress(function (e) {
                if (e.keyCode < 48 || e.keyCode > 57) {
                    e.preventDefault();
                }
            })

        },
        methods: {
            inputPasswordConfirm(){
                if(this.password != this.password_confirmation){
                    this.errors.password_confirmation = ["You must enter the same password twice in order to confirm it."];
                }else{
                    this.errors.password_confirmation = [];
                }
            },
            async load() {
                let query = $router.getQuery();
                const res  = await $get('/xadmin/users/dataContentCreateTeacher?id='+this.school.id, query);
                this.courses=res.course.map(res =>{
                    return {
                      'label':res.course_name,
                        'id':res.id,
                        'units':res.units.map(rec =>{
                            return {
                                'id':rec.id,
                                'label': 'Unit' + ' ' + rec.position +' : '+rec.unit_name
                            }
                        })
                    };
                })
              this.courses=[
                  {
                      'id':'all',
                      'label':'all course',
                      'children':this.courses
                  }
              ]
                let units=this.courses[0].children.map( rec => {
                    return {
                        'id':rec.id,
                        'label':rec.label,
                        'teacher_unit':[],
                        'teacher_unit_tmp':[],
                        'unit':[
                            {
                                'id':'all',
                                'label':'all units',
                                'children':rec.units
                            }
                        ]
                    }
                })
                this.courses=[
                    {
                        'id':'all',
                        'label':'all course',
                        'children':units
                    }
                ]


                this.allocationContent=res.allocationContent;
            },
            backIndex() {

                window.location.href = '/xadmin/users/index';
            },
            selectTotalCourse()
            {
                if(this.courseTeacherTmp.length > 0 && this.courseTeacherTmp[0]=='all')
                {
                    this.courseTeachers=this.courses[0].children.map(res=>{
                        return res.id;
                    })
                }else{
                    this.courseTeachers = this.courseTeacherTmp;
                }
            },
            selectTotalUnit(course)
            {
                if(course.teacher_unit_tmp=='all' )
                {
                    course.teacher_unit=course.unit[0].children.map( res => {
                        return res.id
                    })
                }else{
                    course.teacher_unit = course.teacher_unit_tmp;
                }
            },
            async save() {
                this.$loading(true);
                const res = await $post('/xadmin/users/saveTeacher', {entry: this.entry, roles: this.roles,
                    auto_gen:this.auto_gen,
                    school:this.school,
                    courses:this.courses,
                    courseTeachers:this.courseTeachers,
                    allocationContent:this.allocationContent
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
                    location.replace('/xadmin/schools/teacherList?id='+this.school.id);

                    if (!this.entry.id) {
                    location.replace('/xadmin/schools/teacherList?id='+this.school.id);
                    }
                }
            }
        }
    }
</script>

<style scoped>
    .fa-eye {
        position: absolute;
        top: 50%;
        right: 5%

    }
    .form-group label
    {
        margin-bottom: 2px;
    }

</style>
