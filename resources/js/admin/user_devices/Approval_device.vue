<template>
    <div class="container-fluid" >
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"
                   title="Approval Device"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
<!--                    <div class="card-header border-0 pt-5">-->

<!--                        <div class="row width-full">-->
<!--                            <div class="col-lg-12">-->
<!--                                <form class="form-inline">-->
<!--                                    <div class="form-group mx-sm-3 mb-4">-->
<!--                                        <input @keydown.enter="doFilter('keyword', filter.keyword, $event)"-->
<!--                                               v-model="filter.keyword"-->
<!--                                               type="text"-->
<!--                                               class="form-control" placeholder="Tìm kiếm" value="">-->
<!--                                    </div>-->
<!--                                    <div class="form-group mx-sm-3 mb-2">-->
<!--                                        <button type="button" style="margin-left: 10px"-->
<!--                                                @click="isShowFilter = !isShowFilter"-->
<!--                                                class="btn btn-primary"> Tìm kiếm mở rộng-->
<!--                                            <i class="fa fa-caret-down" v-if="!isShowFilter"></i>-->
<!--                                            <i class="fa fa-caret-up" v-if="isShowFilter" aria-hidden="true"></i>-->

<!--                                        </button>-->


<!--                                    </div>-->
<!--                                    <div class="form-group mx-sm-3 mb-2">-->
<!--                                        <button @click="filterClear()" type="button"-->
<!--                                                class="btn btn-flex btn-light  fw-bolder ">Clear-->
<!--                                        </button>-->
<!--                                    </div>-->


<!--                                </form>-->

<!--                                <form class="col-lg-12" v-if="isShowFilter">-->
<!--                                    <div class="row">-->
<!--                                        <div class="form-group col-lg-3">-->
<!--                                            <label>Fullname </label>-->
<!--                                            <input @keydown.enter="doFilter('full_name', filter.full_name, $event)" class="form-control" placeholder="Enter the full name" v-model="filter.full_name"/>-->

<!--                                        </div>-->
<!--                                        <div class="form-group col-lg-3">-->
<!--                                            <label>Email </label>-->
<!--                                            <input @keydown.enter="doFilter('email', filter.email, $event)" class="form-control" placeholder="Enter the email" v-model="filter.email">-->
<!--                                        </div>-->
<!--                                        <div class="form-group col-lg-3">-->
<!--                                            <label>Role </label>-->
<!--                                            <select @keydown.enter="doFilter('role', filter.role, $event)" class="form-control"  v-model="filter.role" data-placeholder="Select a role">-->
<!--                                                <option  v-for="role in roles" v-bind:value="role.role_name">-->
<!--                                                    {{role.role_name}}-->
<!--                                                </option>-->
<!--                                            </select>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="row">-->
<!--                                        <div class="form-group col-lg-3">-->
<!--                                            <label>Ngày tạo </label>-->
<!--                                            <Daterangepicker v-model="filter.created"-->
<!--                                                             placeholder="Ngày tạo"></Daterangepicker>-->
<!--                                        </div>-->
<!--                                        <div class="form-group col-lg-3">-->
<!--                                            <label>Active</label>-->
<!--                                            <div>-->
<!--                                                <switch-button v-model="filter.state"></switch-button>-->
<!--                                            </div>-->

<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div style="margin: auto 0">-->
<!--                                        <button type="button" class="btn btn-primary" @click="doFilter()">Tìm kiếm</button>-->
<!--                                    </div>-->
<!--                                </form>-->
<!--                            </div>-->

<!--                        </div>-->

<!--                    </div>-->

<!--                    <div class="modal fade"  id="editdeviceConfirm" tabindex="-1" role="dialog"-->
<!--                         aria-labelledby="editdeviceConfirm"-->
<!--                         aria-hidden="true">-->
<!--                        <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"-->
<!--                             style="max-width: 500px;">-->
<!--                            <div class="modal-content box-shadow-main paymment-status" style="margin-right:20px; left:140px">-->
<!--                                <div class="close-popup" data-dismiss="modal"></div>-->
<!--                                <h3 class="popup-title success" style="text-align: center">Approval device</h3>-->
<!--                                <div class="content">-->
<!--                                    <input type="text" class="form-control " placeholder="Nhập lí do từ chối" aria-label="" style="margin-bottom: 10px" aria-describedby="basic-addon1" v-model="reason" >-->
<!--                                    <div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="form-group d-flex justify-content-between">-->
<!--                                    <button  class="btn btn-danger ito-btn-small" style="margin-left: 200px" data-dismiss="modal" @click="save()">Add now</button>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="card-body d-flex flex-column">
                        <div v-text="'Showing '+ from +' to '+ to +' of '+ paginate.totalRecord +' entries'"
                             v-if="entries.length > 0"></div>
                        <table class=" table  table-head-custom table-head-bg table-vertical-center">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Device UID</th>
                                <th>Device Name</th>
                                <th>User Id</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="entry in entries" v-if="entry.status==1">
                                <td v-text="entry.id"></td>
                                <td v-text="entry.device_uid"></td>
                                <td v-text="entry.device_name"></td>
                                <td  v-text="entry.user_id"></td>
                                <td style="color:#FFAC32">Waiting for administrator verify</td>

                                <td>

                                    <button class="" @click="toggleStatus_approval(entry)">
                                  Approval
                                    </button>
<!--                                    <button class="btn-danger" @click="editModalDevice(entry.id,entry.status,entry.reason)">-->
<!--                                        Refuse-->
<!--                                    </button>-->
                                    <a :href="'/xadmin/user_devices/edit?id='+entry.id"> <button class="btn-danger" >Refuse</button></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div style="margin-top:10px; display: flex">
                            <div class="col-4 form-group d-inline-flex mt-2">
                                <div class="mr-2">
                                    <label>Records per page:</label>
                                </div>
                                <div>
                                    <select class="form-select form-select-sm " v-model="limit" @change="changeLimit">
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>

                                    </select>
                                </div>
                            </div>
                            <div style="float: right">
                                <Paginate :value="paginate" :pagechange="onPageChange"></Paginate>
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
                editDevice:"",
                breadcrumbs: [
                    {
                        title: 'Approval Device'
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
                },
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
            // edit: function (id, event){
            //     if (!$(event.target).hasClass('deleted')) {
            //         window.location.href = '/xadmin/user_devices/edit?id=' + id;
            //     }
            // },

            // editModalDevice(id,status,reason)
            // {
            //     const that=this;
            //     that.currId = id;
            //    that.status=status
            //     that.reason=reason
            //     $('#editdeviceConfirm').modal('show');
            // },
            async save() {
                const res = await $post('/xadmin/user_devices/save', {
                    entry: this.entry,
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
                this.entries = res.data;
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
