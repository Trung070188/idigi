<template>
    <div class="container-fluid" >
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="row">
                        <div class="col-lg-12">
                            <div v-if="data==1" class="card card-custom card-stretch gutter-b"  >
                                <div  class="card-body d-flex flex-column" style="height: 563px" >
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
                                            <input class="form-check-input" value="1" type="radio" name="radioFilter" id="filter-none" v-model="role">

                                            <label class="form-check-label" for="filter-none">I’m teacher</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" value="2" type="radio" name="radioFilter" v-model="role" id="filter-1">
                                            <label class="form-check-label text-nowrap" for="filter-1">I’m moderator</label>
                                        </div>
                                    </div>
                                    <div  class="row" v-if="role==1" style="margin-left: 42%;margin-top: 31px;">
                                        <div   class=" col-sm-4">

                                            <label>School <span class="text-danger">*</span></label>
                                            <select  class="form-control form-select" v-model="school" type="" placeholder="Enter the school" >
                                                <option v-for="school in schools" :value="school.id" >{{school.school_name}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div style="margin:0 auto; margin-top: 50px">
                                        <button class="btn btn-primary" :disabled="!role" @click="sendRequest">Yêu cầu cấp quyền</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-custom card-stretch gutter-b"  v-if="data==2">
                                <div  class="card-body d-flex flex-column" style="height: 563px" >
                                    <div class="row" style="margin-top: 65px; margin-bottom: 50px" v-if="entry.role_name=='Teacher'">
                                        <label style="text-align: center">Yêu cầu cấp quyền trở thành teacher của bạn đang chờ duyệt!</label>
                                    </div>
                                     <div class="row" style="margin-top: 65px; margin-bottom: 50px" v-if="entry.role_name=='Moderator'">
                                        <label style="text-align: center">Yêu cầu cấp quyền trở thành Moderator của bạn đang chờ duyệt!</label>
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
                school:'',
                role: '',
                schools:$json.schools||{},
                data:$json.data||{},
                entry:$json.entry||{}
            }
        },
        mounted() {

        },
        methods: {
            async sendRequest() {
                const res = await $post('/xadmin/request_role/save', {role: this.role,school:this.school}, false);
                toastr.success(res.message);
            }

        }
    }
</script>


