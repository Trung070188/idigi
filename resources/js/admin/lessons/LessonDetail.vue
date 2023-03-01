<template>
    <div class="container-fluid">
        <ActionBar type="index"
                   :breadcrumbs="breadcrumbs"  title = "Lesson details"/>

        <div class="row">
            <div class="modal fade" style="margin-right:50px;border:2px solid #333333  " id="delete" tabindex="-1" role="dialog"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered popup-main-1" role="document"
                     style="max-width: 450px;">
                    <div class="modal-content box-shadow-main paymment-status" style="left:120px;text-align: center; padding: 20px 0px 55px;">
                        <div class="close-popup" data-dismiss="modal"></div>
                        <div class="swal2-icon swal2-warning swal2-icon-show">
                            <div class="swal2-icon-content" style="margin: 0px 24.5px 0px ">!</div>
                        </div>
                        <div class="swal2-html-container">
                            <p >Are you sure to delete this lesson?</p>
                        </div>
                        <div class="swal2-actions">
                            <button type="submit" id="kt_modal_new_target_submit" class="swal2-confirm btn fw-bold btn-danger" @click="remove(entry)">
                                <span class="indicator-label">Yes, delete!</span>
                            </button>
                            <button type="reset" id="kt_modal_new_target_cancel" class="swal2-cancel btn fw-bold btn-active-light-primary" data-bs-dismiss="modal" style="margin: 0px 8px 0px">No, cancel</button>

                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-6" style="margin:0px 0px -35px" v-if="entry.id">
                        <div class="card-title"></div>
                        <div class="card-toolbar">
                            <button  class="btn btn-danger" @click="deleteLesson(entry)">
                                Delete lesson <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="row">
                            <div class=" col-sm-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">
                                <div class="row">
                                    <div class="form-group col-sm-9">
                                        <label>Lesson name <span class="text-danger">*</span></label>
                                        <input class="form-control nospace" placeholder="Enter the unit name" v-model="entry.name" >
                                        <error-label  for="f_category_id" :errors="errors.name"></error-label>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label>Subject<span class="text-danger">*</span></label>
                                        <select class="form-control form-select" v-model="entry.subject" required @change="load">
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
                                    <div class="form-group col-sm-3">
                                        <label>Unit </label>
                                        <select class="form-select form-control" required v-model="entry.unit_id">
                                            <option value="" disabled selected>Choose the unit</option>
                                            <option v-for="unit in units" :value="unit.id">{{unit.unit_name}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-12"  style="border: 1px solid #b5b5c3;border-radius: 25px">
                                        <label style="margin:15px 0px 10px ">List of modules</label>
                                        <div style="margin-top: 10px;float: right;display: inline-block;margin-right: -13px" class="form-group col-lg-3">
                                            <select class="form-control form-select" required v-model="filter.type" @change="doFilter()">
                                                <option value="" disabled selected>Choose the type</option>
                                                <option value="Vocabulary">Vocabulary</option>
                                                <option value="Summary">Summary</option>
                                                <option value="Practice">Practice</option>
                                                <option value="Summary">Summary</option>
                                            </select>
                                        </div>
                                        <Treeselect :options="modules" :multiple="true" v-model="listResource" @input="resource()" @search-change="handleSearchChange"/>
<!--                                        <select class="form-control form-select" style="margin-bottom: 15px" v-model="listResource" @change="resource()" required>-->
<!--                                            <option value="" disabled selected>Search module</option>-->
<!--                                            <option v-for="module in modules" :value="module">{{module.name}}</option>-->
<!--                                        </select>-->
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
        name: "LessonDetail.vue",
        components: {ActionBar,draggable,Treeselect},
        data() {
            let filter = {
                type: $q.type || "",
            };
            return {
                lessonUnit:'',
                units:[],
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
                        title:'Lesson detail',
                    },
                ],
                searchLimit:50,
                entry: $json.entry || {},
                isLoading: false,
                errors: {}
            }
        },
        mounted() {
            $router.on("/", this.load).init();
        },
        methods: {
            deleteLesson:function(entry='')
            {
                $('#delete').modal('show');
                this.deleteCour=entry;
            },
            async handleSearchChange(value) {
                if (value) {
                    let query = $router.getQuery();
                    const res = await $get("/xadmin/lessons/dataCreateLesson?subject="+this.entry.subject,query);
                    this.modules=res.module;
                    const filteredOptions = this.modules.filter(option => option.label.includes(value)).slice(0,this.searchLimit);
                    this.modules = filteredOptions
                }
        },
            removeResource(index)
            {
                this.list=this.list.filter((item,key)=>key!==index);
                this.listResource=this.list.map(rec => rec.id);
            },
            resource()
            {
                // this.listResource=
                //     {
                //         'inventory_id':this.listResource.id,
                //         'lesson_id':this.entry.id,
                //         'name':this.listResource.name,
                //         'type':this.listResource.type
                //     }
                // this.list = this.list.concat(this.listResource);
                // this.listResource=[];
                console.log(this.listResource);
                this.list = this.listResource.map(id => {
                    const item = this.modules.find(i => i.id === id);
                    return {id, label: item.label,type:item.type};
                });
            },
            doFilter() {
                $router.setQuery(this.filter);
            },
            async load() {
                let query = $router.getQuery();
                this.$loading(true);
                const res = await $get("/xadmin/lessons/dataEditLesson?id="+this.entry.id,query);
                this.$loading(false);
                let inventory=[];
                res.lessons.forEach(function (e)
                {
                   res.module.forEach(function (e1)
                   {
                       if(e1.id==e.inventory_id)
                       {
                           inventory.push(e1);
                       }
                   })
                })
                this.modules=inventory;
                this.listResource=res.lessons.map(rec => rec.inventory_id);
                // this.modules = res.module;
                this.units=res.units;
            },
            backIndex(){
                window.location.href = '/xadmin/lessons/index';
            },
            async save() {
                this.isLoading = true;
                const res = await $post('/xadmin/lessons/save', {entry: this.entry,inventory:this.list,lessonUnit: this.lessonUnit}, false);
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
                    //     location.replace('/xadmin/lessons/edit?id=' + res.id);
                    // }

                }
            },
            async remove(entry) {
                const res = await $post('/xadmin/lessons/remove', {id: entry.id});

                if (res.code) {
                    toastr.error(res.message);
                } else {
                    toastr.success(res.message);
                    $('#delete').modal('hide');
                    window.location.href = '/xadmin/lessons/index';
                }

            },
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
