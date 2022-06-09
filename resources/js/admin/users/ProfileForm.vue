<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   back-url="dashboard/index"
                   :breadcrumbs = "breadcrumbs"
                   title="ProfileForm"/>
        <div class="container bootstrap snippets bootdey">

            <!-- Modal -->
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
                            <label>Current Password <span class="text-danger">*</span></label>
                            <input id="f_role_name"  name="name" class="form-control"
                                   placeholder="" >
                            <error-label for="f_role_name" ></error-label>

                            <div class="form-group">
                                <label>New Password <span class="text-danger">*</span></label>
                                <input id="f_role_description"  name="name" class="form-control"
                                       placeholder="" >
                                <error-label for="f_role_description" ></error-label>

                            </div>

                            <div class="form-group">
                                <label>Confirm New Password <span class="text-danger">*</span></label>
                                <input  name="name" class="form-control"
                                        placeholder="" >
                                <error-label for="f_role_description" ></error-label>

                            </div>
                        </div>


                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"@click="CloseModal()">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="main-content">

                    <div class="tab-content profile-page">
                        <!-- PROFILE TAB CONTENT -->
                        <div class="tab-pane profile active" id="profile-tab">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="user-info-left">
                                        <div class="profile-avatar-upload">
                                            <div class="profile-avatar-upload-c"
                                                >
                                            </div>
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
                                                <input  class="form-control" placeholder="Enter the full name" v-model="entry.full_name" />
                                            </p>
                                            <p class="data-row col-sm-6 " >
                                                <label >Email </label>
                                                <input  class="form-control" placeholder="Enter the full name" v-model="entry.email" />
                                            </p>
                                            <p class="data-row col-sm-6 " >
                                                <label >Username </label>
                                                <input  class="form-control" disabled v-model="entry.username" />
                                            </p>
                                            <div  class="data-row col-sm-6 " >
                                                <label   >Role </label>
                                                <input  class="form-control" disabled  />
                                                <div  v-for="role in entries">
                                                    <div class="role" v-if="role.id==auth.id">
                                                        {{role.role}}
                                                    </div>
                                                </div>
                                          </div>
                                            <div class="data-row col-sm-6 " >
                                                <button type="reset" @click="save()" class="btn btn-primary mr-2">Save</button>
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

    import ActionBar from "../includes/ActionBar";
    import SwitchButton from "../../components/SwitchButton";
    import $router from "../../lib/SimpleRouter";
    import Uploader from "../../components/Uploader";

    export default {
        name: "ProfileForm.vue",
        components: {Uploader, ActionBar,SwitchButton},
        data() {
            return {
                check_role:[],
                types: [

                ],
                breadcrumbs: [
                    {
                        title:'Profile'
                    },

                ],
                entries: [],
                entry: $json.entry || {},
                roles:$json.roles || [],
                isLoading: false,
                errors: {}
            }
        },
        mounted() {
            $router.on('/', this.load).init();
        },
        methods: {
            modalDevice() {
                $('#deviceConfirm').modal('show');
            },
            CloseModal()
            {
                $('#deviceConfirm').modal('hide');
            },
            backIndex(){

                window.location.href = '/xadmin/dashboard/index';
            },
            async load() {
                let query = $router.getQuery();
                this.$loading(true);
                const res  = await $get('/xadmin/users/data', query);
                this.$loading(false);
                this.paginate = res.paginate;
                this.entries = res.data.data;
                console.log(this.entries);
                this.from = (this.paginate.currentPage-1)*(this.limit) + 1;
                this.to = (this.paginate.currentPage-1)*(this.limit) + this.entries.length;
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
    .role{
        margin-bottom: 22px;
        margin-top: -30px;
        margin-left: 14px;
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
    .profile-avatar-upload-c{
        margin-left: 20%;
        width: 135px;
        height: 135px;
        border-radius: 100%;
        background-color: #fff;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;

    }



</style>
