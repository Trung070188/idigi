<template>
    <div class="container-fluid" >
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title ="Create new plan" />
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-6" style="margin:0px 0px -35px">
                        <div class="card-title"></div>
                        <div class="card-toolbar">
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column" >
                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group col-lg-8">
                                        <label>Plan name <span class="text-danger">*</span></label>
                                        <input  v-model="entry.name"  class="form-control"
                                                placeholder="Enter the name of plan" >
                                        <error-label :errors="errors.name" for="f_school_name" ></error-label>
                                    </div>
                                     <div class="form-group col-lg-4">
                                        <label>Due date </label>
                                        <Datepicker v-model="entry.due_at" readonly/>
                                        <!-- <span v-if="entry.due_at!=''" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0" @click="dueAtClear">
                                            <svg type="button" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" style="float: right;margin: -32px 3px 0px;">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" style="fill:red"/>
                                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" style="fill:red"/>
                                            </svg>
                                        </span> -->
                                        <error-label  :errors="errors.due_at" for="f_title" ></error-label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-8">
                                        <label>Plan description</label>
                                        <textarea   class="form-control"
                                                placeholder="Enter the description" v-model="entry.plan_description">
                                        </textarea>
                                        <error-label for="f_school_name" :errors="errors.plan_description"></error-label>
                                    </div>
                                     <div v-if="authNameRole!='IT'" class="form-group col-lg-4">
                                        <label> Assign to IT <span class="text-danger">*</span></label>
                                        <select   class="form-control form-select" v-model="idRoleIt"
                                        >
                                            <option v-for="role in roleIt" :value="role.id">{{role.full_name}}</option>
                                        </select>
                                        <error-label :errors="errors.idRoleIt" ></error-label>
                                    </div>

                                    <!-- <div v-if="authNameRole=='IT'" class="form-group col-lg-4">
                                        <label> Assign to IT <span class="text-danger">*</span></label>
                                        <input  v-model="roleIt.full_name"  class="form-control" disabled>
                                    </div> -->
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label>Plan expire date<span class="text-danger">*</span></label>
                                        <Datepicker v-model="entry.expire_date" readonly/>
                                        <!-- <span v-if="entry.expire_date!=''" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0" @click="expireDateClear">
                                            <svg type="button" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" style="float: right;margin: -32px 3px 0px;">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" style="fill:red"/>
                                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" style="fill:red"/>
                                            </svg>
                                        </span> -->
                                        <error-label :errors="errors.expire_date" for="f_title" ></error-label>
                                    </div>
                                </div>
                                </div>

                            </div>

                        </div>
                        <hr style="margin-top: 5px;">
                        <div style="margin: 13px 25px 13px">
                            <button type="reset" @click="save()" class="btn btn-primary mr-2">Save</button>
                            <button type="reset" @click="backIndex()" class="btn btn-secondary">Cancel</button>
                        </div>
                    </div>
                </div>


            </div>

        </div>



</template>

<script>
    import {$post} from "../../utils";
    import ActionBar from "../includes/ActionBar";

    export default {
        name: "PlansForm.vue",
        components: {ActionBar},
        data() {
            return {
                deviceName:'',
                deviceUid:'',
                authNameRole:$json.authNameRole,
                idRoleIt:'',
                roleIt:$json.roleIt || [],
                breadcrumbs: [
                    {
                        title: 'Manage plans',
                        url:'/xadmin/plans/index'
                    },
                    {
                        title: 'Create new plan'
                    },
                ],
                entry: $json.entry || {},
                isLoading: false,
                errors: {}
            }
        },
        methods: {
            expireDateClear()
            {
                this.entry.expire_date='';
            },
            dueAtClear()
            {
                this.entry.due_at='';
            },
             modalDevice() {
                $('#deviceConfirm').modal('show');
            },
            backIndex(){
                window.location.href = '/xadmin/plans/index';
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/save', {entry: this.entry,idRoleIt:this.idRoleIt}, false);
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
                        location.replace('/xadmin/plans/edit?id=' + res.id);
                    }

                }
            },
            async saveDevice() {
                this.isLoading = true;
                const res = await $post('/xadmin/plans/saveDevice', {idRoleIt:this.idRoleIt,deviceName:this.deviceName,deviceUid:this.deviceUid}, false);
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
                        // location.replace('/xadmin/plans/edit?id=' + res.id);
                    }

                }
            }
        }
    }
</script>

<style scoped>

</style>
