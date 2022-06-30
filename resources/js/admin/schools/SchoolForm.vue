<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   backUrl="/xadmin/schools/index"
                   :breadcrumbs = "breadcrumbs"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                     <div class="card-header border-0 pt-5">
                        <div class="title">
                            <label v-if="title=='Create new school'">Create new school</label>
                            <label v-if="title=='Edit school'">School details</label>
                        </div>
                         <button style="margin-left:640px" v-if="title=='Edit school'" class="btn btn-primary button-create " @click="remove(entry)">
                        Teacher list <i class="fa fa-users"></i>
                    </button>
                        <button v-if="title=='Edit school'" class="btn btn-primary button-create " @click="remove(entry)">
                        Delete User <i class="fas fa-trash"></i>
                    </button>
                    
                    </div>
                    <hr>
                    <div class="card-body d-flex flex-column" >
                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                     <div class="form-group col-lg-4">
                                        <label>School Name <span class="text-danger">*</span></label>
                                        <input  v-model="entry.school_name"  class="form-control"
                                               placeholder="Nhập vào tên trường" >
                                        <error-label for="f_school_name" :errors="errors.school_name"></error-label>

                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label>School Address <span class="text-danger">*</span></label>
                                        <input  v-model="entry.school_address" class="form-control"
                                               placeholder="Nhập vào địa chỉ của trường" >
                                        <error-label  :errors="errors.school_address"></error-label>

                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>School Email <span class="text-danger">*</span></label>
                                        <input  v-model="entry.school_email"  class="form-control"
                                                placeholder="Nhập vào email của trường" >
                                        <error-label  :errors="errors.school_email"></error-label>

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label>Phone number <span class="text-danger">*</span></label>
                                        <input  v-model="entry.school_phone"  class="form-control"
                                                placeholder="Nhập vào số điện thoại của trường" >
                                        <error-label for="f_school_name" :errors="errors.school_phone"></error-label>

                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label>Setup device per user <span class="text-danger">*</span></label>
                                        <input  v-model="entry.devices_per_user" class="form-control"
                                                placeholder="Nhập số lượng cho phép thiết bị của mỗi giáo viên" >
                                        <error-label  :errors="errors.devices_per_user"></error-label>

                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Setup no. of user <span class="text-danger">*</span></label>
                                        <input  v-model="entry.number_of_users"  class="form-control"
                                                placeholder="Nhập số lượng giáo viên" >
                                        <error-label  :errors="errors.number_of_users"></error-label>

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label>License to <span class="text-danger">*</span></label>
                                        <datepicker :timepicker="true" v-model="entry.license_to"></datepicker>
                                        <error-label  :errors="errors.license_to"></error-label>
                                    </div>
                                </div>
<!--                                <div class="row">
                                    <div class="form-group col-lg-8">
                                        <label>Adminitrator name</label>
                                        <QSelect v-model="entry.user_id"
                                                 datasource="users"
                                                 :multiple="false"></QSelect>
                                        <error-label for="f_user_id" :errors="errors.user_id"></error-label>
                                    </div>
                                </div>-->
                                <div class="row">
                                <div class="form-group col-lg-8">
                                    <label>Licence description</label>
                                    <textarea  v-model="entry.license_info" rows="5" class="form-control" placeholder="Your text here..."></textarea>
                                    <error-label for="f_grade" :errors="errors.license_info"></error-label>

                                </div>
                                </div>
                            </div>
                        </div>
                        <hr style="margin-top: 5px;">
                        <div >
                            <button type="reset" @click="save()" class="btn btn-primary mr-2">Save</button>
                            <button type="reset" @click="backIndex()" class="btn btn-secondary">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import {$post} from "../../utils";
    import ActionBar from "../includes/ActionBar";
    import QSelect from "../../components/QSelect";
    import Datepicker from "../../components/Datepicker";
    export default {
        name: "SchoolsForm.vue",
        components: {ActionBar, QSelect, Datepicker},
        data() {
            return {
                breadcrumbs: [
                    {
                        title: 'Schools',
                        url: '/xadmin/schools/index',
                    },
                    {
                        title: $json.entry ? 'Edit school' : 'Create new school',
                    },
                ],
                title: $json.entry ? 'Edit school' : 'Create new school',

                entry: $json.entry || {},
                isLoading: false,
                errors: {}
            }
        },
        methods: {
            backIndex(){
                window.location.href = '/xadmin/schools/index';
            },
            async save() {
                this.$loading(true);
                const res = await $post('/xadmin/schools/save', {entry: this.entry}, false);
                this.$loading(false);
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
                        location.replace('/xadmin/schools/edit?id=' + res.id);
                    }

                }
            }
        }
    }
</script>

<style scoped>

</style>
