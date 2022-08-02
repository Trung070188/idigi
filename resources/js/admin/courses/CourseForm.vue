<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   backUrl="/xadmin/courses/index"
                   title="CourseForm"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column" >
                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                                                    <div class="form-group">
                                        <label>Name</label>
                                        <input id="f_name" v-model="entry.name" name="name" class="form-control"
                                               placeholder="name" >
                                        <error-label for="f_name" :errors="errors.name"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Public From</label>
                                        <input id="f_public_from" v-model="entry.public_from" name="name" class="form-control"
                                               placeholder="public_from" >
                                        <error-label for="f_public_from" :errors="errors.public_from"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Public To</label>
                                        <input id="f_public_to" v-model="entry.public_to" name="name" class="form-control"
                                               placeholder="public_to" >
                                        <error-label for="f_public_to" :errors="errors.public_to"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Status</label>
                                        <input id="f_status" v-model="entry.status" name="name" class="form-control"
                                               placeholder="status" >
                                        <error-label for="f_status" :errors="errors.status"></error-label>

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
        name: "CoursesForm.vue",
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
                window.location.href = '/xadmin/courses/index';
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/courses/save', {entry: this.entry}, false);
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
                        location.replace('/xadmin/courses/edit?id=' + res.id);
                    }

                }
            }
        }
    }
</script>

<style scoped>

</style>
