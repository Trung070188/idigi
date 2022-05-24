<template>
    <div class="container-fluid" >
        <ActionBar type="index"
                   createUrl="/xadmin/roles/create"
                   :breadcrumbs="breadcrumbs"
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
                                <td  v-for="entry in entries">
                                    <div     class="text-center">
                                        {{entry.role_name}}
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                            <tr >
                                <th scope="col">
                                    <span>Manage user</span>
                                    <span  v-for="permission in permissions" v-if="permission.group_permission_id==1" class="d-block" style="margin-left: 20px"  >{{permission.name}}</span>
                                </th>
                                <td v-for="entry in entries"  >
                                    <br>
                                    <div    v-for="permission in permissions" v-if="permission.group_permission_id==1"  class="text-center check">
                                        <input class="form-check-input" v-model="permission.abc" :value="permission.id" type="checkbox"  >
                                        <br>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <span>Manage role</span>
                                    <span  v-for="permission in permissions" v-if="permission.group_permission_id==2" class="d-block" style="margin-left: 20px" @click="edit()">{{permission.name}}</span>
                                </th>
                                <td v-for="entry in entries">
                                    <br>
                                    <div v-for="permission in permissions" v-if="permission.group_permission_id==2" class="text-center check">
                                        <input  class="form-check-input" v-model="permission.abc" :value="permission.id" type="checkbox" value="" >
                                        <br>

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <span>Manage data</span>
                                    <span  v-for="permission in permissions" v-if="permission.group_permission_id==3" class="d-block" style="margin-left: 20px"  @click="edit()">{{permission.name}}</span>
                                </th>
                                <td v-for="entry in entries">
                                    <br>
                                    <div  v-for="permission in permissions" v-if="permission.group_permission_id==3" class="text-center check">
                                        <input  class="form-check-input" v-model="permission.abc" :value="permission.id" type="checkbox" value="" >
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
                permissions:$json.permissions || [],
                entry:$json.entry||[],
                entries: [],
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
            edit: function (id){
                window.location.href='/xadmin/roles/edit?id='+ id;
            },
            async load() {
                let query = $router.getQuery();
                const res  = await $get('/xadmin/roles/data', query);
                this.paginate = res.paginate;
                this.entries = res.data;
                console.log(this.entries);
                this.from = (this.paginate.currentPage-1)*(this.limit) + 1;
                this.to = (this.paginate.currentPage-1)*(this.limit) + this.entries.length;
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/roles/save', {entry: this.entry,permissions: this.permissions}, false);
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
                const res = await $post('/xadmin/roles/toggleStatus', {
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
            }
        }
    }
</script>

<style scoped>
</style>
