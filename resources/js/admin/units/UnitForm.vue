<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"  title = "Create new unit"/>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column">
                        <div class="row">
                            <div class=" col-sm-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group col-sm-9">
                                        <label>Unit name <span class="text-danger">*</span></label>
                                        <input class="form-control nospace" placeholder="Enter the course name" v-model="entry.unit_name" >
                                        <error-label  for="f_category_id" :errors="errors.unit_name"></error-label>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label>Subject<span class="text-danger">*</span></label>
                                        <select class="form-control form-select">
                                            <option></option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-9">
                                        <label>Description </label>
                                        <textarea class="form-control"  placeholder="Type the description here (200 characters)" rows="5" v-model="entry.description"></textarea>
                                        <error-label for="f_category_id" :errors="errors.description"></error-label>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label>Course <span class="text-danger">*</span></label>
                                        <select class="form-select form-control">
                                            <option></option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-12"  style="border: 1px solid #b5b5c3;border-radius: 25px">
                                        <label style="margin:15px 0px 10px ">List of lesson</label>
                                        <select class="form-control form-select" style="margin-bottom: 15px">
                                            <option></option>
                                        </select>
                                        <draggable
                                            :animation="200"
                                            ghost-class="moving-card"
                                            group="users"
                                            filter=".action-button"
                                            class="form-group col-sm-12"
                                            tag="ul"
                                        >
                                            <div style="width: 100%">
                                                <i class="bi bi-text-center" style="width: 5%; display: inline-block"></i>
                                                <div style="width: 5%;display: inline-block"></div>
                                                <div style="width: 50%;display: inline-block">
                                                    <input class="form-control">
                                                </div>
                                                <div style="width: 30%;display: inline-block">
                                                    <input class="form-control">
                                                </div>
                                                <div style="width: 10%;display: inline-block"></div>
                                            </div>

                                        </draggable>
                                    </div>


                                </div>
                                <div class="form-check form-check-custom form-check-solid pb-5">
                                    <input id="state" type="checkbox" v-model="entry.active" class="form-check-input h-20px w-20px" checked>
                                    <label for="state" class="form-check-label fw-bold">Active</label>
                                    <error-label for="f_grade" :errors="errors.active"></error-label>
                                </div>
                            </div>
                        </div>
                        <!--<hr style="margin: 0px 0px 16px;">-->
                        <div class="mt-5">
                            <button type="reset" @click="save()"  class="btn btn-primary mr-3" ><i class="bi bi-send mr-1"></i>Save</button>
                            <button type="reset" @click="backIndex()" class="btn btn-light">Cancel</button>
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
    import draggable from "vuedraggable";

    export default {
        name: "UnitsForm.vue",
        components: {ActionBar,draggable},
        data() {
            return {
                breadcrumbs: [
                    {
                        title: 'Resource management',
                    },
                    {
                        title: 'Unit',
                        url: '/xadmin/units/index',
                    },
                    {
                        title:'Create new unit',
                    },
                ],
                entry: $json.entry || {},
                isLoading: false,
                errors: {}
            }
        },
        methods: {
            backIndex(){
                window.location.href = '/xadmin/units/index';
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/units/save', {entry: this.entry}, false);
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
                        location.replace('/xadmin/units/edit?id=' + res.id);
                    }

                }
            }
        }
    }
</script>

<style scoped>

</style>
