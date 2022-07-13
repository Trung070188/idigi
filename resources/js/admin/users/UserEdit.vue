<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   back-url="/xadmin/users/index"
                   :breadcrumbs="breadcrumbs"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                     <div class="card-header border-0 pt-5">
                        <div class="title">
                            <label>{{entry.full_name}} - {{title_role}}</label>

                        </div>
                           <button class="btn btn-primary button-create " @click="remove(entry)">
                        Delete User <i class="fas fa-trash"></i>
                    </button>
                    </div>
                    <hr>

                    <div class="card-body d-flex flex-column">
                        <div class="row">
                            <div class=" col-sm-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div  class="form-group  col-sm-4">
                                        <label>Username <span class="text-danger">*</span></label>
                                        <input class="form-control" placeholder="Enter the username" v-model="entry.username" disabled>

                                        <error-label for="f_category_id" :errors="errors.username"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>Full name <span class="text-danger">*</span></label>
                                        <input class="form-control nospace" placeholder="Enter the full name" v-model="entry.full_name">

                                        <error-label for="f_category_id" :errors="errors.full_name"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input class="form-control" placeholder="Enter the email" v-model="entry.email">
                                        <error-label for="f_category_id" :errors="errors.email"></error-label>
                                    </div>
                                    <div v-if="entry.id==null" class="form-group  col-sm-4">
                                        <label>Password <span class="text-danger">*</span></label>
                                        <input :type="showPass ? 'text' : 'password'" class="form-control"
                                               ref="password" v-model="entry.password">
                                        <i @click="showPass = !showPass" class="fa fa-eye"></i>
                                        <error-label for="f_category_id" :errors="errors.password"></error-label>
                                    </div>

                                    <div v-if="entry.id==null" class="form-group  col-sm-4">
                                        <label>Confirm your password <span class="text-danger">*</span></label>
                                        <input class="form-control" :type="showConfirm ? 'text' : 'password'"
                                               v-model="entry.password_confirmation">
                                        <i @click="showConfirm = !showConfirm" class="fa fa-eye"></i>
                                        <error-label for="f_category_id"
                                                     :errors="errors.password_confirmation"></error-label>
                                    </div>
                                </div>
                                <div class="row">

                                    <label>Role</label>
                                    <div  class="form-group col-sm-2" v-for="role in roles">
                                        <input  type="radio"  v-model="name_role" :value="role.id">
                                        <label>{{role.role_name}}</label>
                                    </div>
                                </div>
                                <div class="row" v-if="name_role==2 || name_role==5">
                                    <div class="form-group  col-sm-4">
                                        <label>School <span class="text-danger">*</span></label>
                                        <input class="form-control " type="" placeholder="Enter the school" v-model="school.school_name"  disabled/>
                                    </div>
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
                        <hr style="margin: 0px 0px 16px;">
                        <div>
                            <button type="reset" @click="save()" class="btn btn-primary mr-2">Save</button>
                            <button type="reset" @click="backIndex()" class="btn btn-secondary">Cancel</button>
                            <!--                            <label style="margin-left: 20px">Thông tin đăng nhập và mật khẩu sẽ được gửi tới người dùng
                                                            qua email.</label>-->
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
        name: "UsersForm.vue",
        components: {ActionBar, SwitchButton},
        data() {

            return {
                showConfirm: false,
                showPass: false,
                types: [],
                breadcrumbs: [
                    {
                        title: 'Users',
                        url: '/xadmin/users/index',
                    },
                    {
                        title: $json.entry ? 'View user detail' : 'Create new User',
                    },
                ],
                entry: $json.entry || {
                },
                name_role: $json.name_role
                    || {
                },
                school:$json.school||[],
                roles: $json.roles || [],
                role: $json.role || [],
                title_role: $json.title_role || [],
                isLoading: false,
                errors: {}
            }
        },
        mounted() {
            $('.nospace').keypress(function (e) {
                if (e.keyCode == 32 ) {
                    e.preventDefault();
                }
            })
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
                const res = await $post('/xadmin/users/save', {entry: this.entry, name_role: this.name_role,user_school:this.user_school}, false);
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
            },
             async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return false;
                }

                const res = await $post('/xadmin/users/remove', {id: entry.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }
                location.replace('/xadmin/users/index');

                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});
            },
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
