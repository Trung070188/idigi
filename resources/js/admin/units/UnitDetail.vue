<template>
    <div class="container-fluid">
        <ActionBar type="index" :breadcrumbs="breadcrumbs" title="Unit detail" />

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="delete" tabindex="-1"
                        role="dialog" aria-hidden="true">
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
                                        class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal"
                                        style="margin: 0px 8px 0px">No, cancel
                                    </button>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-header border-0">
                        <div class="card-title"></div>
                        <div class="card-toolbar">
                            <button class="btn btn-danger" @click="deleteUnit(entry)"
                                :disabled="!permissionFields['unit_delete']">Delete unit<i
                                    class="bi bi-trash ml-1"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <input v-model="entry.id" type="hidden" name="id" value="">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label>Unit name <span class="text-danger">*</span></label>
                                <input class="form-control nospace" placeholder="Enter the unit name"
                                    v-model="entry.unit_name" :disabled="!permissionFields['unit_name']">
                                <error-label for="f_category_id" :errors="errors.unit_name"></error-label>
                            </div>
                            <div class="form-group col-sm-3">
                                <label>Unit ID<span class="text-danger">*</span></label>
                                <input class="form-control" v-model="entry.id" disabled />
                            </div>
                            <div class="form-group col-sm-3">
                                <label>Subject<span class="text-danger">*</span></label>
                                <select class="form-control form-select" required v-model="entry.subject"
                                    @change="changeSubject" :disabled="!permissionFields['unit_subject']">
                                    <option value="" disabled selected>Choose the subject</option>
                                    <option value="Math">Math</option>
                                    <option value="Science">Science</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-9">
                                <label>Description </label>
                                <textarea class="form-control" placeholder="Your text here..." rows="5"
                                    v-model="entry.description" :disabled="!permissionFields['unit_description']"></textarea>
                                <error-label for="f_category_id" :errors="errors.description"></error-label>
                            </div>
                            <div class="form-group col-sm-3" v-if="entry.subject">
                                <label>Course</label>
                                <select class="form-select form-control" required v-model="entry.course_id" :disabled="!permissionFields['unit_course']">
                                    <option value="" disabled selected>Choose the course</option>
                                    <option v-for="course in courses" :value="course.id">
                                        {{ course.course_name }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12" v-if="entry.subject">
                                <div class="border rounded-3 p-5">
                                    <div class="d-flex justify-content-between mb-5">
                                        <label>List of lesson</label>
                                    </div>
                                    <treeselect :options="lessons" :multiple="true" :valueFormat="'object'"
                                        :disabled="!permissionFields['unit_lessons_list']" v-model="listLesson" />
                                    <draggable :list="listLesson" :animation="200" ghost-class="moving-card" group="users"
                                        filter=".action-button" class="form-group col-sm-12 mt-5" tag="div">
                                        <div class="form-row justify-content-center cursor-move" title="Drag to move"
                                            v-for="(res, index) in listLesson" :key="index">
                                            <div class="form-group col-md-1 d-flex align-items-center justify-content-end">
                                                <i class="bi bi-text-center mt-6"></i><span
                                                    class="font-size-h1 mt-6 mx-5">{{ index + 1 }}</span>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="unit_name">Lesson name:</label>
                                                <input type="text" class="form-control" id="unit_name" v-model="res.label"
                                                    disabled>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="unit_id">Lesson ID:</label>
                                                <input type="text" class="form-control" id="unit_id" v-model="res.id"
                                                    disabled>
                                            </div>
                                            <div
                                                class="form-group col-md-1 d-flex align-items-center justify-content-start">
                                                <i class="fa fa-times fa-2x mt-6 cursor-pointer"
                                                    @click="removeLesson(index)"
                                                    v-if="permissionFields['unit_lessons_list']" title="Remove lesson"></i>
                                            </div>
                                        </div>
                                    </draggable>
                                </div>
                            </div>
                        </div>
                        <div class="form-check form-check-custom form-check-solid pb-5">
                            <input id="state" type="checkbox" v-model="entry.active" class="form-check-input h-20px w-20px"
                                checked :disabled="!permissionFields['unit_active']">
                            <label for="state" class="form-check-label fw-bold">Active</label>
                            <error-label for="f_grade" :errors="errors.active"></error-label>
                        </div>
                        <div class="mt-5">
                            <button type="reset" @click="save()" class="btn btn-primary mr-3" :disabled="!permissions['056']"><i
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
import { $get, $post, clone } from "../../utils";
import ActionBar from "../includes/ActionBar";
import draggable from "vuedraggable";
import $router from "../../lib/SimpleRouter";
import Treeselect from '@riophae/vue-treeselect';
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

export default {
    name: "UnitDetail.vue",
    components: { ActionBar, draggable, Treeselect },
    data() {
        let listLesson = [];
        let jsonData = $json.jsonData;
        let entry = jsonData.entry;
        const permissions = clone(window.$permissions)

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
            permissionFields: jsonData.permissionFields || [],
            permissions,
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
            entry: jsonData.entry || {},
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
            const res = await $post('/xadmin/units/save', {
                entry: this.entry,
                list: this.listLesson
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

                if (!this.entry.id) {
                    location.replace('/xadmin/units/edit?id=' + res.id);
                }

            }
        },
        async remove(entry) {
            const res = await $post('/xadmin/units/remove', { id: entry.id });

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
