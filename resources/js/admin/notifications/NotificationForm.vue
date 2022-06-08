<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   backUrl="/xadmin/notifications/index"
                   title="NotificationForm"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column" >
                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                                                    <div class="form-group">
                                        <label>Type</label>
                                        <input id="f_type" v-model="entry.type" name="name" class="form-control"
                                               placeholder="type" >
                                        <error-label for="f_type" :errors="errors.type"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Url</label>
                                        <input id="f_url" v-model="entry.url" name="name" class="form-control"
                                               placeholder="url" >
                                        <error-label for="f_url" :errors="errors.url"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Channel</label>
                                        <input id="f_channel" v-model="entry.channel" name="name" class="form-control"
                                               placeholder="channel" >
                                        <error-label for="f_channel" :errors="errors.channel"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Status</label>
                                        <input id="f_status" v-model="entry.status" name="name" class="form-control"
                                               placeholder="status" >
                                        <error-label for="f_status" :errors="errors.status"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Content</label>
                                        <input id="f_content" v-model="entry.content" name="name" class="form-control"
                                               placeholder="content" >
                                        <error-label for="f_content" :errors="errors.content"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Title</label>
                                        <input id="f_title" v-model="entry.title" name="name" class="form-control"
                                               placeholder="title" >
                                        <error-label for="f_title" :errors="errors.title"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Sent At</label>
                                        <input id="f_sent_at" v-model="entry.sent_at" name="name" class="form-control"
                                               placeholder="sent_at" >
                                        <error-label for="f_sent_at" :errors="errors.sent_at"></error-label>

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

    export default {
        name: "NotificationsForm.vue",
        components: {ActionBar},
        data() {
            return {
                entry: $json.entry || {},
                isLoading: false,
                errors: {}
            }
        },
        methods: {
            backIndex(){
                window.location.href = '/xadmin/notifications/index';
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/notifications/save', {entry: this.entry}, false);
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
                        location.replace('/xadmin/notifications/edit?id=' + res.id);
                    }

                }
            }
        }
    }
</script>

<style scoped>

</style>
