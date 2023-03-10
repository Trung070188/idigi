<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"  title = "Create new lesson"/>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column">
                        <div class="row">
                            <div class=" col-sm-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group col-sm-9">
                                        <label>Lesson name <span class="text-danger">*</span></label>
                                        <input class="form-control nospace" placeholder="Enter the lesson name" v-model="entry.name" >
                                        <error-label  for="f_category_id" :errors="errors.name"></error-label>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label>Subject<span class="text-danger">*</span></label>
                                        <select class="form-control form-select" v-model="entry.subject" required @change="changeSubject">
                                            <option value="" disabled selected>Choose the subject</option>
                                            <option value="Math">Math</option>
                                            <option value="Science">Science</option>
                                        </select>
                                        <error-label  for="f_category_id" :errors="errors.subject"></error-label>

                                    </div>
                                    <div class="form-group col-sm-9">
                                        <label>Description </label>
                                        <textarea class="form-control"  placeholder="Your text here..." rows="5" v-model="entry.description"></textarea>
                                        <error-label for="f_category_id" :errors="errors.description"></error-label>
                                    </div>
                                    <div class="form-group col-sm-3" v-if="entry.subject">
                                        <label>Unit </label>
                                        <select class="form-select form-control" required v-model="entry.unit_id" @change="changeUnit">
                                            <option value="" disabled selected>Choose the unit</option>
                                            <option v-for="unit in units" :value="unit.id">{{unit.unit_name}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-12"  style="border: 1px solid #b5b5c3;border-radius: 25px" v-if="entry.subject">
                                        <label style="margin:15px 0px 10px ">List of modules</label>
                                        <div style="margin-top: 10px;float: right;display: inline-block;margin-right: -13px" class="form-group col-lg-3">
                                            <select class="form-control form-select" required v-model="filter.type" @change="changeType">
                                                <option value="" disabled selected>Choose the type</option>
                                                <option value="Vocabulary">Vocabulary</option>
                                                <option value="Lecture">Lecture</option>
                                                <option value="Practice">Practice</option>
                                                <option value="Summary">Summary</option>
                                            </select>
                                        </div>
                                        <Treeselect :options="modules" :async="true"  placeholder="Search module" :multiple="true" v-model="listResource"  :valueFormat="'object'":cacheOptions="false" :load-options="handleSearchChange"/>
                                        <draggable
                                            :list="listResource"
                                            :animation="200"
                                            ghost-class="moving-card"
                                            group="users"
                                            filter=".action-button"
                                            class="form-group col-sm-12"
                                            tag="ul"
                                        >
                                            <div style="width: 100%;cursor: pointer" v-for="(res,index) in listResource" :key="index">
                                                <i class="bi bi-text-center" style="width: 10%; display: inline-block"></i>
                                                <div style="width: 50%;display: inline-block;margin-left: -75px">
                                                    <span>Resource name:</span>
                                                    <input class="form-control" v-model="res.label" disabled>
                                                </div>
                                                <div style="width: 30%;display: inline-block;margin-left: 20px">
                                                    <span>Type:</span>
                                                    <input class="form-control" v-model="res.type" disabled>
                                                </div>
                                                <i style="width: 10%;
                                                display: inline-block;
                                                font-size: 50px;
                                                top: 14px;
                                                position: relative;
                                                left: -10px;
                                                cursor: pointer" class="bi bi-x" @click="removeResource(index)"></i>
                                            </div>

                                        </draggable>
                                    </div>


                                </div>
                                <div class="form-check form-check-custom form-check-solid pb-5">
                                    <input id="state" type="checkbox" v-model="entry.active" class="form-check-input h-20px w-20px" checked>
                                    <label for="state" class="form-check-label fw-bold">Active</label>
                                    <error-label for="f_grade" :errors="errors.active"></error-label>
                                </div>
                            </div>
                        </div>
                        <!--<hr style="margin: 0px 0px 16px;">-->
                        <div class="mt-5">
                            <button type="reset" @click="save()"  class="btn btn-primary mr-3" ><i class="bi bi-send mr-1"></i>Save</button>
                            <button type="reset" @click="backIndex()" class="btn btn-light">Cancel</button>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</template>

<script>
    import {$get, $post, getTimeRangeAll} from "../../utils";
    import ActionBar from "../includes/ActionBar";
    import draggable from "vuedraggable";
    import $router from "../../lib/SimpleRouter";
    import Treeselect from '@riophae/vue-treeselect';
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'


    let created = getTimeRangeAll();
    const $q = $router.getQuery();
    export default {
        name: "LessonsForm.vue",
        components: {ActionBar,draggable,Treeselect},
        data() {
            let filter = {
                type: $q.type || "",
            };
            return {
                units:[],
                allUnit:[],
                checkResource:[],
                listResource:[],
                filter:filter,
                module_type:'',
                modules:[],
                list:[],
                breadcrumbs:[
                    {
                        title: 'Resource management',
                    },
                    {
                        title: 'Lesson',
                        url: '/xadmin/lessons/index',
                    },
                    {
                        title:'Create new lesson',
                    },
                ],
                entry:{
                    unit_id:'',
                    subject:''
                },
                isLoading: false,
                searchLimit:50,
                errors: {}
            }
        },
        mounted() {
            this.getUnits();
        },
        methods: {
            async handleSearchChange({action, searchQuery, callback}) {

                const res = await $get("/xadmin/lessons/getModules", {subject: this.entry.subject, type: this.filter.type, keyword: searchQuery});
                callback(null, res)

            },
            removeResource(index) {
                this.listResource=this.listResource.filter((item,key)=>key!==index);
            },

            changeSubject(){
                this.modules = [];
                this.listResource = [];
                this.entry.unit_id = null;
                this.units = this.allUnit.filter(e=> e.subject == this.entry.subject);

            },
            changeUnit(){
                this.modules = [];
                this.listResource = [];
            },
            changeType(){
                this.modules = [];
                this.listResource = [];
            },
            async getUnits() {
                const res = await $get("/xadmin/units/getUnits");
                this.units=res;
                this.allUnit=res;

            },
            backIndex(){
                window.location.href = '/xadmin/lessons/index';
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/lessons/save', {entry: this.entry,inventory:this.listResource}, false);
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

                    // if (!this.entry.id) {
                        location.replace('/xadmin/lessons/edit?id=' + res.id);
                    // }

                }
            }
        }
    }
</script>

<style scoped>
    select:required:invalid {
        color: #adadad;
    }

    option[value=""][disabled] {
        display: none;
    }

    option {
        color: black;
    }
</style>
