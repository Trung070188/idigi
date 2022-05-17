<template>
    <div class="container-fluid" >
        <ActionBar type="index"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-custom card-stretch gutter-b">
                                <div class="card-body d-flex flex-column" style="height: 563px" >
                                    <div class="row" style="margin-top: 65px">
                                        <label style="text-align: center">Tài khoản của bạn chưa được phân quyền để sử dụng!</label>
                                        <br>
                                        <br>

                                         <label style="text-align: center">   Hãy gửi yêu cầu cấp quyền hoặc liên hệ với quản trị viên để được cấp quyền sử dụng hệ thống.
                                            </label>
                                        <label style="text-align: center"> hoặc liên hệ hotline: 0912345678</label>
                                    </div>
                                    <div class="d-flex" style="margin: auto;margin-top: 100px">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="radioFilter" id="filter-none" checked>
                                            <label class="form-check-label" for="inlineRadio1">Tôi là giáo viên</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="radioFilter" id="filter-1">
                                            <label class="form-check-label text-nowrap" for="inlineRadio2">Tôi là quản trị viên</label>
                                        </div>
                                    </div>
                                    <div style="margin: auto;margin-bottom: 135px;margin-left: 410px">
                                        <button class="grant_permission">Yêu cầu cấp quyền</button>
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
    import {$get, $post, getTimeRangeAll} from "../../utils";
    import $router from '../../lib/SimpleRouter';
    import ActionBar from "../includes/ActionBar";

    let created = getTimeRangeAll();
    const $q = $router.getQuery();

    export default {
        name: "Request_roleIndex.vue",
        components: {ActionBar},
        data() {
            return {
                entries: [],
                filter: {
                    keyword: $q.keyword || '',
                    created: $q.created || created,
                },
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
            edit: function (id, event){
                if (!$(event.target).hasClass('deleted')){
                    window.location.href='/xadmin/request_role/edit?id='+ id;
                }

            },
            async load() {
                let query = $router.getQuery();
                const res  = await $get('/xadmin/request_role/data', query);
                this.paginate = res.paginate;
                this.entries = res.data;
                this.from = (this.paginate.currentPage-1)*(this.limit) + 1;
                this.to = (this.paginate.currentPage-1)*(this.limit) + this.entries.length;
            },
            async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return;
                }

                const res = await $post('/xadmin/request_role/remove', {id: entry.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }

                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});
            },
        }
    }
</script>

<style scoped>
    .grant_permission{
        background: #333333;
        border-radius: 32px;
        color: #ffffff;
        width: 262px;
        height: 42px;

    }
</style>
