<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-5">
                        <div class="title">
                            <label>Manage users</label>
                        </div>
                           <a :href="'/xadmin/users/create'" class="btn btn-primary button-create " >
                        Create new
                    </a>
                    </div>
                    <hr>
                    <div class="card-header border-0 pt-5">

                        <div class="row width-full">
                            <div class="col-lg-12">
                                <form class="form-inline">
                                    <div class="form-group mx-sm-3 mb-4" >
                                        <input @keydown.enter="doFilter('keyword', filter.keyword, $event)"
                                               v-model="filter.keyword"
                                               type="text"
                                               style="width: 400px"
                                               class="form-control " placeholder="Search ID,username,email,role..."
                                               value="">
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
                                            <label  style="margin-bottom: 2px">Full name </label>
                                            <input @keydown.enter="doFilter('full_name', filter.full_name, $event)"
                                                   class="form-control" placeholder="Enter the full name"
                                                   v-model="filter.full_name"/>

                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label >Email </label>
                                            <input @keydown.enter="doFilter('email', filter.email, $event)"
                                                   class="form-control" placeholder="Enter the email"
                                                   v-model="filter.email">
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Role </label>
                                            <select @keydown.enter="doFilter('role', filter.role, $event)" required
                                                    class="form-control form-select" v-model="filter.role">
                                                <option value="" disabled selected>Choose role</option>
                                                <option value="0">All</option>
                                                <option value="Super Administrator">Super Administrator</option>
                                                <option value="Administrator">Administrator</option>
                                                <option value="Partner">Partner</option>
                                                <option value="Teacher">Teacher</option>
                                                <option value="Moderator">Moderator</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label >Creation date </label>
                                            <Daterangepicker v-model="filter.created"
                                                             placeholder="Creation date"></Daterangepicker>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label >Active</label>
                                            <div>
                                                <switch-button v-model="filter.state"></switch-button>
                                            </div>

                                        </div>
                                    </div>
                                    <div style="margin: auto 0">
                                        <button type="button" class="btn btn-dark" @click="doFilter()">Search</button>
                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>


                    <div class="card-body d-flex flex-column">
                       <div>
                           <div  @click="filterClear()">
                              <span style="float: right; margin:0px 0px -20px">
                                Resfesh List
                                  <i class="fas fa-sync"></i>
                              </span>
                           </div>
                           <div>
                              <span style="float: right;margin-bottom: -20px;margin-right: 105px">
                                Last update at {{d(last_updated)}}
                              </span>
                           </div>

                           <div style="float: left"
                                v-text="'Showing '+ from +' to '+ to +' of '+ paginate.totalRecord +' entries'"
                                v-if="entries.length > 0"></div>
                       </div>
                        <table class=" table  table-head-custom table-head-bg table-vertical-center">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>FullName</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Creation Date</th>
                                <th>Active</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="entry in entries">
                                <td v-text="entry.id"></td>
                                <td v-text="entry.username"></td>
                                <td v-text="entry.full_name"></td>
                                <td v-text="entry.email"></td>
                                <td v-text="entry.role"></td>
                                <td v-text=" d(entry.created_at)"></td>
                                <td v-if="entry.state==1">Yes</td>
                                <td v-if="entry.state==0">No</td>
                                <td>
                                    <a :href="'/xadmin/users/edit?id='+entry.id"><i style="font-size:1.3rem"
                                                                                    class="fa fa-edit"></i></a>
                                    <a @click="remove(entry)" href="javascript:;" class="btn-trash deleted"><i
                                        class="fa fa-trash mr-1 deleted"></i></a>
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
    import SwitchButton from "../../components/SwitchButton";


    let created = getTimeRangeAll();
    const $q = $router.getQuery();

    export default {
        name: "UsersIndex.vue",
        components: {ActionBar, SwitchButton},
        data() {
            let isShowFilter = false;
            let filter = {
                keyword: $q.keyword || '',
                created: $q.created || '',
                full_name: $q.full_name || '',
                email: $q.email || '',
                state: $q.state || '',
                role: $q.role || '',

            };
            for (var key in filter) {
                if (filter[key] != '') {
                    // isShowFilter = true;
                }
            }
            return {
                isShowFilter: isShowFilter,
                breadcrumbs: [
                    {
                        title: 'Users & Roles'
                    },
                    {
                        title: 'Manage Users'
                    },
                ],
                roles: $json.roles || [],
                last_updated: [],
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

            // edit: function (id, event){
            //     if (!$(event.target).hasClass('deleted')) {
            //         window.location.href = '/xadmin/users/edit?id=' + id;
            //     }
            // },

            async load() {

                let query = $router.getQuery();
                this.$loading(true);
                const res = await $get('/xadmin/users/data', query);
                this.$loading(false);
                this.paginate = res.paginate;
                this.entries = res.data.data;
                this.last_updated = res.data.last_updated;
                this.from = (this.paginate.currentPage - 1) * (this.limit) + 1;
                this.to = (this.paginate.currentPage - 1) * (this.limit) + this.entries.length;
            },
            async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return false;
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

                for (var key in this.filter) {
                    this.filter[key] = '';
                }
                $router.setQuery({});
            },
            doFilter() {
                $router.setQuery(this.filter)
            },
            changeLimit() {
                let params = $router.getQuery();
                params['page'] = 1;
                params['limit'] = this.limit;
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
    select:required:invalid {
        color: #adadad;
    }

    option[value=""][disabled] {
        display: none;
    }

    option {
        color: black;
    }



</style>
