<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title = "User Device Manager"/>
        <div class="modal fade" style="margin-right:50px " id="sentConfirm" tabindex="-1" role="dialog"
             aria-labelledby="sentConfirm"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                 style="max-width: 500px;">
                <div class="modal-content box-shadow-main paymment-status" style="margin-right:20px; left:140px">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 class="popup-title success" style="margin-top: 20px">Delete Device</h3>
                    <div class="content" style="text-align: center;margin: 0px">
                        <p>Yêu cầu xóa thiết bị khỏi danh sách cần có phê duyệt của Admin.</p>
                        <p > Bạn có muốn gửi yêu cầu tới Admin không? </p>
                    </div>
                    <div  class="form-group d-flex justify-content-between" >
                        <button  class="btn btn-light ito-btn-add" data-dismiss="modal" @click="Cancel()" style="margin-left: 110px">Cancel</button>

                        <button   class="btn btn-primary"  data-dismiss="modal"  style="margin-right: 150px" @click="save">
                            Send request
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" style="margin-right:50px " id="deviceConfirm" tabindex="-1" role="dialog"
             aria-labelledby="deviceConfirm"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                 style="max-width: 500px;">
                <div class="modal-content box-shadow-main paymment-status" style="margin-right:20px; left:140px">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 style="margin:20px auto;font-weight: 500;" class="popup-title success">Add more device</h3>
                    <div class="content" style="margin: 0 20px 20px">
                        <p>Bước 1 :Sử dụng máy tính mà bạn muốn thêm thiết bị mở ứng dụng IDIGI trên Desktop</p>
                        <p>Bước 2:Nhấn vào nút "Get device information" và copy đoạn mã thông tin thiết bị </p>
                        <p>Bước 3:Dán đoạn mã vào ô phía dưới</p>
                        <input type="text" class="form-control " placeholder="Device name" aria-label="" style="margin-bottom: 10px" aria-describedby="basic-addon1" v-model="entry.device_name" >
                        <error-label for="f_category_id" :errors="errors.device_name"></error-label>

                        <input type="text" class="form-control col-2" placeholder="Enter the device information code" aria-label="" aria-describedby="basic-addon1" v-model="entry.device_uid" >
                        <error-label for="f_category_id" :errors="errors.device_uid"></error-label>
                    </div>
                    <div class="form-group d-flex justify-content-between">
                        <!--                        <button  class="btn btn-danger ito-btn-small" data-dismiss="modal" @click="save()">Add now</button>-->
                        <button class="btn btn-primary ito-btn-add" data-dismiss="modal" @click="save_send()" style="margin:0 auto">
                            Add now
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editDeviceName" tabindex="-1" role="dialog"
             aria-labelledby="editDeviceName"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document">
                <div class="modal-content" style="margin-right:20px; left:140px">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 style="margin:20px auto;font-weight: 500;" class="popup-title success">Edit device name</h3>
                    <div class="content">
                        <label>Device Name</label>
                        <input type="text" class="form-control " placeholder="Device name" aria-label=""
                               style="margin-bottom: 10px" aria-describedby="basic-addon1" v-model="Edit_name.device_name">
                        <error-label for="f_category_id" :errors="errors.device_name"></error-label>
                                <div style="text-align:center">
                                    <button class="btn btn-primary" @click="save_name" >Save</button>
                                </div>



                    </div>


                </div>
            </div>
        </div>
        <div class="modal fade" id="editdeviceConfirm" tabindex="-1" role="dialog"
             aria-labelledby="editdeviceConfirm"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document">
                <div class="modal-content" style="margin-right:20px; left:140px">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 style="margin:20px auto;font-weight: 500;" class="popup-title success">  Get confirmation code</h3>
                    <div class="content">
                        <label>Device Name</label>
                        <input type="text" class="form-control " placeholder="Device name" aria-label=""
                               style="margin-bottom: 10px" aria-describedby="basic-addon1" v-model="editDevice"
                               disabled>
                        <error-label for="f_category_id" :errors="errors.device_name"></error-label>
                        <div>
                            <button  type="button" class="btn btn-primary" v-on:click="genToken"> Generate Key</button>
                        </div>
                        <div style="text-align:right"><button type="button" v-if="token" class="btn btn-primary" v-on:click="copyTextToken" title="Copy Token" style="padding: 5px 20px;margin:0px 7px 10px"> Copy</button></div>

                        <div v-if="token" style="font-size: 14px; word-wrap: break-word;white-space: pre-wrap;word-break: normal;background-color: #f7f7f9;">{{token}}</div>
                    </div>


                </div>
            </div>
        </div>

        <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="deviceConfirmLimit" tabindex="-1" role="dialog"
             aria-labelledby="deviceConfirmLimit"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                 style="max-width: 500px;">
                <div class="modal-content box-shadow-main paymment-status" style="left:140px;text-align: center; padding: 27px 0px 10px;">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 class="popup-title success" style="margin-left:25px">Can not add more devices</h3>
                    <div class="content">
                        <p>Bạn chỉ được truy cập vào tối đa 3 thiết bị, hãy xóa bớt thiết bị cũ nếu muốn truy cập vào thiết bị mới.</p>
                    </div>
                </div>
            </div>
        </div>

        <!--<div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column">
                        <table class=" table  table-head-custom table-head-bg table-vertical-center">
                            <thead>
                            <tr>
                                <th class="text-center" width = "50">ID</th>
                                <th>Device</th>
                                <th width = "150"></th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="entry in entries">
                                    <td class="text-center" v-text="entry.id"></td>
                                    <td v-text="entry.device_name"></td>
                                    <td class="text-right">
                                        <a href="#" @click="saveEditName(entry)">
                                            <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </a>
                                        <a @click="remove(entry)" href="javascript:;">
                                            <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary">
                                                <i class="fa fa-trash mr-1 deleted"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>-->

        <!-- <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column" style="height: 563px" >
                        <div class="card-header border-0 pt-5" style="margin: -24px -30px 12px;">
                            <div class="title">
                                <label>User Device</label>
                            </div>
                            <div class="row" >
                                <button v-if="entries.length<3 " type="button" class="col-lg-2 btn btn-primary modal-devices " @click="modalDevice()">
                                    Add more device
                                </button>
                                <button  v-if="entries.length>=3" type="button" class="col-lg-2 btn btn-primary modal-devices " @click="closeModal()">
                                    Add more device
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div>Number of devices: {{entries.length}}/3</div>
                        <div class="">
                            <div style="margin-top: 75px"  class="row" v-for="entry in entries" >
                                <div class="col-lg-12 body "  >
                                    <form  class="form-inline"  >
                                        <div  class="form-group mx-sm-3 mb-2">
                                            <label>{{entry.device_name}}
                                                <i  type="button" class="bi bi-pencil-square" style="margin:0px 15px 0px;color:#333333" @click="saveEditName(entry)"></i>
                                            </label>

                                        </div>
                                    </form>

                                    <div  class="form-group mx-sm-3 mb-2" style="position: absolute;right:24px;margin-top: -33px;" v-if="entry.status==1">

                                        <button type="button"
                                                class="btn btn-flex btn-dark  fw-bolder " v-for="role in entry.role"  v-if="role.id!==5"  @click="remove(entry)">Delete device
                                        </button>
                                        <span v-for="role in entry.role" v-if="role.id==5"
                                              style="color: #f1c40f;margin-right: 5px" ><i class="fas fa-exclamation-circle" style="color: #f1c40f"></i> Delete request sent
                                       </span>
                                        <button  type="button"
                                                 class="btn btn-primary " style="margin-right: 5px" @click="editModalDevice(entry.id,entry.device_name,entry.secret_key)" >
                                            Get confirmation code
                                        </button>

                                    </div>
                                    <div  class="form-group mx-sm-3 mb-2" style="position: absolute;right:30px;margin-top: -33px;" v-if="entry.status!==1">
                                        <button  type="button"
                                                 class="btn btn-primary" style="margin-right: 5px" @click="editModalDevice(entry.id,entry.device_name,entry.secret_key)" >
                                            Get confirmation code
                                        </button>

                                        <button type="button"
                                                class="btn btn-flex btn-danger  fw-bolder " v-for="role in entry.role"  v-if="role.id!==5"  @click="remove(entry)">Delete device
                                        </button>
                                        <button v-for="role in entry.role" v-if="role.id==5" type="button"
                                                class="btn btn-flex btn-danger  fw-bolder " @click="Sent(entry)">Delete device
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div> -->
          <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="card-header border-0 pt-5">

                        <div class="row width-full">
                            <div class="col-lg-12" >
                                <div >
                                    <div class="form-group mx-sm-3 mb-4" >
                                <button v-if="entries.length<3 " type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer" @click="modalDevice()">Add More Device</button>
                                <button  v-if="entries.length>=3" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer" @click="closeModal()">Add More Device</button>

                                    </div>

                                </div>

                            </div>
                        </div>



                    </div>


                    <div class="tab-content">
                        <div class="d-flex flex-stack pt-4 pl-9 pr-9">
                        <div class="badge badge-lg badge-light-primary mb-15">
                                <div class="d-flex align-items-center flex-wrap">



                                    <div  v-if="entries.length > 0">Number of devices: {{entries.length}}/3</div>



                                </div>
                            </div>
                        </div>

                        <table class="table table-row-bordered align-middle gy-4 gs-9">
                            <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                            <tr>
                                <th class="text-center">Device Name</th>
                                <th class="text-center">Status</th>
                                 <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody >
                            <tr v-for="entry in entries">
                                    <td class="text-center" v-text="entry.device_name" ></td>
                                    <td class="text-center" >
                                        <span class="status" v-if="entry.status==2">Active</span>
                                        <span   class="status-request" v-if="entry.status==1 ">Delete request sent</span>

                                    </td>
                                <td class="text-center">
                                    <a href="list.html#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                        <span class="svg-icon svg-icon-5 m-0">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black" />
															</svg>
														</span>
                                        <!--end::Svg Icon--></a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a @click="editModalDevice(entry.id,entry.device_name,entry.secret_key)" class="menu-link px-3">Get confirmation code</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a @click="saveEditName(entry)" class="menu-link px-3">Edit</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3"  v-for="role in entry.role">
                                            <a v-if="role.id!==5" @click="remove(entry)" data-kt-subscriptions-table-filter="delete_row" class="menu-link px-3">Delete</a>
                                            <a v-if="role.id==5 && entry.status==2 " @click="Sent(entry)" data-kt-subscriptions-table-filter="delete_row" class="menu-link px-3">Delete</a>

                                        </div>
                                        <div class="menu-item px-3"  v-for="role in entry.role">
                                            <a v-if="role.id==5 && entry.status==1"  data-kt-subscriptions-table-filter="delete_row" class="menu-link px-3" >Delete</a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
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
    import {$get, $post, getTimeRangeAll} from "../../utils";
    import $router from '../../lib/SimpleRouter';
    import ActionBar from "../includes/ActionBar";
    let created = getTimeRangeAll();
    const $q = $router.getQuery();

    export default {
        name: "User_deviceIndex.vue",
        components: {ActionBar},
        data() {
            return {

              device:'',
                curDevice:{},
                isHidden:false,
                token:'',
                secret_key:"",
                Edit_name:'',
                device_name:"",
                editDevice:"",
                breadcrumbs: [
                    {
                        title: 'User Device'
                    },
                ],
                entries: [],
                limit: 25,
                from: 0,
                to: 0,
                entry: $json.entry || {
                },
                isLoading: false,
                errors:{},
            }
        },
        mounted() {
            $router.on('/', this.load).init();
        },
        methods: {

            Sent: function (device = {}){
                $('#sentConfirm').modal('show');
                this.curDevice = device;
            },
            saveEditName:function(deviceEditName={}){
                $('#editDeviceName').modal('show');
                this.Edit_name=deviceEditName;
            },
            Cancel()
            {
                $('#sentConfirm').modal('hide');
            },
            async genToken(){
                const res  = await $post('/xadmin/user_devices/generateToken', {device_id: this.currId});
                this.token = res.token;
            },

            editModalDevice(id,device_name,secret_key)
            {
                const that=this;
                that.currId = id;
                that.editDevice = device_name;
                that.secret_key=secret_key;
                $('#editdeviceConfirm').modal('show');
            },
            modalDevice() {
                $('#deviceConfirm').modal('show');
            },
            closeModal()
            {
                $('#deviceConfirmLimit').modal('show');
            },
            async load() {

                let query = $router.getQuery();
                this.$loading(true);
                const res  = await $get('/xadmin/user_devices/data', query);
                this.$loading(false);
                setTimeout(function (){
                    KTMenu.createInstances();
                }, 0)
                this.paginate = res.paginate;
                this.entries = res.data;
                console.log(this.entries)
            },
            async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return;
                }
                const res = await $post('/xadmin/user_devices/remove', {id: entry.id});
                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                    location.replace('/xadmin/user_devices/index');

                }
            },
            changeLimit() {
                let params = $router.getQuery();
                params['page']=1;
                params['limit'] = this.limit;
                $router.setQuery(params)
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/user_devices/save', {
                    entry: this.curDevice,
                }, false);
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
                        location.replace('/xadmin/user_devices/index');
                    }

                }
            },
            async save_name() {
                this.isLoading = true;
                const res = await $post('/xadmin/user_devices/save_name', {
                    entry: this.Edit_name,
                }, false);
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
                        location.replace('/xadmin/user_devices/index');
                    }

                }
            },
            async save_send() {
                this.isLoading = true;
                const res = await $post('/xadmin/user_devices/savesend', {
                    entry: this.entry,
                    status: this.status
                }, false);
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
                        location.replace('/xadmin/user_devices/index');
                    }

                }
            },

            async toggleStatus(entry) {
                const res = await $post('/xadmin/user_devices/toggleStatus', {
                    id: entry.id,
                    status: entry.status
                });

                if (res.code === 200) {
                    toastr.success(res.message);
                    location.replace('/xadmin/user_devices/index');
                } else {
                    toastr.error(res.message);

                }

            },

            async getDeviceByUser(entry) {
                const res = await $post('/xadmin/user_devices/getDeviceByUser', {
                    id: entry.id,
                    status: entry.status
                });

                if (res.code === 200) {
                    toastr.success(res.message);
                    location.replace('/xadmin/user_devices/index');
                } else {
                    toastr.error(res.message);

                }

            },
            copyTextToken() {
                navigator.clipboard.writeText(this.token);
            },
            onPageChange(page) {
                $router.updateQuery({page: page})
            }
        }
    }
</script>

<style scoped>
    .popup-title{
        margin-left: 160px;
    }
    .content{
       margin: -10px 39px 14px;
    }
    .form-control{
        max-width: 400px;
    }
    .btn btn-danger ito-btn-small{
        padding: 5px;
    }

    .body{
        padding: 23px;
        width: 100%;
        left: 10px;
        background: #FFFFFF;
        border: 1px solid black;
        border-radius: 20px;
        margin:-70px 0px 10px;
    }
    .modal-devices{
        position: absolute;
        right: 27px;
        max-width: 150px;


    }
    .btn-action{
        background: #696CFF;
    padding: 0.5em 0.85em;
    font-size: .85rem;
    font-weight: 500;
    color: #fff;
    border-radius: 0.475rem;
}
    .status{
        color: #50cd89;
        background-color: #e8fff3;
        padding: 0.5em 0.85em;
        font-size: .85rem;
        font-weight: 600;
        border-radius: 0.475rem;

    }
    .status-request{
       background-color: #fff8dd;
        padding: 0.5em 0.85em;
        font-size: .85rem;
        font-weight: 600;
        border-radius: 0.475rem;
        color:#ffc700;

    }



</style>
