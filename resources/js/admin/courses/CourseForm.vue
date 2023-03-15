<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"  title = "Create new course"/>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column">
                        <div class="row">
                            <div class=" col-sm-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group col-sm-9">
                                        <label>Course name <span class="text-danger">*</span></label>
                                        <input class="form-control nospace" placeholder="Enter the course name" v-model="entry.course_name" >
                                        <error-label  for="f_category_id" :errors="errors.course_name"></error-label>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label>Subject<span class="text-danger">*</span></label>
                                        <select class="form-control form-select" v-model="entry.subject" required @change="changeSubject">
                                            <option value="" disabled selected>Choose the subject</option>
                                            <option value="Math">Math</option>
                                            <option value="Science">Science </option>
                                        </select>
                                        <error-label  for="f_category_id" :errors="errors.subject"></error-label>

                                    </div>
                                    <div class="form-group col-sm-9">
                                        <label>Description </label>
                                        <textarea class="form-control"  placeholder="Your text here..." rows="5" v-model="entry.description"></textarea>
                                        <error-label for="f_category_id" :errors="errors.description"></error-label>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label>Grade <span class="text-danger">*</span></label>
                                        <select class="form-select form-control" v-model="entry.grade" required>
                                            <option value="" disabled selected>Choose the grade</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                        </select>
                                        <error-label  for="f_category_id" :errors="errors.grade"></error-label>

                                    </div>
                                    <div class="form-group col-sm-12"  style="border: 1px solid #b5b5c3;border-radius: 25px" v-if="entry.subject">
                                        <label style="margin:15px 0px 10px ">List of unit</label>
                                        <Treeselect :options="units" :multiple="true" :valueFormat="'object'" v-model="listUnit"  placeholder="Search unit"/>
                                        <draggable
                                            :list="listUnit"
                                            :animation="200"
                                            ghost-class="moving-card"
                                            group="users"
                                            filter=".action-button"
                                            class="form-group col-sm-12"
                                            tag="ul"
                                        >
                                            <div style="width: 100%;cursor: pointer" v-for="(res,index) in listUnit" :key="index">
                                                <i class="bi bi-text-center" style="width: 5%; display: inline-block"></i>
                                                <div style="width: 5%;display: inline-block;position: relative;left: -17px;font-size: 20px">{{index+1}}</div>
                                                <div style="width: 50%;display: inline-block;margin-left: -50px">
                                                    <span>Unit name:</span>
                                                    <input class="form-control" v-model="res.label" disabled>
                                                </div>
                                                <div style="width: 30%;display: inline-block;margin-left: 20px">
                                                    <span>Unit ID:</span>
                                                    <input class="form-control" v-model="res.id" disabled>
                                                </div>
                                                <i style="width: 10%;
                                                display: inline-block;
                                                font-size: 50px;
                                                top: 14px;
                                                position: relative;
                                                left: -10px;
                                                cursor: pointer" class="bi bi-x" @click="removeUnit(index)"></i>
                                            </div>

                                        </draggable>
                                    </div>
                                </div>
                                <div class="form-check form-check-custom form-check-solid pb-5">
                                    <input id="state" type="checkbox" v-model="entry.active" class="form-check-input h-20px w-20px" >
                                    <label for="state" class="form-check-label fw-bold">Active</label>
                                    <error-label for="f_grade" :errors="errors.active"></error-label>
                                </div>
                            </div>
                        </div>
                        <!--<hr style="margin: 0px 0px 16px;">-->
                        <div class="mt-5">
                            <button type="reset" @click="save()"  class="btn btn-primary mr-3" ><i class="bi bi-send mr-1"></i>Save</button>
                            <button type="reset" @click="backIndex()" class="btn btn-light">Cancel</button>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</template>

<script>
    import {$get, $post} from "../../utils";
    import ActionBar from "../includes/ActionBar";
    import draggable from "vuedraggable";
    import Treeselect from '@riophae/vue-treeselect';
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'
    import $router from "../../lib/SimpleRouter";

    export default {
        name: "CoursesForm.vue",
        components: {ActionBar,draggable,Treeselect},
        data() {
            return {
                list:[],
                listUnit:[],
                allUnits:[],
                units:[],
                breadcrumbs: [
                    {
                        title: 'Resource management',
                    },
                    {
                        title: 'Course',
                        url: '/xadmin/courses/index',
                    },
                    {
                        title:'Create new course',
                    },
                ],
                entry:{
                    'subject':'',
                    'grade':''
                },
                isLoading: false,
                errors: {}
            }
        },
        mounted() {
            $router.on("/", this.load).init();
        },
        methods: {
            removeUnit(index)
            {
                this.listUnit=this.listUnit.filter((item,key)=>key!==index);
            },
            changeSubject(){
                this.units = this.allUnits.filter(e => e.subject ==  this.entry.subject);
                this.listUnit = [];
            },

            async load() {
                this.$loading(true);
                const res = await $get("/xadmin/units/getUnits");
                let units = res.filter(e =>!e.course_id);
                this.allUnits = units.map(function(e){

                    return {
                        id:e.id,
                        label:e.unit_name,
                        subject:e.subject,
                        course_id:e.course_id,
                    }
                });
                this.units = this.allUnits.filter(e => e.subject ==  this.entry.subject && !e.course_id);

                this.$loading(false);

            },
            backIndex(){
                window.location.href = '/xadmin/courses/index';
            },
            async save() {
                this.$loading(true);
                const res = await $post('/xadmin/courses/save', {entry: this.entry,units:this.listUnit}, false);
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
                        location.replace('/xadmin/courses/edit?id=' + res.id);
                    }

                }
            }
        }
    }
</script>

<style scoped>
    select:required:invalid {
        color: #adadad;
    }
    option[value=""][disabled] {
        display: none;
    }
    option {
        color: black;
    }

</style>
