<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"  title = "View user detail"/>
        <div class="row">
            <div class="col-lg-12">
                <!--modal xoa device -->
                <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="delete1" tabindex="-1" role="dialog"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                         style="max-width: 450px;">
                        <div class="modal-content box-shadow-main paymment-status" style="left:120px;text-align: center; padding: 20px 0px 55px;">
                            <div class="close-popup" data-dismiss="modal"></div>
                            <div class="swal2-icon swal2-warning swal2-icon-show">
                                <div class="swal2-icon-content" style="margin: 0px 25px 0px ">!</div>
                            </div>
                            <div class="swal2-html-container">
                                <p >Are you sure to delete this device?</p>
                            </div>
                            <div class="swal2-actions">
                                <button type="submit" id="kt_modal_new_target_submit1" class="swal2-confirm btn fw-bold btn-danger" @click="deleteDevice(deleteUserDevice)" :disabled="!permissionFields['user_delete_device']">
                                    <span class="indicator-label">Yes, delete!</span>
                                </button>
                                <button type="reset" id="kt_modal_new_target_cancel1" class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal" style="margin: 0px 8px 0px">No, cancel</button>

                            </div>

                        </div>
                    </div>
                </div>
                <!-- end modal xoa device -->
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
                                <button type="submit" id="kt_modal_new_target_submit" class="swal2-confirm btn fw-bold btn-danger" @click="remove(entry)" :disabled="!permissionFields['user_remove']">
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
                    <div class="card-body d-flex flex-column mt-4">
                        <div class="row">
                            <div class=" col-sm-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label>Username <span class="text-danger">*</span></label>
                                        <input class="form-control  nospace" :disabled="true" placeholder="Enter the username" v-model="entry.username">
                                        <error-label for="f_category_id" :errors="errors.username" ></error-label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Full name <span class="text-danger">*</span></label>
                                        <input class="form-control" placeholder="Enter the full name" :disabled="permissionFields['user_full_name']==false" v-model="entry.full_name">
                                        <error-label for="f_category_id" :errors="errors.full_name"></error-label>
                                    </div>
                                    <div class="form-group  col-sm-4">
                                        <label>Email</label>
                                        <input class="form-control" placeholder="Enter the email address" :disabled="true" v-model="entry.email">
                                        <error-label for="f_category_id" :errors="errors.email"></error-label>
                                    </div>
                                    <div  class="form-group  col-sm-4">
                                        <label>Password <span class="text-danger">*</span></label>
                                        <input :type="showPass ? 'text' : 'password'" class="form-control"
                                               ref="password" v-model="password" placeholder="Enter the password" :disabled="!permissionFields['user_password']">
                                        <error-label for="f_category_id" :errors="errors.password"></error-label>
                                    </div>

                                    <div  class="form-group  col-sm-4">
                                        <label>Confirm your password <span class="text-danger">*</span></label>
                                        <input class="form-control" :type="showConfirm ? 'text' : 'password'"
                                               v-model="password_confirmation"  placeholder="Re-enter to confirm the password" :disabled="!permissionFields['user_password']">
                                        <error-label for="f_category_id"
                                                     :errors="errors.password_confirmation"></error-label>
                                    </div>
                                </div>
                                <div class="row py-3" >
                                    <div class="form-group col-sm-8" >
                                        <label>Role <span class="text-danger">*</span></label>
                                        <div class="d-flex align-items-center justify-content-start mt-2">
                                            <div class="form-check form-check-custom form-check-solid mr-10" v-for="role in roles">
                                                <input :disabled="!permissionFields['user_role']"  class="form-check-input mr-2" type="radio" :id="role.id" v-model="name_role" :value="role.id">
                                                <label :for="role.id">{{role.role_name}}</label>
                                            </div>
                                            <error-label for="f_grade" :errors="errors.name_role"></error-label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" v-if="name_role==2">
                                    <div class="form-group col-sm-4 mb-7">
                                        <label>School <span class="text-danger">*</span></label>
                                        <Treeselect :options="schools" :multiple="true" v-model="userSchool" placeholder="Choose school"  :disabled="!permissionFields['user_role']"/>
                                    <error-label for="f_grade" :errors="errors.userSchool"></error-label>
                                    </div>
                                </div>
                                <div class="row" v-if="name_role==5">
                                    <div class="form-group col-sm-4">
                                        <label>School <span class="text-danger">*</span></label>
                                        <select required  class="form-control form-select"  v-model="entry.school_id" >
                                            <option value="" disabled selected>Choose role</option>
                                            <option v-for="school in schools" :value="school.id">{{school.label}}</option>
                                        </select>
                                        <error-label for="f_grade" :errors="errors.school_id"></error-label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12 mb-3">
                                        <label>User description</label>
                                        <textarea v-model="entry.description" rows="5" class="form-control" :disabled="!permissionFields['user_description']"
                                                  placeholder="Type the description here (200 characters)"></textarea>
                                        <error-label for="f_grade" :errors="errors.description"></error-label>

                                    </div>
                                </div>
                                <div class="form-check form-check-custom form-check-solid pb-5" style="margin-top: 6px">
                                    <input id="state" type="checkbox" v-model="entry.state" :disabled="permissionFields['user_active']==false" class="form-check-input h-20px w-20px">
                                    <label for="state" class="form-check-label fw-bold">Active</label>
                                    <error-label for="f_grade" :errors="errors.state"></error-label>
                                </div>
                                <div class="mt-2 mb-4">
                                    <button type="reset" @click="save()" class="btn btn-primary mr-3" style="padding: 8.375px 13.75px" :disabled="!permissions['002']"><i class="bi bi-save2 mr-1"></i>Save</button>
                                    <button type="reset" @click="backIndex()" class="btn btn-light" style="padding: 8.375px 13.75px">Cancel</button>
                                </div>
                            </div>
                        </div>
                        <!--<hr style="margin: 0px 0px 16px;">-->
                        <h3 style="margin-top: 10px">Current registered devices</h3>
                        <table class="table table-row-bordered align-middle gy-4 gs-9">
                            <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                            <tr>
                                <th  class="">Device name</th>
                                <th  class="">Device detail</th>
                                <th  class="">Registered date</th>
                                <th  class=""></th>
                                <th  class=""> </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="device in userDevice">
                                <td>{{device.device_name}}</td>
                                <td>{{device.device_uid}}</td>
                                <td>{{d(device.created_at)}}</td>
                                <td v-if="device.delete_request!=null" class="status-request" v-text="device.delete_request"></td>
                                <td v-else ></td>
                                <td v-if="permissionFields['user_delete_device']"><i class="bi bi-trash text-danger"  style="cursor: pointer" @click="deleteDeviceModal(device)"></i></td>
                                <td v-else></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>

