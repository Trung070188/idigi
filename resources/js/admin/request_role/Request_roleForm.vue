<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   back-url="dashboard/index"
                   :breadcrumbs="breadcrumbs"/>
        <div class="  ">

            <!-- Modal -->
            <!--            <div class="modal fade" id="deviceConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">-->
            <!--                <div class="modal-dialog modal-dialog-centered" role="document">-->
            <!--                    <div class="modal-content">-->
            <!--                        <div class="modal-header">-->
            <!--                            <h5 class="modal-title" id="exampleModalLongTitle">Change password</h5>-->
            <!--                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
            <!--                                <span aria-hidden="true">&times;</span>-->
            <!--                            </button>-->
            <!--                        </div>-->
            <!--                        <div class="modal-body">-->
            <!--                            <div class="form-group">-->
            <!--                                <label>Current Password <span class="text-danger">*</span></label>-->
            <!--                                <input id="f_role_name" v-model="entry.old_password" type="password" name="old_password" class="form-control"-->
            <!--                                       placeholder="" >-->
            <!--                                <error-label for="f_role_name"  :errors="errors.old_password" ></error-label>-->
            <!--                            </div>-->
            <!--                            <div class="form-group">-->
            <!--                                <label>New Password <span class="text-danger">*</span></label>-->
            <!--                                <input id="f_role_description" v-model="entry.password" type="password" name="password" class="form-control "-->
            <!--                                       placeholder="" >-->

            <!--                                <error-label for="f_role_description"  :errors="errors.password"></error-label>-->

            <!--                            </div>-->
            <!--                            <div class="form-group">-->
            <!--                                <label>Confirm New Password <span class="text-danger">*</span></label>-->
            <!--                                <input  name="new_password_confirmation" v-model="entry.confirm_password" type="password" class="form-control"-->
            <!--                                        placeholder="" >-->
            <!--                                <error-label for="f_role_description" :errors="errors.confirm_password" ></error-label>-->

            <!--                            </div>-->
            <!--                        </div>-->
            <!--                        <div class="modal-footer">-->
            <!--                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="CloseModal()">Close</button>-->
            <!--                            <button type="button" class="btn btn-primary" @click="updatePassword()">Save changes</button>-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->


            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-custom card-stretch gutter-b">
                        <div class="card-header border-0 pt-5">
                            <div class="title">
                                <label>User profile</label>
                            </div>

                        </div>
                        <hr>
                        <div class="card-header border-0 pt-5">

                            <div class="row width-full">
                                <div class="col-lg-12">

                                    <div class="tab-content profile-page">
                                        <!-- PROFILE TAB CONTENT -->
                                        <div class="tab-pane profile active" id="profile-tab">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="user-info-left">
                                                        <div class="nva">

                                                        </div>
                                                        <div class="contact" style="margin-top: 20px"
                                                             v-if="entry.status=='Aprrove'">
                                                            <a href="#" class="btn-block" @click="modalDevice()">Reset
                                                                password</a>
                                                            <a href="/xadmin/logout" class=" btn-block">Deactive
                                                                account</a>

                                                        </div>
                                                        <div class="contact" style="margin-top: 20px"
                                                             v-if="entry.status=='Waiting'">
                                                            <button class="btn btn-primary"
                                                                    @click="toggleStatus(entry)">Verify account
                                                            </button>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="user-info-right">
                                                        <div class="basic-info">
                                                            <p class="data-row col-sm-6 ">
                                                                <label>Fullname </label>
                                                                <input class="form-control" disabled
                                                                       placeholder="Enter the full name"
                                                                       v-model="user.full_name"/>
                                                            </p>
                                                            <p class="data-row col-sm-6 ">
                                                                <label>Email </label>
                                                                <input class="form-control" disabled
                                                                       placeholder="Enter the full name"
                                                                       v-model="user.email"/>
                                                            <p class="data-row col-sm-6 ">
                                                                <label>Username </label>
                                                                <input class="form-control" disabled
                                                                       v-model="user.username"/>
                                                            </p>
                                                            <div class="data-row col-sm-6 " v-if="entry.status=='Waiting'">
                                                                <label>Role </label>
                                                                <input class="form-control" disabled
                                                                       v-model="entry.role_name"/>
                                                            </div>
                                                            <div class="data-row col-sm-6 " v-if="entry.status=='Aprrove' && entry.role_name=='Teacher'" >
                                                                <label>Role </label>
                                                                <input class="form-control" disabled
                                                                       v-model="entry.role_name"/>
                                                            </div>
                                                            <div class="data-row col-sm-6 " v-if="entry.status=='Aprrove' && entry.role_name=='Moderator'">
                                                                <label>Role </label>
                                                                <select class="form-control form-select" required
                                                                       v-model="role">
                                                                    <option value="" disabled selected>{{roles}}</option>
                                                                    <option value="12">Moderator</option>
                                                                    <option  value="2">Administrator</option>
                                                                    <option value="13">Partner</option>
                                                                    <option value="5">Teacher</option>
                                                                </select>


                                                            </div>
                                                            <div class="data-row col-sm-6 " style="margin: 14px 0px 0px;" v-if="entry.status=='Aprrove' && entry.role_name=='Teacher'">
                                                                <label>School </label>
                                                                <input class="form-control" disabled
                                                                       v-model="school.school_name"/>
                                                            </div>

                                                            <div  v-if="entry.status=='Aprrove' && entry.role_name=='Moderator'" class="data-row col-sm-6 " style="margin: 22px 0px 0px;">
                                                                <button  class="btn btn-primary" @click="save_role()">Save
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
    import {$get, $post} from "../../utils";
    import FileManagerInput from "../../components/FileManagerInput";
    import ActionBar from "../includes/ActionBar";
    import UploadImage from "../../components/UploadImage";

    export default {
        name: "Request_roleForm.vue",
        components: {ActionBar, UploadImage},
        data() {
            return {
                check_role: [],
                breadcrumbs: [
                    {
                        title: ''
                    },

                ],
                role:'',
                entry: $json.entry || {},
                user: $json.user || {},
                school:$json.school||{},
                roles:$json.roles||{},
                isLoading: false,
                errors: {}
            }
        },
        mounted() {
        },
        methods: {
            backIndex() {

                window.location.href = '/xadmin/dashboard/index';
            },
            async toggleStatus(entry) {
                const res = await $post('/xadmin/request_roles/toggleStatus', {
                    id: entry.id,
                    status: entry.status
                });

                if (res.code === 200) {
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message);
                }
                location.replace('/xadmin/request_roles/edit?id=' + entry.id);
            },
            async save_role() {
                this.isLoading = true;
                const res = await $post('/xadmin/request_roles/save_role', {entry: this.entry, role: this.role}, false);

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
                        location.replace('/xadmin/request_roles/edit?id=' + res.id);
                    }
                }
            }

        }
    }
</script>

<style scoped>


    .tab-content.profile-page {
        padding: 35px 15px;
    }

    .profile .user-info-left {
        text-align: center;
    }

    .profile .user-info-left,
    .profile .user-info-right {
        padding: 10px 0;
    }

    .profile .user-info-left img {
        border: 3px solid #fff;
    }

    .profile .user-info-left h2 {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 1.3em;
        margin-bottom: 20px;
    }

    .user-info-left .btn {
        border-radius: 0px;
    }

    .profile .user-info-left ul.social a {
        font-size: 20px;
        color: #b9b9b9;
    }

    .profile .user-info-right {
        border-left: 1px solid #ddd;
        padding-left: 30px;
    }


    hr {
        border-top-color: #ddd;
    }

    .form-horizontal .control-label {
        text-align: left;
    }


    .btn-block {
        background: #000000;
        color: #f1f1f1;
        border: 1px solid #000000;
        border-radius: 32px;
        align-items: center;
        padding: 7px 15px 9px;
        gap: 6px;

    }


    option[value=""][disabled] {
        display: none;
    }

    option {
        color: black;
    }
    .nva{
        width: 150px;
        height: 150px;
        margin: 0 auto;
        background: #EFF0F6;
        border-radius: 50%;
    }



</style>
