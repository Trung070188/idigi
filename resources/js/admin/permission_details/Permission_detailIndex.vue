<template>
    <div class="container-fluid" >
        <ActionBar type="index"
                   title="Permission Detail"/>
       <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-5">
                        <!--<div class="title">
                            <label>Roles</label>
                        </div>-->
                        <button class="btn btn-primary button-create mb-3"><i class="bi bi-plus-lg"></i>Add feature</button>
                    </div>
                    <!--<hr>-->
                    <div class="card-header border-0 pt-2">
                        <table class="table bg-white table-bordered">
                            <tbody>
                            <tr>
                                <td></td>
                                <td  v-for="role in roles" >
                                    <div class="text-center" style="cursor: pointer">
                                        <span class="badge badge-warning fs-6 fw-bold" @click="showModalRole(role)" >{{role.role_name}} </span>
                                        <span><i v-if="role.allow_deleted == 1" @click="remove(role)" class="fa fa-trash" style="margin-left:10px"></i></span>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                            <tr >
                                <th scope="col">
                                    <span ></span>
                                    <span  class="d-block fw-bold ml-5 text-lowercase"><i class="bi bi-arrow-right-short mr-1"></i></span>
                                </th>

                                <td v-for="role in roles">

                                    <div class="form-check form-check-custom form-check-solid justify-content-center" >
                                        <input  class="form-check-input h-20px w-20px"   type="checkbox"  value="" >
                                        <br>
                                    </div>
                                </td>
                            </tr>

                        </table>
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
        name: "Permission_detailsIndex.vue",
        components: {ActionBar},
        data() {
            return {
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
            edit: function (id, event){
                if (!$(event.target).hasClass('deleted')){
                    window.location.href='/xadmin/permission_details/edit?id='+ id;
                }

            },
            async load() {
                let query = $router.getQuery();
                const res  = await $get('/xadmin/permission_details/data', query);
                this.paginate = res.paginate;
                this.entries = res.data;
                this.from = (this.paginate.currentPage-1)*(this.limit) + 1;
                this.to = (this.paginate.currentPage-1)*(this.limit) + this.entries.length;
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
            }
        }
    }
</script>

<style scoped>

</style>
