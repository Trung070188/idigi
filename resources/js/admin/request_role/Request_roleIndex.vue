<template>
    <div class="container-fluid" >
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-custom card-stretch gutter-b">
                                <div class="card-body d-flex flex-column" style="height: 563px" >
                                    <div class="row" style="margin-top: 65px; margin-bottom: 50px">
                                        <label style="text-align: center">Tài khoản của bạn chưa được phân quyền để sử dụng!</label>
                                        <br>
                                        <br>

                                         <label style="text-align: center">   Hãy gửi yêu cầu cấp quyền hoặc liên hệ với quản trị viên để được cấp quyền sử dụng hệ thống.
                                            </label>
                                        <label style="text-align: center"> hoặc liên hệ hotline: 0912345678</label>
                                    </div>
                                    <div class="d-flex" style="margin: 0 auto">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" value="0" type="radio" name="radioFilter" id="filter-none" v-model="role">
                                            <label class="form-check-label" for="filter-none">Tôi là giáo viên</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" value="1" type="radio" name="radioFilter" v-model="role" id="filter-1">
                                            <label class="form-check-label text-nowrap" for="filter-1">Tôi là quản trị viên</label>
                                        </div>
                                    </div>
                                    <div style="margin:0 auto; margin-top: 50px">
                                        <button class="btn btn-primary" :disabled="!role" @click="sendRequest">Yêu cầu cấp quyền</button>
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

    export default {
        name: "Request_roleIndex.vue",
        data() {
            return {
                role: ''
            }
        },
        mounted() {

        },
        methods: {
            async sendRequest() {
                const res = await $post('/xadmin/request_role/save', {role: this.role}, false);
                toastr.success(res.message);
            }

        }
    }
</script>


