<template>
    <div class="container-fluid">

        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title = "Lesson Manager - Lessons"/>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="card-header border-0 pt-5">

                        <div class="row form-filter width-full">
                            <div class="col-lg-12">
                                <form class="form-inline">
                                    <div class="form-group mx-sm-3 mb-4">
                                        <input @keydown.enter="doFilter($event)" v-model="filter.keyword"
                                               type="text"
                                               class="form-control" placeholder="Search..." value="">
                                        <span v-if="filter.keyword!==''" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0" @click="filterClear">
                                            <svg type="button" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin: 3px -25px 0px;">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                            </svg>
                                        </span>
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

                                        <button class="btn btn-primary button-create" style="margin: 0 0 0 15px" @click="openModal()" v-if="permissions['011']"> Download Lesson</button>

                                    </div>
                                    <!--                                    <div class="form-group mx-sm-3 mb-2">-->
                                    <!--                                        <button @click="filterClear()" type="button"-->
                                    <!--                                                class="btn btn-flex btn-light  fw-bolder ">Clear-->
                                    <!--                                        </button>-->
                                    <!--                                    </div>-->

                                </form>
                                <form class="col-lg-12" v-if="isShowFilter">
                                    <div class="row">
                                        <div class="form-group col-lg-3">
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
                                        <div class="form-group col-lg-3">
                                            <label>Creation time </label>
                                            <Daterangepicker v-model="filter.created" class="active"
                                                             placeholder="Creation date" readonly></Daterangepicker>
                                            <span v-if="filter.created!==''" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0" @click="filterClear">
                                            <svg type="button" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" style="float: right;margin: -32px 3px 0px;">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                            </svg>
                                            </span>
                                        </div>              
                                        <div class="form-group col-lg-2">
                                            <label>Active</label>
                                            <div>
                                                <switch-button v-model="filter.enabled"></switch-button>
                                            </div>

                                        </div>                                                                  
                                    </div>
                                    <div style="margin: auto 0">
                                        <button type="button" class="btn btn-primary" @click="doFilter($event)">Search</button>
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
                                <a   href="javascript:;" @click="removeAll" style="color: red; margin-left: 10px">clear all</a>
                            </div>
                        </div>
                        <table class=" table  table-head-custom table-head-bg table-vertical-center">
                            <thead>
                            <tr>
                                <td width = "25">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" v-model="allSelected" @change="selectAll()">
                                    </div>
                                </td>
                                <th class="text-center">ID</th>
                                <th>Name</th>
                                <th class="text-center">Grade</th>
                                <th class="text-center">Subject</th>
                                <th class="text-center">Active</th>
                                <th class="text-center">Creation Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="entry in entries">
                                <td class="text-center">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" v-model="lessonIds" :value="entry.id" @change="updateCheckAll">
                                    </div>
                                </td>
                                <td class="text-center" v-text="entry.id"></td>
                                <td v-text="entry.name"></td>
                                <td class="text-center" v-text="entry.grade"></td>
                                <td class="text-center" v-text="entry.subject"></td>
                                <td class="text-center" v-text="entry.enabled == 0 ? 'No' : 'Yes'"></td>
                                <td class="text-center" v-text=" d(entry.created_at)"></td>

                                <td class="text-center">
                                    <!--                                    <a :href="'/xadmin/lessons/edit?id='+entry.id" style="margin-right: 10px"><i style="font-size:1.3rem" class="fa fa-edit"></i></a>-->
                                    <a @click="openModalEntry(entry)" href="javascript:;" class=" btn-action" >
                                        <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <!--begin::Svg Icon | path: icons/duotune/coding/cod007.svg-->
                                            <span class="svg-icon svg-icon-5 m-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3" d="M18.4 5.59998C18.7766 5.9772 18.9881 6.48846 18.9881 7.02148C18.9881 7.55451 18.7766 8.06577 18.4 8.44299L14.843 12C14.466 12.377 13.9547 12.5887 13.4215 12.5887C12.8883 12.5887 12.377 12.377 12 12C11.623 11.623 11.4112 11.1117 11.4112 10.5785C11.4112 10.0453 11.623 9.53399 12 9.15698L15.553 5.604C15.9302 5.22741 16.4415 5.01587 16.9745 5.01587C17.5075 5.01587 18.0188 5.22741 18.396 5.604L18.4 5.59998ZM20.528 3.47205C20.0614 3.00535 19.5074 2.63503 18.8977 2.38245C18.288 2.12987 17.6344 1.99988 16.9745 1.99988C16.3145 1.99988 15.661 2.12987 15.0513 2.38245C14.4416 2.63503 13.8876 3.00535 13.421 3.47205L9.86801 7.02502C9.40136 7.49168 9.03118 8.04568 8.77863 8.6554C8.52608 9.26511 8.39609 9.91855 8.39609 10.5785C8.39609 11.2384 8.52608 11.8919 8.77863 12.5016C9.03118 13.1113 9.40136 13.6653 9.86801 14.132C10.3347 14.5986 10.8886 14.9688 11.4984 15.2213C12.1081 15.4739 12.7616 15.6039 13.4215 15.6039C14.0815 15.6039 14.7349 15.4739 15.3446 15.2213C15.9543 14.9688 16.5084 14.5986 16.975 14.132L20.528 10.579C20.9947 10.1124 21.3649 9.55844 21.6175 8.94873C21.8701 8.33902 22.0001 7.68547 22.0001 7.02551C22.0001 6.36555 21.8701 5.71201 21.6175 5.10229C21.3649 4.49258 20.9947 3.93867 20.528 3.47205Z" fill="black"></path>
                                                    <path d="M14.132 9.86804C13.6421 9.37931 13.0561 8.99749 12.411 8.74695L12 9.15698C11.6234 9.53421 11.4119 10.0455 11.4119 10.5785C11.4119 11.1115 11.6234 11.6228 12 12C12.3766 12.3772 12.5881 12.8885 12.5881 13.4215C12.5881 13.9545 12.3766 14.4658 12 14.843L8.44699 18.396C8.06999 18.773 7.55868 18.9849 7.02551 18.9849C6.49235 18.9849 5.98101 18.773 5.604 18.396C5.227 18.019 5.0152 17.5077 5.0152 16.9745C5.0152 16.4413 5.227 15.93 5.604 15.553L8.74701 12.411C8.28705 11.233 8.28705 9.92498 8.74701 8.74695C8.10159 8.99737 7.5152 9.37919 7.02499 9.86804L3.47198 13.421C2.52954 14.3635 2.00009 15.6417 2.00009 16.9745C2.00009 18.3073 2.52957 19.5855 3.47202 20.528C4.41446 21.4704 5.69269 21.9999 7.02551 21.9999C8.35833 21.9999 9.63656 21.4704 10.579 20.528L14.132 16.975C14.5987 16.5084 14.9689 15.9544 15.2215 15.3447C15.4741 14.735 15.6041 14.0815 15.6041 13.4215C15.6041 12.7615 15.4741 12.108 15.2215 11.4983C14.9689 10.8886 14.5987 10.3347 14.132 9.86804Z" fill="black"></path>
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </button>
                                    </a>
                                    <a v-if="permissions['012']" @click="remove(entry)" href="javascript:;" class="btn-trash btn-action">
                                        <i class="fa fa-trash"></i></a>

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
                            <div style="float: right; margin: 10px">
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
                                @click="downloadLesson" :disabled="lessons.length == 0 || !device || isConfirm == 0"> Download for Windows  <i class="bi bi-windows"></i>
                        </button>
                        <button type="button" class="btn btn-primary"  @click="downloadLesson" :disabled="lessons.length == 0 || !device || isConfirm == 0"
                              >
                            Download for MacOS <i style="margin:-3px 0px 0px" class="bi bi-apple"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import {$get, $post, getTimeRangeAll, clone} from "../../utils";
    import $router from '../../lib/SimpleRouter';
    import ActionBar from "../includes/ActionBar";

    let created = getTimeRangeAll();
    const $q = $router.getQuery();

    export default {
        name: "LessonsIndex.vue",
        components: {ActionBar},
        data() {
            const permissions = clone(window.$permissions)

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
                permissions,
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
                $('input:checkbox').each(function() { this.checked = false; });
                // if (!confirm('Xóa bản ghi: ' + JSON.stringify(this.lessonIds))) {
                //     return;
                // }
                //
                // const res = await $post('/xadmin/lessons/removeAll', {ids: this.lessonIds});
                //
                // if (res.code) {
                //     toastr.error(res.message);
                // } else {
                //     toastr.success(res.message);
                // }
                //
                // $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});
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
