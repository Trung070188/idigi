<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"
                   title="User Device"/>
        <div class="modal fade" style="margin-right:50px " id="sentConfirm" tabindex="-1" role="dialog"
             aria-labelledby="sentConfirm"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                 style="max-width: 500px;">
                <div class="modal-content box-shadow-main paymment-status" style="margin-right:20px; left:140px">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 class="popup-title success" style="margin-top: 20px">Delete device</h3>
                    <div class="content" style="text-align: center;margin: 0px">
                        <p>Yêu cầu xóa thiết bị khỏi danh sách cần có phê duyệt của Admin.</p>
                        <p > Bạn có muốn gửi yêu cầu tới Admin không? </p>
                    </div>
                    <div  class="form-group d-flex justify-content-between" >
                        <button  class="btn btn-dark ito-btn-small" data-dismiss="modal" @click="Cancel()" style="margin-left: 110px">Cancel</button>

                        <button   class="btn btn-light ito-btn-add"  data-dismiss="modal"  style="margin-right: 150px" @click="save">
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
                    <h3 class="popup-title success">Add more device</h3>
                    <div class="content">
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
                        <button class="btn btn-dark ito-btn-add" data-dismiss="modal" @click="save_send()" style="margin-left: 170px">
                            Add now
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editdeviceConfirm" tabindex="-1" role="dialog"
             aria-labelledby="editdeviceConfirm"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document">
                <div class="modal-content">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h2 style="margin-top: 30px" class="popup-title success">Thông tin device</h2>
                    <div class="content">
                        <label>Device Name</label>
                        <input type="text" class="form-control " placeholder="Device name" aria-label=""
                               style="margin-bottom: 10px" aria-describedby="basic-addon1" v-model="editDevice"
                               disabled>
                        <error-label for="f_category_id" :errors="errors.device_name"></error-label>
                        <div>
                            <button  type="button" class="generate" v-on:click="genToken"> Generate Key</button>
                        </div>
                        <div style="text-align:right"><button type="button" v-if="token" class="btn-primary" v-on:click="copyTextToken" title="Copy Token"> Copy</button></div>

                        <div v-if="token" style="font-size: 16px; word-wrap: break-word;white-space: pre-wrap;word-break: normal;">{{token}}</div>
                    </div>


                </div>
            </div>
        </div>
        <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="deviceConfirmLimit" tabindex="-1" role="dialog"
             aria-labelledby="deviceConfirmLimit"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                 style="max-width: 500px;">
                <div class="modal-content box-shadow-main paymment-status" style="margin-right:20px; left:140px;margin-bottom: 200px;padding: 20px">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 class="popup-title success" style="margin-left:100px">Can not add more devices</h3>
                    <div class="content">
                        <p>Bạn chỉ được truy cập vào tối đa 3 thiết bị, hãy xóa bớt thiết bị cũ nếu muốn truy cập vào thiết bị mới.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column" style="height: 563px" >
                        <div v-for="entry in entries"  class="row" v-if="entry.id==auth.id">
                            <button     v-if="entry.user_devices.length<3 " type="button" class="col-lg-2 btn btn-primary modal-devices " @click="modalDevice()">
                                Add more device
                            </button>
                            <button  v-if="entry.user_devices.length>=3" type="button" class="col-lg-2 btn btn-primary modal-devices " @click="closeModal()">
                                Add more device
                            </button>
                        </div>
                        <div   class="row width-full" v-for="entry in entries" v-if="entry.id==auth.id">
                            <div class="col-lg-12 body " v-for="device in entry.user_devices" >
                                <form  class="form-inline"  >
                                    <div  class="form-group mx-sm-3 mb-2">
                                        <label>{{device.device_name}}</label>
                                    </div>
                                </form>

                                <div  class="form-group mx-sm-3 mb-2" style="position: absolute;right:65px;margin-top: -33px;" v-if="device.status==1">

                                    <button type="button"
                                            class="btn btn-flex btn-dark  fw-bolder " v-for="role in entry.roles"  v-if="role.id!==5"  @click="remove(device)">Delete device
                                    </button>
                                    <span v-for="role in entry.roles" v-if="role.id==5"
                                          style="color: #f1c40f;margin-right: 5px" ><i class="fas fa-exclamation-circle" style="color: #f1c40f"></i> Delete request sent
                                       </span>
                                    <button  type="button"
                                             class="btn btn-flex btn-dark  fw-verify " style="margin-right: 5px" @click="editModalDevice(device.id,device.device_name,device.secret_key)" >
                                        Get activity code
                                    </button>

                                </div>
                                <div  class="form-group mx-sm-3 mb-2" style="position: absolute;right:65px;margin-top: -33px;" v-if="device.status!==1">
                                    <button  type="button"
                                             class="btn btn-flex btn-secondary  fw-verify " style="margin-right: 5px" @click="editModalDevice(device.id,device.device_name,device.secret_key)" >
                                        Get activity code
                                    </button>

                                    <button type="button"
                                            class="btn btn-flex btn-danger  fw-bolder " v-for="role in entry.roles"  v-if="role.id!==5"  @click="remove(device)">Delete device
                                    </button>
                                    <button v-for="role in entry.roles" v-if="role.id==5" type="button"
                                            class="btn btn-flex btn-info  fw-bolder " @click="Sent(device)">Delete device
                                    </button>
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
                curDevice:{},
                isHidden:false,
                token:'',
                secret_key:"",
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
                this.paginate = res.paginate;
                this.entries = res.users;
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
        margin: 30px;
    }
    .form-control{
        max-width: 400px;
    }
    .btn btn-danger ito-btn-small{
        padding: 5px;
    }

    .body{
        padding: 20px;
        /*max-width: 1612px;*/
        box-sizing: border-box;
        position: static;
        width: 100%;
        left: 0px;
        top: 0px;
        background: #FFFFFF;
        border: 1px solid black;
        border-radius: 44px;
        margin-top: 70px;
    }
    .modal-devices{
        position: absolute;
        right: 20px;
        max-width: 150px;


    }
    .generate{
        padding: 8px 18px;
        border-radius: 8px;
        margin-bottom: 40px;
        background: #008cff;
        color: #ffffff;

    }

</style>
