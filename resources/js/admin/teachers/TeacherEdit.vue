<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   back-url="/xadmin/users/index"
                   :breadcrumbs="breadcrumbs"
                   title="TeacherEdit"/>
        <div class="modal fade" style="margin-right:50px " id="deviceConfirm" tabindex="-1" role="dialog"
             aria-labelledby="deviceConfirm"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                 style="max-width: 500px;">
                <div class="modal-content box-shadow-main paymment-status" style="margin-right:20px; left:140px">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 class="popup-title success" style="text-align: center">Remove teacher device</h3>
                    <div class="content" style="text-align: center">
                        <p>Bạn có chắc chắn muốn hủy đăng ký thiết bị Device name của giáo viên không?</p>
                          <p>  Giáo viên sẽ không thể tiếp tục sử dụng thiết bị này để cài đặt bài giảng.</p>
                    </div>
                    <div class="form-group d-flex justify-content-between" style="margin: auto;margin-bottom: 20px">
                        <button class="btn btn-primary ito-btn-add" data-dismiss="modal" style="margin-right: 5px" data-bs-dismiss="modal">
                           Cancel
                        </button>
                        <button v-for="device in user_device" v-if="device.id==currId" class="btn btn-danger ito-btn-small" data-dismiss="modal" @click="remove_device(device)" >Accept remove</button>
                    </div>
                </div>
            </div>
        </div>
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
                                        <input class="form-control" v-model="entry.school_id">
                                        <error-label for="f_category_id" :errors="errors.school_id"></error-label>
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
                            </div>
                        </div>
                        <hr>
                        <div>
                            <button type="reset" @click="save()" class="btn btn-primary mr-2">Save change</button>
                            <button type="reset" @click="backIndex()" class="btn btn-secondary">Cancel</button>
                        </div>
                        <hr>
                        <h2>Current registed devices</h2>
                        <table class=" table  table-head-custom table-head-bg table-vertical-center">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Device name</th>
                                <th>Device detail</th>
                                <th>Registed date</th>
                                <th></th>
                                <th></th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="device in user_device" v-if="device.user_id===entry.id && device.status===2  ">
                                <td v-text="device.id"></td>
                                <td v-text="device.device_name"></td>
                                <td v-text="device.device_uid"></td>
                                <td v-text="d(device.created_at)"></td>
                                <td></td>
                                <td>
                                    <a @click="modalDevice(device.id)" href="javascript:;" class="btn-trash deleted"><i
                                        class="fa fa-trash mr-1 deleted"></i></a>
                                </td>
                            </tr>
                            <tr v-for="device in user_device" v-if=" device.user_id===entry.id && device.status===1 ">
                                <td v-text="device.id"></td>
                                <td v-text="device.device_name"></td>
                                <td v-text="device.device_uid"></td>
                                <td v-text="d(device.created_at)"></td>
                                <td style="color: #f1c40f">Deleting request</td>
                                <td>
                                    <a @click="modalDevice(device.id)" href="javascript:;" class="btn-trash deleted"><i
                                        class="fa fa-trash mr-1 deleted"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <h2>Devices activities</h2>
                        <table class=" table  table-head-custom table-head-bg table-vertical-center">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                                <th>Device detail</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr >
                                <td v-text=""></td>
                                <td v-text=""></td>
                                <td v-text=""></td>
                                <td>

                                </td>
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
    import {$post} from "../../utils";

    import ActionBar from "../includes/ActionBar";
    import SwitchButton from "../../components/SwitchButton";
    import $router from "../../lib/SimpleRouter";

    export default {
        name: "TeacherEdit.vue",
        components: {ActionBar, SwitchButton},
        data() {

            return {

                showConfirm: false,
                showPass: false,
                types: [],
                currId:'',
                breadcrumbs: [
                    {
                        title: 'Techers',
                        url: '/xadmin/users/index_teacher',
                    },
                    {
                        title: $json.entry ? 'Edit Teacher' : 'Create new User',
                    },
                ],
                entry: $json.entry || {
                    roles: []
                },
                user_device: $json.user_device || [],
                isLoading: false,
                errors: {}
            }
        },
        methods: {
            modalDevice(id) {
                const that=this;
                that.currId = id;
                console.log(that.currId);
                $('#deviceConfirm').modal('show');

            },

            backIndex() {

                window.location.href = '/xadmin/users/index';
            },
            async remove_device(entry) {

                const res = await $post('/xadmin/users/remove_device', {id: entry.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }
                window.location.reload();

                $router.updateQuery({ _: Date.now()});

            },

            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/users/save', {entry: this.entry, roles: this.roles}, false);
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
                        location.replace('/xadmin/users/edit_teacher?id=' + res.id);
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
