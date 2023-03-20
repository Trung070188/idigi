<template>
    <div class="container-fluid">
        <ActionBar type="index" :breadcrumbs="breadcrumbs" title="My devices" />

        <div class="modal fade" style="margin-right:50px " id="sentConfirm" tabindex="-1" role="dialog"
            aria-labelledby="sentConfirm" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document" style="max-width: 500px;">
                <div class="modal-content box-shadow-main paymment-status" style="margin-right:20px; left:140px">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 class="popup-title success" style="margin-top: 20px">Delete Device</h3>
                    <div class="content" style="text-align: center;margin: 0px">
                        <p>Requesting the removal of a device requires admin approval.</p>
                        <p> Do you want to send a request to admin?</p>
                    </div>
                    <div class="form-group d-flex justify-content-between">
                        <button class="btn btn-light ito-btn-add" data-dismiss="modal" @click="Cancel()"
                            style="margin-left: 113px">Cancel</button>

                        <button class="btn btn-primary" data-dismiss="modal" style="margin-right: 127px" @click="save">
                            Send request
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="delete" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document" style="max-width: 450px;">
                <div class="modal-content box-shadow-main paymment-status"
                    style="left:120px;text-align: center; padding: 20px 0px 55px;">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <div class="swal2-icon swal2-warning swal2-icon-show">
                        <div class="swal2-icon-content" style="margin: 0px 24.5px 0px ">!</div>
                    </div>
                    <div class="swal2-html-container">
                        <p>Are you sure to delete this device?</p>
                    </div>
                    <div class="swal2-actions">
                        <button type="submit" id="kt_modal_new_target_submit" class="swal2-confirm btn fw-bold btn-danger"
                            @click="remove(idDevice)">
                            <span class="indicator-label">Yes, delete!</span>
                        </button>
                        <button type="reset" id="kt_modal_new_target_cancel"
                            class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal"
                            style="margin: 0px 8px 0px">No, cancel</button>

                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" style="margin-right:50px " id="deviceConfirm" tabindex="-1" role="dialog"
            aria-labelledby="deviceConfirm" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document" style="max-width: 500px;">
                <div class="modal-content box-shadow-main paymment-status" style="/*margin-right:20px; left:140px*/">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 style="text-align: center;" class="pt-7 fs-1 fw-bolder">Register New Device</h3>
                    <div class="px-10 py-5 text-left">
                        <div class="d-flex align-items-start justify-content-start mb-5">
                            <span class="svg svg-icon mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-1-circle" viewBox="0 0 16 16">
                                    <path
                                        d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8Zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0ZM9.283 4.002V12H7.971V5.338h-.065L6.072 6.656V5.385l1.899-1.383h1.312Z" />
                                </svg>
                            </span>
                            <span>Open iDIGI application on your device.</span>
                        </div>
                        <div class="d-flex align-items-start justify-content-start mb-5">
                            <span class="svg svg-icon mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-2-circle" viewBox="0 0 16 16">
                                    <path
                                        d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8Zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0ZM6.646 6.24v.07H5.375v-.064c0-1.213.879-2.402 2.637-2.402 1.582 0 2.613.949 2.613 2.215 0 1.002-.6 1.667-1.287 2.43l-.096.107-1.974 2.22v.077h3.498V12H5.422v-.832l2.97-3.293c.434-.475.903-1.008.903-1.705 0-.744-.557-1.236-1.313-1.236-.843 0-1.336.615-1.336 1.306Z" />
                                </svg>
                            </span>
                            <span>Click on button "Get device information" and copy "Register Code".</span>
                        </div>
                        <div class="d-flex align-items-start justify-content-start mb-7">
                            <span class="svg svg-icon mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-3-circle" viewBox="0 0 16 16">
                                    <path
                                        d="M7.918 8.414h-.879V7.342h.838c.78 0 1.348-.522 1.342-1.237 0-.709-.563-1.195-1.348-1.195-.79 0-1.312.498-1.348 1.055H5.275c.036-1.137.95-2.115 2.625-2.121 1.594-.012 2.608.885 2.637 2.062.023 1.137-.885 1.776-1.482 1.875v.07c.703.07 1.71.64 1.734 1.917.024 1.459-1.277 2.396-2.93 2.396-1.705 0-2.707-.967-2.754-2.144H6.33c.059.597.68 1.06 1.541 1.066.973.006 1.6-.563 1.588-1.354-.006-.779-.621-1.318-1.541-1.318Z" />
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0ZM1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8Z" />
                                </svg>
                            </span>
                            <span>Paste it to the following input field.</span>
                        </div>

                        <input type="text" class="form-control mb-3 mw-100" placeholder="Enter the device name"
                            aria-label="" aria-describedby="basic-addon1" v-model="entry.device_name">
                        <error-label for="f_category_id" :errors="errors.device_name"></error-label>

                        <input type="text" class="form-control mw-100" placeholder="Enter the register code" aria-label=""
                            aria-describedby="basic-addon1" v-model="entry.device_uid">
                        <error-label for="f_category_id" :errors="errors.device_uid"></error-label>
                    </div>
                    <div class="px-10 form-group d-flex justify-content-between">
                        <!--                        <button  class="btn btn-danger ito-btn-small" data-dismiss="modal" @click="save()">Add now</button>-->
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary ito-btn-add" data-dismiss="modal" @click="save_send()">
                            <i class="bi bi-send mr-1"></i>Register
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editDeviceName" tabindex="-1" role="dialog" aria-labelledby="editDeviceName"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document">
                <div class="modal-content" style="margin-right:20px; left:140px">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 style="margin:20px auto;font-weight: 500;" class="popup-title success">Edit device name</h3>
                    <div class="content">
                        <label>Device Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control " placeholder="Device name" aria-label=""
                            style="margin-bottom: 10px" aria-describedby="basic-addon1" v-model="Edit_name.device_name">
                        <error-label for="f_category_id" :errors="errors.device_name"></error-label>
                        <div style="text-align:center">
                            <button class="btn btn-primary" @click="save_name">Save</button>
                        </div>



                    </div>


                </div>
            </div>
        </div>

        <div class="modal fade" id="editdeviceConfirm" tabindex="-1" role="dialog" aria-labelledby="editdeviceConfirm"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document">
                <div class="modal-content" style="margin-right:20px; left:140px">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 style="margin:20px auto;font-weight: 500;" class="popup-title success"> Get confirmation code</h3>
                    <div class="content">
                        <label>Device Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control " placeholder="Device name" aria-label=""
                            style="margin-bottom: 10px" aria-describedby="basic-addon1" v-model="editDevice" disabled>
                        <error-label for="f_category_id" :errors="errors.device_name"></error-label>
                        <div>
                            <button type="button" class="btn btn-primary" v-on:click="genToken"> Generate Key</button>
                        </div>
                        <div style="text-align:right"><button type="button" v-if="token" class="btn btn-primary"
                                v-on:click="copyTextToken" title="Copy Token" style="padding: 5px 20px;margin:0px 7px 10px">
                                Copy</button></div>

                        <div v-if="token"
                            style="font-size: 14px; word-wrap: break-word;white-space: pre-wrap;word-break: normal;background-color: #f7f7f9;">
                            {{ token }}</div>
                    </div>


                </div>
            </div>
        </div>

        <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="deviceConfirmLimit" tabindex="-1"
            role="dialog" aria-labelledby="deviceConfirmLimit" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered popup-main-1" role="document" style="max-width: 500px;">
                <div class="modal-content box-shadow-main paymment-status"
                    style="left:140px;text-align: center; padding: 27px 0px 10px;">
                    <div class="close-popup" data-dismiss="modal"></div>
                    <h3 class="popup-title success" style="margin-left:25px">Can not add more devices</h3>
                    <div class="content">
                        <p> You are allowed to access up to {{ devicesPerUser }} devices. Remove your old device if you want
                            to access a new one.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <!--                            <div class="d-flex align-items-center position-relative my-1">-->
                            <!--                              <span-->
                            <!--                                    class="svg-icon svg-icon-1 position-absolute ms-6"-->
                            <!--                                >-->
                            <!--                                    <svg-->
                            <!--                                        xmlns="http://www.w3.org/2000/svg"-->
                            <!--                                        width="24"-->
                            <!--                                        height="24"-->
                            <!--                                        viewBox="0 0 24 24"-->
                            <!--                                        fill="none"-->
                            <!--                                    >-->
                            <!--                                        <rect-->
                            <!--                                            opacity="0.5"-->
                            <!--                                            x="17.0365"-->
                            <!--                                            y="15.1223"-->
                            <!--                                            width="8.15546"-->
                            <!--                                            height="2"-->
                            <!--                                            rx="1"-->
                            <!--                                            transform="rotate(45 17.0365 15.1223)"-->
                            <!--                                            fill="black"-->
                            <!--                                        ></rect>-->
                            <!--                                        <path-->
                            <!--                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"-->
                            <!--                                            fill="black"-->
                            <!--                                        ></path>-->
                            <!--                                    </svg>-->
                            <!--                                </span>-->
                            <!--                                  <input-->
                            <!--                                    type="text"-->
                            <!--                                    data-kt-filemanager-table-filter="search"-->
                            <!--                                    class="form-control form-control-solid w-250px ps-15"-->


                            <!--                                    placeholder="Search..."-->
                            <!--                                    value=""-->
                            <!--                                />-->
                            <!--                                <span-->

                            <!--                                    class="svg-icon svg-icon-2 svg-icon-lg-1 me-0"-->

                            <!--                                >-->
                            <!--                                </span>-->


                            <!--                            </div>-->
                        </div>

                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                <button
                                    v-if="entries.length < devicesPerUser && permissions['019'] && roleName == 'School Admin' || entries.length < devicesPerUser && permissions['019'] && roleName == 'Teacher'"
                                    type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_add_customer" @click="modalDevice()"><i
                                        class="bi bi-plus-lg"></i>New Device</button>
                                <button
                                    v-if="entries.length >= devicesPerUser && roleName == 'School Admin' || entries.length >= devicesPerUser && roleName == 'Teacher'"
                                    type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_add_customer" @click="closeModal()"><i
                                        class="bi bi-plus-lg"></i>New Device</button>
                                <button v-if="roleName != 'School Admin' && roleName != 'Teacher'" type="button"
                                    class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                    @click="modalDevice()"><i class="bi bi-plus-lg"></i>New Device</button>


                            </div>

                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="d-flex flex-stack pt-4 pl-9 pr-9">
                            <div class="badge badge-lg badge-light-dark mb-15">
                                <div class="d-flex align-items-center flex-wrap"
                                    v-if="roleName == 'School Admin' || roleName == 'Teacher'">
                                    <div v-if="entries.length > 0">Number of devices: {{ entries.length }}/{{ devicesPerUser
                                    }}</div>
                                </div>
                            </div>
                        </div>

                        <table class="table table-hover table-row-bordered align-middle gy-4 gs-9">
                            <thead
                                class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                                <tr>
                                    <th class="">Device Name</th>
                                    <th class="text-center">Creation Date</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Delete</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="entry in entries" v-on:mouseover="mouseover(entry.id)"
                                    v-on:mouseleave="mouseleave()">
                                    <td class="">{{
                                        entry.device_name }}</td>
                                    <td class="text-center">
                                        {{ d(entry.created_at) }}</td>
                                    <td class="text-center">
                                        <span class="status-request"
                                            v-if="entry.status == 2 && entry.delete_request == 'Deleting request'">Delete
                                            request sent</span>
                                        <span class="status" v-else>Active</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-around">
                                            <i v-if="checkDeleteDeviceRequest == 0 && permissionFields['device_delete'] == true"
                                                :class="'bi bi-trash text-danger cursor-pointer action-btn action-btn-' + entry.id"
                                                :title="'Delete ' + entry.device_name" @click="removeDevice(entry.id)"></i>
                                            <i v-if="checkDeleteDeviceRequest == 1 && entry.status == 2 && permissionFields['device_delete'] == true"
                                                :class="'bi bi-trash text-danger cursor-pointer action-btn action-btn-' + entry.id"
                                                :title="'Delete ' + entry.device_name" @click="Sent(entry)"></i>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button :class="'btn btn-icon btn-active-light-dark text-dark btn-xs action-btn action-btn-' + entry.id"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" style="display: none;">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                            data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a @click="saveEditName(entry, permissionFields['device_rename'])"
                                                    class="menu-link px-3">Rename</a>
                                            </div>
                                            <div class="menu-item px-3" v-if="permissionFields['device_confirmation_code']">
                                                <a class="menu-link px-3" @click="editModalDevice(entry.id, entry.device_name, entry.secret_key)"
                                                    data-kt-subscriptions-table-filter="delete_row">Get confirmation code</a>
                                            </div>
                                        </div>
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
import { $get, $post, clone, getTimeRangeAll } from "../../utils";
import $router from '../../lib/SimpleRouter';
import ActionBar from "../includes/ActionBar";
let created = getTimeRangeAll();
const $q = $router.getQuery();

