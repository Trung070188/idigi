<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   backUrl="/xadmin/allocation_content_schools/index"
                   title="Allocation_content_schoolForm"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column" >
                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                                                    <div class="form-group">
                                        <label>Allocation Content Id</label>
                                        <input id="f_allocation_content_id" v-model="entry.allocation_content_id" name="name" class="form-control"
                                               placeholder="allocation_content_id" >
                                        <error-label for="f_allocation_content_id" :errors="errors.allocation_content_id"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>School Id</label>
                                        <input id="f_school_id" v-model="entry.school_id" name="name" class="form-control"
                                               placeholder="school_id" >
                                        <error-label for="f_school_id" :errors="errors.school_id"></error-label>

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
        name: "Allocation_content_schoolsForm.vue",
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
                window.location.href = '/xadmin/allocation_content_schools/index';
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/allocation_content_schools/save', {entry: this.entry}, false);
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
                        location.replace('/xadmin/allocation_content_schools/edit?id=' + res.id);
                    }

                }
            }
        }
    }
</script>

<style scoped>

</style>
