<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"  title = "User Manager - Users"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="delete" tabindex="-1" role="dialog"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                         style="max-width: 450px;">
                        <div class="modal-content box-shadow-main paymment-status" style="left:120px;text-align: center; padding: 20px 0px 55px;">
                            <div class="close-popup" data-dismiss="modal"></div>
                            <div class="swal2-icon swal2-warning swal2-icon-show">
                                <div class="swal2-icon-content" style="margin: 0px 25px 0px ">!</div>
                            </div>
                            <div class="swal2-html-container">
                                <p >Are you sure to delete this user?</p>
                            </div>
                            <div class="swal2-actions">
                                <button type="submit" id="kt_modal_new_target_submit" class="swal2-confirm btn fw-bold btn-danger" @click="remove(entry)">
                                    <span class="indicator-label">Yes, delete!</span>
                                </button>
                                <button type="reset" id="kt_modal_new_target_cancel" class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal" style="margin: 0px 8px 0px">No, cancel</button>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="card card-custom card-stretch gutter-b">
                     <div class="card-header border-0 pt-6" style="margin:0px 0px -35px">
                         <div class="card-title"></div>
                         <div class="card-toolbar">
                             <button v-if="permissions['003'] && auth.id!=entry.id" :disabled="permissionFields['user_remove']==false" class="btn btn-danger" @click="removeUser">
                                 <i class="bi bi-person-dash mr-1"></i>Delete user
                             </button>
                         </div>
                    </div>
                   <!-- <div class="card-body d-flex flex-column"> -->
                    <div class="card-body d-flex flex-column mt-4">
                        <div class="row">
                            <div class=" col-sm-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label>Username <span class="text-danger">*</span></label>
                                        <input class="form-control  nospace" :disabled="permissionFields['user_username']==false" placeholder="Enter the username" v-model="entry.username" >

                                        <error-label for="f_category_id" :errors="errors.username" ></error-label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Full name <span class="text-danger">*</span></label>
                                        <input class="form-control" placeholder="Enter the full name" :disabled="permissionFields['user_full_name']==false" v-model="entry.full_name">

                                        <error-label for="f_category_id" :errors="errors.full_name"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>Email</label>
                                        <input class="form-control" placeholder="Enter the email address" :disabled="permissionFields['user_email']==false" v-model="entry.email">
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
                                <div class="row py-3">
                                    <div class="form-group col-sm-12">
                                        <label>Role <span class="text-danger">*</span></label>
                                        <div class="d-flex align-items-center justify-content-start mt-2">
                                            <div class="form-check form-check-custom form-check-solid mr-10" v-for="role in roles">
                                                <input :disabled="permissionFields['user_role']==false"  class="form-check-input mr-2" type="radio" :id="role.id" v-model="name_role" :value="role.id">
                                                <span :for="role.id">{{role.role_name}}</span>
                                            </div>
                                            <error-label for="f_grade" :errors="errors.name_role"></error-label>
                                        </div>
                                    </div>

                                    <!--<div  class="form-group col-sm-2" v-for="role in roles">
                                        <input type="radio" :id="role.id" v-model="name_role" :value="role.id">
                                        <label :for="role.id">{{role.role_name}}</label>
                                    </div>-->
                                </div>
                                <div class="row" v-if="name_role==2 || name_role==5">
                                    <div class="form-group col-sm-4 mb-7">
                                        <label>School <span class="text-danger">*</span></label>
                                        <select  class="form-control form-select" v-model="entry.school_id" required>
                                            <option v-for="school in schools" :value="school.id" >{{school.label}}</option>
                                        </select>
                                        <error-label for="f_grade" :errors="errors.school_id"></error-label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12 mb-3">
                                        <label>Description</label>
                                        <textarea v-model="entry.description" rows="5" class="form-control" :disabled="permissionFields['user_description']==false"
                                                  placeholder="Type the description here (200 characters)"></textarea>
                                        <error-label for="f_grade" :errors="errors.description"></error-label>

                                    </div>
                                </div>
                                <div class="form-check form-check-custom form-check-solid pb-5">
                                    <input id="state" type="checkbox" v-model="entry.state" :disabled="permissionFields['user_active']==false" class="form-check-input h-20px w-20px">
                                    <label for="state" class="form-check-label fw-bold">Active</label>
                                    <error-label for="f_grade" :errors="errors.state"></error-label>
                                </div>
                            </div>
                        </div>
                        <!--<hr style="margin: 0px 0px 16px;">-->
                        <div class="mt-5">
                            <button type="reset" @click="save()" class="btn btn-primary mr-3"><i class="bi bi-save2 mr-1"></i>Save</button>
                            <button type="reset" @click="backIndex()" class="btn btn-light">Cancel</button>
                        <label style="margin-left: 20px">Username and password will be sent to the user's email.
                          </label>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</template>

<script>
    import {$post, clone} from "../../utils";

    import ActionBar from "../includes/ActionBar";
    import SwitchButton from "../../components/SwitchButton";
    import _ from "lodash";

    export default {
        name: "UsersForm.vue",
        components: {ActionBar, SwitchButton},
        data() {
            const permissions = clone(window.$permissions)

            return {
                changed: false,
                permissions,
                showConfirm: false,
                showPass: false,
                types: [],
                breadcrumbs: [
                    {
                        title: 'Users & roles',
                        url: '/xadmin/users/index',
                    },
                    {
                        title: 'Manage users',
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
                schools:$json.schools||[],
                school:$json.school||[],
                roles: $json.roles || [],
                role: $json.role || [],
                title_role: $json.title_role || [],
                permissionFields: $json.permissionFields || [],
                isLoading: false,
                errors: {}
            }
        },
        watch: {
            // entry: {
            //     handler(value){
            //         if(value) {
            //             this.changed = !_.isEqual(value, this.actual);
            //         }
            //     },
            //     deep: true,
            // }
        },
        mounted() {
            $('.nospace').keypress(function (e) {
                if (e.keyCode == 32 ) {
                    e.preventDefault();
                }
            })
        },

        methods: {
             removeUser:function(deleteUser='')
            {
                  $('#delete').modal('show');
            },
            // checkbox_roles()
            // {
            //     this.entry=this.roles;
            // },
            backIndex() {

                window.location.href = '/xadmin/users/index';
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/users/save', {entry: this.entry, name_role: this.name_role}, false);
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
                    location.replace('/xadmin/users/index');
                    if (!this.entry.id) {
                        location.replace('/xadmin/users/edit?id=' + res.id);
                    }
                }
            },
             async remove(entry) {


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
