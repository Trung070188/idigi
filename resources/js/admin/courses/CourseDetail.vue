<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"  title = "Create new course"/>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column">
                        <div class="row">
                            <div class=" col-sm-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group col-sm-9">
                                        <label>Course name <span class="text-danger">*</span></label>
                                        <input class="form-control nospace" placeholder="Enter the course name" v-model="entry.course_name" >
                                        <error-label  for="f_category_id" :errors="errors.course_name"></error-label>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label>Subject<span class="text-danger">*</span></label>
                                        <select class="form-control form-select" v-model="entry.subject">
                                                <option value="Math">Math</option>
                                                <option value="Science ">Science </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-9">
                                        <label>Description </label>
                                        <textarea class="form-control"  placeholder="Type the description here (200 characters)" rows="5" v-model="entry.description"></textarea>
                                        <error-label for="f_category_id" :errors="errors.description"></error-label>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label>Grade <span class="text-danger">*</span></label>
                                        <select class="form-select form-control" v-model="entry.grade">
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
                                    </div>
                                    <div class="form-group col-sm-12"  style="border: 1px solid #b5b5c3;border-radius: 25px">
                                        <label style="margin:15px 0px 10px ">List of unit</label>
                                        <Treeselect :options="units" :multiple="true" v-model="listUnit" @input="unit()"/>
                                        <draggable
                                            :list="list"
                                            :animation="200"
                                            ghost-class="moving-card"
                                            group="users"
                                            filter=".action-button"
                                            class="form-group col-sm-12"
                                            tag="ul"
                                        >
                                            <div style="width: 100%;cursor: pointer" v-for="(res,index) in list" :key="index">
                                                <i class="bi bi-text-center" style="width: 5%; display: inline-block"></i>
                                                <div style="width: 5%;display: inline-block;position: relative;left: -17px;font-size: 20px">{{index+1}}</div>
                                                <div style="width: 50%;display: inline-block;margin-left: -50px">
                                                    <span>Resource name:</span>
                                                    <input class="form-control" v-model="res.label" disabled>
                                                </div>
                                                <div style="width: 30%;display: inline-block;margin-left: 20px">
                                                    <span>Type:</span>
                                                    <input class="form-control" v-model="res.id" disabled>
                                                </div>
                                                <i style="width: 10%;
                                                display: inline-block;
                                                font-size: 50px;
                                                top: 14px;
                                                position: relative;
                                                left: -10px;
                                                cursor: pointer" class="bi bi-x" @click="removeUnit(index,res.id)"></i>
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
    import {$get, $post} from "../../utils";
    import ActionBar from "../includes/ActionBar";
    import draggable from "vuedraggable";
    import Treeselect from '@riophae/vue-treeselect';
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'
    import $router from "../../lib/SimpleRouter";

    export default {
        name: "CourseDetail.vue",
        components: {ActionBar,draggable,Treeselect},
        data() {
            return {
                deleteUnit:[],
                list:[],
                listUnit:[],
                units:[],
                breadcrumbs: [
                    {
                        title: 'Resource management',
                    },
                    {
                        title: 'Course',
                        url: '/xadmin/courses/index',
                    },
                    {
                        title:'Create new course',
                    },
                ],
                entry: $json.entry || {},
                isLoading: false,
                errors: {}
            }
        },
        mounted() {
            $router.on("/", this.load).init();
        },
        methods: {
            removeUnit(index,id)
            {
                console.log(id);
                this.list=this.list.filter((item,key)=>key!==index);
                this.listUnit=this.list.map(rec => rec.id);
               this.deleteUnit=this.deleteUnit.concat(id);

            },
            unit()
            {
                this.list = this.listUnit.map(id => {
                    const item = this.units.find(i => i.id === id);
                    return {id, label: item.label};
                });
                console.log(this.list);
            },
            async load() {
                let query = $router.getQuery();
                this.$loading(true);
                const res = await $get("/xadmin/courses/dataEditCourse?id="+this.entry.id, query);
                this.$loading(false);
                this.units = res.units.map(rec => {
                    return {
                        'id':rec.id,
                        'label':rec.unit_name,
                    }
                });
                this.listUnit=res.listUnit.map(rec => rec.id);
            },
            backIndex(){
                window.location.href = '/xadmin/courses/index';
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/courses/save', {entry: this.entry,units:this.list,deleteUnit:this.deleteUnit}, false);
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
                        location.replace('/xadmin/courses/edit?id=' + res.id);
                    }

                }
            }
        }
    }
</script>

<style scoped>

</style>
