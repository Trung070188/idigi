<template>
    <div class="container-fluid" >
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title ="Create new plan" />
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-6" style="margin:0px 0px -35px">
                        <div class="card-title"></div>
                        <div class="card-toolbar">

                        </div>

                    </div>

                    <div class="card-body d-flex flex-column" >
                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group col-lg-8">
                                        <label>Plan name <span class="text-danger">*</span></label>
                                        <input  v-model="entry.name"  class="form-control"
                                                placeholder="Enter the name of plan" >
                                        <error-label for="f_school_name" ></error-label>

                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label> Assign to IT <span class="text-danger">*</span></label>
                                        <select   class="form-control form-select"
                                        >
                                            <option></option>
                                        </select>
                                        <error-label  ></error-label>

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-8">
                                        <label>Plan description  <span class="text-danger">*</span> </label>
                                        <input   class="form-control"
                                                placeholder="Enter the description" >
                                        <error-label for="f_school_name" :errors="errors.school_phone"></error-label>

                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label>Due date  <span class="text-danger">*</span></label>
                                        <Datepicker />
                                        <error-label for="f_title" ></error-label>


                                    </div>


                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-8">
                                        <label>Devices <span class="text-danger">*</span></label>
                                        <div class="card-header  border border-dashed border-gray-300">
                                            <!--begin::Card title-->
                                            <div class="card-title">
                                                <h2 class="fw-bolder"></h2>
                                            </div>
                                            <!--end::Card title-->
                                            <!--begin::Card toolbar-->
                                            <div class="card-toolbar">
                                              <button class="btn btn-primary">View devices</button>
                                                <button class="btn btn-primary" style="margin: 0px 15px 0px">Add a device</button>
                                                <button class="btn btn-primary">Import devices</button>
                                            </div>
                                            <!--end::Card toolbar-->
                                        </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-8">
                                        <label>Lesson package <span class="text-danger">*</span></label>
                                        <div class="card-header  border border-dashed border-gray-300">
                                            <!--begin::Card title-->
                                            <div class="card-title">
                                                <h2 class="fw-bolder"></h2>
                                            </div>
                                            <!--end::Card title-->
                                            <!--begin::Card toolbar-->
                                            <div class="card-toolbar">
                                                <button class="btn btn-primary">Download package</button>
                                                <button class="btn btn-primary" style="margin: 0px 15px 0px">View lessons</button>
                                                <button class="btn btn-primary">Add lesson</button>
                                            </div>
                                            <!--end::Card toolbar-->
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <hr style="margin-top: 5px;">
                        <div style="margin: 13px 25px 13px">
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
                breadcrumbs: [
                    {
                        title: 'Manage plans',
                        url:'/xadmin/plans/index'
                    },
                    {
                        title: 'Create new plan'
                    },
                ],
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
