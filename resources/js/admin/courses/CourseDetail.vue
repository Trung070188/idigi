<template>
    <div class="container-fluid">
        <ActionBar type="index" :breadcrumbs="breadcrumbs" title="Course details" />

        <div class="row">
            <div class="col-lg-12">
                <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="delete" tabindex="-1"
                    role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered popup-main-1" role="document" style="max-width: 450px;">
                        <div class="modal-content box-shadow-main paymment-status"
                            style="left:120px;text-align: center; padding: 20px 0px 55px;">
                            <div class="close-popup" data-dismiss="modal"></div>
                            <div class="swal2-icon swal2-warning swal2-icon-show">
                                <div class="swal2-icon-content" style="margin: 0px 24.5px 0px ">!</div>
                            </div>
                            <div class="swal2-html-container">
                                <p>Are you sure to delete this course?</p>
                            </div>
                            <div class="swal2-actions">
                                <button type="submit" id="kt_modal_new_target_submit"
                                    class="swal2-confirm btn fw-bold btn-danger" @click="remove(entry)">
                                    <span class="indicator-label">Yes, delete!</span>
                                </button>
                                <button type="reset" id="kt_modal_new_target_cancel"
                                    class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal"
                                    style="margin: 0px 8px 0px">No, cancel</button>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-6" v-if="entry.id">
                        <div class="card-title"></div>
                        <div class="card-toolbar">
                            <button class="btn btn-danger" @click="deleteCourse(entry)"
                                :disabled="!permissionFields['course_delete']">
                                Delete course <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <input v-model="entry.id" type="hidden" name="id" value="">
                        <div class="row">
                            <div class=" col-sm-12">
                                <div class="form-group col-sm-6">
                                    <label>Course name <span class="text-danger">*</span></label>
                                    <input class="form-control nospace" placeholder="Enter the course name"
                                        v-model="entry.course_name" :disabled="!permissionFields['course_name']">
                                    <error-label for="f_category_id" :errors="errors.course_name"></error-label>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label>Course ID<span class="text-danger">*</span></label>
                                    <input class="form-control " v-model="entry.id" disabled>
                                    <error-label for="f_category_id" :errors="errors.subject"></error-label>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label>Subject<span class="text-danger">*</span></label>
                                    <select class="form-control form-select" v-model="entry.subject" @change="changeSubject"
                                        :disabled="!permissionFields['course_subject']">
                                        <option value="Math">Math</option>
                                        <option value="Science">Science </option>
                                    </select>
                                    <error-label for="f_category_id" :errors="errors.subject"></error-label>
                                </div>
                                <div class="form-group col-sm-9">
                                    <label>Description </label>
                                    <textarea class="form-control" placeholder="Your text here..." rows="5"
                                        v-model="entry.description"
                                        :disabled="!permissionFields['course_description']"></textarea>
                                    <error-label for="f_category_id" :errors="errors.description"></error-label>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label>Grade <span class="text-danger">*</span></label>
                                    <select class="form-select form-control" v-model="entry.grade"
                                        :disabled="!permissionFields['course_grade']">
                                        <option v-for="n in 9" :value="n">{{ n }}</option>
                                    </select>
                                    <error-label for="f_category_id" :errors="errors.grade"></error-label>
                                </div>
                                <div class="form-group col-sm-12" v-if="entry.subject">
                                    <div class="border rounded-3 p-5">
                                        <div class="d-flex justify-content-between mb-5">
                                            <label>List of modules</label>
                                        </div>
                                        <Treeselect :options="units" :multiple="true" :valueFormat="'object'"
                                            :disabled="!permissionFields['course_list_unit']" v-model="listUnit"
                                            placeholder="Search unit" />
                                        <draggable :list="listUnit" :animation="200" ghost-class="moving-card" group="users"
                                            filter=".action-button" class="form-group col-sm-12 mt-5" tag="div">
                                            <div class="form-row justify-content-center cursor-move" title="Drag to move"
                                                v-for="(res, index) in listUnit" :key="index">
                                                <div
                                                    class="form-group col-md-1 d-flex align-items-center justify-content-end">
                                                    <i class="bi bi-text-center mt-6"></i><span
                                                        class="font-size-h1 mt-6 mx-5">{{ index + 1 }}</span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="unit_name">Unit name:</label>
                                                    <input type="text" class="form-control" id="unit_name"
                                                        v-model="res.label" disabled>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="unit_id">Unit ID:</label>
                                                    <input type="text" class="form-control" id="unit_id" v-model="res.id"
                                                        disabled>
                                                </div>
                                                <div
                                                    class="form-group col-md-1 d-flex align-items-center justify-content-start">
                                                    <i class="fa fa-times fa-2x mt-6 cursor-pointer"
                                                        @click="removeUnit(index, res.id)"
                                                        v-if="permissionFields['course_list_unit']" title="Remove unit"></i>
                                                </div>
                                            </div>
                                        </draggable>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-check form-check-custom form-check-solid pb-5">
                            <input id="state" type="checkbox" v-model="entry.active" class="form-check-input h-20px w-20px"
                                checked>
                            <label for="state" class="form-check-label fw-bold">Active</label>
                            <error-label for="f_grade" :errors="errors.active"></error-label>
                        </div>
                        <div class="mt-5">
                            <button type="reset" @click="save()" class="btn btn-primary mr-3"
                                :disabled="!permissions['054']">
                                <i class="bi bi-send mr-1"></i>Save
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
import Treeselect from '@riophae/vue-treeselect';
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import $router from "../../lib/SimpleRouter";

