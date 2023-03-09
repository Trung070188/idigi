<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title = "Allocation detail"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column" >
                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                    <div class="form-group">
                                        <label>Title <span class="text-danger">*</span></label>
                                        <input id="f_title" :disabled="permissionFields['allocation_title']==false" v-model="entry.title" name="name" class="form-control"
                                               placeholder="title" >
                                        <error-label for="f_title" :errors="errors.title"></error-label>
                                    </div>
                                        <div class="form-group">
                                        <label>Course <span class="text-danger">*</span></label>
                                        <treeselect :options="allCourses" :multiple="true" @deselect="deleteCourse" v-model="total_course" @input="selectTotalCourse" :disabled="permissionFields['allocation_course']==false"/>
                                        <error-label for="f_total_course" :errors="errors.total_course"></error-label>
                                    </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12" style="display: flex" v-if="total_course.length>0">
                                <div style="display: flex;align-items: center;flex-basis: 10%">Course name</div>
                                <div style="flex-basis: 90%" >Unit <span class="text-danger">*</span></div>
                            </div>
                            <div class="col-lg-12" style="display: flex ;margin: 16px 0px 0px" v-for="number_course in total_course">
                                <div  v-for="course in courses" v-if="number_course==course.id" style="display: flex;align-items: center;flex-basis: 10%"> {{course.label}}</div>
                                <div v-for="course in courses" v-if="number_course==course.id" style="flex-basis: 90%">
                                    <treeselect :options="course.units" :multiple="true"  v-model="course.total_unit" @input="selectTotalUnit(course)" :disabled="permissionFields['allocation_unit']==false" />
                                    <error-label  for="f_total_course" :errors="errors.total_unit"></error-label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="form-group">
                                    <label>School <span class="text-danger">*</span></label>
                                    <treeselect :options="schools" :multiple="true" v-model="school"/>
                                    <error-label for="f_total_course" :errors="errors.total_course"></error-label>
                                </div>

                            </div>
                        </div>
                        <hr style="margin:20px 0px 20px">
                        <div >
                            <button type="reset" @click="save()" class="btn btn-primary mr-2"><i class="bi bi-save2 mr-1"></i>Save</button>
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
    import Treeselect from '@riophae/vue-treeselect'
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'

    export default {
        name: "Allocation_contentEdit.vue",
        components: {ActionBar, Treeselect},
        data() {
            const units=$json.units;
            const unitTreeselect = units.map(rec => {
                return {
                    'id':rec.id,
                    'label': rec.unit_name,
                }
            })
            const course=$json.courses;
            course.forEach(function (e) {
                e.unit.forEach(function (e1) {
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

            const courseTreeselect = course.map(rec => {
                let unitAll=selectAllUnits.map(res =>{
                    return {
                        id:res.id,
                        label:res.label,
                        children:rec.unit,

                    }
                })

                return {
                    'id':rec.id,
                    'label': rec.course_name,
                    'total_unit':rec.total_unit,
                    'units':unitAll,
                }
            })
            console.log(courseTreeselect);
            selectAllCourses.forEach(function (e) {
                courseTreeselect.forEach(function (e1) {
                    e.children.push(e1);
                })
            })
            const allCourses= selectAllCourses.map(res => {


                return{
                    'id':'all',
                    'label':res.label,
                    'children':res.children
                }
            })
            return {
                minWidth:940,
                newTotalCourse:[],
                breadcrumbs: [
                    {
                        title: 'Resource management'
                    },
                    {
                        title: 'Resource allocation',
                        url:'/xadmin/allocation_contents/index'

                    },
                    {
                        title: 'Allocation detail'
                    },
                ],
                school:$json.school || {},
                allCourses:allCourses,
                unit:[],
                total_school:$json.totalSchoolArray ||{},
                total_course:$json.totalCourseArray ||{},
                entry: $json.entry || {},
                schools:$json.schools ||[],
                permissionFields:$json.permissionFields || [],
                courses:courseTreeselect,
                units:unitTreeselect,
                isLoading: false,
                errors: {}
            }

        },
        methods: {
             selectTotalUnit(course)
            {
              let self=this;
              self.courses.forEach(function (e)
              {
                  if(e.id==course.id)
                  {
                      console.log(e.units);

                      if(e.units.length>0 && e.total_unit[0]=='all')
                      {
                          e.childrens=e.units.map(res =>{
                              return res.children;
                          })
                          e.total_unit=e.childrens[0].map(rec => {
                              return rec.id;
                          })
                      }

                  }
              })
            },
            selectTotalCourse()
            {
               if(this.total_course.length > 0 && this.total_course[0]=='all')
               {
                   this.total_course=this.courses.map(res=>{
                       return res.id;
                   })
               }

                if(this.total_course.length > 0 && this.total_course[0]=='all'){

                    this.newTotalCourse =  this.courses.map(rec => {
                        return rec.id;
                    })

                }else{
                    this.newTotalCourse = this.total_course;
                }
            },
            deleteCourse: function (node, instanceId) {
                node.total_unit = [];
            },

            backIndex(){
                window.location.href = '/xadmin/allocation_contents/index';
            },
            async save() {
                this.$loading(true);
                const res = await $post('/xadmin/allocation_contents/save', {
                    entry: this.entry,
                    total_school:this.total_school,
                    total_course:this.total_course,
                    total_unit:this.courses.total_unit,
                    unit:this.courses,
                    school:this.school
                }, false);
                this.$loading(false);
                if (res.errors) {
                    this.errors = res.errors;
                    return;
                }
                if (res.code) {
                    toastr.error(res.message);
                }
                else {
                    this.errors = {};
                    toastr.success(res.message);
                    location.replace('/xadmin/allocation_contents/index');

                    if (!this.entry.id) {
                        location.replace('/xadmin/allocation_contents/index');
                    }

                }
            }
        }
    }
</script>

<style scoped>

</style>

