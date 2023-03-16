<template>
    <div class="container-fluid">
        <ActionBar type="index" :breadcrumbs="breadcrumbs" title="Modules" />
        <div class="row">
            <div class="col-lg-12">
                <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="delete" tabindex="-1"
                    role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered popup-main-1" role="document" style="max-width: 450px;">
                        <div class="modal-content box-shadow-main paymment-status"
                            style="left:120px;text-align: center; padding: 20px 0px 55px;">
                            <div class="close-popup" data-dismiss="modal"></div>
                            <div class="swal2-icon swal2-warning swal2-icon-show">
                                <div class="swal2-icon-content" style="margin: 0px 24.5px 0px ">!</div>
                            </div>
                            <div class="swal2-html-container">
                                <p>Are you sure to delete this resource?</p>
                            </div>
                            <div class="swal2-actions">
                                <button type="submit" id="kt_modal_new_target_submit"
                                    class="swal2-confirm btn fw-bold btn-danger" @click="remove(entry)">
                                    <span class="indicator-label">Yes, delete!</span>
                                </button>
                                <button type="reset" id="kt_modal_new_target_cancel"
                                    class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal"
                                    style="margin: 0px 8px 0px">No, cancel
                                </button>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="deleteMutiple"
                    tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered popup-main-1" role="document" style="max-width: 450px;">
                        <div class="modal-content box-shadow-main paymment-status"
                            style="left:120px;text-align: center; padding: 20px 0px 55px;">
                            <div class="close-popup" data-dismiss="modal"></div>
                            <div class="swal2-icon swal2-warning swal2-icon-show">
                                <div class="swal2-icon-content" style="margin: 0px 24.5px 0px ">!</div>
                            </div>
                            <div class="swal2-html-container">
                                <p>Are you sure to delete these modules?</p>
                            </div>
                            <div class="swal2-actions">
                                <button type="submit" id="kt_modal_new_target_submit1"
                                    class="swal2-confirm btn fw-bold btn-danger" @click="deleteMutiple()">
                                    <span class="indicator-label">Yes, delete!</span>
                                </button>
                                <button type="reset" id="kt_modal_new_target_cancel1"
                                    class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal"
                                    style="margin: 0px 8px 0px">No, cancel</button>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                            transform="rotate(45 17.0365 15.1223)" fill="black"></rect>
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="black"></path>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                <input type="text" data-kt-filemanager-table-filter="search"
                                    class="form-control form-control-solid w-250px ps-15" @keydown.enter="doFilter($event)"
                                    v-model="filter.keyword" placeholder="Search..." value="" />
                                <span v-if="filter.keyword !== ''" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0"
                                    @click="filterClear">
                                    <svg type="button" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" style="margin: 3px -25px 0px">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                            transform="rotate(-45 6 17.3137)" fill="black" style="fill: red" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                            transform="rotate(45 7.41422 6)" fill="black" style="fill: red" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base"
                                v-if="inventoryIds == ''">
                                <button type="button" style="margin-left: 10px" @click="isShowFilter = !isShowFilter"
                                    class="btn btn-light" v-if="isShowFilter">
                                    <i style="margin-left: 5px" class="fas fa-times"></i>
                                    Close Advanced Search
                                </button>
                                <button type="button" style="margin-left: 10px" @click="isShowFilter = !isShowFilter"
                                    class="btn btn-light" v-if="!isShowFilter">
                                    <i class="bi bi-funnel"></i>
                                    Advanced Search
                                </button>
                                <a :href="'/xadmin/inventories/create'" v-if="permissions['007']">
                                    <button class="btn btn-primary button-create" style="margin: 0 0 0 15px">
                                        <i class="bi bi-plus-lg"></i>Create new
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end align-items-center d-none"
                            data-kt-customer-table-toolbar="selected" v-if="inventoryIds != ''">
                            <div class="fw-bolder me-5">
                                <span class="me-2" data-kt-customer-table-select="selected_count"></span>{{
                                    inventoryIds.length }} Selected
                            </div>
                            <button v-if="permissions['010']" @click="deleteMultipleConfirm" type="button"
                                class="btn btn-danger" data-kt-customer-table-select="delete_selected">
                                Delete Selected
                            </button>
                        </div>
                        <form class="col-lg-12" v-if="!isShowFilter">
                            <div class="row">
                                <div style="margin:7px 3px 0px">
                                    <button type="button" class="btn btn-primary" @click="doFilter()">Search</button>
                                </div>
                            </div>
                        </form>
                        <form class="col-lg-12" v-if="isShowFilter">
                            <div class="row">
                                <div class="form-group col-lg-3">
                                    <label>Name </label>
                                    <input class="form-control" placeholder="Enter the inventories name"
                                        v-model="filter.name" />
                                </div>
                                <div class="form-group col-lg-3">
                                    <label>Subject </label>
                                    <select required class="form-control form-select" v-model="filter.subject">
                                        <option value="" disabled selected>Choose Subject
                                        </option>
                                        <option value="0">All</option>
                                        <option value="math">Maths</option>
                                        <option value="science ">Science
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3">
                                    <label>Grade </label>
                                    <select required class="form-control form-select" v-model="filter.grade">
                                        <option value="" disabled selected>Choose Grade
                                        </option>
                                        <option value="">All</option>
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
                                    <label>Type </label>
                                    <select required class="form-control form-select" v-model="filter.type">
                                        <option value="" disabled selected>Choose Type
                                        </option>
                                        <option value="">All</option>
                                        <option value="vocabulary">Vocabulary
                                        </option>
                                        <option value="summary">Summary</option>
                                        <option value="lecture">Lecture</option>
                                        <option value="activity1">Activity1
                                        </option>
                                        <option value="activity2">Activity2
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-3">
                                    <label>Creation date </label>
                                    <Daterangepicker v-model="filter.created" class="active" placeholder="Creation date"
                                        readonly></Daterangepicker>
                                    <span v-if="filter.created !== ''" class="svg-icon svg-icon-2 svg-icon-lg-1 me-0"
                                        @click="filterClear">
                                        <svg type="button" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" style="float: right; margin: -32px 3px 0px">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                                transform="rotate(-45 6 17.3137)" fill="black" style="fill: red" />
                                            <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                transform="rotate(45 7.41422 6)" fill="black" style="fill: red" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="form-group col-lg-3">
                                    <label>Active</label>
                                    <div>
                                        <switch-button v-model="filter.enabled"></switch-button>
                                    </div>
                                </div>
                            </div>
                            <div style="margin: auto 0">
                                <button type="button" class="btn btn-primary" @click="doFilter($event)">
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-content">
                        <div class="d-flex flex-stack pt-4 pl-9 pr-9">
                            <div class="badge badge-lg badge-light-dark mb-15">
                                <div class="d-flex align-items-center flex-wrap">
                                    <span class="svg-icon svg-icon-dark mx-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none">
                                            <path
                                                d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z"
                                                fill="black"></path>
                                        </svg>
                                    </span>

                                    <div v-text="
                                        from +
                                        '-' +
                                        to +
                                        ' of ' +
                                        countInventory
                                    " v-if="entries.length > 0"></div>
                                </div>
                            </div>
                        </div>

                        <table class="table table-row-bordered align-middle gy-4 gs-9">
                            <thead class=" border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75 ">
                                <tr>
                                    <td width="25" class="text-center">
                                        <div class=" form-check form-check-sm form-check-custom form-check-solid ">
                                            <input class="form-check-input" type="checkbox" v-model="allSelected"
                                                @change="selectAll()" />
                                        </div>
                                    </td>
                                    <th class="text-center">No.</th>
                                    <th>Name
                                        <button class="btn-sort" @click="onSort('name')">
                                            <i class="fa fa-sort"></i>
                                        </button>
                                    </th>
                                    <th class="">Type</th>
                                    <th class="text-center">Grade</th>
                                    <th class="text-center">Creation Date</th>
                                    <th class="text-center" v-if="permissions['010']">Delete</th>
                                    <th class="text-center" v-if="permissions['008']">Active</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(entry, index) in entries">
                                    <td class="text-center">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" v-model="inventoryIds"
                                                :value="entry.id" @change="updateCheckAll" />
                                        </div>
                                    </td>
                                    <td class="cursor-pointer text-center" @click="edit(entry.id, permissions['008'])" v-text="index + from"></td>
                                    <td class="cursor-pointer" @click="edit(entry.id, permissions['008'])" v-text="entry.name"></td>
                                    <td class="cursor-pointer" @click="edit(entry.id, permissions['008'])" v-text="entry.type"></td>
                                    <td class="cursor-pointer text-center" @click="edit(entry.id, permissions['008'])" v-text="entry.grade"></td>
                                    <td class="cursor-pointer text-center" @click="edit(entry.id, permissions['008'])" v-text="d(entry.created_at)"></td>
                                    <td class="text-center" v-if="permissions['010']">
                                        <i class="bi bi-trash cursor-pointer text-danger font-size-h1" @click="removeResource(entry.id)"></i>
                                    </td>
                                    <td class="text-center" v-if="permissions['008']">
                                        <div class="form-check form-switch form-check-custom form-check-primary justify-content-center">
                                            <input v-model="entry.enabled" @change="toggleEnable(entry)" class="form-check-input" type="checkbox" value="" id="flexSwitchDefault">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex pl-9 pr-9 mb-8">
                            <div class="
                              col-sm-12 col-md-5
                              d-flex
                              align-items-center
                              justify-content-center justify-content-md-start
                            ">
                                <!--<div class="mr-2">
                                    <label>Records per page:</label>
                                </div>-->
                                <div>
                                    <select class="form-select form-select-sm form-select-solid" v-model="limit"
                                        @change="changeLimit">
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                            <!--<div style="float: right; margin: 10px">-->
                            <div class="
                              col-sm-12 col-md-7
                              d-flex
                              align-items-center
                              justify-content-center justify-content-md-end
                            ">
                                <div class="dataTables_paginate paging_simple_numbers" id="kt_customers_table_paginate">
                                    <Paginate :value="paginate" :pagechange="onPageChange"></Paginate>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { $get, $post, getTimeRangeAll, clone } from "../../utils";
