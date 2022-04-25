<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   backUrl="/xadmin/{{$table}}/index"
                   title="{{$ucTable.'Form'}}"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">

                    <div class="card-body d-flex flex-column" >

                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                @foreach ($fields as $field)
                                    <div class="form-group">
                                        <label>{{word_normalized($field)}}</label>
                                        <input id="f_{{$field}}" v-model="entry.{{$field}}" name="name" class="form-control"
                                               placeholder="{{$field}}" >
                                        <error-label for="f_{{$field}}" :errors="errors.{{$field}}"></error-label>

                                    </div>
                                @endforeach

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

    export default {
        name: "{{ucfirst($table)}}Form.vue",
        components: {ActionBar},
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
                const res = await $post('{{$routePrefix}}/{{$table}}/save', {entry: this.entry}, false);
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
                        location.replace('{{$routePrefix}}/{{$table}}/edit?id=' + res.id);
                    }

                }
            }
        }
    }
</script>

<style scoped>

</style>
