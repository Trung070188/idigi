<template>
    <div class="container-fluid">
        <ActionBar type="index" :breadcrumbs="breadcrumbs" :title="title" />
        <div class="row">
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
                            <p>Are you sure to delete this module?</p>
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
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-6" v-if="entry.id">
                        <div class="card-title"></div>
                        <div class="card-toolbar">
                            <button class="btn btn-danger" @click="deleteModule(entry)"
                                :disabled="!permissionFields['module_delete']">
                                Delete module <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <input v-model="entry.id" type="hidden" name="id" value="">
                        <div class="form-row">
                            <div class="form-group col-lg-9 col-sm-12">
                                <label>Module name <span class="text-danger">*</span></label>
                                <input v-model="entry.name" class="form-control"
                                    :disabled="!permissionFields['module_name']" placeholder="Enter the module name">
                                <error-label for="f_grade" :errors="errors.name"></error-label>
                            </div>
                            <div class="form-group col-lg-3 col-sm-12">
                                <label>Type <span class="text-danger">*</span></label>
                                <select class="form-control form-select" v-model="entry.type"
                                    :disabled="!permissionFields['module_type']" required>
                                    <option value="" selected disabled>Choose type</option>
                                    <option value="Vocabulary">Vocabulary</option>
                                    <option value="Lecture">Lecture</option>
                                    <option value="Practice">Practice</option>
                                    <option value="Summary">Summary</option>
                                </select>
                                <error-label for="f_category_id" :errors="errors.type"></error-label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-lg-9 col-sm-12">
                                <label>Description</label>
                                <textarea v-model="entry.description" rows="5" class="form-control"
                                    :disabled="!permissionFields['module_description']"
                                    placeholder="Your text here..."></textarea>
                                <error-label for="f_grade" :errors="errors.description"></error-label>
                            </div>
                            <div class="form-group col-lg-3 col-sm-12" v-if="permissionFields['module_file_asset_bundle']">
                                <label>File asset bundle<span class="text-danger">*</span></label>
                                <file-manager-input v-model="entry.file_asset_new"></file-manager-input>
                                <error-label for="f_title" :errors="errors.file_asset_new"></error-label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" @click="location"
                                :disabled="!permissionFields['module_location']">Location</button>
                        </div>
                        <div class="form-group col-lg-9 col-sm-12 border rounded-3 p-5" v-if="entry.location == 1">
                            <div class="form-row justify-content-center">
                                <div class="form-group col-md-5">
                                    <label>Subject</label>
                                    <select class="form-control form-select " required v-model="entry.subject"
                                        @change="changeSubject()" :disabled="!permissionFields['module_subject']">
                                        <option value="" selected disabled>Choose subject</option>
                                        <option value="Math">Math</option>
                                        <option value="Science">Science</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Lesson<span class="text-danger">*</span></label>
                                    <select class="form-control form-select" v-model="entry.lessonId" required :disabled="!permissionFields['module_lesson']">
                                        <option value="" selected disabled>Choose lesson</option>
                                        <option v-for="lesson in lessons" :value="lesson.id">{{ lesson.label }}</option>
                                    </select>
                                    <error-label for="f_grade" :errors="errors.lessonId"></error-label>
                                </div>
                                <div class="form-group col-md-auto d-flex align-items-center justify-content-start">
                                    <i class="fa fa-times fa-2x mt-6 cursor-pointer" @click="removeLesson"
                                        title="Remove" v-if="permissionFields['module_location']"></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-check form-check-custom form-check-solid me-10 pb-5 mt-2">
                                <input id="enabled" type="checkbox" class="form-check-input h-20px w-20px"
                                    v-model="entry.enabled" :disabled="!permissionFields['module_active']">
                                <label for="enabled" class="form-check-label fw-bold">Active</label>
                                <error-label for="f_grade" :errors="errors.enabled"></error-label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="reset" @click="save()" class="btn btn-primary mr-2" :disabled="!permissions['008']">
                            <i class="bi bi-save2 mr-1"></i>Save</button>
                        <button type="reset" @click="backIndex()" class="btn btn-light">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { $get, $post, clone } from "../../utils";
import ActionBar from "../includes/ActionBar";
//import UploadFileComponent from "../../components/UploadFileComponent";
import FileManagerInput from "../../components/FileManagerInput";
import SwitchButton from "../../components/SwitchButton";
import $router from "../../lib/SimpleRouter";

export default {
    name: "InventoriesForm.vue",
    components: { ActionBar, SwitchButton, FileManagerInput },
    data() {
        const permissions = clone(window.$permissions)

        return {

            types: [

            ],
            breadcrumbs: [
                {
                    title: 'Resource management',
                },
                {
                    title: 'Modules',
                    url: '/xadmin/inventories/index',
                },
                {
                    title: $json.entry ? 'Module details' : 'Create new module',
                },
            ],
            title: $json.entry ? 'Module details' : 'Create new module',
            entry: $json.entry || {
                'type': '',
                'location': 0,
            },
            lessonId: '',
            subject: '',
            lessons: [],
            allLesson: [],
            permissionFields: $json.permissionFields || [],
            permissions,
            isLoading: false,
            errors: {},
            check: 1,
        }
    },
    mounted() {
        $router.on("/", this.data).init();
    },
    methods: {
        deleteModule: function (entry = '') {
            $('#delete').modal('show');
            this.deleteCour = entry;
        },
        removeLesson() {
            this.entry.location = 0;
            this.subject = '';
            this.lessonId = '';
            this.errors.lessonId = '';
        },
        filterSubject(lessonId) {
            this.check = 1;
            if (this.check == 1) {
                let filter = this.lessons.find(function (e) {
                    if (e.id == lessonId) {
                        return e
                    }
                })
                this.subject = filter.subject;
                return this.subject;
            }
            else {
                return this.subject
            }

        },
        changeSubject() {

            this.entry.lessonId = null;
            this.lessons = this.allLesson.filter(e => e.subject == this.entry.subject);
        },
        async data() {
            this.check = 0;
            this.$loading(true);
            const res = await $get('/xadmin/lessons/getLessons');
            this.allLesson = res;
            console.log(this.entry.subject)

            if (this.entry.subject) {
                this.lessons = this.allLesson.filter(e => e.subject == this.entry.subject);
            } else {
                this.lessons = this.allLesson;
            }

            this.$loading(false);

        },
        location() {
            this.entry.location = 1;
        },
        backIndex() {
            window.location.href = '/xadmin/inventories/index';
        },
        async save() {
            if (this.entry.id) {
                this.$loading(true);
            }

            const res = await $post('/xadmin/inventories/save', {
                entry: this.entry,
                lessonId: this.lessonId,
                subject: this.subject
            }, false);

            if (this.entry.id) {
                this.$loading(false);
            }
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
                    //location.replace('/xadmin/inventories/edit?id=' + res.id);
                    location.replace('/xadmin/inventories/index');
                }

            }
        },
        async remove(entry) {
            const res = await $post('/xadmin/inventories/remove', { id: entry.id });

            if (res.code) {
                toastr.error(res.message);
            } else {
                toastr.success(res.message);
                $('#delete').modal('hide');
                window.location.href = '/xadmin/inventories/index';
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
