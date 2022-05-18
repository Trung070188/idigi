<template>
    <div class="container-fluid" >
        <ActionBar type="form" @save="save()"
                   back-url="/xadmin/user_devices/approval"
                   :breadcrumbs="breadcrumbs"
                   title="Device"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-custom card-stretch gutter-b">
                                <div class="card-body d-flex flex-column" style="height: 563px" >
                                    <div class="row" style="margin-top: 65px">
                                    <label style="text-align: center;font-weight: bold;font-size: 30px;">Từ chối đăng kí thiết bị</label>
                                    </div>
                                    <div class="d-flex" style="margin: auto;margin-top: 100px">
                                        <div class="form-check form-check-inline" style="margin-bottom: 26px; margin-top: -80px;">
                                            <textarea style="width: 600px;height: 200px" v-model="entry.reason"  class="form-control" placeholder="Nhập lí do hủy thiết bị" >
                                            </textarea>
                                            <label v-model="entry.status=3"></label>
                                        </div>
                                    </div>
                                    <div style="margin: auto;margin-bottom: 135px">
                                        <button class="grant_permission" @click="save()">Từ chối thiết bị</button>
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
    import { $post} from "../../utils";
    import ActionBar from "../includes/ActionBar";
    export default {
        name: "User_deviceForm.vue",
        components: {ActionBar},
        data() {
            return {
              status:2,
                breadcrumbs: [
                    {
                        title: 'User Device',
                        url: '/xadmin/user_devices/approval',
                    },
                    {
                        title: $json.entry ? ' Device' : '',
                    },
                ],
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
        methods: {
            async save() {
                this.$loading(true);
                const res = await $post('/xadmin/user_devices/save', {entry: this.entry
                }, false);
                  location.replace('/xadmin/user_devices/approval');
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
