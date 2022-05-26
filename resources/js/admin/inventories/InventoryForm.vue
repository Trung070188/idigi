<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   backUrl="/xadmin/inventories/index"
                   :breadcrumbs = "breadcrumbs"
                   title="Inventory"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="card-body d-flex flex-column" >

                        <div class="row">
                            <div class="col-lg-9 col-sm-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">

                                <div class="form-group">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input  v-model="entry.name"  class="form-control" placeholder="Enter the inventories name" >
                                    <error-label for="f_grade" :errors="errors.name"></error-label>

                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label>Type <span class="text-danger">*</span></label>
                                        <select class="form-control" v-model="entry.type">
                                            <option value="Vocabulary">Vocabulary</option>
                                            <option value="Summary">Summary</option>
                                            <option value="Lecture">Lecture</option>
                                            <option value="Activity1">Activity1</option>
                                            <option value="Activity2">Activity2</option>
                                        </select>
                                        <error-label for="f_category_id" :errors="errors.type"></error-label>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Subject <span class="text-danger">*</span></label>
                                        <select class="form-control" v-model="entry.subject">

                                            <option value="math">Maths</option>
                                            <option value="science ">Science </option>
                                        </select>
                                        <error-label for="f_category_id" :errors="errors.subject"></error-label>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Grade <span class="text-danger">*</span></label>
                                        <select class="form-control" v-model="entry.grade">

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
                                        <error-label for="f_category_id" :errors="errors.grade"></error-label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea  v-model="entry.description" rows="5" class="form-control" placeholder="Your text here"></textarea>
                                    <error-label for="f_grade" :errors="errors.description"></error-label>

                                </div>
                                <div class="form-group">
                                    <label>Tags</label>
                                    <input  v-model="entry.tags"  class="form-control" placeholder="Enter the tags name" >
                                    <error-label for="f_grade" :errors="errors.tags"></error-label>

                                </div>

                                <div class="form-group">
                                    <input id="enabled" type="checkbox" v-model="entry.enabled">
                                    <label for="enabled"  class="pl-2">Active</label>
                                    <error-label for="f_grade" :errors="errors.enabled"></error-label>

                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-12">
                                <div class="form-group mb-3">
                                    <label>Chọn ảnh</label>
                                    <file-manager-input v-model="entry.file_image_array" :hide-preview="true"></file-manager-input>
                                    <error-label for="f_title" :errors="errors.file_image_array"></error-label>

                                </div>
                                <div class="form-group mb-3">
                                    <label>File asset bundle</label>
                                    <file-manager-input v-model="entry.file_asset_array"></file-manager-input>
                                    <error-label for="f_title" :errors="errors.file_asset_array"></error-label>

                                </div>
                            </div>

                        </div>

                        <hr>
                        <div >
                            <button type="reset" @click="save()" class="btn btn-primary mr-2">Save</button>
                            <button type="reset" @click="backIndex()" class="btn btn-secondary">Cancel</button>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</template>

<script>
    import {$post} from "../../utils";
    import ActionBar from "../includes/ActionBar";
    //import UploadFileComponent from "../../components/UploadFileComponent";
    import FileManagerInput from "../../components/FileManagerInput";
    import SwitchButton from "../../components/SwitchButton";

    export default {
        name: "InventoriesForm.vue",
        components: {ActionBar,SwitchButton,FileManagerInput},
        data() {
            console.log($json.entry);
            return {

                types: [

                ],
                breadcrumbs: [
                    {
                        title: 'Inventories',
                        url: '/xadmin/inventories/index',
                    },
                    {
                        title: $json.entry ? 'Edit inventory' : 'Create new inventory',
                    },
                ],
                entry: $json.entry || {},
                isLoading: false,
                errors: {}
            }
        },
        methods: {
            backIndex(){
                window.location.href = '/xadmin/inventories/index';
            },
            async save() {

               // this.$loading(true);
                const res = await $post('/xadmin/inventories/save', {entry: this.entry}, false);

               // this.$loading(false);
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
                        location.replace('/xadmin/inventories/edit?id=' + res.id);
                    }

                }
            }
        }
    }
</script>

<style scoped>

</style>
