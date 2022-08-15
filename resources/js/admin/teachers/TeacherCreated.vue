<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title = "Create New Teacher"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column">
                        <div class="row">
                            <div class=" col-sm-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group  col-sm-4">
                                        <label>Username <span class="text-danger">*</span></label>
                                        <input class="form-control" v-model="entry.username">

                                        <error-label for="f_category_id" :errors="errors.username"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>Full name <span class="text-danger">*</span></label>
                                        <input class="form-control" v-model="entry.full_name">

                                        <error-label for="f_category_id" :errors="errors.full_name"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>Email </label>
                                        <input class="form-control" v-model="entry.email" >
                                        <error-label for="f_category_id" :errors="errors.email"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>Phone number </label>
                                        <input class="form-control" v-model="entry.phone">
                                        <error-label for="f_category_id" :errors="errors.phone"></error-label>
                                    </div>
                                     <div class="form-group  col-sm-4">
                                        <label>Class</label>
                                        <input class="form-control" v-model="entry.class">
                                        <error-label for="f_category_id" :errors="errors.class"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>School<span class="text-danger">*</span></label>
                                        <input class="form-control" v-model="school" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div v-if="entry.id==null" class="form-group  col-sm-4">
                                        <label>Password </label>
                                        <input v-if="auto_gen==true" disabled :type="showPass ? 'text' : 'password'" class="form-control"
                                               ref="password" v-model="entry.password">
                                        <input v-if="auto_gen==false"  :type="showPass ? 'text' : 'password'" class="form-control"
                                               ref="password" v-model="entry.password">
                                        <i @click="showPass = !showPass" class="fa fa-eye"></i>
                                        <error-label for="f_category_id" :errors="errors.password"></error-label>
                                    </div>

                                    <div v-if="entry.id==null" class="form-group  col-sm-4">
                                        <label>Confirm your password</label>
                                        <input v-if="auto_gen==true" disabled class="form-control" :type="showConfirm ? 'text' : 'password'"
                                               v-model="entry.password_confirmation">
                                        <input v-if="auto_gen==false"  class="form-control" :type="showConfirm ? 'text' : 'password'"
                                               v-model="entry.password_confirmation">
                                        <i @click="showConfirm = !showConfirm" class="fa fa-eye"></i>
                                        <error-label for="f_category_id" :errors="errors.password_confirmation"></error-label>
                                    </div>
                                </div>

                                <div class="form-group  col-sm-8">
                                    <input  type="checkbox" v-model="auto_gen">
                                    <label>Auto password</label>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-8">
                                        <label>Description</label>
                                        <textarea v-model="entry.description" rows="5" class="form-control"
                                                  placeholder="Your text here"></textarea>
                                        <error-label for="f_grade" :errors="errors.description"></error-label>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <input id="state" type="checkbox" v-model="entry.state">
                                    <label for="state" class="pl-2">Active</label>
                                    <error-label for="f_grade" :errors="errors.state"></error-label>
                                </div>
                            </div>
                        </div>
                        <hr style="margin-top: 5px;">
                        <div>
                            <button type="reset" @click="save()" class="btn btn-primary mr-2">Create new teacher</button>
                            <button type="reset" @click="backIndex()" class="btn btn-secondary">Cancel</button>
                             <label style="margin-left: 20px">Thông tin đăng nhập và mật khẩu sẽ được gửi tới người dùng
                            qua email.</label>
                        </div>
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
                auto_gen:true,
                showConfirm: false,
                showPass: false,
                types: [],
                breadcrumbs: [
                    {
                        title: 'Teachers',
                        url: '/xadmin/users/teacher',
                    },
                    {
                        title: $json.entry ? 'Edit User' : 'Create New Teacher',
                    },
                ],
                school:$json.school,
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
                const res = await $post('/xadmin/users/saveTeacher', {entry: this.entry, roles: this.roles}, false);
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
        top: 50%;
        right: 5%

    }
    .form-group label
    {
        margin-bottom: 2px;
    }

</style>
