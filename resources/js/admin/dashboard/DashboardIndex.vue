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
                        <GoogleChart  :chart_data="dataChart"/>
                    </div>
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
                                        <button class="btn btn-active-danger btn-light-danger btn-sm" @click="remove(entry)">Delete</button>
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
    import {$get, $post} from "../../utils";
    import GoogleChart from "../../components/google-chart/GoogleChart"
    export default {
        name: "DashboardIndex",
        components: {ActionBar,GoogleChart},
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
                dataChart:[]
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
            this.dataChart=res.dataChart

        },

            async remove(entry)
            {
                const res= await $post('/xadmin/dashboard/remove',{
                    id:entry.id
                })
                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                    let self=this;
                    self.entries=  self.entries.filter(item => item.id !=entry.id);
                }
            }
        },
    }
</script>

<style scoped>

</style>
