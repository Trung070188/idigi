<template>

    <div class="toolbar" id="kt_toolbar" style="z-index: 96">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0" style="width: 100%;">
                <template v-if="title">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{title}}</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>      
                </template>

                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="/xadmin/lessons/index" class="text-muted text-hover-primary">Home</a>
                    </li>

                    <template v-if="breadcrumbs" v-for="breadcrumb in breadcrumbs">
                        <li class="breadcrumb-item">
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li class="breadcrumb-item text-dark" v-if="breadcrumb.is_active">{{breadcrumb.title}}</li>
                        <li class="breadcrumb-item text-muted" v-else>
                            <a :href="breadcrumb.url?breadcrumb.url:'#'" class="text-muted text-hover-primary">{{breadcrumb.title}}</a>
                        </li>
                    </template>
                </ul>

                <div v-if="type==='form'" class="flex-1">
                    <div class="d-flex align-items-center">
                        <!--<a class="btn btn-default" :href="backUrl">
                            <i class="fa fa-arrow-left"></i>
                            Back
                        </a>-->
                        <div class="flex-1 text-right">
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm" @click="deleteItem()" v-if="isShowDelete">
                                Delete
                            </a>
                        </div>
                    </div>
                </div>
                <div class="flex-1 text-right" v-else-if="type==='index'">
                    <a :href="createUrl" class="btn btn-primary " v-if="createUrl">
                        Create new
                    </a>

                    <a href="javascript:void(0);" @click="downloadLesson" class="btn btn-primary " v-if="download">
                        Download Lesson
                    </a>

                    <a href="javascript:void(0);" @click="createWithFunction" class="btn btn-primary " v-if="createFunction">
                        <template v-if="nameFunction">{{nameFunction}}</template>
                        <template v-else>Create new</template>
                    </a>

                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" @click="deleteAll()" v-if="isShowDelete">
                        Delete
                    </a>
                </div>

            </div>
            <div class="d-flex align-items-center py-1">

            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "ActionBar",
        props: ['title', 'type', 'createUrl', 'code', 'backUrl', 'isShowDelete', 'createFunction','breadcrumbs', 'download','createDevice', 'nameFunction'],
        methods: {
            save() {
                this.isLoading = true;
                this.$emit('save');
                this.isLoading = false;
            },


            createNew() {
                this.isLoading = true;
                this.$emit('createNew');
                this.isLoading = false;
            },

            createWithFunction() {
                this.isLoading = true;
                this.$emit('createWithFunction');
                this.isLoading = false;
            },

            downloadLesson() {
                this.isLoading = true;
                this.$emit('download_lesson');
                this.isLoading = false;
            },

            deleteAll() {
                this.isLoading = true;
                this.$emit('deleteAll');
                this.isLoading = false;
            },

            deleteItem() {
                this.isLoading = true;
                this.$emit('deleteItem');
                this.isLoading = false;
            }
        },
        data() {

            return {
                isLoading: false
            }
        }
    }
</script>

<style scoped>

</style>
