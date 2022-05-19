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
                                <td  >
                                    <div class="text-center">

                                    </div>

                                </td>

                            </tr>
                            </tbody>
                            <tr>
                                <th scope="col">
                                    <span>Manage user</span>
                                    <span  v-for="entry in entries" v-if="entry.group_permission_id==1" class="d-block" style="margin-left: 20px">{{entry.name}}</span>
                                </th>
                                <td v-for="entry in entries">
                                    <br>
                                    <div class="text-center check">
                                        <input  class="form-check-input" type="checkbox"  value="" >
                                    </div>
                                    <br>
                                    <div class="text-center check">
                                        <input  class="form-check-input" type="checkbox" value="" >

                                    </div>
                                    <br>
                                    <div class="text-center check">
                                        <input  class="form-check-input" type="checkbox" value="" >

                                    </div>
                                    <br>
                                    <div class="text-center check">
                                        <input  class="form-check-input" type="checkbox" value="" >

                                    </div>
                                    <br>
                                    <div class="text-center check">
                                        <input  class="form-check-input" type="checkbox" value="" >

                                    </div>  <br>

                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <span>Manage user</span>
                                    <span v-for="entry in entries" v-if="entry.group_permission_id==2" class="d-block" style="margin-left: 20px">{{entry.name}}</span>
                                </th>
                                <td v-for="entry in entries">
                                    <br>
                                    <div class="text-center check">
                                        <input  class="form-check-input" type="checkbox" value="" >
                                    </div>
                                    <br>
                                    <div class="text-center check">
                                        <input  class="form-check-input" type="checkbox" value="" >
                                    </div>
                                    <br>
                                    <div class="text-center check">
                                        <input  class="form-check-input" type="checkbox" value="" >
                                    </div>
                                    <br>
                                    <div class="text-center check">
                                        <input  class="form-check-input" type="checkbox" value="" >
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <span>Manage data</span>
                                    <span v-for="entry in entries" v-if="entry.group_permission_id==3" class="d-block" style="margin-left: 20px">{{entry.name}}</span>
                                </th>
                                <td v-for="entry in entries">
                                    <br>
                                    <div class="text-center check">
                                        <input  class="form-check-input" type="checkbox" value="" >
                                    </div>
                                    <br>
                                    <div class="text-center check">
                                        <input  class="form-check-input" type="checkbox" value="" >
                                    </div>
                                </td>

                            </tr>




                        </table>


<!--                    <div class="card-body d-flex flex-column" >-->
<!--                        <div v-text="'Showing '+ from +' to '+ to +' of '+ paginate.totalRecord +' entries'"></div>-->
<!--                        <table class=" table  table-head-custom table-head-bg table-vertical-center">-->
<!--                            <thead>-->
<!--                            <tr v-for="entry in entries" @click="edit(entry.id)">-->

<!--                                                                    <th>{{entry.role_name}}</th>-->

<!--                            </tr>-->
<!--                            </thead>-->
<!--                            <tr >-->
<!--&lt;!&ndash;                                <td v-text="entry.id"></td>&ndash;&gt;-->
<!--&lt;!&ndash;                                                                    <td v-text="entry.role_name"></td>&ndash;&gt;-->
<!--&lt;!&ndash;                                                                    <td v-text="entry.role_description"></td>&ndash;&gt;-->

<!--&lt;!&ndash;                                <td class="">&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                                    <a :href="'/xadmin/roles/edit?id='+entry.id" ><i style="font-size:1.3rem" class="fa fa-edit"></i></a>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                    <a @click="remove(entry)" href="javascript:;" class="btn-trash"><i  class="fa fa-trash mr-1"></i></a>&ndash;&gt;-->
<!--&lt;!&ndash;                                </td>&ndash;&gt;-->
<!--                            </tr>-->
<!--                        </table>-->
<!--                        <div style="margin-top:10px; display: flex">-->
<!--                            <div class="col-4 form-group d-inline-flex mt-2">-->
<!--                                <div class="mr-2">-->
<!--                                    <label>Records per page:</label>-->
<!--                                </div>-->
<!--                                <div>-->
<!--                                    <select class="form-select form-select-sm " v-model="limit" @change="changeLimit">-->
<!--                                        <option value="25">25</option>-->
<!--                                        <option value="50">50</option>-->
<!--                                        <option value="100">100</option>-->

<!--                                    </select>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div style="float: right">-->
<!--                                <Paginate :value="paginate" :pagechange="onPageChange"></Paginate>-->
<!--                            </div>-->
<!--                        </div>-->


<!--                    </div>-->
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
            // async load() {
            //     let query = $router.getQuery();
            //     const res  = await $get('/xadmin/roles/data', query);
            //     this.paginate = res.paginate;
            //     this.entries = res.data;
            //     console.log(this.entries);
            //     this.from = (this.paginate.currentPage-1)*(this.limit) + 1;
            //     this.to = (this.paginate.currentPage-1)*(this.limit) + this.entries.length;
            // },
            async load() {
                let query = $router.getQuery();
                const res  = await $get('/xadmin/permissions/data', query);
                this.paginate = res.paginate;
                this.entries = res.data;
                this.from = (this.paginate.currentPage-1)*(this.limit) + 1;
                this.to = (this.paginate.currentPage-1)*(this.limit) + this.entries.length;
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
