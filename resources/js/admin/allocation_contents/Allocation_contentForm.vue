<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   backUrl="/xadmin/allocation_contents/index"
                   title="Allocation Form"/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column" >
                        <div class="row">
                            <div class="col-lg-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input id="f_title" v-model="entry.title" name="name" class="form-control"
                                               placeholder="title" >
                                        <error-label for="f_title" :errors="errors.title"></error-label>
                                    </div>

                                                                    <div class="form-group">
                                        <label>Course</label>
                                        <treeselect :options="courses" :multiple="true" v-model="total_course"/>
                                        <error-label for="f_total_course" :errors="errors.total_course"></error-label>

                                    </div>
                            </div>
                        </div>
                         <table class="table table-row-bordered align-middle gy-4 gs-9">
                            <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bolder bg-light bg-opacity-75">
                            <tr>
                                <th class="">Course Name</th>
                                <th>Unit</th>
                            </tr>
                            </thead>
                            <tbody v-for="entry in total_course" >
                            <tr v-for="course in courses" v-if="entry==course.id">
                                <td >
                                    {{course.label}}
                                </td>
                                <td >
                                <treeselect :options="course.units" :multiple="true" v-model="course.total_unit"  />
                                </td>
                            </tr>

                            </tbody>
                        </table>

                        <hr style="margin:20px 0px 20px">
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
    import Treeselect from '@riophae/vue-treeselect'
  // import the styles
  import '@riophae/vue-treeselect/dist/vue-treeselect.css'

    export default {
        name: "Allocation_contentForm.vue",
        components: {ActionBar, Treeselect},
        data() {
            const units=$json.units;
            const unitTreeselect = units.map(rec => {
                return {
                    'id':rec.id,
                    'label': rec.unit_name,
                }
            })
            const course=$json.courses;
            course.forEach(function (e) {
                e.units.forEach(function (e1) {
                    e1.label = e1.unit_name;
                })

            })
            const courseTreeselect = course.map(rec => {
                return {
                    'id':rec.id,
                    'label': rec.course_name,
                    'total_unit':rec.total_unit,
                    'units':rec.units,
                }
            })
                return {
                total_school:[],
                total_course:[],
                total_unit:[],
                value: [],
                courses:courseTreeselect,
                schools:$json.schools||{},
                units:unitTreeselect,
                entry: $json.entry || {},
                isLoading: false,
                errors: {}
            }
        },
        methods: {
            backIndex(){
                window.location.href = '/xadmin/allocation_contents/index';
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/allocation_contents/save', {entry: this.entry,total_school:this.total_school,total_course:this.total_course,unit:this.courses}, false);
                this.isLoading = false;
                if (res.errors) {
                    this.errors = res.errors;
                    return;
                }
                if (res.code) {
                    toastr.error(res.message);
                }
                else {
                    this.errors = {};
                    toastr.success(res.message);
                    location.replace('/xadmin/allocation_contents/index');

                    if (!this.entry.id) {
                        location.replace('/xadmin/allocation_contents/index');
                    }

                }
            }
        }
    }
</script>

<style scoped>

</style>

