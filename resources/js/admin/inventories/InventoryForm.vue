<template>
    <div class="container-fluid">
        <ActionBar
            type="index"
            :breadcrumbs="breadcrumbs"
            :title="title"
        />
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex flex-column" >

                        <div class="row">
                            <div class="col-lg-9 col-sm-12">
                                <input v-model="entry.id" type="hidden" name="id" value="">

                                <div class="form-group">
                                    <label>Module name <span class="text-danger">*</span></label>
                                    <input  v-model="entry.name"  class="form-control" :disabled="permissionFields['resource_name']==false" placeholder="Enter the module name" >
                                    <error-label for="f_grade" :errors="errors.name"></error-label>

                                </div>
                                <div class="row">

<!--                                    <div class="form-group col-lg-4">-->
<!--                                        <label>Subject <span class="text-danger">*</span></label>-->
<!--                                        <select class="form-control" v-model="entry.subject" :disabled="permissionFields['resource_subject']==false">-->

<!--                                            <option value="math">Math</option>-->
<!--                                            <option value="science ">Science </option>-->
<!--                                        </select>-->
<!--                                        <error-label for="f_category_id" :errors="errors.subject"></error-label>-->
<!--                                    </div>-->
<!--                                    <div class="form-group col-lg-4">-->
<!--                                        <label>Grade <span class="text-danger">*</span></label>-->
<!--                                        <select class="form-control" v-model="entry.grade" :disabled="permissionFields['resource_grade']==false">-->

<!--                                            <option value="1">1</option>-->
<!--                                            <option value="2">2</option>-->
<!--                                            <option value="3">3</option>-->
<!--                                            <option value="4">4</option>-->
<!--                                            <option value="5">5</option>-->
<!--                                            <option value="6">6</option>-->
<!--                                            <option value="7">7</option>-->
<!--                                            <option value="8">8</option>-->
<!--                                            <option value="9">9</option>-->
<!--                                        </select>-->
<!--                                        <error-label for="f_category_id" :errors="errors.grade"></error-label>-->
<!--                                    </div>-->
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea  v-model="entry.description" rows="5" class="form-control" :disabled="permissionFields['resource_description']==false" placeholder="Your text here..."></textarea>
                                    <error-label for="f_grade" :errors="errors.description"></error-label>

                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" @click="location">Location</button>
                                </div>
                                 <div class="form-group col-sm-10"  style="border: 1px solid #b5b5c3;
                                                                          border-radius: 25px;
                                                                          justify-content: center;
                                                                          display: flex;
                                                                          align-items: center" v-if="entry.location==1">
                                    <select class="form-control form-select col-lg-4" style="margin-bottom: 20px;top:10px" required v-model="subject" @change="data()">
                                        <option value="" selected disabled>Choose subject</option>
                                        <option value="Math">Math</option>
                                        <option value="Science">Science</option>
                                    </select>
                                    <select class="form-control form-select col-lg-4" style="margin-left: 20px" v-model="lessonId" @change="filterSubject(lessonId)" required>
                                        <option value="" selected disabled>Choose lesson</option>
                                        <option v-for="lesson in lessons" :value="lesson.id">{{lesson.name}}</option>
                                    </select>
                                    <i style="width: 10%;
                                                display: inline-block;
                                                font-size: 50px;
                                                cursor: pointer" class="bi bi-x" @click="removeLesson"></i>
                                </div>
<!--                                <div class="form-group">-->
<!--                                    <label>Tags</label>-->
<!--                                    <input  v-model="entry.tags"  class="form-control" :disabled="permissionFields['resource_tags']==false" placeholder="Enter the tags name" >-->
<!--                                    <error-label for="f_grade" :errors="errors.tags"></error-label>-->

<!--                                </div>-->

                                <div class="form-check form-check-custom form-check-solid me-10 pb-5">
                                    <input id="enabled" type="checkbox" class="form-check-input h-20px w-20px" v-model="entry.enabled" :disabled="permissionFields['resource_active']==false">
                                    <label for="enabled" class="form-check-label fw-bold">Active</label>
                                    <error-label for="f_grade" :errors="errors.enabled"></error-label>
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-12">
                                <div class="form-group mb-3">
                                    <label>Type <span class="text-danger">*</span></label>
                                    <select class="form-control form-select" v-model="entry.type" :disabled="permissionFields['resource_type']==false" required>
                                        <option value="" selected disabled>Choose type</option>
                                        <option value="Vocabulary">Vocabulary</option>
                                        <option value="Summary">Lecture</option>
                                        <option value="Practice">Practice</option>
                                        <option value="Summary">Summary</option>
                                    </select>
                                    <error-label for="f_category_id" :errors="errors.type"></error-label>
                                </div>
