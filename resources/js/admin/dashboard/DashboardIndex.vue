<template>


    <div class="post d-flex flex-column-fluid" id="kt_post">
         <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title = "Dashboard"/>
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <!--begin::Row-->
            <div class="row gy-5 g-xl-8">
                <!--begin::Col-->
                <div class="col-xl-3">
                    <div class="col bg-light-warning px-6 py-8 rounded-2 me-7 mb-7">
                        <a href="index.html#" class="text-warning fw-bold fs-6">Lessons uploaded</a>
                        <div class="text-warning fw-bold fs-6">{{lessons}}</div>

                    </div>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xl-3">
                    <div class="col bg-light-primary px-6 py-8 rounded-2 me-7 mb-7">
                        <a href="index.html#" class="text-primary fw-bold fs-6">Users</a>
                        <div  class="text-primary fw-bold fs-6">{{users}}</div>
                    </div>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xl-3">
                    <div class="col bg-light-danger px-6 py-8 rounded-2 me-7 mb-7">

                        <a href="index.html#" class="text-danger fw-bold fs-6">Devices</a>
                        <div class="text-danger fw-bold fs-6">{{devices}}</div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="col bg-light-success px-6 py-8 rounded-2">
                        <a href="index.html#" class="text-success fw-bold fs-6 mt-2">Schools</a>
                        <div  class="text-success fw-bold fs-6 mt-2">{{schools}}</div>
                    </div>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row gy-5 g-xl-8">
                <div class="col-xl-8">
                    <div class="card card-xl-stretch mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">Recent Statistics</span>
                                <span class="text-muted fw-bold fs-7">More than 400 new members</span>
                            </h3>
                            <!--end::Title-->
                            <!--begin::Toolbar-->
                            <div class="card-toolbar">
                                <!--begin::Menu-->
                                <button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen024.svg-->
                                    <span class="svg-icon svg-icon-2">
															<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<rect x="5" y="5" width="5" height="5" rx="1" fill="#000000" />
																	<rect x="14" y="5" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
																	<rect x="5" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
																	<rect x="14" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
																</g>
															</svg>
														</span>
                                    <!--end::Svg Icon-->
                                </button>
                                <!--begin::Menu 1-->
                                <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_619665c554852">
                                    <!--begin::Header-->
                                    <div class="px-7 py-5">
                                        <div class="fs-5 text-dark fw-bolder">Filter Options</div>
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Menu separator-->
                                    <div class="separator border-gray-200"></div>
                                    <!--end::Menu separator-->
                                    <!--begin::Form-->
                                    <div class="px-7 py-5">
                                        <!--begin::Input group-->
                                        <div class="mb-10">
                                            <!--begin::Label-->
                                            <label class="form-label fw-bold">Status:</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <div>
                                                <select class="form-select form-select-solid" data-kt-select2="true" data-placeholder="Select option" data-dropdown-parent="#kt_menu_619665c554852" data-allow-clear="true">
                                                    <option></option>
                                                    <option value="1">Approved</option>
                                                    <option value="2">Pending</option>
                                                    <option value="2">In Process</option>
                                                    <option value="2">Rejected</option>
                                                </select>
                                            </div>
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10">
                                            <!--begin::Label-->
                                            <label class="form-label fw-bold">Member Type:</label>
                                            <!--end::Label-->
                                            <!--begin::Options-->
                                            <div class="d-flex">
                                                <!--begin::Options-->
                                                <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                                                    <input class="form-check-input" type="checkbox" value="1" />
                                                    <span class="form-check-label">Author</span>
                                                </label>
                                                <!--end::Options-->
                                                <!--begin::Options-->
                                                <label class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox" value="2" checked="checked" />
                                                    <span class="form-check-label">Customer</span>
                                                </label>
                                                <!--end::Options-->
                                            </div>
                                            <!--end::Options-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10">
                                            <!--begin::Label-->
                                            <label class="form-label fw-bold">Notifications:</label>
                                            <!--end::Label-->
                                            <!--begin::Switch-->
                                            <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                                <input class="form-check-input" type="checkbox" value="" name="notifications" checked="checked" />
                                                <label class="form-check-label">Enabled</label>
                                            </div>
                                            <!--end::Switch-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Actions-->
                                        <div class="d-flex justify-content-end">
                                            <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>
                                            <button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Apply</button>
                                        </div>
                                        <!--end::Actions-->
                                    </div>
                                    <!--end::Form-->
                                </div>
                                <!--end::Menu 1-->
                                <!--end::Menu-->
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body">
                            <!--begin::Chart-->
                            <div id="kt_charts_widget_1_chart" style="height: 350px"></div>
                            <!--end::Chart-->
                        </div>
                    </div>
                    <!--end::Tables Widget 9-->
                </div>
                <div class="col-xl-4">
                    <!--begin::List Widget 3-->
                    <div class="card card-xl-stretch mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0">
                            <h3 class="card-title fw-bolder text-dark"></h3>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body pt-2" >
                            <!--begin::Item-->
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end:List Widget 3-->
                </div>
            </div>

            <div class="row g-5 g-xl-12">
                <div class="col-xl-12">
                    <div class="card card-xl-stretch mb-xl-12">
                        <div class="card-header border-0">
                            <h3 class="card-title fw-bolder text-dark">Activities</h3>
                        </div>
                        <div class="mh-650px scroll-y me-n7 pe-7">
                            <table class="table table-row-bordered align-middle gy-4 gs-9">
                                <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                                <tr >
                                    <th>No.</th>
                                    <th class="">User name</th>
                                    <th class="">Role</th>
                                    <th class="">Description</th>
                                    <th class="">Object</th>
                                    <th>IP</th>
                                    <th>Time</th>
                                    <th></th>

                                </tr>
                                </thead>
                                <tbody >
                                <tr v-for="(entry,index) in entries">
                                    <td>{{index+1}}</td>
                                    <td v-text="entry.username"></td>
                                    <td v-text="entry.role"></td>
                                    <td v-text="entry.status"></td>
                                    <td v-text="entry.object"></td>
                                    <td v-text="entry.ip"></td>
                                    <td>{{d(entry.time)}}</td>
                                    <td class="">
                                        <button class="btn btn-active-danger btn-light-danger btn-sm" >Delete</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>


</template>

<script>
    import ActionBar from "../includes/ActionBar";
    import $router from "../../lib/SimpleRouter";
    import {$get} from "../../utils";
    export default {
        name: "DashboardIndex",
        components: {ActionBar},
        data()
        {
            return {
                breadcrumbs:[
                    {
                        title: 'Dashboard'
                    },
                ],
                entries:[],
                users:$json.users,
                schools:$json.schools,
                devices:$json.devices,
                lessons:$json.lessons,
                licenseRemain:$json.licenseRemain,
            }
        },
        mounted() {
            $router.on('/', this.load).init();
        },
        methods:
        {
            async load() {
            let query = $router.getQuery();
            const res = await $get('/xadmin/dashboard/data', query);
            this.entries = res.data;
            console.log(this.entries);



        },
        },
    }
</script>

<style scoped>

</style>
