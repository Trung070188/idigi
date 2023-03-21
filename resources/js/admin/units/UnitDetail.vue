<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title="Unit detail"/>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="delete"
                         tabindex="-1" role="dialog"
                         aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                             style="max-width: 450px;">
                            <div class="modal-content box-shadow-main paymment-status"
                                 style="left:120px;text-align: center; padding: 20px 0px 55px;">
                                <div class="close-popup" data-dismiss="modal"></div>
                                <div class="swal2-icon swal2-warning swal2-icon-show">
                                    <div class="swal2-icon-content" style="margin: 0px 24.5px 0px ">!</div>
                                </div>
                                <div class="swal2-html-container">
                                    <p>Are you sure to delete this unit?</p>
                                </div>
                                <div class="swal2-actions">
                                    <button type="submit" id="kt_modal_new_target_submit"
                                            class="swal2-confirm btn fw-bold btn-danger" @click="remove(deleteUni)">
                                        <span class="indicator-label">Yes, delete!</span>
                                    </button>
                                    <button type="reset" id="kt_modal_new_target_cancel"
                                            class="swal2-cancel btn fw-bold btn-active-light-primary"
                                            data-bs-dismiss="modal" style="margin: 0px 8px 0px">No, cancel
                                    </button>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-header border-0 pt-6" style="margin:0px 0px -35px">
                        <div class="card-title"></div>
                        <div class="card-toolbar" @click="deleteUnit(entry)" style="z-index: 1">
                            <button class="btn btn-danger">Delete unit<i class="bi bi-trash ml-1"></i></button>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="row">
                            <div class=" col-sm-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Unit name <span class="text-danger">*</span></label>
                                        <input class="form-control nospace" placeholder="Enter the unit name"
                                               v-model="entry.unit_name">
                                        <error-label for="f_category_id" :errors="errors.unit_name"></error-label>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label>Unit ID<span class="text-danger">*</span></label>
                                        <input class="form-control" v-model="entry.id" disabled/>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label>Subject<span class="text-danger">*</span></label>
                                        <select class="form-control form-select" required v-model="entry.subject"
                                                @change="changeSubject">
                                            <option value="" disabled selected>Choose the subject</option>
                                            <option value="Math">Math</option>
                                            <option value="Science">Science</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-9">
                                        <label>Description </label>
                                        <textarea class="form-control" placeholder="Your text here..." rows="5"
                                                  v-model="entry.description"></textarea>
                                        <error-label for="f_category_id" :errors="errors.description"></error-label>
                                    </div>
                                    <div class="form-group col-sm-3" v-if="entry.subject">
                                        <label>Course</label>
                                        <select class="form-select form-control" required v-model="entry.course_id">
                                            <option value="" disabled selected>Choose the course</option>
                                            <option v-for="course in courses" :value="course.id">
                                                {{ course.course_name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-12"
                                         style="border: 1px solid #b5b5c3;border-radius: 25px" v-if="entry.subject">
                                        <label style="margin:15px 0px 10px ">List of lesson</label>
                                        <treeselect :options="lessons" :valueFormat="'object'" :multiple="true"
                                                    v-model="listLesson"/>
                                        <draggable
                                            :list="listLesson"
                                            :animation="200"
                                            ghost-class="moving-card"
                                            group="users"
                                            filter=".action-button"
                                            class="form-group col-sm-12"
                                            tag="ul"
                                        >
                                            <div style="width: 100%;cursor: pointer" v-for="(res,index) in listLesson"
                                                 :key="index">
                                                <i class="bi bi-text-center"
                                                   style="width: 10%; display: inline-block"></i>
                                                <div style="width: 50%;display: inline-block;margin-left: -75px">
                                                    <span>Lesson name:</span>
                                                    <input class="form-control" v-model="res.label" disabled>
                                                </div>
                                                <div style="width: 30%;display: inline-block;margin-left: 20px">
                                                    <span>Lesson ID:</span>
                                                    <input class="form-control" v-model="res.id" disabled>
                                                </div>
                                                <i style="width: 10%;
                                                display: inline-block;
                                                font-size: 50px;
                                                top: 14px;
                                                position: relative;
                                                left: -10px;
                                                cursor: pointer" class="bi bi-x" @click="removeLesson(index)"></i>
                                            </div>

                                        </draggable>
                                    </div>


                                </div>
                                <div class="form-check form-check-custom form-check-solid pb-5">
                                    <input id="state" type="checkbox" v-model="entry.active"
                                           class="form-check-input h-20px w-20px" checked>
                                    <label for="state" class="form-check-label fw-bold">Active</label>
                                    <error-label for="f_grade" :errors="errors.active"></error-label>
                                </div>
                            </div>
                        </div>
                        <!--<hr style="margin: 0px 0px 16px;">-->
                        <div class="mt-5">
                            <button type="reset" @click="save()" class="btn btn-primary mr-3"><i
                                class="bi bi-send mr-1"></i>Save
                            </button>
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
import $router from "../../lib/SimpleRouter";
import Treeselect from '@riophae/vue-treeselect';
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

export default {
    name: "UnitDetail.vue",
    components: {ActionBar, draggable, Treeselect},
    data() {
        let listLesson = [];
        let entry = $json.entry;

        if (entry.lessons) {
            entry.lessons.forEach(function (e) {
                listLesson.push({
                    id: e.id,
                    label: e.name,
                })
            })
        }
        return {
            deleteUni: '',
            allCourse: [],
            allLesson: [],
            courses: [],
            listLesson,
            lessons: [],
            list: [],
            breadcrumbs: [
                {
                    title: 'Resource management',
                },
                {
                    title: 'Unit',
                    url: '/xadmin/units/index',
                },
                {
                    title: 'Unit detail',
                },
            ],
            entry: $json.entry || {},
            isLoading: false,
            errors: {}
        }
    },
    mounted() {
        this.getAllData();

    },
    methods: {
        deleteUnit(entry) {
            $('#delete').modal('show');
            this.deleteUni = entry
        },
        changeSubject() {
            this.courses = this.allCourse.filter(e => e.subject == this.entry.subject);
            this.listLesson = [];
            this.lessons = this.allLesson.filter(e => e.subject == this.entry.subject);
        },
        async getAllData() {
            this.$loading(true);
            const courses = await $get("/xadmin/courses/getCourses");
            this.allCourse = courses;
            this.courses = this.allCourse.filter(e => e.subject == this.entry.subject);

            const lessons = await $get("/xadmin/lessons/getLessons");
            this.allLesson = lessons.filter(e => !e.unit_id);
            this.lessons = this.allLesson.filter(e => e.subject == this.entry.subject);
            this.$loading(false);
        },


        removeLesson(index) {
            this.listLesson = this.listLesson.filter((item, key) => key !== index);

        },

        backIndex() {
            window.location.href = '/xadmin/units/index';
        },
        async save() {
            this.$loading(true);
            const res = await $post('/xadmin/units/save', {entry: this.entry, list: this.listLesson}, false);
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
                    location.replace('/xadmin/units/edit?id=' + res.id);
                }

            }
        },
        async remove(entry) {
            const res = await $post('/xadmin/units/remove', {id: entry.id});

            if (res.code) {
                toastr.error(res.message);
            } else {
                toastr.success(res.message);
                $('#delete').modal('hide');
            }
            location.replace('/xadmin/units/index');
        },
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