export default {
    name: "User_deviceIndex.vue",
    components: { ActionBar },
    data() {
        const permissions = clone(window.$permissions)
        return {
            permissionFields: $json.permissionFields || [],
            permissions,
            checkDeleteDeviceRequest: $json.checkDeleteDeviceRequest,
            roleName: $json.roleName,
            device: '',
            curDevice: {},
            isHidden: false,
            token: '',
            secret_key: "",
            Edit_name: '',
            device_name: "",
            editDevice: "",
            breadcrumbs: [
                {
                    title: 'My devices'
                },
            ],
            idDevice: '',
            entries: [],
            limit: 25,
            from: 0,
            to: 0,
            entry: $json.entry || {
            },
            devicesPerUser: $json.devicesPerUser || {},
            isLoading: false,
            errors: {},
        }
    },
    mounted() {
        $router.on('/', this.load).init();
    },
    methods: {
        removeDevice: function (removeDevice = {}) {
            $('#delete').modal('show');
            this.idDevice = removeDevice;
        },
        Sent: function (device = {}) {
            $('#sentConfirm').modal('show');
            this.curDevice = device;
        },
        saveEditName: function (deviceEditName = {}, permissions = {}) {
            if (permissions) {
                $('#editDeviceName').modal('show');
                this.Edit_name = deviceEditName;
            }
        },
        Cancel() {
            $('#sentConfirm').modal('hide');
        },
        async genToken() {
            const res = await $post('/xadmin/user_devices/generateToken', { device_id: this.currId });
            this.token = res.token;
        },

        editModalDevice(id, device_name, secret_key) {
            const that = this;
            that.currId = id;
            that.editDevice = device_name;
            that.secret_key = secret_key;
            $('#editdeviceConfirm').modal('show');
        },
        modalDevice() {
            $('#deviceConfirm').modal('show');
        },
        closeModal() {
            $('#deviceConfirmLimit').modal('show');
        },
        async load() {

            let query = $router.getQuery();
            this.$loading(true);
            const res = await $get('/xadmin/user_devices/data', query);
            this.$loading(false);
            setTimeout(function () {
                KTMenu.createInstances();
            }, 0)
            this.paginate = res.paginate;
            this.entries = res.data;
        },
        async remove() {
            const res = await $post('/xadmin/user_devices/remove', { id: this.idDevice });
            if (res.code) {
                toastr.error(res.message);
            } else {
                toastr.success(res.message);
                $('#delete').modal('hide');
                location.replace('/xadmin/user_devices/index');

            }
        },
        changeLimit() {
            let params = $router.getQuery();
            params['page'] = 1;
            params['limit'] = this.limit;
            $router.setQuery(params)
        },
        async save() {
            this.isLoading = true;
            const res = await $post('/xadmin/user_devices/save', {
                entry: this.curDevice,
            }, false);
            console.log(res);
            this.isLoading = false;
            if (res.errors) {
                this.errors = res.errors;
                return;
            }
            if (res.code) {
                toastr.error(res.message);
            } else {
                this.errors = {};
                toastr.success(res.message);
                if (!this.entry.id) {
                    location.replace('/xadmin/user_devices/index');
                }

            }
        },
        async save_name() {
            this.isLoading = true;
            const res = await $post('/xadmin/user_devices/saveName', {
                entry: this.Edit_name,
            }, false);
            this.isLoading = false;
            if (res.errors) {
                this.errors = res.errors;
                return;
            }
            if (res.code) {
                toastr.error(res.message);
            } else {
                this.errors = {};
                toastr.success(res.message);
                if (!this.entry.id) {
                    location.replace('/xadmin/user_devices/index');
                }

            }
        },
        async save_send() {
            this.isLoading = true;
            const res = await $post('/xadmin/user_devices/savesend', {
                entry: this.entry,
                status: this.status
            }, false);
            console.log(res);
            this.isLoading = false;
            if (res.errors) {
                this.errors = res.errors;
                return;
            }
            if (res.code) {
                toastr.error(res.message);
            } else {
                this.errors = {};
                toastr.success(res.message);
                if (!this.entry.id) {
                    location.replace('/xadmin/user_devices/index');
                }

            }
        },

        async toggleStatus(entry) {
            const res = await $post('/xadmin/user_devices/toggleStatus', {
                id: entry.id,
                status: entry.status
            });

            if (res.code === 200) {
                toastr.success(res.message);
                location.replace('/xadmin/user_devices/index');
            } else {
                toastr.error(res.message);

            }

        },

        async getDeviceByUser(entry) {
            const res = await $post('/xadmin/user_devices/getDeviceByUser', {
                id: entry.id,
                status: entry.status
            });

            if (res.code === 200) {
                toastr.success(res.message);
                location.replace('/xadmin/user_devices/index');
            } else {
                toastr.error(res.message);

            }

        },
        copyTextToken() {
            navigator.clipboard.writeText(this.token);
        },
        onPageChange(page) {
            $router.updateQuery({ page: page })
        },
        mouseover(id) {
            $('.action-btn-' + id).show();
        },
        mouseleave() {
            $('.action-btn').hide();
        }
    }
}
</script>

