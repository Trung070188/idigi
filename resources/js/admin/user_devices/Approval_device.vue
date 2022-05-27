<template>
    <div class="container-fluid" >
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"
                   title="Approval Device"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="modal fade"  id="editdeviceConfirm" tabindex="-1" role="dialog"
                         aria-labelledby="editdeviceConfirm"
                         aria-hidden="true">

                        <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                             style="max-width: 500px;">
                            <div class="modal-content box-shadow-main paymment-status" style="margin-right:20px; left:140px">
                                <div class="close-popup" data-dismiss="modal"></div>
                                <h3 class="popup-title success" style="text-align: center">Approval device</h3>
                                <input v-model="curDevice.id" type="hidden" name="id" value="">
                                <div class="content">
                            <textarea style="width: 100%;height: 50px" v-model="curDevice.reason"  class="form-control" placeholder="Nhập lí do hủy thiết bị" ></textarea>
                                    <error-label for="f_role_name" :errors="errors.reason"></error-label>
                                    <div>
                                    </div>
                                </div>


                                <div class="form-group d-flex justify-content-between">
                                    <button  class="btn btn-danger ito-btn-small" style="margin-left: 200px" data-dismiss="modal"  @click="save" >Refuse now</button>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column">
<!--                        <div v-text="'Showing '+ from +' to '+ to +' of '+ paginate.totalRecord +' entries'"-->
<!--                             v-if="entries.length > 0"></div>-->
                        <table class=" table  table-head-custom table-head-bg table-vertical-center">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Device UID</th>
                                <th>Device Name</th>
                                <th>User Name</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="entry in entries" v-if="entry.status==1">
                                <td v-text="entry.id"></td>
                                <td v-text="entry.device_uid"></td>
                                <td v-text="entry.device_name"></td>
                                <td v-for="user in entry.users" v-if="entry.user_id==user.id" v-text="user.username"></td>
                                <td style="color:#FFAC32">Waiting for administrator verify</td>

                                <td >

                                    <button style="background:#50f14b;color: #f1f1f1;border-color: #50f14b;margin: 10px;" class="" @click="toggleStatus_approval(entry)">
                                  Approval
                                    </button>
                                        <button   @click="editModalDevice(entry)" class="btn-danger" >Refuse</button>
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
                curDevice:{},
                breadcrumbs: [
                    {
                        title: 'Approval Device'
                    },
                ],
                entries: [],
                users:[],

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

            editModalDevice: function (device = {}){
                $('#editdeviceConfirm').modal('show');
                this.curDevice = device;

            },

            async save() {
                const res = await $post('/xadmin/user_devices/save', {
                    entry: this.curDevice,
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
                        location.replace('/xadmin/user_devices/approval');
                    }

                }
            },
            async load() {

                let query = $router.getQuery();
                this.$loading(true);
                const res  = await $get('/xadmin/user_devices/data_approval', query);
                this.$loading(false);
                this.paginate = res.paginate;
                this.entries = res.data.entries;
                console.log(this.entries);
                this.from = (this.paginate.currentPage-1)*(this.limit) + 1;
                this.to = (this.paginate.currentPage-1)*(this.limit) + this.entries.length;
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
                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});
            },
            changeLimit() {
                let params = $router.getQuery();
                params['page']=1;
                params['limit'] = this.limit;
                $router.setQuery(params)
            },

            async toggleStatus_approval(entry) {
                const res = await $post('/xadmin/user_devices/toggleStatus_approval', {
                    id: entry.id,
                    status: entry.status
                });

                if (res.code === 200) {
                    toastr.success(res.message);
                    location.replace('/xadmin/user_devices/approval');
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
            onPageChange(page) {
                $router.updateQuery({page: page})
            }
        }
    }
</script>

<style scoped>

</style>
