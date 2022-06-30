<template>
    <div class="container-fluid" >
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                     <div class="card-header border-0 pt-5">
                        <div class="title">
                            <label>School</label>
                        </div>
                           <a :href="'/xadmin/schools/create'" class="btn btn-primary button-create " >
                        Create new
                    </a>
                    </div>
                    <hr>
                    <div class="card-header border-0 pt-5">

                        <div class="row width-full">
                            <div class="col-lg-12">
                                <form class="form-inline">
                                    <div class="form-group mx-sm-3 mb-4">
                                        <input @keydown.enter="doFilter($event)"
                                               v-model="filter.keyword"
                                               type="text"
                                               class="form-control" placeholder="Search..." value="">
                                    </div>
                                    <div class="form-group mx-sm-3 mb-4">
                                        <button type="button"
                                                @click="isShowFilter = !isShowFilter"
                                                class="btn btn-primary" v-if="isShowFilter"> Close Adventure search
                                            <i style="margin-left: 5px" class="fas fa-times"></i>

                                        </button>
                                        <button type="button"
                                                @click="isShowFilter = !isShowFilter"
                                                class="btn btn-primary" v-if="!isShowFilter"> Adventure search
                                            <i class="fa fa-filter" v-if="!isShowFilter" aria-hidden="true"></i>

                                        </button>

                                    </div>
                                </form>

                                <form class="col-lg-12" v-if="isShowFilter">
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>School name </label>
                                            <input class="form-control" type="text" placeholder="Enter your school name" v-model="filter.school_name"/>

                                        </div>

                                    </div>

                                    <div style="margin: auto 0">
                                        <button type="button" class="btn btn-primary" @click="doFilter($event)">Search</button>
                                    </div>
                                </form>
                            </div>

                        </div>

                    </div>

                    <div class="card-body d-flex flex-column" >
                        <div v-text="'Showing '+ from +' to '+ to +' of '+ paginate.totalRecord +' entries'" v-if="entries.length > 0"></div>
                        <table class=" table  table-head-custom table-head-bg table-vertical-center">
                            <thead>
                            <tr> <th>ID</th>
                                <th>School Name</th>
                                <th>School Address</th>
                                <th>No Of Users</th>
                                <th>Devices Per User</th>
                                <th>Region/City</th>
                                <th>License State</th>
                            <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="entry in entries">
                                <td v-text="entry.id"></td>
                                <td v-text="entry.school_name"></td>
                                <td v-text="entry.school_address"></td>
                                <td v-text="entry.number_of_users"></td>
                                <td v-text="entry.devices_per_user"></td>
                                <td ></td>
                                <td v-text="entry.license_state"></td>

                                <td class="">
                                    <a :href="'/xadmin/schools/edit?id='+entry.id" style="margin-right: 10px"><i style="font-size:1.3rem" class="fa fa-edit"></i></a>
                                    <a @click="remove(entry)" href="javascript:;" class="btn-trash deleted"><i  class="fa fa-trash mr-1"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div style="margin-top:10px; display: flex">
                            <div class="col-4 form-group align-items-center  d-inline-flex mt-2">
                                <div class="mr-2">
                                    <label>Records per page:</label>
                                </div>
                                <div>
                                    <select class="form-select form-select-sm " v-model="limit" @change="changeLimit">
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>

                                    </select>
                                </div>
                            </div>
                            <div style="float: right;margin: 10px">
                                <Paginate :value="paginate" :pagechange="onPageChange"></Paginate>
                            </div>
                        </div>

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
        name: "SchoolsIndex.vue",
        components: {ActionBar},
        data() {
            let isShowFilter = false;
            let filter = {
                keyword: $q.keyword || '',
                school_name: $q.school_name || ''
            };
            for (var key in filter) {
                if (filter[key] != '') {
                    isShowFilter = true;
                }
            }
            return {
                breadcrumbs: [
                    {
                        title: 'Schools'
                    },
                ],
                entries: [],
                isShowFilter: isShowFilter,
                filter: filter,
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
                    window.location.href='/xadmin/schools/edit?id='+ id;
                }

            },
            async load() {
                let query = $router.getQuery();
                this.$loading(true);
                const res  = await $get('/xadmin/schools/data', query);
                this.$loading(false);
                this.paginate = res.paginate;
                this.entries = res.data;
                this.from = (this.paginate.currentPage-1)*(this.limit) + 1;
                this.to = (this.paginate.currentPage-1)*(this.limit) + this.entries.length;
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

                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});
            },
            filterClear() {
                for (var key in this.filter) {
                    this.filter[key] = '';
                }

                $router.setQuery({});
            },
            doFilter(event) {
                if (event) {
                    event.preventDefault();
                }
                $router.setQuery(this.filter)
            },
            changeLimit() {
                let params = $router.getQuery();
                params['page']=1;
                params['limit'] = this.limit;
                $router.setQuery(params)
            },

            async toggleStatus(entry) {
                const res = await $post('/xadmin/schools/toggleStatus', {
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
