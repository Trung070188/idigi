<template>

    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   back-url="dashboard/index"
                   :breadcrumbs = "breadcrumbs" title="My Profile"/>

        <div>

            <div class="modal fade" id="deviceConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Change password</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Current Password <span class="text-danger">*</span></label>
                                <input id="f_role_name" v-model="entry.old_password" type="password" name="old_password" class="form-control"
                                       placeholder="" >
                                <error-label for="f_role_name"  :errors="errors.old_password" ></error-label>
                            </div>
                            <div class="form-group">
                                <label>New Password <span class="text-danger">*</span></label>
                                <input id="f_role_description" v-model="entry.password" type="password" name="password" class="form-control "
                                       placeholder="" >

                                <error-label for="f_role_description"  :errors="errors.password"></error-label>

                            </div>
                            <div class="form-group">
                                <label>Confirm New Password <span class="text-danger">*</span></label>
                                <input  name="new_password_confirmation" v-model="entry.confirm_password" type="password" class="form-control"
                                        placeholder="" >
                                <error-label for="f_role_description" :errors="errors.confirm_password" ></error-label>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="CloseModal()">Close</button>
                            <button type="button" class="btn btn-primary" @click="updatePassword()">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-5 mb-xl-10">
                <div class="card-body pt-9 pb-0">
                    <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                        <div class="me-7 mb-4">
                            <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url(/themes/admin/assets/media/avatars/blank.png)">
                                <div class="image-input-wrapper w-160px h-160px" :style="'background-image: url('+entry.file_image_new.uri+')'"></div>
                                <label class="" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change avatar">
                                    <!--<i class="bi bi-pencil-fill fs-7"></i>-->
                                    <input type="file" @change="fileChanged()" ref="uploader" name="avatar" accept=".png, .jpg, .jpeg" />
                                    <input type="hidden" name="avatar_remove">
                                </label>
                                <!--<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel avatar">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove avatar">
                                    <i class="bi bi-x fs-2"></i>
                                </span>-->
                            </div>

                        </div>

                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-2">
                                        <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1">{{entry.username}}</a>
                                    </div>
                                    <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                                        <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                        <span class="svg-icon svg-icon-4 me-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3" d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z" fill="black"></path>
                                                <path d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z" fill="black"></path>
                                            </svg>
                                        </span>
                                        {{role}}</a>
                                        <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                        <span class="svg-icon svg-icon-4 me-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3" d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z" fill="black"></path>
                                                <path d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z" fill="black"></path>
                                            </svg>
                                        </span>
                                        {{entry.email}}</a>
                                    </div>
                                </div>
                                <div class="d-flex my-4">
                                    <a href="#" class="btn btn-sm btn-primary me-3" @click="chooseFile()">Change Avatar</a>
                                    <a href="#" class="btn btn-sm btn-primary me-3" @click="modalDevice()">Set Password</a>
                                </div>

                            </div>
                            <div class="d-flex flex-wrap flex-stack">
                                <div class="d-flex flex-column flex-grow-1 pe-8">
                                    <div class="d-flex flex-wrap">
                                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="black"></rect>
                                                        <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="black"></path>
                                                    </svg>
                                                </span>
                                                <div class="fs-2 fw-bolder counted">100</div>
                                            </div>
                                            <div class="fw-bold fs-6 text-gray-400">Downloaded</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center w-200px w-sm-300px flex-column mt-3">
                                    <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                        <span class="fw-bold fs-6 text-gray-400">Device Registration</span>
                                        <span class="fw-bolder fs-6">100%</span>
                                    </div>
                                    <div class="h-5px mx-3 w-100 bg-light mb-3">
                                        <div class="bg-success rounded h-5px" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">Profile Details</h3>
                    </div>
                </div>
                <div id="kt_account_settings_profile_details" class="collapse show">
                    <form id="kt_account_profile_details_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                        <div class="card-body border-top p-9">
                            <div class="row mb-6">
                                <label class="col-lg-2 col-form-label fw-bold fs-6">Fullname</label>

                                <div class="col-lg-10 fv-row fv-plugins-icon-container">
                                    <input type="text" name="full_name" class="form-control form-control-lg form-control-solid" placeholder="Enter the Fullname" v-model="entry.full_name" />
                                    <div class="fv-plugins-message-container invalid-feedback" v-if="errors.full_name">{{errors.full_name}}</div>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-2 col-form-label fw-bold fs-6">Email</label>

                                <div class="col-lg-10 fv-row fv-plugins-icon-container">
                                    <input type="text" name="email" class="form-control form-control-lg form-control-solid" placeholder="Enter the email" v-model="entry.email" />
                                    <div class="fv-plugins-message-container invalid-feedback" v-if="errors.email">{{errors.email}}</div>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-2 col-form-label fw-bold fs-6">Username</label>

                                <div class="col-lg-10 fv-row fv-plugins-icon-container">
                                    <input type="text" name="username" class="form-control form-control-lg form-control-solid" placeholder="Enter the username" v-model="entry.username" disabled />
                                    <div class="fv-plugins-message-container invalid-feedback" v-if="errors.username">{{errors.username}}</div>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-2 col-form-label fw-bold fs-6">Role</label>

                                <div class="col-lg-10 fv-row fv-plugins-icon-container">
                                    <input type="text" name="role" class="form-control form-control-lg form-control-solid" placeholder="Enter the role" v-model="role" disabled />
                                    <div class="fv-plugins-message-container invalid-feedback" v-if="errors.role">{{errors.role}}</div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <button type="button" class="btn btn-primary" @click="save_profile()" :disabled="!changed">Save Changes</button>
                        </div>

                    </form>
                </div>
            </div>

            <!--<div class="row">
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
                        <div class="tab-pane profile active" id="profile-tab">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="user-info-left" >
                                        <div  class="contact">

                                            <upload-image v-model="entry.file_image_new"  :hide-preview="true"></upload-image>

                                        </div>
                                        <div class="contact" style="margin-top: 20px">
                                            <a href="#" class="btn-block" @click="modalDevice()">Change password</a>
                                            <a href="/xadmin/logout" class=" btn-block"> Log out</a>

                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="user-info-right">
                                        <div class="basic-info">
                                            <p class="data-row col-sm-6 " >
                                                <label >Fullname </label>
                                                <input  class="form-control" placeholder="Enter the full name" v-model="entry.full_name"  />
                                                <error-label for="f_category_id"
                                                             :errors="errors.full_name"></error-label>
                                            </p>
                                            <p class="data-row col-sm-6 " >
                                                <label >Email </label>
                                                <input  class="form-control" placeholder="Enter the full name" v-model="entry.email"  />
                                                <error-label for="f_category_id"
                                                             :errors="errors.email"></error-label>
                                            </p>
                                            <p class="data-row col-sm-6 " >
                                                <label >Username </label>
                                                <input  class="form-control" disabled v-model="entry.username"  />
                                                <error-label for="f_category_id"
                                                             :errors="errors.username"></error-label>
                                            </p>
                                            <div  class="data-row col-sm-6 " >
                                                <label   >Role </label>
                                                <input  class="form-control" disabled  v-model="role" />
                                          </div>
                                            <div class="data-row col-sm-6 " >
                                                <button type="reset" @click="save_profile()" :disabled="!changed" class="btn btn-primary mr-2" style="margin:14px 0px 0px">Save</button>
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
            </div>-->

        </div>

    </div>

