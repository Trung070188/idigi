<template>
    <div class="container-fluid" >
        <ActionBar type="index"
                   v-on:createWithFunction="showModalRole"
                   :createFunction="1"
                   title="RoleIndex"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-5">
                    </div>
                    <table class="table bg-white table-bordered">
                        <tbody>
                        <tr>
                            <td></td>
                            <td  v-for="role in roles">
                                <div   class="text-center" style="cursor: pointer">
                                    <span  @click="showModalRole(role)">{{role.role_name}} </span><span><i @click="remove(role)" class="fa fa-trash" style="margin-left:10px"></i></span>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                        <tr v-for="groupPermission in groupPermissions">
                            <th scope="col">
                                <span v-text="groupPermission.name"></span>
                                <span  v-for="permission in groupPermission.permissions" class="d-block"  style="margin-left: 18px; font-size:12px">{{permission.name}}</span>
                            </th>

                            <td v-for="role in roles">

                                <div   class="text-center check" v-for="permission in role.permissions" v-if="permission.group_permission==groupPermission.id">
                                    <input @change="changeRolePermission(role.id,permission.id,permission.value)"  class="form-check-input" v-model="permission.value"   type="checkbox"  value="" >
                                    <br>
                                </div>
                            </td>
                        </tr>

                    </table>


                </div>
            </div>
        </div>

        <div class="modal" tabindex="-1" role="dialog" id="modal-role">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div style="text-align: center"><h2 class="modal-title">Add new role</h2></div>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <input v-model="curRole.id" type="hidden" name="id" value="">
                            <div class="form-group">
                                <label>Role Name <span class="text-danger">*</span></label>
                                <input id="f_role_name" v-model="curRole.role_name" name="name" class="form-control"
                                       placeholder="role_name" >
                                <error-label for="f_role_name" :errors="errors.role_name"></error-label>

                            </div>
                            <div class="form-group">
                                <label>Role Description</label>
                                <input id="f_role_description" v-model="curRole.role_description" name="name" class="form-control"
                                       placeholder="role_description" >
                                <error-label for="f_role_description" :errors="errors.role_description"></error-label>

                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="save">Save changes</button>
                        <button type="button" class="btn btn-secondary"@click="closeModalRole">Close</button>
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
        name: "RolesIndex.vue",
        components: {ActionBar},
        data() {
            return {
                breadcrumbs: [
                    {
                        title: 'Roles'
                    },
                ],
                roles: [],
                curRole: {},
                errors: {},
                groupPermissions: [],

            }
        },
        mounted() {
            $router.on('/', this.load).init();
        },
        methods: {
            showModalRole: function (role = {}){
                $('#modal-role').modal('show');
                this.curRole = role;
            },
            closeModalRole: function (){
                $('#modal-role').modal('hide');
            },

            async load() {
                let query = $router.getQuery();
                const res  = await $get('/xadmin/roles/data', query);
                this.roles = res.data.roles;
                this.groupPermissions = res.data.groupPermissions;

            },
            async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return;
                }

                const res = await $post('/xadmin/roles/remove', {id: entry.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }

                window.location.reload();
            },

            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/roles/save', {entry: this.curRole}, false);
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


                }
            },

            async changeRolePermission(roleId, permissionId, check){
                const res  = await $post('/xadmin/roles/changeRolePermission', {
                    'role_id' : roleId,
                    'permission_id' : permissionId,
                    'check' : check,
                });
                toastr.success(res.message);
            }
        }
    }
</script>

<style scoped>
    .save_role{
        width: 101px;
        height: 34px;
        background: #333333;
        border-radius: 32px;
        float: right;
        color: #ffffff;
    }

</style>
