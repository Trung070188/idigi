<template>
    <div class="container-fluid">
        <ActionBar
            type="index"
            :breadcrumbs="breadcrumbs"
            title="Inventory Manager - Inventories"
        />
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div
                                class="d-flex align-items-center position-relative my-1"
                            >
                                <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
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
                                <!--end::Svg Icon-->
                                <input
                                    type="text"
                                    data-kt-filemanager-table-filter="search"
                                    class="form-control form-control-solid w-250px ps-15"
                                    @keydown.enter="doFilter($event)"
                                    v-model="filter.keyword"
                                    placeholder="Search..."
                                    value=""
                                />
                                <span
                                    v-if="filter.keyword !== ''"
                                    class="svg-icon svg-icon-2 svg-icon-lg-1 me-0"
                                    @click="filterClear"
                                >
                                    <svg
                                        type="button"
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        style="margin: 3px -25px 0px;"
                                    >
                                        <rect
                                            opacity="0.5"
                                            x="6"
                                            y="17.3137"
                                            width="16"
                                            height="2"
                                            rx="1"
                                            transform="rotate(-45 6 17.3137)"
                                            fill="black"
                                            style="fill:red"
                                        />
                                        <rect
                                            x="7.41422"
                                            y="6"
                                            width="16"
                                            height="2"
                                            rx="1"
                                            transform="rotate(45 7.41422 6)"
                                            fill="black"
                                            style="fill:red"
                                        />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div
                                class="d-flex justify-content-end"
                                data-kt-customer-table-toolbar="base"
                                v-if="inventoryIds == ''"
                            >
                                <button
                                    type="button"
                                    style="margin-left: 10px"
                                    @click="isShowFilter = !isShowFilter"
                                    class="btn btn-primary"
                                    v-if="isShowFilter"
                                >
                                    Close Advanced Search
                                    <i
                                        style="margin-left: 5px"
                                        class="fas fa-times"
                                    ></i>
                                </button>
                                <button
                                    type="button"
                                    style="margin-left: 10px"
                                    @click="isShowFilter = !isShowFilter"
                                    class="btn btn-primary"
                                    v-if="!isShowFilter"
                                >
                                    Advanced Search
                                    <i
                                        class="fa fa-filter"
                                        v-if="!isShowFilter"
                                        aria-hidden="true"
                                    ></i>
                                </button>
                                <a
                                    :href="'/xadmin/inventories/create'"
                                    v-if="permissions['007']"
                                >
                                    <button
                                        class="btn btn-primary button-create"
                                        style="margin:0 0 0 15px"
                                    >
                                        Create New
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div
                            class="d-flex justify-content-end align-items-center d-none"
                            data-kt-customer-table-toolbar="selected"
                            v-if="inventoryIds != ''"
                        >
                            <div class="fw-bolder me-5">
                                <span
                                    class="me-2"
                                    data-kt-customer-table-select="selected_count"
                                ></span
                                >{{ inventoryIds.length }} Selected
                            </div>
                            <button
                                @click="removeAll"
                                type="button"
                                class="btn btn-danger"
                                data-kt-customer-table-select="delete_selected"
                            >
                                Delete Selected
                            </button>
                        </div>

                        <form class="col-lg-12" v-if="isShowFilter">
                            <div class="row">
                                <div class="form-group col-lg-3">
                                    <label>Name </label>
                                    <input
                                        class="form-control"
                                        placeholder="Enter the inventories name"
                                        v-model="filter.name"
                                    />
                                </div>
                                <div class="form-group col-lg-3">
                                    <label>Subject </label>
                                    <select
                                        required
                                        class="form-control form-select"
                                        v-model="filter.subject"
                                    >
                                        <option value="" disabled selected
                                            >Choose Subject</option
                                        >
                                        <option value="0">-</option>
                                        <option value="math">Maths</option>
                                        <option value="science "
                                            >Science</option
                                        >
                                    </select>
                                </div>
                                <div class="form-group col-lg-3">
                                    <label>Grade </label>
                                    <select
                                        required
                                        class="form-control form-select"
                                        v-model="filter.grade"
                                    >
                                        <option value="" disabled selected
                                            >Choose Grade</option
                                        >
                                        <option value="">-</option>
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
                                    <select
                                        required
                                        class="form-control form-select"
                                        v-model="filter.type"
                                    >
                                        <option value="" disabled selected
                                            >Choose Type</option
                                        >
                                        <option value="">-</option>
                                        <option value="vocabulary"
                                            >Vocabulary</option
                                        >
                                        <option value="summary">Summary</option>
                                        <option value="lecture">Lecture</option>
                                        <option value="activity1"
                                            >Activity1</option
                                        >
                                        <option value="activity2"
                                            >Activity2</option
                                        >
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-3">
                                    <label>Creation date </label>
                                    <Daterangepicker
                                        v-model="filter.created"
                                        class="active"
                                        placeholder="Creation date"
                                        readonly
                                    ></Daterangepicker>
                                    <span
                                        v-if="filter.created !== ''"
                                        class="svg-icon svg-icon-2 svg-icon-lg-1 me-0"
                                        @click="filterClear"
                                    >
                                        <svg
                                            type="button"
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            style="float: right;margin: -32px 3px 0px;"
                                        >
                                            <rect
                                                opacity="0.5"
                                                x="6"
                                                y="17.3137"
                                                width="16"
                                                height="2"
                                                rx="1"
                                                transform="rotate(-45 6 17.3137)"
                                                fill="black"
                                                style="fill:red"
                                            />
                                            <rect
                                                x="7.41422"
                                                y="6"
                                                width="16"
                                                height="2"
                                                rx="1"
                                                transform="rotate(45 7.41422 6)"
                                                fill="black"
                                                style="fill:red"
                                            />
                                        </svg>
                                    </span>
                                </div>
                                <div class="form-group col-lg-3">
                                    <label>Active</label>
                                    <div>
                                        <switch-button
                                            v-model="filter.enabled"
                                        ></switch-button>
                                    </div>
                                </div>
                            </div>
                            <div style="margin: auto 0">
                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    @click="doFilter($event)"
                                >
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-content">
                        <div class="d-flex flex-stack pt-4 pl-9 pr-9">
                            <div
                                class="badge badge-lg badge-light-primary mb-15"
                            >
                                <div
                                    class="d-flex align-items-center flex-wrap"
                                >
                                    <span
                                        class="svg-icon svg-icon-2x svg-icon-primary mx-1"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                        >
                                            <path
                                                d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z"
                                                fill="black"
                                            ></path>
                                        </svg>
                                    </span>

                                    <div
                                        v-text="
                                            'Showing ' +
                                                from +
                                                ' to ' +
                                                to +
                                                ' of ' +
                                                paginate.totalRecord +
                                                ' entries'
                                        "
                                        v-if="entries.length > 0"
                                    ></div>
                                </div>
                            </div>
                        </div>

                        <table
                            class="table table-row-bordered align-middle gy-4 gs-9"
                        >
                            <thead
                                class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75"
                            >
                                <tr>
                                    <td width="25">
                                        <div
                                            class="form-check form-check-sm form-check-custom form-check-solid"
                                        >
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                v-model="allSelected"
                                                @change="selectAll()"
                                            />
                                        </div>
                                    </td>
                                    <th class="">ID</th>
                                    <th>
                                        Name<button
                                            class="btn-sort"
                                            @click="onSort('name')"
                                        >
                                            <i class="fa fa-sort"></i>
                                        </button>
                                    </th>
                                    <th class="">Grade</th>
                                    <th class="">Type</th>
                                    <th class="">Active</th>
                                    <th class="">Creation Date</th>
                                    <th class="">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="entry in entries">
                                    <td class="">
                                        <div
                                            class="form-check form-check-sm form-check-custom form-check-solid"
                                        >
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                v-model="inventoryIds"
                                                :value="entry.id"
                                                @change="updateCheckAll"
                                            />
                                        </div>
                                    </td>
                                    <td class="" v-text="entry.id"></td>
                                    <td v-text="entry.name"></td>
                                    <td class="" v-text="entry.grade"></td>
                                    <td class="" v-text="entry.type"></td>
                                    <td
                                        class=""
                                        v-text="
                                            entry.enabled == 0 ? 'No' : 'Yes'
                                        "
                                    ></td>
                                    <td
                                        class=""
                                        v-text="d(entry.created_at)"
                                    ></td>

                                    <td class="">
                                        <!--<a :href="'/xadmin/inventories/edit?id='+entry.id" style="margin-right: 10px"><i style="font-size:1.3rem" class="fa fa-edit" v-if="permissions['008']"></i></a>
                                    <a v-if="permissions['010']" @click="remove(entry)" href="javascript:;" class="btn-trash deleted"><i
                                        class="fa fa-trash mr-1 deleted"></i></a>-->

                                        <a
                                            :href="
                                                '/xadmin/inventories/edit?id=' +
                                                    entry.id
                                            "
                                            class="btn-action"
                                            v-if="permissions['008']"
                                        >
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-icon btn-light btn-active-light-primary"
                                            >
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </a>
                                        <a
                                            @click="remove(entry)"
                                            href="javascript:;"
                                            v-if="permissions['010']"
                                        >
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-icon btn-light btn-active-light-primary"
                                            >
                                                <i
                                                    class="fa fa-trash mr-1 deleted"
                                                ></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex pl-9 pr-9 mb-8">
                            <div
                                class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"
                            >
                                <!--<div class="mr-2">
                                    <label>Records per page:</label>
                                </div>-->
                                <div>
                                    <select
                                        class="form-select form-select-sm form-select-solid"
                                        v-model="limit"
                                        @change="changeLimit"
                                    >
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                            <!--<div style="float: right; margin: 10px">-->
                            <div
                                class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end"
                            >
                                <div
                                    class="dataTables_paginate paging_simple_numbers"
                                    id="kt_customers_table_paginate"
                                >
                                    <Paginate
                                        :value="paginate"
                                        :pagechange="onPageChange"
                                    ></Paginate>
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
            inventory: [],
            inventoryIds: [],
            allSelected: false,
            permissions,
            last_updated: [],
            isShowFilter: isShowFilter,
            breadcrumbs: [
                {
                    title: "Inventories"
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
        edit: function(id, event) {
            if (!$(event.target).hasClass("deleted")) {
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
            this.paginate = res.paginate;
            this.entries = res.data;
            this.last_updated = res.last_updated;
            this.from = (this.paginate.currentPage - 1) * this.limit + 1;
            this.to =
                (this.paginate.currentPage - 1) * this.limit +
                this.entries.length;
        },
        async remove(entry) {
            if (!confirm("Xóa bản ghi: " + entry.id)) {
                return false;
            }

            const res = await $post("/xadmin/inventories/remove", {
                id: entry.id
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
            self.inventoryIds.forEach(function(e) {
                self.entries.forEach(function(e1) {
                    if (e1.id == e) {
                        self.inventory.push(e1);
                    }
                });
            });
        },
         async removeAll()
            {
                if (!confirm('Xóa bản ghi: ' + JSON.stringify(this.inventoryIds))) {
                    return;
                }

                const res = await $post('/xadmin/inventorys/removeAll', {ids: this.inventoryIds});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }

                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});

            }
    }
};
</script>

<style scoped>
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
}
</style>