</template>
<script>
    import {$get, $post, $upload} from "../../utils";
    import FileManagerInput from "../../components/FileManagerInput";
    import ActionBar from "../includes/ActionBar";
    import UploadImage from "../../components/UploadImage";
    import _ from 'lodash';
    export default {
        name: "ProfileForm.vue",
        components: { ActionBar,UploadImage},

        data() {
            return {
                changed: false,
                isSaved:true,
                notYetClicked: true,
                check_role:[],
                breadcrumbs: [
                    {
                        title:'Profile'
                    },

                ],
                entry: $json.entry || {},
                role:$json.role || [],
                isLoading: false,
                errors: {}
            }
        },
        watch: {
            entry: {
                handler(value){
                    if(value) {
                        this.changed = !_.isEqual(value, this.actual);
                    }
                },
                deep: true,
            }
        },
        mounted() {
        },
        methods: {
            modalDevice() {
                $('#deviceConfirm').modal('show');
            },
            CloseModal() {
                $('#deviceConfirm').modal('hide');
            },
            backIndex() {
                window.location.href = '/xadmin/dashboard/index';
            },
            async save_profile() {
                this.isLoading = true;
                const res = await $post('/xadmin/users/saveProfile', {entry: this.entry,role:this.role}, false);
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
                        location.replace('/xadmin/users/profile?id=' + res.id);
                    }

                }
            },
            async updatePassword() {
                this.isLoading = true;
                const res = await $post('/xadmin/users/updatePassword',{entry: this.entry}, false);
                this.isLoading = false;
                if (res.errors) {
                    this.errors = res.errors;
                }
                if (res.code) {
                    toastr.error(res.message);
                } else {
                    this.errors = {};
                    toastr.success(res.message);
                    location.replace('/xadmin/users/profile?id=' + res.id);
                }
            },
            chooseFile() {
                this.$refs.uploader.click();
            },
            fileMap(file) {
                return {
                    id: file.id,
                    uri: file.url
                };
            },
            async fileChanged() {
                const files = this.$refs.uploader.files;

                if (files.length > 0) {
                    const res = await $upload('/xadmin/files/upload', files);
                    if (res.code !== 200) {
                        toastr.error(res.message);
                    } else {
                        toastr.success(res.message);

                        this.entry.file_image_new = this.fileMap(res.file);

                        console.log(this.entry);
                    }

                }

            },
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

    .user-info-left .btn{
        border-radius:0px;
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
    .btn-block{
        background: #000000;
        color: #f1f1f1;
        border: 1px solid #000000;
        border-radius: 32px;
        align-items: center;
        padding: 7px 15px 9px;
        gap: 6px;

    }




</style>
