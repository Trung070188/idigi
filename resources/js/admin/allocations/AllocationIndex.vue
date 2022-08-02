<template>
    <div class="container-fluid" >
        <ActionBar type="index"
                   title="AllocationIndex"/>
         <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="card-header border-0 pt-6">

                        <div class="card-title">
                            <div
                                class="d-flex align-items-center position-relative my-1"
                            >
                              <span
                                  class="svg-icon svg-icon-1 position-absolute ms-6"
                              >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                    >
                                        <rect
                                            opacity="0.5"
                                            x="17.0365"
                                            y="15.1223"
                                            width="8.15546"
                                            height="2"
                                            rx="1"
                                            transform="rotate(45 17.0365 15.1223)"
                                            fill="black"
                                        ></rect>
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="black"
                                        ></path>
                                    </svg>
                                </span>
                                <input
                                    type="text"
                                    data-kt-filemanager-table-filter="search"
                                    class="form-control form-control-solid w-250px ps-15"
                                    placeholder="Search..."
                                    value=""
                                />
                                <span
                                    class="svg-icon svg-icon-2 svg-icon-lg-1 me-0"
                                >
                                </span>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div
                                class="d-flex justify-content-end"
                                data-kt-customer-table-toolbar="base"

                            >
                                <a :href="'/xadmin/allocations/create'">
                                    <button   type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer" >Create New Allocation</button>

                                </a>

                            </div>

                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="d-flex flex-stack pt-4 pl-9 pr-9">
                            <div class="badge badge-lg badge-light-primary mb-15">
                                <div class="d-flex align-items-center flex-wrap">
                                    <div> </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-row-bordered align-middle gy-4 gs-9">
                            <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                            <tr>
                                <th class="">Title</th>
                                <th>No. of schools</th>
                                <th >No. of courses</th>
                                <th>No. of units</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody >
                            <tr >
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="">
                                    <a href="list.html#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                        <span class="svg-icon svg-icon-5 m-0">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black" />
															</svg>
														</span>
                                        <!--end::Svg Icon--></a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a  class="menu-link px-3">Rename</a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a class="menu-link px-3">Get confirmation code</a>
                                        </div>

                                    </div>
                                    <!--end::Menu-->
                                </td>
                            </tr>
                            </tbody>
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
        name: "AllocationsIndex.vue",
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
        },
        methods: {
           
          
            async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return;
                }

                const res = await $post('/xadmin/allocations/remove', {id: entry.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }

                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});
            },
            
            doFilter() {
               
            },
            changeLimit() {
                let params = $router.getQuery();
                params['page']=1;
                params['limit'] = this.limit;
                $router.setQuery(params)
            },

           
            onPageChange(page) {
                $router.updateQuery({page: page})
            }
        }
    }
</script>

<style scoped>

</style>
