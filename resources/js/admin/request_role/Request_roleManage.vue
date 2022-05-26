<template>
    <div class="container-fluid" >
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"
                   title="RequestRole"/>
        <div class="row">
            <div class="modal fade"  id="editRequestConfirm" tabindex="-1" role="dialog"
                 aria-labelledby="editRequestConfirm"
                 aria-hidden="true">

                <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                     style="max-width: 500px;">
                    <div class="modal-content box-shadow-main paymment-status" style="margin-right:20px; left:140px">
                        <div class="close-popup" data-dismiss="modal"></div>
                        <h3 class="popup-title success" style="text-align: center">Refuse Request</h3>
                        <input v-model="curRequest.id" type="hidden" name="id" value="">
                        <div class="content">
                            <textarea style="width: 100%;height: 50px" v-model="curRequest.reason"  class="form-control" placeholder="Nhập lí do từ chối yêu cầu" ></textarea>
                            <error-label for="f_role_name" :errors="errors.reason"></error-label>
                            <div>
                            </div>
                        </div>


                        <div class="form-group d-flex justify-content-between">
                            <button  class="btn btn-danger ito-btn-small" style="margin-left: 200px" data-dismiss="modal"  @click="refuse" >Refuse now</button>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-5">

                    </div>

                    <div class="card-body d-flex flex-column">
                        <div v-text="'Showing '+ from +' to '+ to +' of '+ paginate.totalRecord +' entries'"
                             v-if="entries.length > 0"></div>
                        <table class=" table  table-head-custom table-head-bg table-vertical-center">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name User</th>
                                <th>Content</th>
                                <th>Status</th>
                                <th>Created_at</th>
                                <th style="float: left;margin-left: 144px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="entry in entries" >
                                <td v-text="entry.id"></td>
                                <td v-for="user in entry.users" v-if="entry.user_id==user.id" v-text="user.username"></td>
                                <td v-text="entry.content"></td>
                                <td v-if="entry.status=='Refuse'" style="color:#d70f0f">Refuse</td>
                                <td v-if="entry.status=='Waiting'" style="color:#ddaf0d">Waiting</td>
                                <td v-if="entry.status=='Aprrove'" style="color:#10d13e">Aprrove</td>
                                <td v-text=" d(entry.created_at)"></td>
                                <td >
                                    <button class="btn" style="margin-right: 20px;background:#50f14b;color: #f1f1f1;border-color: #50f14b" @click="toggleStatus(entry)">Aprrove</button>
                                    <a :href="'/xadmin/users/edit?id='+entry.user_id" ><button class="btn btn-success" style="margin-right: 20px">grant permission</button></a>
                                    <button class="btn btn-danger" @click="editModalDevice(entry)">Refuse</button>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div style="margin-top:10px; display: flex">
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
    import SwitchButton from "../../components/SwitchButton";


    let created = getTimeRangeAll();
    const $q = $router.getQuery();

    export default {
        name: "Request_roleManage.vue",
        components: {ActionBar,SwitchButton},
        data() {

            return {
                users:[],
                errors:{},
                curRequest:{},
                breadcrumbs: [
                    {
                        title: 'RequestRole'
                    },
                ],
                roles:$json.roles || [],
                entries: [],
            }
        },
        mounted() {
            $router.on('/', this.load).init();
        },
        methods: {

            editModalDevice: function (request = {}){
                $('#editRequestConfirm').modal('show');
                this.curRequest = request;

            },
            async refuse() {
                const res = await $post('/xadmin/request_role/refuse', {
                    entry: this.curRequest,
                }, false);
                this.isLoading = false;
                if (res.errors) {
                    this.errors = res.errors;
                }
                if (res.code) {
                    toastr.error(res.message);
                } else {
                    this.errors = {};
                    toastr.success(res.message);
                    location.replace('/xadmin/request_role/manage');
                }
            },
            async load() {
                let query = $router.getQuery();
                this.$loading(true);
                const res  = await $get('/xadmin/request_role/data', query);
                this.$loading(false);
                this.paginate = res.paginate;
                this.entries = res.data.entries;
                this.from = (this.paginate.currentPage-1)*(this.limit) + 1;
                this.to = (this.paginate.currentPage-1)*(this.limit) + this.entries.length;
            },
            async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return false;
                }

                const res = await $post('/xadmin/request_role/remove', {id: entry.id});

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
                const res = await $post('/xadmin/request_role/toggleStatus', {
                    id: entry.id,
                    status: entry.status
                });

                if (res.code === 200) {
                    toastr.success(res.message);
                }

                else {
                    toastr.error(res.message);
                }
                location.replace('/xadmin/request_role/manage');

            },
            onPageChange(page) {
                $router.updateQuery({page: page})
            }
        }
    }
</script>

<style scoped>

</style>
