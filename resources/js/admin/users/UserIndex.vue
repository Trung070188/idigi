<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   createUrl="/xadmin/student/create"
                   title="List of Accounts" :breadcrumbs = "breadcrumbs" />
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b" style="overflow: scroll">
                    <div class="card-header border-0 pt-5">

                        <div class="row width-full">
                            <div class="col-lg-12">
                                <form class="form-inline">
                                    <div class="form-group mx-sm-3 mb-4">
                                        <input @keydown.enter="doFilter('keyword', filter.keyword, $event)"
                                               v-model="filter.keyword"
                                               type="text"
                                               class="form-control" placeholder="tìm kiếm" value="">
                                    </div>


                                    <div class="form-group mx-sm-3 mb-2">
                                        <button @click="filterClear()" type="button" v-on:click="filterClear()"
                                                class="btn btn-sm btn-flex btn-light  fw-bolder ">Clear
                                        </button>
                                        <button type="button" style="margin-left: 10px" @click="isShowFilter = !isShowFilter"
                                                class="btn btn-primary"> Tìm kiếm mở rộng
                                        </button>
                                    </div>
                                    <div class="col-md-12"></div>
                                    <div class="form-group mx-sm-3 mb-4"  v-if="isShowFilter">
                                        <Daterangepicker v-model="filter.created"
                                                         placeholder="Ngày tạo"></Daterangepicker>
                                    </div>
                                    <div class="col-md-12"></div>

                                    <div class=" mx-sm-3 col-sm-4" style="margin-bottom: 10px" v-if="isShowFilter">
                                        <div class="form-group">
                                            <label v-text="'Test'" class="mr-2"></label> <i class="far fa-caret-square-down"></i>
                                            <QSelect :multiple="true">
                                            </QSelect>
                                        </div>
                                    </div>

                                    <div class="  col-md-12"></div>
                                    <div class="mx-sm-3 col-3"  style="margin: auto 0" v-if="isShowFilter">
                                        <button  class="btn btn-primary">Tìm kiếm</button>
                                    </div>
                                </form>

                            </div>

                        </div>

                    </div>

                    <div class="card-body d-flex flex-column">
                        <table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-row-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Last Login</th>
                                <th>Avatar</th>
                                <th>Phone</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="entry in entries">
                                <td>
                                    <a class="edit-link" :href="'/xadmin/users/edit?id='+entry.id"
                                       v-text="entry.id"></a>
                                </td>
                                <td v-text="entry.name"></td>
                                <td v-text="entry.email"></td>
                                <td v-text="entry.email"></td>
                                <td v-text="entry.email"></td>
                                <td v-text="entry.email"></td>

                                <td><a data-v-4e32d33e="" href="javascript:;"><i data-v-4e32d33e="" class="fas fa-trash"></i></a></td>
                            </tr>
                            </tbody>
                        </table>
                        <div style="margin-top:10px; ">
                            <Paginate :value="paginate" :pagechange="onPageChange"></Paginate>
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
        name: "UsersIndex.vue",
        components: {ActionBar},
        data() {
            return {
                isShowFilter: false,
                entries: [],
                filter: {
                    keyword: $q.keyword || '',
                    created: $q.created || created,
                },
                paginate: {
                    currentPage: 1,
                    lastPage: 1
                },
                breadcrumbs: [
                    {
                        title: 'Account'
                    },
                    {
                        title: 'Students',
                        is_active: true
                    }
                ]
            }
        },
        mounted() {
            $router.on('/', this.load).init();
        },
        methods: {
            async load() {
                let query = $router.getQuery();
                const res = await $get('/xadmin/users/data', query);
                this.paginate = res.paginate;
                this.entries = res.data;
            },
            async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return;
                }

                const res = await $post('/xadmin/users/remove', {id: entry.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }

                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});
            },
            filterClear() {
                for (var key in app.filter) {
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
            async toggleStatus(entry) {
                const res = await $post('/xadmin/users/toggleStatus', {
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
