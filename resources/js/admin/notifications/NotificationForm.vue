<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"
                   title="Notifications"/>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column" style="height: 563px" >
                        <div     class="row width-full">



                            <div v-if="entry.type=='App\\Notifications\\InvoicePaid'" class="col-lg-12 body " >
                                <form  class="form-inline"  >
                                    <div class="form-group mx-sm-3 mb-2" style="font-size: 15px">
                                        giáo viên  {{(JSON.parse(entry.data)).username}} yêu cầu xóa thiết bị '{{(JSON.parse(entry.data)).device_name}}'
                                    </div>
                                </form>
                                <div  class="form-group mx-sm-3 mb-2" style="position: absolute;right:65px;margin-top: -33px;" >

                                </div>
                                <div  class="form-group mx-sm-3 mb-2" style="position: absolute;right:65px;margin-top: -33px;" >
                                    <a :href="'/xadmin/users/edit_teacher?id='+(JSON.parse(entry.data)).user_id" >
                                        <button  type="button"
                                                 class="btn btn-flex btn-dark  fw-verify " style="margin-right: 5px">
                                            Xem chi tiết
                                        </button>
                                    </a>
                                </div>
                            </div>
                            <div v-if="entry.type=='App\\Notifications\\RequestRoleNotification'" class="col-lg-12 body " >
                                <form  class="form-inline"  >
                                    <div class="form-group mx-sm-3 mb-2" style="font-size: 15px">
                                        giáo viên  {{(JSON.parse(entry.data)).user_name}} yêu cầu cấp quyền '{{(JSON.parse(entry.data)).content}}'
                                    </div>
                                </form>
                                <div  class="form-group mx-sm-3 mb-2" style="position: absolute;right:65px;margin-top: -33px;" >

                                </div>
                                <div  class="form-group mx-sm-3 mb-2" style="position: absolute;right:65px;margin-top: -33px;" >
                                    <a :href="'/xadmin/users/edit_teacher?id='+(JSON.parse(entry.data)).user_id" >
                                        <button  type="button"
                                                 class="btn btn-flex btn-dark  fw-verify " style="margin-right: 5px">
                                            Xem chi tiết
                                        </button>
                                    </a>
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
    import {$get, $post} from "../../utils";
    import ActionBar from "../includes/ActionBar";
    import $router from "../../lib/SimpleRouter";

    export default {
        name: "NotificationsForm.vue",
        components: {ActionBar},
        data() {
            return {
                entries: [],
                breadcrumbs: [
                    {
                        title: 'Notifications'
                    },
                ],
                entry: $json.entry || {},
                isLoading: false,
                errors: {}
            }
        },
        methods: {
            backIndex(){
                window.location.href = '/xadmin/notifications/index';
            },

            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/notifications/save', {entry: this.entry}, false);
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
                        location.replace('/xadmin/notifications/edit?id=' + res.id);
                    }

                }
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
        padding: 40px;
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

</style>
