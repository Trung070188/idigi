<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   backUrl="/xadmin/lessons/index"
                   title="LessonForm"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column" >
                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                                                    <div class="form-group">
                                        <label>Created By</label>
                                        <input id="f_created_by" v-model="entry.created_by" name="name" class="form-control"
                                               placeholder="created_by" >
                                        <error-label for="f_created_by" :errors="errors.created_by"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Created Date</label>
                                        <input id="f_created_date" v-model="entry.created_date" name="name" class="form-control"
                                               placeholder="created_date" >
                                        <error-label for="f_created_date" :errors="errors.created_date"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Enabled</label>
                                        <input id="f_enabled" v-model="entry.enabled" name="name" class="form-control"
                                               placeholder="enabled" >
                                        <error-label for="f_enabled" :errors="errors.enabled"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Grade</label>
                                        <input id="f_grade" v-model="entry.grade" name="name" class="form-control"
                                               placeholder="grade" >
                                        <error-label for="f_grade" :errors="errors.grade"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Last Modified By</label>
                                        <input id="f_last_modified_by" v-model="entry.last_modified_by" name="name" class="form-control"
                                               placeholder="last_modified_by" >
                                        <error-label for="f_last_modified_by" :errors="errors.last_modified_by"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Last Modified Date</label>
                                        <input id="f_last_modified_date" v-model="entry.last_modified_date" name="name" class="form-control"
                                               placeholder="last_modified_date" >
                                        <error-label for="f_last_modified_date" :errors="errors.last_modified_date"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Name</label>
                                        <input id="f_name" v-model="entry.name" name="name" class="form-control"
                                               placeholder="name" >
                                        <error-label for="f_name" :errors="errors.name"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Rating</label>
                                        <input id="f_rating" v-model="entry.rating" name="name" class="form-control"
                                               placeholder="rating" >
                                        <error-label for="f_rating" :errors="errors.rating"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Shared</label>
                                        <input id="f_shared" v-model="entry.shared" name="name" class="form-control"
                                               placeholder="shared" >
                                        <error-label for="f_shared" :errors="errors.shared"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Structure</label>
                                        <input id="f_structure" v-model="entry.structure" name="name" class="form-control"
                                               placeholder="structure" >
                                        <error-label for="f_structure" :errors="errors.structure"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Subject</label>
                                        <input id="f_subject" v-model="entry.subject" name="name" class="form-control"
                                               placeholder="subject" >
                                        <error-label for="f_subject" :errors="errors.subject"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Unit</label>
                                        <input id="f_unit" v-model="entry.unit" name="name" class="form-control"
                                               placeholder="unit" >
                                        <error-label for="f_unit" :errors="errors.unit"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Number</label>
                                        <input id="f_number" v-model="entry.number" name="name" class="form-control"
                                               placeholder="number" >
                                        <error-label for="f_number" :errors="errors.number"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Customized</label>
                                        <input id="f_customized" v-model="entry.customized" name="name" class="form-control"
                                               placeholder="customized" >
                                        <error-label for="f_customized" :errors="errors.customized"></error-label>

                                    </div>
                                
                            </div>
                        </div>
                        <hr>
                        <div >
                            <button type="reset" @click="save()" class="btn btn-primary mr-2">Save</button>
                            <button type="reset" @click="backIndex()" class="btn btn-secondary">Cancel</button>
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

    export default {
        name: "LessonsForm.vue",
        components: {ActionBar},
        data() {
            return {
                entry: $json.entry || {},
                isLoading: false,
                errors: {}
            }
        },
        methods: {
            backIndex(){
                window.location.href = '/xadmin/lessons/index';
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/lessons/save', {entry: this.entry}, false);
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

                    if (!this.entry.id) {
                        location.replace('/xadmin/lessons/edit?id=' + res.id);
                    }

                }
            }
        }
    }
</script>

<style scoped>

</style>
