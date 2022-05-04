<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   backUrl="/xadmin/roles/index"
                   :breadcrumbs = "breadcrumbs"
                   title="RoleForm"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="card-body d-flex flex-column" >
                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                                                    <div class="form-group">
                                        <label>Role Name</label>
                                        <input id="f_role_name" v-model="entry.role_name" name="name" class="form-control"
                                               placeholder="role_name" >
                                        <error-label for="f_role_name" :errors="errors.role_name"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Role Description</label>
                                        <input id="f_role_description" v-model="entry.role_description" name="name" class="form-control"
                                               placeholder="role_description" >
                                        <error-label for="f_role_description" :errors="errors.role_description"></error-label>

                                    </div>

                            </div>

                        </div>

                        <hr>
                        <div >
                            <button type="reset" @click="save()" class="btn btn-primary mr-2">Save</button>
                            <button type="reset" @click="cancel()" class="btn btn-secondary">Cancel</button>
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

    export default {
        name: "RolesForm.vue",
        components: {ActionBar},
        data() {
            return {
                breadcrumbs: [
                    {
                        title: 'Role',
                        url: '/xadmin/roles/index',
                    },
                    {
                        title: $json.entry ? 'Edit inventory' : 'Create new inventory',
                    },

                ],
                entry: $json.entry || {},
                isLoading: false,
                errors: {}
            }
        },
        methods: {
            cancel(){
                window.location.href = '/xadmin/roles/index';
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/roles/save', {entry: this.entry}, false);
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
                        location.replace('/xadmin/roles/edit?id=' + res.id);
                    }

                }
            }
        }
    }
</script>

<style scoped>

</style>
