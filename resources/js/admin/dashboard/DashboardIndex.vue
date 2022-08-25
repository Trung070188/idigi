<template>

    <div class="post d-flex flex-column-fluid" id="kt_post">
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
                    <!--begin::Tables Widget 9-->
                    <div class="card card-xl-stretch-50 mb-5 mb-xl-8">
                        <!--begin::Body-->
                        <div class="card-body p-0 d-flex justify-content-between flex-column overflow-hidden">
                            <!--begin::Hidden-->
                            <div class="d-flex flex-stack flex-wrap flex-grow-1 px-9 pt-9 pb-3">
                                <div class="me-2">

                                </div>

                            </div>
                            <!--end::Hidden-->
                            <!--begin::Chart-->
                            <div class="mixed-widget-10-chart" data-kt-color="primary" style="height: 175px"></div>
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
                            <h3 class="card-title fw-bolder text-dark">Activities</h3>

                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body pt-2" >
                            <!--begin::Item-->
                            <div class="d-flex align-items-center mb-8" v-for="entry in entries">

                                <div class="flex-grow-1" >
                                    <div  class="text-gray-800 text-hover-primary fw-bolder fs-6">{{entry.username}} {{entry.status}} school {{entry.createSchoolName}} </div>
                                </div>

                            </div>

                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end:List Widget 3-->
                </div>

            </div>

            <div class="row g-5 g-xl-8">
                <div class="col-xl-8">
                    <!--begin::Tables Widget 5-->
                    <div class="card card-xxl-stretch mb-5 mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">Licenses</span>
                            </h3>

                        </div>

                        <div class="card-body py-3">
                            <div class="tab-content">
                                <!--begin::Tap pane-->
                                    <!--begin::Table container-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table table-row-gray-200  gs-0 gy-4">
                                            <!--begin::Table head-->
                                            <thead>
                                            <tr class="border-0">
                                                <th class="p-0 w-50px"></th>
                                                <th class="p-0 min-w-150px"></th>
                                                <th class="p-0 min-w-140px"></th>
                                                <th class="p-0 min-w-110px"></th>
                                                <th class="p-0 min-w-50px"></th>
                                            </tr>
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody >
                                            <tr v-for="license in licenseRemain">
                                                <td>
                                                    <div class="symbol symbol-45px me-2">
																				<span class="symbol-label">
                                                                                    <i class="bi bi-bank"></i>
																				</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="index.html#" class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{license.label}}</a>
                                                    <span class="text-muted fw-bold d-block">{{license.dayEnd}} days remaining</span>
                                                </td>
                                                <td></td>

                                                <td >

                                                </td>
                                                <td class="text-end">
                                                    <a :href="license.url">
                                                        <button class="btn btn-primary">Renew license</button>
                                                    </a>

                                                </td>
                                            </tr>

                                            </tbody>

                                        </table>
                                    </div>


                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Tables Widget 5-->
                </div>
                <div class="col-xl-4">
                    <!--begin::Mixed Widget 5-->
                    <div class="card card-xxl-stretch mb-xl-8">
                        <!--begin::Beader-->
                        <div class="card-header border-0 py-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">New schools</span>
                            </h3>
                            <div class="card-toolbar">

                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">

                                </div>

                            </div>
                        </div>


                        <!--end::Body-->
                    </div>
                    <!--end::Mixed Widget 5-->
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
