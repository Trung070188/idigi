<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-5">
                        <div class="title">
                            <label>Lesson</label>
                        </div>
                        <!-- <a :href="'/xadmin/schools/create'" class="btn btn-primary button-create " >
                     Create new
                 </a> -->
                        <button class="btn btn-primary button-create " @click="openModal()"> Download Lesson</button>
                    </div>
                    <hr>
                    <div class="card-header border-0 pt-5">

                        <div class="row width-full">
                            <div class="col-lg-12">
                                <form class="form-inline">
                                    <div class="form-group mx-sm-3 mb-4">
                                        <input @keydown.enter="doFilter($event)" v-model="filter.keyword"
                                               type="text"
                                               class="form-control" placeholder="Search..." value="">
                                    </div>
                                    <div class="form-group mx-sm-3 mb-4">
                                        <button type="button" style="margin-left: 10px"
                                                @click="isShowFilter = !isShowFilter"
                                                class="btn btn-primary" v-if="isShowFilter"> Close Adventure search
                                            <i style="margin-left: 5px" class="fas fa-times"></i>

                                        </button>
                                        <button type="button" style="margin-left: 10px"
                                                @click="isShowFilter = !isShowFilter"
                                                class="btn btn-primary" v-if="!isShowFilter"> Adventure search
                                            <i class="fa fa-filter" v-if="!isShowFilter" aria-hidden="true"></i>

                                        </button>

                                    </div>
                                    <!--                                    <div class="form-group mx-sm-3 mb-2">-->
                                    <!--                                        <button @click="filterClear()" type="button"-->
                                    <!--                                                class="btn btn-flex btn-light  fw-bolder ">Clear-->
                                    <!--                                        </button>-->
                                    <!--                                    </div>-->

                                </form>
                                <form class="col-lg-12" v-if="isShowFilter">
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <label>Name </label>
                                            <input class="form-control" placeholder="Enter the lesson name"
                                                   v-model="filter.subject"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Subject </label>
                                            <select required class="form-control form-select" v-model="filter.subject">
                                                <option value="" disabled selected>Choose Subject</option>
                                                <option value="0">All</option>
                                                <option value="Math">Math</option>
                                                <option value="Science ">Science</option>
                                            </select>

                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Grade </label>
                                            <select required class="form-control form-select" v-model="filter.grade">
                                                <option value="" disabled selected>Choose Grade</option>
                                                <option value="0">All</option>
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
                                            <label>Creation time </label>
                                            <Daterangepicker v-model="filter.created"
                                                             placeholder="Creation time"></Daterangepicker>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Active</label>
                                            <div>
                                                <switch-button v-model="filter.enabled"></switch-button>
                                            </div>

                                        </div>
                                    </div>
                                    <div style="margin: auto 0">
                                        <button type="button" class="btn btn-primary" @click="doFilter($event)">Search
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <div class="d-flex">
                            <div v-text="'Showing '+ from +' to '+ to +' of '+ paginate.totalRecord +' entries'"
                                 v-if="entries.length > 0"></div>
                            <div style="margin-left: 20px" v-if="lessonIds.length > 0"> {{ lessonIds.length }} lesson
                                selected
                                <a href="javascript:;" @click="removeAll" style="color: red; margin-left: 10px">clear all</a>
                            </div>
                        </div>
                        <table class=" table  table-head-custom table-head-bg table-vertical-center">
                            <thead>
                            <tr>
                                <th><input type="checkbox" v-model="allSelected" @change="selectAll()"/> ID</th>
                                <th>Name</th>
                                <th>Grade</th>
                                <th>Subject</th>
                                <th>Active</th>
                                <th>Creation Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="entry in entries">
                                <td><input type="checkbox" class="deleted" v-model="lessonIds" :value="entry.id"
                                           @change="updateCheckAll"/> {{ entry.id }}
                                </td>
                                <td v-text="entry.name"></td>
                                <td v-text="entry.grade"></td>
                                <td v-text="entry.subject"></td>
                                <td v-text="entry.enabled == 0 ? 'No' : 'Yes'"></td>
                                <td v-text=" d(entry.created_at)"></td>

                                <td class="">
                                    <!--                                    <a :href="'/xadmin/lessons/edit?id='+entry.id" style="margin-right: 10px"><i style="font-size:1.3rem" class="fa fa-edit"></i></a>-->
                                    <a @click="openModalEntry(entry)" href="javascript:;" class=" btn-action"><i
                                        class="fa fa-download"></i></a>
                                    <a @click="remove(entry)" href="javascript:;" class="btn-trash btn-action "><i
                                        class="fa fa-trash "></i></a>

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
        <div class="modal" id="download-lesson" tabindex="-1">
            <div id="overlay">
                <div class="la-3x text">
                    <i class="la la-spinner la-spin"></i>
                </div>
            </div>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Download Lesson</h5>
                        <button type="button" class="close" data-bs-dismiss="modal">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn đang lựa chọn để tải về các bài học:</p>
                        <ul>
                            <li v-for="lesson in lessons"><strong style="word-break: break-word;">{{ lesson.name
                                }}</strong></li>

                        </ul>
                        <p>Hãy chọn thiết bị để tải về các bài học này:</p>
                        <ul class="device">
                            <li v-for="_device in devices"><input type="radio" v-model="device" :value="_device.id"/>
                                <strong>{{ _device.device_name }}</strong></li>
                        </ul>
                    </div>
                    <div class="modal-footer" style="justify-content: center">
                        <button type="button" class="btn btn-primary"
                                :disabled="lessons.length == 0 || !device || isConfirm == 0"
                                @click="downloadLesson">Confirm
                        </button>
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
        name: "LessonsIndex.vue",
        components: {ActionBar},
        data() {

            let isShowFilter = false;
            let filter = {
                keyword: $q.keyword || '',
                created: $q.created || '',
                subject: $q.subject || '',
                grade: $q.grade || '',
                enabled: $q.enabled || ''
            };
            for (var key in filter) {
                if (filter[key] != '') {
                    isShowFilter = true;
                }
            }
            return {
                device: '',
                devices: [],
                lessonIds: [],
                lessons: [],
                allSelected: false,
                breadcrumbs: [
                    {
                        title: 'Lessons'
                    },
                ],
                entries: [],
                filter: filter,
                isShowFilter: isShowFilter,
                limit: $q.limit || 25,
                from: 0,
                to: 0,
                paginate: {
                    currentPage: 1,
                    lastPage: 1,
                    totalRecord: 0
                },
                isConfirm: 0,
            }
        },
        mounted() {
            $router.on('/', this.load).init();
            let self = this;
            $.get('/xadmin/user_devices/getDeviceByUser', function (res) {
                self.devices = res;
            });
        },
        methods: {
            openModalEntry(entry) {
                this.isConfirm = 1;
                this.lessons = [entry];
                this.lessonIds = [entry.id];
                $('#download-lesson').modal('show');
            },

            async downloadLesson() {
                this.isConfirm = 0;
                $('#overlay').show();
                const res = await $post('/xadmin/lessons/downloadLesson', {
                    csrf: window.$csrf,
                    lessonIds: this.lessonIds,
                    device: this.device
                });

                window.location.href = res.url;
                $('#overlay').hide();
                $('#download-lesson').modal('hide');

            },

            openModal: function () {
                this.isConfirm = 1;
                if (this.lessons.length > 3) {
                    alert('Bạn chỉ được chọn tối đa 3 lesson');
                    return false;
                }
                $('#download-lesson').modal('show');
            },
            selectAll() {
                if (this.allSelected) {
                    const selected = this.entries.map((u) => u.id);
                    this.lessonIds = selected;
                    this.lessons = this.entries
                } else {
                    this.lessonIds = [];
                    this.lessons = [];
                }

            },
            updateCheckAll() {
                this.lessons = [];
                if (this.lessonIds.length === this.entries.length) {
                    this.allSelected = true;
                } else {
                    this.allSelected = false;
                }
                let self = this;
                self.lessonIds.forEach(function (e) {
                    self.entries.forEach(function (e1) {
                        if (e1.id == e) {
                            self.lessons.push(e1);
                        }
                    })
                })
            },
            edit: function (id, event) {
                if (!$(event.target).hasClass('deleted')) {
                    window.location.href = '/xadmin/lessons/edit?id=' + id;
                }

            },
            async load() {
                let query = $router.getQuery();
                this.$loading(true);
                const res = await $get('/xadmin/lessons/data', query);
                this.$loading(false);
                this.paginate = res.paginate;
                this.entries = res.data;
                this.from = (this.paginate.currentPage - 1) * (this.limit) + 1;
                this.to = (this.paginate.currentPage - 1) * (this.limit) + this.entries.length;
            },
            async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return;
                }

                const res = await $post('/xadmin/lessons/remove', {id: entry.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }

                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});
            },

            async removeAll() {
                if (!confirm('Xóa bản ghi: ' + JSON.stringify(this.lessonIds))) {
                    return;
                }

                const res = await $post('/xadmin/lessons/removeAll', {ids: this.lessonIds});

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
                const res = await $post('/xadmin/lessons/toggleStatus', {
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
    ul.device {
        list-style-type: none;
    }

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
