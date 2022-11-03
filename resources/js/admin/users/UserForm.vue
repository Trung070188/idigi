<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"  title = "Create new user"/>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column">
                        <div class="row">
                            <div class=" col-sm-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div v-if="errors!==''"  class="form-group col-sm-4">
                                        <label>Username <span class="text-danger">*</span></label>
                                        <input class="form-control nospace" placeholder="Enter the username" v-model="entry.username">
                                        <error-label  for="f_category_id" :errors="errors.username"></error-label>
                                    </div>
                                    <div v-if="errors==''" class="form-group col-sm-4">
                                        <label>Username <span class="text-danger">*</span></label>
                                        <input class="form-control nospace" placeholder="Enter the username" v-model="entry.username">
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label>Full name <span class="text-danger">*</span></label>
                                        <input class="form-control" placeholder="Enter the full name" v-model="entry.full_name">

                                        <error-label for="f_category_id" :errors="errors.full_name"></error-label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Email </label>
                                        <input class="form-control" placeholder="Enter the email address" v-model="entry.email">
                                        <error-label for="f_category_id" :errors="errors.email"></error-label>
                                    </div>
                                    <div v-if="entry.id==null" class="form-group col-sm-4 mb-3">
                                        <label>Password <span class="text-danger">*</span></label>
                                        <input v-if="auto_gen==true" disabled :type="showPass ? 'text' : 'password'" class="form-control"  placeholder="Enter the password"
                                               ref="password" v-model="entry.password">
                                        <input v-if="auto_gen==false"  :type="showPass ? 'text' : 'password'" class="form-control"  placeholder="Enter the password"
                                               ref="password" v-model="entry.password">
<!--                                        <i @click="showPass = !showPass" class="fa fa-eye"></i>-->
                                        <error-label for="f_category_id" :errors="errors.password"></error-label>
                                    </div>

                                    <div v-if="entry.id==null" class="form-group col-sm-4 mb-3">
                                        <label>Confirm password <span class="text-danger">*</span></label>
                                        <input v-if="auto_gen==true" disabled class="form-control" :type="showConfirm ? 'text' : 'password'"  placeholder="Re-enter to confirm the password"
                                               v-model="entry.password_confirmation">
                                        <input v-if="auto_gen==false"  class="form-control" :type="showConfirm ? 'text' : 'password'"  placeholder="Re-enter to confirm the password"
                                               v-model="entry.password_confirmation">
<!--                                        <i @click="showConfirm = !showConfirm" class="fa fa-eye"></i>-->
                                        <error-label for="f_category_id" :errors="errors.password_confirmation"></error-label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid ml-3 pb-5">
                                        <input type="checkbox" v-model="auto_gen" class="form-check-input h-20px w-20px">
                                        <label class="form-check-label fw-bold">Auto generate password</label>
                                    </div>
                                </div>
                                <div class="row py-3">
                                    <div class="form-group col-sm-12">
                                        <label>Role <span class="text-danger">*</span></label>
                                        <div class="d-flex align-items-center justify-content-start mt-2">
                                            <div class="form-check form-check-custom form-check-solid mr-10" v-for="role in roles">
                                                <input type="radio" class="form-check-input mr-2" v-model="name_role" v-bind:value="role.id">
                                                <span>{{role.role_name}}</span>
                                            </div>
                                            <error-label for="f_grade" :errors="errors.name_role"></error-label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" v-if="name_role==2||name_role==5">
                                    <div class="form-group col-sm-4">
                                        <label>School <span class="text-danger">*</span></label>
                                        <select required  class="form-control form-select"  v-model="entry.school_id" >
                                            <option  :value="null" disabled selected >Choose role</option>
                                            <option v-for="school in schools" :value="school.id">{{school.label}}</option>
                                        </select>
                                        <error-label for="f_grade" :errors="errors.school_id"></error-label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12 mb-5">
                                        <label>Description</label>
                                        <textarea v-model="entry.description" rows="5" class="form-control"
                                                  placeholder="Type the description here (200 characters)"></textarea>
                                        <error-label for="f_grade" :errors="errors.description"></error-label>

                                    </div>
                                </div>
                                <div class="form-check form-check-custom form-check-solid pb-5">
                                    <input id="state" type="checkbox" v-model="entry.state" class="form-check-input h-20px w-20px" checked>
                                    <label for="state" class="form-check-label fw-bold">Active</label>
                                    <error-label for="f_grade" :errors="errors.state"></error-label>
                                </div>
                            </div>
                        </div>
                        <!--<hr style="margin: 0px 0px 16px;">-->
                        <div class="mt-5">
                            <button type="reset" @click="save()"  class="btn btn-primary mr-3"><i class="bi bi-send mr-1"></i>Submit</button>
                            <button type="reset" @click="backIndex()" class="btn btn-light">Cancel</button>
                            <label class="fw-bold ml-4">Username and password will be sent to the user's email.</label>
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
    import _ from "lodash";

    export default {
        name: "UsersForm.vue",
        components: {ActionBar, SwitchButton},
        data() {

            return {
                changed: false,
                user_school:'',
                name_role:'',
                auto_gen:false,
                showConfirm: false,
                showPass: false,
                types: [],
                breadcrumbs: [
                    {
                        title: 'Account management',
                    },
                    {
                        title: 'Manage users',
                        url: '/xadmin/users/index',
                    },
                    {
                        title: $json.entry ? 'Edit User' : 'Create new user',
                    },
                ],
                entry: $json.entry || {
                    role: []
                },
                roles: $json.roles || [],
                schools:$json.schools|| [],
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
            // checkbox_roles()
            // {
            //     this.entry=this.roles;
            // },
            backIndex() {

                window.location.href = '/xadmin/users/index';
            },
            async save() {
                console.log(this.role);

                this.isLoading = true;
                const res = await $post('/xadmin/users/save', {entry: this.entry, name_role: this.name_role,user_school:this.user_school,auto_gen: this.auto_gen}, false);

                this.isLoading = false;
                if (res.errors) {
                    this.errors = res.errors;
                    return;
                }
                if (res.code) {
                    toastr.error(res.message);
                }
                else {
                    this.errors = {};
                    toastr.success(res.message);
                    location.replace('/xadmin/users/index');
                     if (!this.entry.id) {
                         location.replace('/xadmin/users/index');
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
