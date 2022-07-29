<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs" title = "Create new key collection"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="card-body d-flex flex-column">
                        <div class=" card-header border-0 pt-5 row" style="margin-top:-30px;margin-left: -35px;">
                            <div class=" col-sm-12">
                                <div class="row">
                                    <div class="form-group  col-sm-8">
                                        <label>Title <span class="text-danger">*</span></label>
                                        <input class="form-control" >

                                        <error-label for="f_category_id" ></error-label>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group  col-sm-8">
                                        <label>Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control"/>
                                        <error-label for="f_category_id" ></error-label>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <hr style="margin-top: 10px">
                        <div class="tab-content">

                            <h3>Add Device</h3>
                            <div class="d-flex flex-stack pt-4 pl-9 pr-9">
                                <div class="badge badge-lg badge-light-primary mb-15">
                                    <div class="d-flex align-items-center flex-wrap">

                                    <span class="svg-icon svg-icon-2x svg-icon-primary mx-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z" fill="black"></path>
                                        </svg>
                                    </span>


                                    </div>
                                </div>
                                <div class="d-flex justify-content-end" style="margin: 0px -25px 0px">
                                    <button style="margin:0px 20px 0px" type="button" class="btn btn-primary" @click="importDevice()" > Import device

                                    </button>
                                    <button type="button" class="btn btn-primary" > Add device
                                    </button>
                                </div>
                            </div>

                            <table class=" table  table-head-custom table-head-bg table-vertical-center">
                                <thead>
                                <tr>
                                    <th>Device name</th>
                                    <th>Register code</th>
                                    <th>Operating system</th>
                                    <th>Description</th>


                                </tr>
                                </thead>
                                <tbody>
                                <tr >
                                    <td></td>
                                    <td ></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                            <div>
                                <button class="btn btn-primary" >Create</button>
                                <button class="btn btn-primary">Cancel</button>
                            </div>
                        </div>

                    </div>

                </div>

            </div>


        </div>

    </div>


</template>

<script>
    import ActionBar from "../includes/ActionBar";

    export default {
        name: "KeyCollectionForm",
        components: {ActionBar},
        data() {
            return {
                breadcrumbs: [
                    {

                    },

                ],
                isLoading: false,
                errors: {}
            }
        },
        methods:{
            importDevice() {
                $('#deviceConfirm').modal('show');
            },
        },
    }
</script>

<style scoped>

</style>
