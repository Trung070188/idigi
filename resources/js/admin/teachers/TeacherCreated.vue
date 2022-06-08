<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   back-url="/xadmin/users/index"
                   :breadcrumbs="breadcrumbs"
                   title="UserForm"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="card-body d-flex flex-column">
                        <div class="row">
                            <div class=" col-sm-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group  col-sm-4">
                                        <label>Teacher name <span class="text-danger">*</span></label>
                                        <input class="form-control" v-model="entry.username">

                                        <error-label for="f_category_id" :errors="errors.username"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>Class <span class="text-danger">*</span></label>
                                        <input class="form-control" v-model="entry.class">

                                        <error-label for="f_category_id" :errors="errors.class"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>School <span class="text-danger">*</span></label>
                                        <input class="form-control" >
                                        <error-label for="f_category_id" ></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>Phone number <span class="text-danger">*</span></label>
                                        <input class="form-control" v-model="entry.phone">
                                        <error-label for="f_category_id" :errors="errors.phone"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input class="form-control" v-model="entry.email">
                                        <error-label for="f_category_id" :errors="errors.email"></error-label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input id="state" type="checkbox" v-model="entry.state">
                                    <label for="state" class="pl-2">Active</label>
                                    <error-label for="f_grade" :errors="errors.state"></error-label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <button type="reset" @click="save()" class="btn btn-primary mr-2">Create new teacher</button>
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
    import SwitchButton from "../../components/SwitchButton";

    export default {
        name: "TeacherCreated.vue",
        components: {ActionBar, SwitchButton},
        data() {

            return {

                showConfirm: false,
                showPass: false,
                types: [],
                breadcrumbs: [
                    {
                        title: 'Teachers',
                        url: '/xadmin/users/index_teacher',
                    },
                    {
                        title: $json.entry ? 'Edit User' : 'Create new Teachers',
                    },
                ],
                entry: $json.entry || {
                    roles: []
                },
                roles: $json.roles || [],
                isLoading: false,
                errors: {}
            }
        },
        methods: {
            // checkbox_roles()
            // {
            //     this.entry=this.roles;
            // },
            backIndex() {

                window.location.href = '/xadmin/users/index';
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/users/save_teacher', {entry: this.entry, roles: this.roles}, false);
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
                        location.replace('/xadmin/users/edit?id=' + res.id);
                    }
                }
            }
        }
    }
</script>

<style scoped>
    .fa-eye {
        position: absolute;
        top: 40%;
        right: 5%

    }

</style>
