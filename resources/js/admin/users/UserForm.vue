<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   back-url="/xadmin/users/index"
                   title="UserForm"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="card-body d-flex flex-column">

                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input id="f_name" v-model="entry.name" name="name" class="form-control"
                                           placeholder="name">
                                    <error-label for="f_name" :errors="errors.name"></error-label>

                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input id="f_email" v-model="entry.email" name="name" class="form-control"
                                           placeholder="email">
                                    <error-label for="f_email" :errors="errors.email"></error-label>

                                </div>

                                <div class="form-group">
                                    <label>Birthday</label>
                                    <input id="f_birthday" v-model="entry.birthday" name="name" class="form-control"
                                           placeholder="birthday">
                                    <error-label for="f_birthday" :errors="errors.birthday"></error-label>

                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input id="f_phone" v-model="entry.phone" name="name" class="form-control"
                                           placeholder="phone">
                                    <error-label for="f_phone" :errors="errors.phone"></error-label>

                                </div>
                                <div class="form-group">
                                    <label>Avatar</label>
                                    <file-manager-input v-model="entry.avatar"/>
                                    <upload-file-component v-model="entry.avatar"/>
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
    import {$post} from "../../utils";
    import ActionBar from "../includes/ActionBar";
    import FileManagerInput from "../../components/FileManagerInput";
    import UploadFileComponent from "../../components/UploadFileComponent";

    export default {
        name: "UsersForm.vue",
        components: {FileManagerInput, ActionBar, UploadFileComponent},
        data() {
            return {
                entry: $json.entry || {},
                isLoading: false,
                errors: {}
            }
        },
        methods: {
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/users/save', {entry: this.entry}, false);
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
                        location.replace('/xadmin/users/edit?id=' + res.id);
                    }

                }
            }
        }
    }
</script>

<style scoped>

</style>