export default {
    name: "CourseDetail.vue",
    components: { ActionBar, draggable, Treeselect },
    data() {
        let listUnit = [];
        let jsonData = $json.jsonData;
        let entry = jsonData.entry;
        const permissions = clone(window.$permissions)
        if (entry.unit) {
            entry.unit.forEach(function (e) {
                listUnit.push({
                    id: e.id,
                    label: e.unit_name
                })
            })
        }

        return {
            deleteUnit: [],
            permissionFields: jsonData.permissionFields || [],
            permissions,
            list: [],
            listUnit,
            units: listUnit,
            allUnits: [],
            breadcrumbs: [
                {
                    title: 'Resource management',
                },
                {
                    title: 'Course',
                    url: '/xadmin/courses/index',
                },
                {
                    title: 'Course details',
                },
            ],
            entry: jsonData.entry || {},
            isLoading: false,
            errors: {}
        }
    },
    mounted() {
        this.load();
    },
    methods: {
        changeSubject() {
            this.units = this.allUnits.filter(e => e.subject == this.entry.subject);
            this.listUnit = [];
        },
        deleteCourse: function (entry = '') {
            $('#delete').modal('show');
            this.deleteCour = entry;
        },
        removeUnit(index, id) {
            this.listUnit = this.listUnit.filter((item, key) => key !== index);
        },

        async load() {
            this.$loading(true);
            const res = await $get("/xadmin/units/getUnits");
            let units = res.filter(e => !e.course_id);
            this.allUnits = units.map(function (e) {
                return {
                    id: e.id,
                    label: e.unit_name,
                    subject: e.subject,
                    course_id: e.course_id,
                }
            });
            this.units = this.allUnits.filter(e => e.subject == this.entry.subject);

            this.$loading(false);

        },
        backIndex() {
            window.location.href = '/xadmin/courses/index';
        },
        async save() {
            this.$loading(true);
            const res = await $post('/xadmin/courses/save', {
                entry: this.entry,
                units: this.listUnit,
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
                if (!this.entry.id) {
                    location.replace('/xadmin/courses/edit?id=' + res.id);
                }
                toastr.options.timeOut = 1000;
                toastr.options.preventDuplicates = true;
                toastr.success(res.message);
            }
        },
        async remove(entry) {
            const res = await $post('/xadmin/courses/remove', { id: entry.id });

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

<style scoped></style>