</template>

<script>
    import {$get, $post, clone} from "../../utils";

    import ActionBar from "../includes/ActionBar";
    import SwitchButton from "../../components/SwitchButton";
    import _ from "lodash";
    import Treeselect from '@riophae/vue-treeselect'
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'
    import $router from "../../lib/SimpleRouter";


    export default {
        name: "UsersForm.vue",
        components: {ActionBar, SwitchButton,Treeselect},
        data() {
            const permissions = clone(window.$permissions)
            console.log($json.schoolTeacher)

            return {
                userSchool:$json.userSchool || [],
                password:'',
                password_confirmation:'',
                changed: false,
                permissions,
                showConfirm: false,
                showPass: false,
                types: [],
                breadcrumbs: [
                    {
                        title: 'Account Management',
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
                schoolTeacher:$json.schoolTeacher||[],
                school:$json.school||[],
                roles: $json.roles || [],
                role: $json.role || [],
                title_role: $json.title_role || [],
                permissionFields: $json.permissionFields || [],
                userDevice :[],
                isLoading: false,
                errors: {},
                deleteUserDevice:[],
            }
        },
        mounted() {
            $('.nospace').keypress(function (e) {
                if (e.keyCode == 32 ) {
                    e.preventDefault();
                }
            })
            $router.on('/', this.load).init();

        },

        methods: {
            inputPasswordConfirm(){
                if(this.password != this.password_confirmation){
                    this.errors.password_confirmation = ["You must enter the same password twice in order to confirm it."];
                }else{
                    this.errors.password_confirmation = [];
                }
            },
            async load() {

                let query = $router.getQuery();
                this.$loading(true);
                const res = await $get('/xadmin/users/dataUserDetail?id='+this.entry.id, query);
                this.$loading(false);
                this.userDevice=res.devices;
                setTimeout(function (){
                    KTMenu.createInstances();
                }, 0)
            },
            deleteDeviceModal:function(device=[])
            {
                $('#delete1').modal('show');
                this.deleteUserDevice=device;
            },
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
                const res = await $post('/xadmin/users/save', {entry: this.entry, name_role: this.name_role,password:this.password,userSchool:this.userSchool,password_confirmation:this.password_confirmation}, false);
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
            async deleteDevice (deleteUserDevice)
            {
                const res = await $post('/xadmin/users/deleteDevice', {id: deleteUserDevice.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                    let self=this;
                    self.userDevice=  self.userDevice.filter(item => item.id !=deleteUserDevice.id);
                    $('#delete1').modal('hide');
                    return self.userDevice;
                }
            }

        }
    }
</script>

<style scoped>
    .disableOption{
        background-color:#cccccc
    }
    .fa-eye {
        position: absolute;
        top: 40%;
        right: 5%

    }
    .status-request{
        padding: 0.5em 0.85em;
        font-size: .85rem;
        font-weight: 600;
        border-radius: 0.475rem;
        color:#ffc700;

    }
    select:required:invalid {
        color: #adadad;
    }

    option[value=""][disabled] {
        display: none;
    }

    option {
        color: black;
    }

</style>
