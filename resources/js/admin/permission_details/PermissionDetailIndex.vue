<template>
    <div class="container-fluid" >
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title="Permission"/>
         <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-2">
                        <table class="table bg-white table-bordered">
                            <tbody>
                            <tr >
                                <th></th>
                                <th v-for="role in roles">
                                    <div class="text-center">
                                        <span class="badge badge-warning fs-6 fw-bold" >{{role.role_name}}</span>
                                    </div>
                                </th>
                            </tr>
                            </tbody>
                            <tr v-for="permission in permissions" >
                                <th scope="col" style="border-left:1px solid #ddd;border-bottom: none;border-right: none;border-top: none">
                                    <span >{{permission.display_permission_detail}}</span>
                                    <span v-for="permissionDetail in permission.permission_details" class="d-block fw-bold ml-5" style="border-bottom:1px dotted #ddd"><i class="bi bi-arrow-right-short mr-1"></i>{{permissionDetail.name}}</span>
                                </th>
                                <th scope="row" v-for="role in roles" style="border-left:1px solid #ddd;border-bottom: none;border-right: none;border-top: none;padding: 5px 0px 0px" >
                                    <span></span>
                                    <div class="form-check form-check-custom form-check-solid justify-content-center" v-for="permissionDetail in role.permission" v-if="permissionDetail.permission==permission.id" style="border-bottom:1px dotted #ddd">
                                        <input :disabled="userPermissions['063']!= 1"  @change="changePermissionDetail(role.id,permissionDetail.id,permissionDetail.value)"  class="form-check-input h-20px w-20px"  type="checkbox"  v-model="permissionDetail.value" style="border-color:#ddd">
                                        <br>
                                    </div>
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {$get, $post, clone, getTimeRangeAll} from "../../utils";
    import $router from '../../lib/SimpleRouter';
    import ActionBar from "../includes/ActionBar";

    let created = getTimeRangeAll();
    const $q = $router.getQuery();

    export default {
        name: "Permission_detailsIndex.vue",
        components: {ActionBar},
        data() {
            const userPermissions = clone(window.$permissions);
            return {
                userPermissions,
                activeColor:'',
                permissions:[],
                roles:[],
                entries: [],
                breadcrumbs: [
                    {
                        title: 'Account management'
                    },
                    {
                        title: 'Permission'
                    },
                ],
                filter: {
                    keyword: $q.keyword || '',
                    created: $q.created || created,
                },
                limit: 25,
                from: 0,
                to: 0,
                paginate: {
                    currentPage: 1,
                    lastPage: 1,
                    totalRecord: 0
                }
            }
        },
        mounted() {
            $router.on('/', this.load).init();
        },
        methods: {

            edit: function (id, event){
                if (!$(event.target).hasClass('deleted')){
                    window.location.href='/xadmin/permission_details/edit?id='+ id;
                }

            },
            async load() {
                let query = $router.getQuery();
                const res  = await $get('/xadmin/permission_details/data', query);
               this.roles=res.data.roles;
               this.permissions=res.data.permissions;
                console.log(this.roles);
                console.log(this.permissions);
            },
            async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return;
                }

                const res = await $post('/xadmin/permission_details/remove', {id: entry.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }

                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});
            },
            filterClear() {
                for( var key in app.filter) {
                    app.filter[key] = '';
                }

                $router.setQuery({});
            },
            doFilter(field, value, event) {
                if (event) {
                    event.preventDefault();
                }

                const params = {page: 1};
                params[field] = value;
                $router.setQuery(params)
            },
            changeLimit() {
                let params = $router.getQuery();
                params['page']=1;
                params['limit'] = this.limit;
                $router.setQuery(params)
            },

            async toggleStatus(entry) {
                const res = await $post('/xadmin/permission_details/toggleStatus', {
                    id: entry.id,
                    status: entry.status
                });

                if (res.code === 200) {
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message);
                }

            },
            onPageChange(page) {
                $router.updateQuery({page: page})
            },
          async  changePermissionDetail(roleId, permissionDetailId, check)
            {
                const res  = await $post('/xadmin/permission_details/changeDetailPermission', {
                    'role_id' : roleId,
                    'permission_detail_id' :permissionDetailId,
                    'check' : check,
                });
                toastr.options.timeOut=1000;
                toastr.options.preventDuplicates = true;
                toastr.success(res.message);
            }
        }
    }
</script>

<style scoped>

</style>
