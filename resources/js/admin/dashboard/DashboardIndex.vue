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
                        <div class="card-header border-0 pt-5 mt-0">
                            <div>
                                <i class="bi bi-arrow-left" style="cursor: pointer"  @click="subMonthYear()"></i>
                                <span style="padding:8px 10px 5px 10px; color:#043B79; font-weight:800" v-model="cbYear"> {{(select=='Month')?(cbMonth + '/'):('')}}{{cbYear}} </span>
                                <i class="bi bi-arrow-right" style="cursor: pointer" @click="addMonthYear()"></i>
                            </div>

                        </div>
                        <GoogleChart  :chart_data="dataChart" />
                    </div>
                </div>
                <div class="col-xl-4">
                    <!--begin::List Widget 3-->
                    <div class="card card-xl-stretch mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0">
                            <h3 class="card-title fw-bolder text-dark">Licenses</h3>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body pt-2 mh-600px scroll-y me-n7 pe-7" >
                            <div class="d-flex flex-stack mb-5" v-for="license in licenseRemain">
                                <!--begin::Section-->
                                <div class="d-flex align-items-center me-2">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-light">
                                            <i class="bi bi-bank"></i>
                                        </div>
                                    </div>
                                    <!--end::Symbol-->
                                    <!--begin::Title-->
                                    <div>
                                        <a href="mixed.html#" class="fs-6 text-gray-800 text-hover-primary fw-bolder">{{license.label}}</a>
                                        <div class="fs-7 text-muted fw-bold mt-1">{{license.dayEnd}} days remaining</div>
                                    </div>
                                    <!--end::Title-->
                                </div>
                                <!--end::Section-->
                                <!--begin::Label-->
                                <div class="badge  fw-bold py-4 px-3"><a :href="license.url"><button class="btn btn-primary">Renew</button></a></div>
                                <!--end::Label-->
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end:List Widget 3-->
                </div>
            </div>

            <div class="row g-5 g-xl-12">
                <div class="col-xl-12">
                    <div class="card card-xl-stretch mb-xl-12">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">Activity</span>
                            </h3>
                            <div class="card-toolbar">
                                <Datepicker v-model="filter.created" @input="doFilter"/>
                            </div>
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

                                </tr>
                                </thead>
                                <tbody >
                                <tr v-for="(entry,index) in entries">
                                    <td>{{index+1}}</td>
                                    <td v-text="entry.username"></td>
                                    <td v-text="entry.role"></td>
                                    <td v-text="entry.status"></td>
                                    <td class="" data-bs-toggle="tooltip" :title="entry.object" v-text="entry.object"></td>
                                    <td v-text="entry.ip"></td>
                                    <td>{{d(entry.time)}}</td>
<!--                                    <td class="">-->
<!--                                        <button class="btn btn-active-danger btn-light-danger btn-sm" @click="remove(entry)">Delete</button>-->
<!--                                    </td>-->
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
    import {$get, $post, getTimeRangeAll} from "../../utils";
    import GoogleChart from "../../components/google-chart/GoogleChart"
    let created = getTimeRangeAll();
    const $q = $router.getQuery();
    export default {

        name: "DashboardIndex",
        components: {ActionBar,GoogleChart},
        data()
        {
            let today=new Date();
            let date=today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
            let filter = {
                created: $q.created || '' ,
            };
            return {
                logAuth:[],
                created:'',
                filter: filter,
                select:null,
                cbYear: new Date().getFullYear(),
                cbMonth: new Date().getMonth(),
                labelsYear: ["Jan","Feb","Mar","Apr","May","Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                labelsMonth:[],

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
        watch:{
            cbMonth:function(){
                if (this.select != 'Year'){
                    var dayOfMonth = new Date(this.cbYear, this.cbMonth, 0).getDate()
                    this.labelsMonth=[]
                    for(let i=1; i <= dayOfMonth; i++){
                        this.labelsMonth.push(i.toString())
                    }
                }
            }
        },
        methods:
        {
            filterClear() {

                for (var key in this.filter) {
                    this.filter[key] = '';
                }
                $router.setQuery({});
            },
            doFilter() {
                $router.setQuery(this.filter)
            },
            changeCombo(){
                if (this.select == 'Month'){
                    this.cbYear = new Date().getFullYear()
                    this.cbMonth= new Date().getMonth() + 1
                }
                else{
                    this.cbYear = new Date().getFullYear()
                }

            },
            addMonthYear(){
                if (this.select == 'Month'){
                    if (this.cbMonth + 1 > 12){
                        this.cbMonth = 1;
                        this.cbYear += 1;
                    }
                    else this.cbMonth += 1;
                }else{
                    this.cbYear += 1;

                }
                this.load();
            },
            subMonthYear(){
                if (this.select == 'Month'){
                    if (this.cbMonth - 1 == 0){
                        this.cbMonth = 12;
                        this.cbYear -= 1;
                    }
                    else this.cbMonth -= 1;
                }else{

                    this.cbYear -= 1;
                }
                this.load();
            },
            async load() {
            let query = $router.getQuery();
                let today=new Date();
                let create=today.getFullYear()+'-'+String(today.getMonth() + 1).padStart(2, '0')+'-'+String(today.getDate()).padStart(2, '0');
                console.log(create);
            const res = await $get('/xadmin/dashboard/data?year='+this.cbYear+'&created='+create, query);
            this.entries = res.data;
            this.logAuth=res.logAu;
            this.entries=this.entries.concat(this.logAuth);
            console.log(this.entries);

            this.dataChart=res.dataChart;

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
    .table th, .table td
    {
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        cursor: pointer;
        padding: 0.75rem;
        vertical-align: top;
    }

</style>