<!--                                <div class="form-group mb-3" v-if="permissionFields['resource_picture']==false">-->
<!--                                    <label>Chọn ảnh</label>-->
<!--                                </div>-->
<!--                                <div class="form-group mb-3" v-else>-->
<!--                                    <label>Chọn ảnh</label>-->
<!--                                    <file-manager-input v-model="entry.file_image_new" :disabled="permissionFields['resource_picture']==false"  :hide-preview="true"></file-manager-input>-->
<!--                                    <error-label for="f_title" :errors="errors.file_image_new"></error-label>-->

<!--                                </div>-->
                                <div class="form-group mb-3" v-if="permissionFields['resource_file_asset_bundle']==false">
                                    <label>File asset bundle<span class="text-danger">*</span></label>
                                </div>
                                <div class="form-group mb-3" v-else>
                                    <label>File asset bundle<span class="text-danger">*</span></label>
                                    <file-manager-input v-model="entry.file_asset_new" ></file-manager-input>
                                    <error-label for="f_title" :errors="errors.file_asset_new"></error-label>

                                </div>
                            </div>

                        </div>
                        <hr style="margin-top: 5px;" >
                        <div >
                            <button type="reset" @click="save()" class="btn btn-primary mr-2"><i class="bi bi-save2 mr-1"></i>Save</button>
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
    //import UploadFileComponent from "../../components/UploadFileComponent";
    import FileManagerInput from "../../components/FileManagerInput";
    import SwitchButton from "../../components/SwitchButton";
import $router from "../../lib/SimpleRouter";

    export default {
        name: "InventoriesForm.vue",
        components: {ActionBar,SwitchButton,FileManagerInput},
        data() {
            return {

                types: [

                ],
                breadcrumbs: [
                    {
                        title: 'Resource management',
                    },
                    {
                        title: 'Modules',
                        url: '/xadmin/inventories/index',
                    },
                    {
                        title: $json.entry ? 'Module details' : 'Create new module',
                    },
                ],
                title: $json.entry ?  'Module details' : 'Create new module',
                entry: $json.entry || {
                    'type': '',
                    'location':0,
                },
                lessonId:'',
                subject:'',
                lessons:[],
                permissionFields:$json.permissionFields || [],
                isLoading: false,
                errors: {},
                check:1,
            }
        },
        mounted() {
            $router.on("/", this.data).init();
        },
        methods: {
            removeLesson()
            {
              this.entry.location=0;
            },
            filterSubject(lessonId)
            {
                this.check=1;
                if(this.check==1)

                {
                    let filter= this.lessons.find(function (e) {
                        if(e.id==lessonId)
                        {
                            return e
                        }
                    })
                    this.subject=filter.subject;
                    return this.subject;
                }
                else {
                    return this.subject
                }

            },
            async data()
            {
                this.check=0;
                this.$loading(true);
                if(this.entry.id)
                {
                    const res=await $get('/xadmin/inventories/dataForm?id='+this.entry.id+'&subject='+this.subject);
                    this.lessons=res.lessons;
                    if(res.lesson.length>0)
                    {
                        this.lessonId=res.lesson[0].id;
                        this.subject=res.lesson[0].subject
                    }

                }
                else {
                    const res=await $get('/xadmin/inventories/dataForm?subject='+this.subject+'&lessonId='+this.lessonId);
                    this.lessons=res.lessons;

                }
                this.$loading(false);
            },
            location()
            {
                console.log(this.entry.location);
                this.entry.location=1;
            },
            backIndex(){
                window.location.href = '/xadmin/inventories/index';
            },
            async save() {

               // this.$loading(true);
                const res = await $post('/xadmin/inventories/save', {entry: this.entry,lessonId: this.lessonId,subject:this.subject}, false);

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