<style scoped>
.popup-title {
    margin-left: 160px;
}

.content {
    margin: -10px 39px 14px;
}

.form-control {
    max-width: 400px;
}

.btn btn-danger ito-btn-small {
    padding: 5px;
}

.body {
    padding: 23px;
    width: 100%;
    left: 10px;
    background: #FFFFFF;
    border: 1px solid black;
    border-radius: 20px;
    margin: -70px 0px 10px;
}

.modal-devices {
    position: absolute;
    right: 27px;
    max-width: 150px;


}

.btn-action {
    background: #696CFF;
    padding: 0.5em 0.85em;
    font-size: .85rem;
    font-weight: 500;
    color: #fff;
    border-radius: 0.475rem;
}

.status {
    color: #50cd89;
    background-color: #e8fff3;
    padding: 0.5em 0.85em;
    font-size: .85rem;
    font-weight: 600;
    border-radius: 0.475rem;

}

.status-request {
    background-color: #fff8dd;
    padding: 0.5em 0.85em;
    font-size: .85rem;
    font-weight: 600;
    border-radius: 0.475rem;
    color: #ffc700;

}

.menu.menu-sub {
    width: 200px !important;
}

.isDisabled {
    color: currentColor;
    cursor: not-allowed;
    opacity: 0.5;
    text-decoration: none;
    pointer-events: none
}</style>
