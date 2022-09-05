<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   backUrl="/xadmin/plans/index"
                   title="PlanForm"/>
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
                                        <label>Status</label>
                                        <input id="f_status" v-model="entry.status" name="name" class="form-control"
                                               placeholder="status" >
                                        <error-label for="f_status" :errors="errors.status"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Deployed</label>
                                        <input id="f_Deployed" v-model="entry.Deployed" name="name" class="form-control"
                                               placeholder="Deployed" >
                                        <error-label for="f_Deployed" :errors="errors.Deployed"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Created By</label>
                                        <input id="f_created_by" v-model="entry.created_by" name="name" class="form-control"
                                               placeholder="created_by" >
                                        <error-label for="f_created_by" :errors="errors.created_by"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Due At</label>
                                        <input id="f_due_at" v-model="entry.due_at" name="name" class="form-control"
                                               placeholder="due_at" >
                                        <error-label for="f_due_at" :errors="errors.due_at"></error-label>

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
        name: "PlansForm.vue",
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
                window.location.href = '/xadmin/plans/index';
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/save', {entry: this.entry}, false);
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
                        location.replace('/xadmin/plans/edit?id=' + res.id);
                    }

                }
            }
        }
    }
</script>

<style scoped>

</style>
