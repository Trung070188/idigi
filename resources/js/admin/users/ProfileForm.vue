<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   back-url="/xadmin/users/index"
                   :breadcrumbs = "breadcrumbs"
                   title="ProfileForm"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="card-body d-flex flex-column" >
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
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input class="form-control" v-model="entry.email">
                                        <error-label for="f_category_id" :errors="errors.email"></error-label>
                                    </div>
                                </div>
                                <div class="row" >
                                    <label>Role</label>
                                    <div v-for="role in roles" class="form-group col-sm-2">

                                        <input type="checkbox" v-bind:value="role.role_name" v-model="check_role" >
                                        <label >{{role.role_name}}</label>
                                    </div>
                                </div>
                                <div class="row" >
                                    <div class="form-group col-sm-8">
                                        <label>Description</label>
                                        <textarea  v-model="entry.description" rows="5" class="form-control" placeholder="Your text here"></textarea>
                                        <error-label for="f_grade" :errors="errors.description"></error-label>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <input id="state" type="checkbox" v-model="entry.state">
                                    <label for="state"  class="pl-2">Active</label>
                                    <error-label for="f_grade" :errors="errors.state"></error-label>

                                </div>
                            </div>
                        </div>

                        <hr>
                        <div >
                            <button type="reset" @click="save()" class="btn btn-primary mr-2">Save</button>
                            <button type="reset" @click="backIndex()" class="btn btn-secondary">Cancel</button>
                            <label style="margin-left: 20px">Thông tin đăng nhập và mật khẩu sẽ được gửi tới người dùng qua email.</label>
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
        name: "ProfileForm.vue",
        components: { ActionBar,SwitchButton},
        data() {
            return {
                check_role:[],
                types: [

                ],
                breadcrumbs: [
                    {
                        title: 'Users',
                        url: '/xadmin/users/index',
                    },
                    {
                        title: $json.entry ? 'Edit User' : 'Create new User',
                    },
                ],
                entry: $json.entry || {},
                roles:$json.roles || [],
                isLoading: false,
                errors: {}
            }
        },
        methods: {
            backIndex(){

                window.location.href = '/xadmin/users/index';
            },
            async save() {
                console.log(this.check_role);

                this.isLoading = true;
                const res = await $post('/xadmin/users/save', {entry: this.entry}, false);
                console.log(res);
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

</style>
