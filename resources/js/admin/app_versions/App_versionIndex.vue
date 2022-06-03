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
                                        Application for window(4 version)
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
                                                <a href="javascript:;" style="margin-right: 10px" @click="setDefault">Set as default</a>
                                                <a href="javascript:;" style="margin-right: 10px"><i
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
                                        Application for MacOs(4 version)
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
                                        <tr>
                                            <td>John</td>
                                            <td>Doe</td>
                                            <td>john@example.com</td>
                                        </tr>
                                        <tr>
                                            <td>Mary</td>
                                            <td>Moe</td>
                                            <td>mary@example.com</td>
                                        </tr>
                                        <tr>
                                            <td>July</td>
                                            <td>Dooley</td>
                                            <td>july@example.com</td>
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
                        <h5 class="modal-title">Download Lesson</h5>
                        <button type="button" class="close" data-bs-dismiss="modal">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer" style="justify-content: center">

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
                    Are you sure want to set default this version?
                </div>
                <div class="modal-footer" style="justify-content: center">
                    <button type="button" class="btn btn-primary">Yes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
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
    name: "App_versionsIndex.vue",
    components: {ActionBar},
    data() {
        return {
            entries: [],
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
        setDefault: function (){
            $('#setDefault').modal('show');
        },

        showModalUpload() {
            $('#uploadApp').modal('show');
        },
        async load() {
            let query = $router.getQuery();
            const res = await $get('/xadmin/app_versions/data', query);
            this.paginate = res.paginate;
            this.entries = res.data;
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
.table{
    border: 1px solid black;
    border-collapse: collapse;
    border-radius: 10px;
    border-style: hidden;
    box-shadow: 0 0 0 1px #666;
}

</style>
