<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title = "Create new allocation"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column" >
                        <div class="row">
                            <div class="col-lg-12">

                                    <div class="form-group">
                                        <label>Title <span class="text-danger">*</span></label>
                                        <input id="f_title" v-model="entry.title" name="name" class="form-control"
                                               placeholder="title" >
                                        <error-label for="f_title" :errors="errors.title"></error-label>
                                    </div>

                                                                    <div class="form-group">
                                        <label>Course <span class="text-danger">*</span></label>
                                        <treeselect :options="allCourses" :multiple="true" v-model="total_course" @input="selectTotalCourse"  />
                                        <error-label for="f_total_course" :errors="errors.total_course"></error-label>

                                    </div>
                            </div>
                        </div>
                         <table class="table table-row-bordered align-middle gy-4 gs-9">
                            <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                            <tr>
                                <th class="">Course Name</th>
                                <th>Unit</th>
                            </tr>
                            </thead>
                            <tbody v-for="entry in newTotalCourse" >
                            <tr v-for="course in courses" v-if="entry==course.id">
                                <td >
                                    {{course.label}}
                                </td>
                                <td >
                                <treeselect :options="course.units" :multiple="true" v-model="course.total_unit" @input="selectTotalUnit(course)" />
                                <error-label v-if="!course.total_unit" for="f_total_course" :errors="errors.total_unit"></error-label>

                                <!-- <error-label v-if="!course.total_unit " for="f_total_course" :errors="errors.total_unit"></error-label> -->
                                </td>
                            </tr>


                            </tbody>
                        </table>

                        <hr style="margin:20px 0px 20px">
                        <div >
                            <button type="reset" @click="save()" class="btn btn-primary mr-2"><i class="bi bi-send mr-1"></i>Submit</button>
                            <button type="reset" @click="backIndex()" class="btn btn-light">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import {$post, forEach} from "../../utils";
    import ActionBar from "../includes/ActionBar";
    import Treeselect from '@riophae/vue-treeselect'
  // import the styles
  import '@riophae/vue-treeselect/dist/vue-treeselect.css'

    export default {
        name: "Allocation_contentForm.vue",
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
                e.units.forEach(function (e1) {
                    e1.label = 'Unit' + ' ' + e1.position +' : '+e1.unit_name;
                })
            })
            console.log(course);
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
                        children:rec.units,

                    }
                })

                return {
                    'id':rec.id,
                    'label': rec.course_name,
                    'units':unitAll,
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
                    'children':res.children
                }
            })
                return {
                    allCourses:allCourses,
                    breadcrumbs: [
                        {
                            title: 'Resource management'
                        },
                        {
                            title: 'Resource allocation',
                            url:'/xadmin/allocation_contents/index'

                        },
                        {
                            title: 'Create new allocation'
                        },
                    ],
                total_school:[],
                total_course:[],
                total_unit:[],
                value: [],
                courses:courseTreeselect,
                schools:$json.schools||{},
                units:unitTreeselect,
                entry: $json.entry || {},
                isLoading: false,
                errors: {},
                newTotalCourse:[],
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

            backIndex(){
                window.location.href = '/xadmin/allocation_contents/index';
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/allocation_contents/save', {entry: this.entry,total_school:this.total_school,total_course:this.total_course,unit:this.courses,total_unit:this.total_unit}, false);
                this.isLoading = false;
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

