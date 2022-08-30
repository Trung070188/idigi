<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title ="New License" />
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-6" style="margin:0px 0px -35px">
                        <div class="card-title"></div>
                    </div>

                    <div class="card-body d-flex flex-column" >
                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label>School Name <span class="text-danger">*</span></label>
                                        <select  v-model="entry.id"   class="form-control form-select">
                                            <option v-for="school in schools" :value="school.id">{{school.label}}</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label>License expire date <span class="text-danger">*</span></label>
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
                                        <label>License description</label>
                                        <textarea  v-model="entry.license_info" rows="5" class="form-control" placeholder="Your text here..."></textarea>
                                        <error-label for="f_grade" :errors="errors.license_info"></error-label>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <input id="state" type="checkbox" >
                                    <label for="state" class="pl-2">Activate license</label>
                                    <error-label for="f_grade" ></error-label>
                                </div>
                            </div>
                        </div>
                        <hr style="margin-top: 5px;">
                        <div >
                            <button type="reset" @click="save()" class="btn btn-primary mr-2">Create new license</button>
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
    import Datepicker from "../../components/Datepicker";
    import $router from "../../lib/SimpleRouter";
    import Treeselect from '@riophae/vue-treeselect'
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'

    export default {
        name: "LicenseForm.vue",
        components: {ActionBar, Datepicker,Treeselect},
        data() {
            return {
                schools:$json.school||{},
                breadcrumbs: [
                    {
                        title: 'License',
                        url: '/xadmin/schools/license',
                    },
                    {
                        title: $json.entry ? 'School details' : 'License add',
                    },
                ],
                title: $json.entry ? 'Edit school' : 'Create New school',

                entry: $json.entry || {},
                isLoading: false,
                errors: {}
            }
        },
        methods: {
            backIndex(){
                window.location.href = '/xadmin/schools/license';
            },
            async save() {
                this.$loading(true);
                const res = await $post('/xadmin/schools/saveLicense', {entry: this.entry,allocationContenSchool:this.allocationContenSchool}, false);
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
            },
            async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return;
                }

                const res = await $post('/xadmin/schools/remove', {id: entry.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }
                location.replace('/xadmin/schools/index');


                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});
            },
        }
    }
</script>

<style scoped>

</style>
