<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"
                   title="User Device"/>

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
                        <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <div class="form-group d-flex justify-content-between">
                        <button class="btn btn-danger ito-btn-small" data-dismiss="modal">Add now</button>
                        <button class="btn btn-primary ito-btn-add" data-dismiss="modal">
                            Add & send verify request
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column" style="height: 563px" >
                        <div class="row">
                            <button type="button" class="col-lg-2 btn btn-danger modal-devices " @click="modalDevice()">
                                Add more device
                            </button>
                        </div>
                        <div class="row width-full" >
                            <div class="col-lg-12 body ">
                                <form class="form-inline">
                                    <div class="form-group mx-sm-3 mb-2">
                                        <label></label>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label></label>
                                    </div>

                                    <div class="form-group mx-sm-3 mb-2" style="position: absolute;right:65px">
                                        <button type="button"
                                                class="btn btn-flex btn-light  fw-bolder ">Delete
                                        </button>
                                    </div>

                                </form>

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
                breadcrumbs: [
                    {
                        title: 'User Device'
                    },
                ],
                entries: [],
                limit: 25,
                from: 0,
                to: 0,
                paginate: {
                    currentPage: 1,
                    lastPage: 1,
                    totalRecord: 0
                }
            }
        },
        mounted() {
            $router.on('/', this.load).init();
        },
        methods: {
            modalDevice() {
                $('#deviceConfirm').modal('show');
            },
            async load() {
                let query = $router.getQuery();
                this.$loading(true);
                const res  = await $get('/xadmin/user_devices/data', query);
                this.$loading(false);
                this.paginate = res.paginate;
                this.entries = res.data;
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

            async toggleStatus(entry) {
                const res = await $post('/xadmin/user_devices/toggleStatus', {
                    id: entry.id,
                    status: entry.status
                });

                if (res.code === 200) {
                    toastr.success(res.message);
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
    .popup-title{
        margin-left: 160px;
    }
    .content{
        margin-left: 30px;
    }
    .form-control{
        max-width: 400px;
    }
    .btn btn-danger ito-btn-small{
       padding: 5px;
    }
    .ito-btn-small{
        margin-left: 70px;
    }
    .ito-btn-add{
        margin-right:70px;
    }
    .body{
        padding: 30px;
        /*max-width: 1612px;*/
        box-sizing: border-box;
        position: static;
        width: 100%;
        left: 0px;
        top: 0px;
        background: #FFFFFF;
        border: 2px solid #333333;
        border-radius: 44px;
        margin-top: 70px;
    }
    .modal-devices{
        position: absolute;
        right: 20px;
        max-width: 150px;
    }

</style>
