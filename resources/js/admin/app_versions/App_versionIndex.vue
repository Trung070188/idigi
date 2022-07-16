<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"/>
        <div class="row">
            <div class="col-lg-12">
                <div v-if="role=='Super Administrator'" class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-5">
                        <div class="title">

                            <label>Management Application Download</label>
                        </div>
                        <button class="btn btn-primary button-create " @click="showModalUpload()">Upload App</button>
                    </div>
                    <hr>

                    <div id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link btn-border" data-toggle="collapse"
                                            data-target="#collapseOne"
                                            aria-expanded="true" aria-controls="collapseOne">
                                        Application for window({{ totalVersionWindow }} version)
                                    </button>
                                </h5>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                 data-parent="#accordion">
                                <div class="card-body">
                                    <table class="table">

                                        <tbody>
                                        <tr v-for="entry in entries" v-if="entry.type=='window'">
                                            <td v-text="entry.name"></td>
                                            <td>Release date: {{ d2(entry.release_date) }}</td>
                                            <td>

                                                <a href="javascript:;" style="margin-right: 10px;color:#a6a60a"
                                                   v-if="entry.is_default==0"
                                                   @click="showSetDefaultModal(entry.id)">Set as default</a>
                                                <a href="javascript:;" style="margin-right: 10px; color:#34e034"
                                                   v-if="entry.is_default==1"
                                                   @click="showUnsetDefaultModal(entry.id)">Default</a>
                                                <a :href="entry.url" style="margin-right: 10px" download><i
                                                    class="fa fa-download"></i></a>
                                                <a @click="remove(entry)" href="javascript:;" class="btn-trash deleted"><i
                                                    class="fa fa-trash mr-1"></i></a>

                                            </td>

                                        </tr>

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed btn-border" data-toggle="collapse"
                                            data-target="#collapseTwo" aria-expanded="false"
                                            aria-controls="collapseTwo">
                                        Application for MacOs({{ totalVersionIos }} version)
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                 data-parent="#accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <thead>

                                        </thead>
                                        <tbody>
                                        <tr v-for="entry in entries" v-if="entry.type=='ios'">
                                            <td v-text="entry.name"></td>
                                            <td>Release date: {{ d2(entry.release_date) }}</td>
                                            <td>

                                                <a href="javascript:;" style="margin-right: 10px;color:#a6a60a"
                                                   v-if="entry.is_default==0"
                                                   @click="showSetDefaultModal(entry.id)">Set as default</a>
                                                <a href="javascript:;" style="margin-right: 10px; color:#34e034"
                                                   v-if="entry.is_default==1"
                                                   @click="showUnsetDefaultModal(entry.id)">Default</a>
                                                <a :href="entry.url" style="margin-right: 10px" download><i
                                                    class="fa fa-download"></i></a>
                                                <a @click="remove(entry)" href="javascript:;" class="btn-trash deleted"><i
                                                    class="fa fa-trash mr-1"></i></a>

                                            </td>

                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card card-custom card-stretch gutter-b" v-if="role=='Teacher' || role=='Administrator'">
                    <div  class="card-body d-flex flex-column" style="height: 563px" >
                        <div class="" style="margin-top: 65px; margin-bottom: 50px">
                            <h2  style="text-align: center;font-size: 30px">Tải iDIGI PC cho máy tính</h2>
                            <h5 style="text-align: center;font-size:20px">Ứng dụng đã có mặt trên Windows và MacOS.</h5>
                            <br>
                            <div  style="text-align: center;font-size: 14px;">
                                <label  >Cài đặt bài giảng số iDIGI thuận lợi và giảng dạy nhanh chóng.
                                </label>
                                <br>
                                <label  >Sử dụng trực tuyến (online) và ngoại tuyến (offline) mà không gặp gián đoạn.</label>
                                <br>
                                <label>Bảo mật bài giảng riêng cho thiết bị được đăng ký trước.</label>
                            </div>
                        </div>
                        <div class="col-lg-12" style="text-align: center;padding: 0 114px;" >
                                <div v-for="entry in entries" v-if="entry.type=='window'&& entry.is_default==1" style="">

                                    <a :href="entry.url">
                                        <button class="btn btn-primary">Download for Windows
                                            <i class="bi bi-windows"></i>
                                        </button>
                                    </a>
                                    <br>
                                    <label style="margin: 3px 34px 20px;">{{entry.name}}</label>
                                </div>
                                <div  v-for="entry in entries" v-if="entry.type=='ios'&& entry.is_default==1" style="">

                                    <a :href="entry.url">
                                        <button class="btn btn-primary">Download for MacOS
                                            <i style="margin:-3px 0px 0px" class="bi bi-apple"></i>
                                        </button>
                                    </a>
                                    <br>
                                    <label style="margin: 4px 34px 0px;">{{entry.name}}</label>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal" id="uploadApp" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload App</h5>
                        <button type="button" class="close" data-bs-dismiss="modal">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">

                        <div id="overlay">
                            <div class="la-3x text">
                                <i class="la la-spinner la-spin"></i>
                            </div>
                        </div>

                        <form>
                            <div class="form-group">
                                <label >File <span class="required"></span></label>
                                <input type="file" ref="uploader" class="form-control-file" accept=".zip,.rar,.7zip" >
                                <error-label :errors="errors.file_0"></error-label>
                            </div>
                            <div class="form-group">
                                <label>Name <span class="required"></span></label>
                                <input type="text" v-model="model.name" class="form-control">
                                <error-label :errors="errors.name"></error-label>
                            </div>

                            <div class="form-group">
                                <label>Type <span class="required"></span></label>
                                <select v-model="model.type" class="form-control">
                                    <option value="">---</option>
                                    <option value="ios">Mac OS</option>
                                    <option value="window">window</option>
                                </select>
                                <error-label :errors="errors.type"></error-label>
                            </div>

                            <div class="form-group">
                                <label>Release date <span class="required"></span></label>
                                <Datepicker v-model="model.release_date"/>
                                <error-label for="f_title" :errors="errors.release_date"></error-label>

                            </div>
                        </form>

                    </div>
                    <div class="modal-footer" style="justify-content: center">
                        <button type="button" class="btn btn-primary" @click="save">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="setDefault" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">Set as Default</h2>
                        <button type="button" class="close" data-bs-dismiss="modal">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure want to set this version as default?
                    </div>
                    <div class="modal-footer" style="justify-content: center">
                        <button type="button" class="btn btn-primary" @click="setDefaultVersion(1)">Yes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal fade" id="unsetDefault" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">Unset as Default</h2>
                        <button type="button" class="close" data-bs-dismiss="modal">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure want to uset this version as default
                    </div>
                    <div class="modal-footer" style="justify-content: center">
                        <button type="button" class="btn btn-primary" @click="setDefaultVersion(0)">Yes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import {$get, $post, forEach, getTimeRangeAll} from "../../utils";
    import $router from '../../lib/SimpleRouter';
    import ActionBar from "../includes/ActionBar";


    let created = getTimeRangeAll();
    const $q = $router.getQuery();

    export default {
        name: "App_versionsIndex.vue",
        components: {ActionBar},
        data() {
            return {
                model: {
                    type: ''
                },
                role: '',
                errors: {},
                entries: [],
                totalVersionIos: 0,
                totalVersionWindow: 0,
                curVersion: '',
                breadcrumbs: [
                    {
                        title: 'Download Application'
                    },
                ],
            }
        },
        mounted() {
            $router.on('/', this.load).init();
        },
        methods: {

            showSetDefaultModal: function (id) {
                this.curVersion = id;
                $('#setDefault').modal('show');
            },
            showUnsetDefaultModal: function (id) {
                this.curVersion = id;
                $('#unsetDefault').modal('show');
            },

            showModalUpload() {
                $('#uploadApp').modal('show');
            },

            async setDefaultVersion(isDefault) {

                const res = await $post('/xadmin/app_versions/setDefaultVersion', {
                    id: this.curVersion,
                    is_default: isDefault
                });

                $('#setDefault').modal('hide');
                $('#unsetDefault').modal('hide');

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    this.errors = {};
                    $router.on('/', this.load).init();
                    toastr.success(res.message);
                }

            },

            async save() {
                this.errors = {};
                const files = this.$refs.uploader.files;
                const formData = new FormData();
                formData.append('_token', window.$csrf)
                forEach(files, (v, k) => {
                    formData.append(k, v);
                });
                for (const [k, v] of Object.entries(this.model)) {
                    formData.append(k, v);
                }

                for (let i = 0; i < files.length; i++) {
                    formData.append('file_' + i, files[i]);
                }

                $('#overlay').show();
                let res = await fetch('/xadmin/app_versions/save', {
                    method: 'POST',
                    body: formData
                })
                    .then((response) => response.json())
                    .catch((error) => {
                        console.error('Error:', error);
                    });

                $('#overlay').hide();
                if (res.code) {
                    this.errors = res.errors;
                } else {
                    $('#uploadApp').modal('hide');
                    this.model = {
                        type: ''
                    }
                    this.$refs.uploader.value=null;
                    $router.on('/', this.load).init();
                    toastr.success(res.message);
                }

            },
            async load() {
                let query = $router.getQuery();
                const res = await $get('/xadmin/app_versions/data', query);
                this.paginate = res.paginate;
                this.entries = res.data;
                this.role = res.role;
                this.totalVersionIos = this.entries.filter(e => e.type == 'ios').length;
                this.totalVersionWindow = this.entries.filter(e => e.type == 'window').length;
                this.from = (this.paginate.currentPage - 1) * (this.limit) + 1;
                this.to = (this.paginate.currentPage - 1) * (this.limit) + this.entries.length;
            },
            async remove(entry) {
                if (!confirm('Xóa bản ghi: ' + entry.id)) {
                    return;
                }

                const res = await $post('/xadmin/app_versions/remove', {id: entry.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                }

                $router.updateQuery({page: this.paginate.currentPage, _: Date.now()});
            }

        }
    }
</script>

<style scoped>
    .table td {
        border-top: none;
    }

    .mb-0 {
        width: 100%;


    }

    .card-header {
        background-color: rgba(0, 0, 0, .03) !important;
    }

    .btn-border {
        text-align: left;
        width: 100%;

    }

    .table {
        border: 1px solid black;
        border-collapse: collapse;
        border-radius: 10px;
        border-style: hidden;
        box-shadow: 0 0 0 1px #666;
    }

</style>
