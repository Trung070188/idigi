<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   backUrl="/xadmin/allocations/index"
                   title="AllocationForm"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column" >
                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input id="f_title" v-model="entry.title" name="name" class="form-control"
                                               placeholder="title" >
                                        <error-label for="f_title" :errors="errors.title"></error-label>
                                    </div>
                                    <div class="form-group">
                                        <label>Total School</label>
                                        <input id="f_total_school" v-model="entry.total_school" name="name" class="form-control"
                                               placeholder="total_school" >
                                        <error-label for="f_total_school" :errors="errors.total_school"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Total Course</label>
                                        <input id="f_total_course" v-model="entry.total_course" name="name" class="form-control"
                                               placeholder="total_course" >
                                        <error-label for="f_total_course" :errors="errors.total_course"></error-label>

                                    </div>
                                    <div class="form-group">
                                        <label>Total Unit</label>
                                        <input id="f_total_unit" v-model="entry.total_unit" name="name" class="form-control"
                                               placeholder="total_unit" >
                                        <error-label for="f_total_unit" :errors="errors.total_unit"></error-label>

                                    </div>
                            </div>
                        </div>
                        <hr style="margin:20px 0px 20px">
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
        name: "AllocationsForm.vue",
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
                window.location.href = '/xadmin/allocations/index';
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/allocations/save', {entry: this.entry}, false);
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
                        location.replace('/xadmin/allocations/edit?id=' + res.id);
                    }

                }
            }
        }
    }
</script>

<style scoped>

</style>
