<template>
    <div class="container-fluid" >
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"
                   createUrl="/xadmin/roles/create"
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
                                <div   class="text-center">
                                    {{role.role_name}}
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
                groupPermissions: [],

            }
        },
        mounted() {
            $router.on('/', this.load).init();
        },
        methods: {

            async load() {
                let query = $router.getQuery();
                const res  = await $get('/xadmin/roles/data', query);
                this.roles = res.data.roles;
                this.groupPermissions = res.data.groupPermissions;

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
</style>
