<template>
    <div class="container-fluid">
        <ActionBar type="index"

                   createUrl="/xadmin/inventories/create"
                   :breadcrumbs="breadcrumbs"
                   title="Inventory"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-5">

                        <div class="row width-full">
                            <div class="col-lg-12">
                                <form class="form-inline">
                                    <div class="form-group mx-sm-3 mb-4">
                                        <input @keydown.enter="doFilter($event)"
                                               v-model="filter.keyword"
                                               type="text"
                                               class="form-control" placeholder="Tìm kiếm" value="">
                                    </div>
                                    <div class="form-group mx-sm-3 mb-2">
                                        <button type="button" style="margin-left: 10px"
                                                @click="isShowFilter = !isShowFilter"
                                                class="btn btn-primary"> Tìm kiếm mở rộng
                                            <i class="fa fa-caret-down" v-if="!isShowFilter"></i>
                                            <i class="fa fa-caret-up" v-if="isShowFilter" aria-hidden="true"></i>

                                        </button>


                                    </div>
                                    <div class="form-group mx-sm-3 mb-2">
                                        <button @click="filterClear()" type="button"
                                                class="btn btn-flex btn-light  fw-bolder ">Clear
                                        </button>
                                    </div>


                                </form>

                                <form class="col-lg-12" v-if="isShowFilter">
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Type </label>
                                            <select class="form-control" v-model="filter.type">
                                                <option value="">-</option>
                                                <option value="vocabulary">Vocabulary</option>
                                                <option value="summary">Summary</option>
                                                <option value="lecture">Lecture</option>
                                                <option value="activity1">Activity1</option>
                                                <option value="activity2">Activity2</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Subject </label>
                                            <select class="form-control" v-model="filter.subject">
                                                <option value="">-</option>
                                                <option value="math">Maths</option>
                                                <option value="science ">Science</option>
                                            </select>

                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Grade </label>
                                            <select class="form-control" v-model="filter.grade">
                                                <option value="">-</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Ngày tạo </label>
                                            <Daterangepicker v-model="filter.created"
                                                             placeholder="Ngày tạo"></Daterangepicker>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Active</label>
                                            <div>
                                                <switch-button v-model="filter.enabled"></switch-button>
                                            </div>

                                        </div>
                                    </div>
                                    <div style="margin: auto 0">
                                        <button type="button" class="btn btn-primary" @click="doFilter($event)">Tìm kiếm</button>
                                    </div>
                                </form>
                            </div>

                        </div>

                    </div>

                    <div class="card-body d-flex flex-column">
                        <div v-text="'Showing '+ from +' to '+ to +' of '+ paginate.totalRecord +' entries'"
                             v-if="entries.length > 0"></div>
                        <table class=" table  table-head-custom table-head-bg table-vertical-center">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Grade</th>
                                <th>Type</th>
                                <th>Active</th>
                                <th>Creation Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="entry in entries">
                                <td v-text="entry.id"></td>
                                <td v-text="entry.name"></td>
                                <td v-text="entry.grade"></td>
                                <td v-text="entry.type"></td>
                                <td v-text="entry.enabled == 0 ? 'No' : 'Yes'"></td>
                                <td v-text=" d(entry.created_at)"></td>

                                <td>
                                    <a :href="'/xadmin/inventories/edit?id='+entry.id" style="margin-right: 10px"><i style="font-size:1.3rem" class="fa fa-edit"></i></a>
                                    <a @click="remove(entry)" href="javascript:;" class="btn-trash deleted"><i
                                        class="fa fa-trash mr-1 deleted"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div style="margin-top:10px; display: flex">
                            <div class="col-4 form-group d-inline-flex mt-2">
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
                            <div style="float: right">
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
    import SwitchButton from "../../components/SwitchButton";

    let created = getTimeRangeAll();
    const $q = $router.getQuery();


    export default {
        name: "InventoriesIndex.vue",
        components: {ActionBar, SwitchButton},
        data() {
            let isShowFilter = false;
            let filter = {
                keyword: $q.keyword || '',
                created: $q.created || '',
                subject: $q.subject || '',
                type: $q.type || '',
                grade: $q.grade || '',
                enabled: $q.enabled || ''
            };
            for (var key in filter) {
                if (filter[key] != '') {
                    isShowFilter = true;
                }
            }
            return {
                isShowFilter: isShowFilter,
                breadcrumbs: [
                    {
                        title: 'Inventories'
                    },
                ],
                entries: [],
                filter: filter,
                limit: $q.limit || 25,
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

            edit: function (id, event) {

                if (!$(event.target).hasClass('deleted')) {
                    window.location.href = '/xadmin/inventories/edit?id=' + id;
                }

            },
            async load() {
                let query = $router.getQuery();
                this.$loading(true);
                const res = await $get('/xadmin/inventories/data', query);
                this.$loading(false);
                this.paginate = res.paginate;
                this.entries = res.data;
                this.from = (this.paginate.currentPage - 1) * (this.limit) + 1;
                this.to = (this.paginate.currentPage - 1) * (this.limit) + this.entries.length;
            },
            async remove(entry) {

                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return false;
                }

                const res = await $post('/xadmin/inventories/remove', {id: entry.id});

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
                params['page'] = 1;
                params['limit'] = this.limit;
                $router.setQuery(params)
            },

            async toggleStatus(entry) {
                const res = await $post('/xadmin/inventories/toggleStatus', {
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
