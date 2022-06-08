<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"
                   v-on:createWithFunction="showModalUpload"
                   :createFunction="1"
                   nameFunction="Upload App"
                   title="Management Application Download"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
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
                                    <button class="btn btn-link collapsed" data-toggle="collapse"
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
                            <div class="la-3x text" >
                                <i class="la la-spinner la-spin" style="font-size: 8rem"></i>
                            </div>
                        </div>

                        <form>
                            <div class="form-group">
                                <label for="file">File <span class="required"></span></label>
                                <input type="file" ref="uploader" class="form-control-file" id="file">
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
                                    <option value="ios">ios</option>
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
            model: {},
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

            if (res.code) {
                toastr.error(res.message);
            } else {
                this.errors = {};
                toastr.success(res.message);

            }

        },

        async save() {
            this.errors = {};
            this.model = {};
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
            let self = this;
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
                self.errors = res.errors;
            } else {
                $('#uploadApp').modal('hide');
                toastr.success(res.message);
            }

        },
        async load() {
            let query = $router.getQuery();
            const res = await $get('/xadmin/app_versions/data', query);
            this.paginate = res.paginate;
            this.entries = res.data;
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

#overlay {
    position: fixed; /* Sit on top of the page content */
    display: none;
    width: 100%; /* Full width (cover the whole page) */
    height: 100%; /* Full height (cover the whole page) */
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5); /* Black background with opacity */
    z-index: 200000; /* Specify a stack order in case you're using a different order for other elements */
    cursor: pointer; /* Add a pointer on hover */
}
#overlay  .text {
    position: absolute;
    top: 50%;
    left: 50%;
    font-size: 50px;
    color: white;
    user-select: none;
    transform: translate(-50%,-50%);
    -ms-transform: translate(-50%,-50%);
}

.table {
    border: 1px solid black;
    border-collapse: collapse;
    border-radius: 10px;
    border-style: hidden;
    box-shadow: 0 0 0 1px #666;
}

</style>
