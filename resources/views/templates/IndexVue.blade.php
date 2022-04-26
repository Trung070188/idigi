<template>
    <div class="container-fluid" >
        <ActionBar type="index"
                   createUrl="/xadmin/{{$table}}/create"
                   title="{{$ucTable.'Index'}}"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-5">

                        <div class="row width-full">
                            <div class="col-lg-12">
                                <form class="form-inline">
                                    <div class="form-group mx-sm-3 mb-4">
                                        <input @keydown.enter="doFilter('keyword', filter.keyword, $event)" v-model="filter.keyword"
                                               type="text"
                                               class="form-control" placeholder="tìm kiếm" value="">
                                    </div>
                                    <div class="form-group mx-sm-3 mb-4">
                                        <Daterangepicker v-model="filter.created" placeholder="Ngày tạo"></Daterangepicker>
                                    </div>

                                    <div class="form-group mx-sm-3 mb-2">
                                        <button @click="filterClear()" type="button" v-on:click="clearFilter()"
                                                class="btn btn-sm btn-flex btn-light  fw-bolder">Xóa
                                        </button>
                                    </div>

                                </form>
                            </div>

                        </div>

                    </div>

                    <div class="card-body d-flex flex-column" >
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr> <th>ID</th>
                                @foreach ($fields as $field)
                                    <th>{{word_normalized($field)}}</th>
                                @endforeach
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="entry in entries">
                                <td >
                                    <a class="edit-link" :href="'{{$routePrefix}}/{{$table}}/edit?id='+entry.id"  v-text="entry.id"></a>
                                </td>
                                @foreach ($fields as $field)
                                    <td v-text="entry.{{$field}}"></td>
                                @endforeach

                                <td class="">
<!--                                    <a :href="'{{$routePrefix}}/{{$table}}/edit?id='+entry.id" ><i style="font-size:1.3rem" class="fa fa-edit"></i></a>-->
                                    <a @click="remove(entry)" href="javascript:;" class="btn-trash"><i  class="fa fa-trash mr-1"></i></a>
                                </td>
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
        name: "{{ucfirst($table)}}Index.vue",
        components: {ActionBar},
        data() {
            return {
                entries: [],
                filter: {
                    keyword: $q.keyword || '',
                    created: $q.created || created,
                },
                paginate: {
                    currentPage: 1,
                    lastPage: 1
                }
            }
        },
        mounted() {
            $router.on('/', this.load).init();
        },
        methods: {
            async load() {
                let query = $router.getQuery();
                const res  = await $get('{{$routePrefix}}/{{$table}}/data', query);
                this.paginate = res.paginate;
                this.entries = res.data;
            },
            async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return;
                }

                const res = await $post('{{$routePrefix}}/{{$table}}/remove', {id: entry.id});

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
            async toggleStatus(entry) {
                const res = await $post('{{$routePrefix}}/{{$table}}/toggleStatus', {
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
