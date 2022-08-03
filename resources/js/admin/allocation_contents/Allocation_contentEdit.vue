<template>
    <div class="container-fluid">
        <ActionBar type="form" @save="save()"
                   :code="entry.id"
                   backUrl="/xadmin/allocation_contents/index"
                   title="AllocationForm"/>
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
                                        <label>Total School</label>
                                        <treeselect :options="schools" :multiple="true" v-model="total_school" />
                                        <error-label for="f_total_school" :errors="errors.total_school"></error-label>

                                    </div>
                                                                    <div class="form-group">
                                        <label>Total Course</label>
                                        <treeselect :options="courses" :multiple="true" v-model="total_course" />
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
                            <tr v-for="course in courses"  v-if="entry==course.id">
                               
                                <td  >
                                    {{course.label}}
                                </td>
                                <td>
                                <treeselect :options="units" :multiple="true" />

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
import QSelect from '../../components/QSelect.vue';
    import {$post} from "../../utils";
    import ActionBar from "../includes/ActionBar";
    import Treeselect from '@riophae/vue-treeselect'
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'

    export default {
        name: "Allocation_contentEdit.vue",
        components: {ActionBar, Treeselect},
        data() {
            return {
                total_school:$json.totalSchoolArray ||{},
                total_course:$json.totalCourseArray ||{},
                entry: $json.entry || {},
                schools:$json.schools ||{},
                courses:$json.courses ||{},
                units:$json.units || {},
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
                const res = await $post('/xadmin/allocation_contents/save', {entry: this.entry,total_school:this.total_school,total_course:this.total_course}, false);
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

