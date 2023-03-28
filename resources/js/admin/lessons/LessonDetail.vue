<template>
    <div class="container-fluid">
        <ActionBar type="index" :breadcrumbs="breadcrumbs" title="Lesson details" />

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
                                <p>Are you sure to delete this lesson?</p>
                            </div>
                            <div class="swal2-actions">
                                <button type="submit" id="kt_modal_new_target_submit"
                                    class="swal2-confirm btn fw-bold btn-danger" @click="remove(entry)">
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
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-6" v-if="entry.id">
                        <div class="card-title"></div>
                        <div class="card-toolbar">
                            <button class="btn btn-danger" @click="deleteLesson(entry)"
                                :disabled="!permissionFields['lesson_delete']">
                                Delete lesson <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <input v-model="entry.id" type="hidden" name="id" value="">
                        <div class="row">
                            <div class=" col-sm-12">
                                <div class="form-group col-sm-6">
                                    <label>Lesson name <span class="text-danger">*</span></label>
                                    <input class="form-control nospace" placeholder="Enter the unit name"
                                        v-model="entry.name" :disabled="!permissionFields['lesson_name']">
                                    <error-label for="f_category_id" :errors="errors.name"></error-label>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label>Lesson ID <span class="text-danger">*</span></label>
                                    <input class="form-control " v-model="entry.id" disabled>
                                    <error-label for="f_category_id" :errors="errors.id"></error-label>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label>Subject<span class="text-danger">*</span></label>
                                    <select class="form-control form-select" v-model="entry.subject" required
                                        @change="changeSubject" :disabled="!permissionFields['lesson_subject']">
                                        <option value="" disabled selected>Choose the subject</option>
                                        <option value="Math">Math</option>
                                        <option value="Science">Science</option>
                                    </select>
                                    <error-label for="f_category_id" :errors="errors.subject"></error-label>

                                </div>
                                <div class="form-group col-sm-9">
                                    <label>Description </label>
                                    <textarea class="form-control" placeholder="Your text here..." rows="5"
                                        v-model="entry.description"
                                        :disabled="!permissionFields['lesson_description']"></textarea>
                                    <error-label for="f_category_id" :errors="errors.description"></error-label>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label>Unit </label>
                                    <select class="form-select form-control" required v-model="entry.unit_id"
                                        @change="changeUnit" :disabled="!permissionFields['lesson_unit']">
                                        <option value="" disabled selected>Choose the unit</option>
                                        <option v-for="unit in units" :value="unit.id">{{ unit.unit_name }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12" v-if="entry.subject">
                                    <div class="border rounded-3 p-5">
                                        <div class="d-flex justify-content-between">
                                            <label>List of modules</label>
                                            <div class="form-group col-md-3 p-0">
                                                <select class="form-control form-select" required v-model="filter.type"
                                                    @change="changeType"
                                                    :disabled="!permissionFields['lesson_modules_list']">
                                                    <option value="" disabled selected>Choose the type</option>
                                                    <option value="Vocabulary">Vocabulary</option>
                                                    <option value="Summary">Summary</option>
                                                    <option value="Practice">Practice</option>
                                                    <option value="Summary">Summary</option>
                                                </select>
                                            </div>
                                        </div>
                                        <Treeselect :options="modules" :async="true" :defaultOptions="modules"
                                            :multiple="true" :cacheOptions="false" :valueFormat="'object'"
                                            v-model="listResource" :load-options="handleSearchChange"
                                            :disabled="!permissionFields['lesson_modules_list']" />
                                        <draggable :list="listResource" :animation="200" ghost-class="moving-card"
                                            group="users" filter=".action-button" class="form-group col-sm-12 mt-5"
                                            tag="div">
                                            <div class="form-row justify-content-center cursor-move" title="Drag to move"
                                                v-for="(res, index) in listResource" :key="index">
                                                <div
                                                    class="form-group col-md-1 d-flex align-items-center justify-content-end">
                                                    <i class="bi bi-text-center mt-6"></i>
                                                    <span class="font-size-h1 mt-6 mx-5">{{ index + 1 }}</span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Resource name:</label>
                                                    <a :href="'/xadmin/inventories/edit?id=' + res.id" target="_blank">
                                                        <input type="text" class="form-control cursor-pointer"
                                                            v-model="res.label" title="Click to see detail" disabled>
                                                    </a>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Type:</label>
                                                    <input type="text" class="form-control" id="unit_id" v-model="res.type"
                                                        disabled>
                                                </div>
                                                <div
                                                    class="form-group col-md-1 d-flex align-items-center justify-content-start">
                                                    <i class="fa fa-times fa-2x mt-6 cursor-pointer"
                                                        @click="removeResource(index)"
                                                        v-if="permissionFields['lesson_modules_list']"
                                                        title="Remove module"></i>
                                                </div>
                                            </div>
                                        </draggable>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-check form-check-custom form-check-solid pb-5">
                            <input id="state" type="checkbox" v-model="entry.enabled" class="form-check-input h-20px w-20px"
                                checked :disabled="!permissionFields['lesson_active']">
                            <label for="state" class="form-check-label fw-bold">Active</label>
                            <error-label for="f_grade" :errors="errors.enabled"></error-label>
                        </div>
                        <div class="mt-5">
                            <button type="reset" @click="save()" class="btn btn-primary mr-3"
                                :disabled="!permissions['068']">
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
import { $get, $post, getTimeRangeAll, clone } from "../../utils";
import ActionBar from "../includes/ActionBar";
import draggable from "vuedraggable";
import $router from "../../lib/SimpleRouter";
import Treeselect from '@riophae/vue-treeselect';
import '@riophae/vue-treeselect/dist/vue-treeselect.css'


let created = getTimeRangeAll();
const $q = $router.getQuery();
export default {
    name: "LessonDetail.vue",
    components: { ActionBar, draggable, Treeselect },
    data() {
        let modules = [];
        let jsonData = $json.jsonData;
        let entry = jsonData.entry;
        const permissions = clone(window.$permissions)
        let filter = {
            type: $q.type || "",
        };
        if (entry.inventories) {
            entry.inventories.forEach(function (e) {
                modules.push({
                    id: e.id,
                    label: e.name,
                    type: e.type,
                })

            })
        }

        return {
            lessonUnit: '',
            units: [],
            permissionFields: jsonData.permissionFields || [],
            permissions,
            listResource: modules,
            filter: filter,
            module_type: '',
            modules,
            list: [],
            breadcrumbs: [
                {
                    title: 'Resource management',
                },
                {
                    title: 'Lesson',
                    url: '/xadmin/lessons/index',
                },
                {
                    title: 'Lesson details',
                },
            ],
            searchLimit: 50,
            entry: jsonData.entry || {},
            isLoading: false,
            errors: {}
        }
    },
    mounted() {

        this.getUnits();
    },
    methods: {
        deleteLesson: function (entry = '') {
            $('#delete').modal('show');
            this.deleteCour = entry;
        },
        async handleSearchChange({ action, searchQuery, callback }) {

            const res = await $get("/xadmin/lessons/getModules", { subject: this.entry.subject, type: this.filter.type, keyword: searchQuery });
            callback(null, res)

        },

        removeResource(index) {
            this.listResource = this.listResource.filter((item, key) => key !== index);
        },


        changeSubject() {
            this.modules = [];
            this.listResource = [];
            this.entry.unit_id = null;
            this.units = this.allUnit.filter(e => e.subject == this.entry.subject);

        },
        changeUnit() {
            this.modules = [];
            this.listResource = [];
        },
        changeType() {
            this.modules = [];
            this.listResource = [];
        },
        async getUnits() {
            this.$loading(true);
            const res = await $get("/xadmin/units/getUnits");
            this.allUnit = res;
            this.units = this.allUnit.filter(e => e.subject == this.entry.subject);
            this.$loading(false);

        },

        backIndex() {
            window.location.href = '/xadmin/lessons/index';
        },
        async save() {
            this.isLoading = true;
            const res = await $post('/xadmin/lessons/save', {
                entry: this.entry,
                inventory: this.listResource,
                lessonUnit: this.lessonUnit
            }, false);
            this.isLoading = false;
            if (res.errors) {
                this.errors = res.errors;
                return;
            }
            if (res.code) {
                toastr.error(res.message);
            } else {
                this.errors = {};
                toastr.success(res.message);
            }
        },
        async remove(entry) {
            const res = await $post('/xadmin/lessons/remove', { id: entry.id });

            if (res.code) {
                toastr.error(res.message);
            } else {
                toastr.success(res.message);
                $('#delete').modal('hide');
                window.location.href = '/xadmin/lessons/index';
            }

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