import $router from "../../lib/SimpleRouter";
import ActionBar from "../includes/ActionBar";
import SwitchButton from "../../components/SwitchButton";

let created = getTimeRangeAll();
const $q = $router.getQuery();

export default {
    name: "InventoriesIndex.vue",
    components: { ActionBar, SwitchButton },
    data() {
        const permissions = clone(window.$permissions);
        let isShowFilter = false;
        let filter = {
            keyword: $q.keyword || "",
            created: $q.created || "",
            subject: $q.subject || "",
            type: $q.type || "",
            grade: $q.grade || "",
            enabled: $q.enabled || ""
        };
        for (var key in filter) {
            if (filter[key] != "") {
                isShowFilter = true;
            }
        }
        return {
            countInventory: '',
            entry: '',
            inventory: [],
            inventoryIds: [],
            allSelected: false,
            permissions,
            last_updated: [],
            isShowFilter: isShowFilter,
            breadcrumbs: [
                {
                    title: "Resource management"
                },
                {
                    title: "Modules"
                }
            ],
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
        };
    },
    mounted() {
        $router.on("/", this.load).init();
    },
    methods: {
        removeResource: function (deleteResource = '') {
            $('#delete').modal('show');
            this.entry = deleteResource;

        },
        edit: function (id, permission) {
            if (permission) {
                window.location.href = "/xadmin/inventories/edit?id=" + id;
            }
        },
        onSort(key) {
            let query = $router.getQuery();
            if (query?.sortBy == "desc") {
                $router.updateQuery({ order: key, page: 1, sortBy: "asc" });
            } else {
                $router.updateQuery({ order: key, page: 1, sortBy: "desc" });
            }
        },
        async load() {
            let query = $router.getQuery();
            this.$loading(true);
            const res = await $get("/xadmin/inventories/data", query);
            this.$loading(false);
            setTimeout(function () {
                KTMenu.createInstances();
            }, 0);
            this.paginate = res.paginate;
            this.entries = res.data;
            this.countInventory = res.countInventory;
            this.last_updated = res.last_updated;
            this.from = (this.paginate.currentPage - 1) * this.limit + 1;
            this.to =
                (this.paginate.currentPage - 1) * this.limit +
                this.entries.length;
        },
        async remove() {
            $('#delete').modal('hide');
            const res = await $post("/xadmin/inventories/remove", {
                id: this.entry
            });


            if (res.code) {
                toastr.error(res.message);
            } else {
                toastr.success(res.message);
            }

            $router.updateQuery({
                page: this.paginate.currentPage,
                _: Date.now()
            });
        },
        filterClear() {
            for (var key in this.filter) {
                this.filter[key] = "";
            }

            $router.setQuery({});
        },
        doFilter(event) {
            if (event) {
                event.preventDefault();
            }
            $router.setQuery(this.filter);
        },
        changeLimit() {
            let params = $router.getQuery();
            params["page"] = 1;
            params["limit"] = this.limit;
            $router.setQuery(params);
        },

        async toggleStatus(entry) {
            const res = await $post("/xadmin/inventories/toggleStatus", {
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
            $router.updateQuery({ page: page });
        },
        selectAll() {
            if (this.allSelected) {
                const selected = this.entries.map(u => u.id);
                this.inventoryIds = selected;
                this.inventory = this.entries;
            } else {
                this.inventoryIds = [];
                this.inventory = [];
            }
        },
        updateCheckAll() {
            this.inventory = [];
            if (this.inventoryIds.length === this.entries.length) {
                this.allSelected = true;
            } else {
                this.allSelected = false;
            }
            let self = this;
            self.inventoryIds.forEach(function (e) {
                self.entries.forEach(function (e1) {
                    if (e1.id == e) {
                        self.inventory.push(e1);
                    }
                });
            });
        },
        async deleteMutiple() {
            const res = await $post("/xadmin/inventories/removeAll", {
                ids: this.inventoryIds
            });

            if (res.code) {
                toastr.error(res.message);
            } else {
                toastr.success(res.message);
                this.inventoryIds = [];
                this.inventory = [];
                $('#deleteMutiple').modal('hide');

            }

            $router.updateQuery({
                page: this.paginate.currentPage,
                _: Date.now()
            });
        },
        deleteMultipleConfirm() {
            $('#deleteMutiple').modal('show');
        },
        async toggleEnable(entry) {
                const res = await $post('/xadmin/inventories/toggleStatus', {
                    id: entry.id,
                    active: entry.active
                });

                if (res.code === 200) {
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message);
                }

            },
    }
};
</script>

<style scoped>
.form-check.form-check-primary .form-check-input:checked{
    background-color: #2196f3 !important;
    border-color: #2196f3!important;
}

.btn-sort {
    border: none;
}

select:required:invalid {
    color: #adadad;
}

option[value=""][disabled] {
    display: none;
}

option {
    color: black;
}</style>
