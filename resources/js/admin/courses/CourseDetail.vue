<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"  title = "Course details"/>

        <div class="row">
            <div class="col-lg-12">
                <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="delete" tabindex="-1" role="dialog"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                         style="max-width: 450px;">
                        <div class="modal-content box-shadow-main paymment-status" style="left:120px;text-align: center; padding: 20px 0px 55px;">
                            <div class="close-popup" data-dismiss="modal"></div>
                            <div class="swal2-icon swal2-warning swal2-icon-show">
                                <div class="swal2-icon-content" style="margin: 0px 24.5px 0px ">!</div>
                            </div>
                            <div class="swal2-html-container">
                                <p >Are you sure to delete this course?</p>
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

                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-6" style="margin:0px 0px -35px">
                        <div class="card-title"></div>
                        <div class="card-toolbar" @click="deleteCourse(entry)" style="z-index: 1">
                            <button  class="btn btn-danger">
                                Delete course <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="row">
                            <div class=" col-sm-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Course name <span class="text-danger">*</span></label>
                                        <input class="form-control nospace" placeholder="Enter the course name" v-model="entry.course_name" >
                                        <error-label  for="f_category_id" :errors="errors.course_name"></error-label>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label>Course ID<span class="text-danger">*</span></label>
                                        <input class="form-control" v-model="entry.id" disabled>
                                        <error-label  for="f_category_id" :errors="errors.subject"></error-label>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label>Subject<span class="text-danger">*</span></label>
                                        <select class="form-control form-select" v-model="entry.subject" @change="changeSubject">
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
                                        <select class="form-select form-control" v-model="entry.grade">
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
                                        <Treeselect :options="units" :multiple="true" :valueFormat="'object'" v-model="listUnit"   placeholder="Search unit"/>
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
                                                cursor: pointer" class="bi bi-x" @click="removeUnit(index,res.id)"></i>
                                            </div>

                                        </draggable>
                                    </div>
                                </div>
                                <div class="form-check form-check-custom form-check-solid pb-5">
                                    <input id="state" type="checkbox" v-model="entry.active" class="form-check-input h-20px w-20px" checked>
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
        name: "CourseDetail.vue",
        components: {ActionBar,draggable,Treeselect},
        data() {
            let listUnit = [];
            let entry =  $json.entry;

            if(entry.unit){
                entry.unit.forEach(function (e){
                    listUnit.push({
                        id:e.id,
                        label:e.unit_name
                    })
                })
            }

            return {
                deleteUnit:[],
                list:[],
                listUnit,
                units:listUnit,
                allUnits:[],
                breadcrumbs: [
                    {
                        title: 'Resource management',
                    },
                    {
                        title: 'Course',
                        url: '/xadmin/courses/index',
                    },
                    {
                        title:'Course details',
                    },
                ],
                entry: $json.entry || {},
                isLoading: false,
                errors: {}
            }
        },
        mounted() {
            this.load();
        },
        methods: {
            changeSubject(){
                this.units = this.allUnits.filter(e => e.subject ==  this.entry.subject);
                this.listUnit = [];
            },
            deleteCourse:function(entry='')
            {
                $('#delete').modal('show');
                this.deleteCour=entry;
            },
            removeUnit(index,id)
            {
                this.listUnit=this.listUnit.filter((item,key)=>key!==index);
            },

            async load() {
                this.$loading(true);
                const res = await $get("/xadmin/units/getUnits");
                this.allUnits = res.map(function(e){
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
                    if (!this.entry.id) {
                        location.replace('/xadmin/courses/edit?id=' + res.id);
                    }
                    toastr.options.timeOut=1000;
                    toastr.options.preventDuplicates = true;
                    toastr.success(res.message);
                }
            },
            async remove(entry) {
                const res = await $post('/xadmin/courses/remove', {id: entry.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                    $('#delete').modal('hide');
                    window.location.href = '/xadmin/courses/index';
                }

            },

        }
    }
</script>

<style scoped>

</style>
