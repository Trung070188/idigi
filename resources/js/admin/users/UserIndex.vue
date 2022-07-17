<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"  title = "User Manager - Users"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="card-header border-0 pt-5">

                        <div class="row width-full">
                            <div class="col-lg-12">
                                <form class="form-inline">
                                    <div class="form-group mx-sm-3 mb-4">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                            <span class="svg-icon svg-icon-1 position-absolute">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"></rect>
                                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black"></path>
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                            <input type="text" data-kt-filemanager-table-filter = "search" class="form-control form-control-solid w-250px ps-15" @keydown.enter="doFilter($event)" v-model="filter.keyword" placeholder="Search ID, username, email, role...." value="" />
                                              <span v-if="filter.keyword!==''" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0" @click="filterClear">
                                            <svg type="button" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin: 3px -25px 0px;">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                            </svg>
                                        </span>
                                        </div>
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
                                        <a v-if="permissions['001']" :href="'/xadmin/users/create'">
                                            <button class="btn btn-primary button-create" style="margin:0 0 0 15px"> Create new</button>
                                        </a>                                        

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
                                            <Daterangepicker v-model="filter.created" class="active"
                                                             placeholder="Creation date" readonly></Daterangepicker>
                                            <span v-if="filter.created!==''" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0" @click="filterClear">
                                            <svg type="button" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" style="float: right;margin: -32px 3px 0px;">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                            </svg>
                                        </span>
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
                    <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="deviceConfirm" tabindex="-1" role="dialog"
                         aria-labelledby="deviceConfirm"
                         aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                             style="max-width: 500px;">
                            <div class="modal-content box-shadow-main paymment-status" style="left:140px;text-align: center; padding: 27px 0px 10px;">
                                <div class="close-popup" data-dismiss="modal"></div>
                                <h3 class="popup-title success" >Delete user</h3>
                                <div class="content">
                                    <p>Can not delete Super Administrator account!</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card-body d-flex flex-column">
                       <div>
                           <div  @click="filterClear()">
                              <span style="float: right; margin:0px 0px -20px">
                               Refresh list
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
                                    <!--<a v-if="permissions['002']" :href="'/xadmin/users/edit?id='+entry.id"><i style="font-size:1.3rem"
                                                                                    class="fa fa-edit"></i></a>
                                    <a v-if="permissions['003'] && entry.role!=='Super Administrator'" @click="remove(entry)" href="javascript:;" class="btn-trash deleted"><i
                                        class="fa fa-trash mr-1 deleted"></i></a>
                                    <a v-if="permissions['003'] && entry.role=='Super Administrator'"  @click="modalDevice()" href="javascript:;" class="btn-trash deleted"><i
                                        class="fa fa-trash mr-1 deleted"></i></a>-->
                                        
                                    <a v-if="permissions['002']" :href="'/xadmin/users/edit?id='+entry.id">
                                        <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </a>                                        
                                    <a v-if="permissions['003'] && entry.role!=='Super Administrator'" @click="remove(entry)" href="javascript:;">
                                        <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary">
                                            <i class="fa fa-trash mr-1 deleted"></i>
                                        </button>
                                    </a>  
                                    <a v-if="permissions['003'] && entry.role=='Super Administrator'"  @click="modalDevice()" href="javascript:;">
                                        <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary">
                                            <i class="fa fa-trash mr-1 deleted"></i>
                                        </button>
                                    </a>  

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
    import {$get, $post, getTimeRangeAll,clone} from "../../utils";
    import $router from '../../lib/SimpleRouter';
    import ActionBar from "../includes/ActionBar";
    import SwitchButton from "../../components/SwitchButton";


    let created = getTimeRangeAll();
    const $q = $router.getQuery();

    export default {
        name: "UsersIndex.vue",
        components: {ActionBar, SwitchButton},
        data() {
            const permissions = clone(window.$permissions)
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
                permissions,
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
            modalDevice() {
                $('#deviceConfirm').modal('show');
            },

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
