<template>
    <div ref="root">
        <div class="fmi-preview" >
            <ul class="fmi-preview-ul">
                <li v-for="(file, index) in files">
                    <div v-if="file.is_image">
                        <a target="_blank" :href="file.url">
                            <img  class="fm-img-preview" :src="file.url"/>
                        </a>
                    </div>

                    <template v-else>
                        <div>
                            <i :class="getIconClass(file.extension)"></i>
                        </div>
                        <div style="word-break: break-all">
                            <span v-text="file.name"></span>
                        </div>
                    </template>

                    <span @click="remove(file, index)" class="fmi-close">
                        <i class="fa fa-times" style="color:red"/>
                    </span>
                </li>
            </ul>
        </div>
        <div class="fmi-dropdown" ref="dropdown">
            <input @change="fileChanged()" type="file" ref="uploader" accept="image/*" style="display: none;width: 1px;height: 1px;"/>
            <a class="" href="#" @click.prevent="chooseFile()">
                <button  ref="button" @click="toggleTooltip()" class="btn btn-primary btn-sm" type="button" >
                    Change avatar
                    <i class="fas fa-camera"></i>
                </button>
            </a>

            <div>
                <span class="qinput-error-label" v-if="errorMessage">{{ errorMessage }}</span>
            </div>
            <div class="fmi-dropdown-menu" v-if="showToolTip" ref="tooltip">
                <a class="fmi-dropdown-item" href="#" @click.prevent="chooseFile()">Tải file</a>

            </div>
        </div>
        <div>
            <div ref="modal" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document" style="    max-width: 80%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Quản lí file</h5>
                            <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal"
                                    aria-label="Close">
                                &times;
                            </button>
                        </div>
                        <div class="modal-body">
                            <FileManager v-if="showFileManager" :multiple="isMultiple" @fileclick="fileClicked"/>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
    import {$upload, arr} from "../utils";
    import FileManager from "./FileManager";

    function fileMap(file) {
        return {
            id: file.id,
            uri: file.url
        };
    }

    const fileExtensionIcons = {
        'xlsx': 'fa fa-file-excel',
        'xls': 'fa fa-file-excel',
        'docx': 'fa fa-file-word',
        'doc': 'fa fa-file-word',
        'pdf': 'fa fa-file-pdf',
        '': 'fa fa-file',
    };

    export default {
        name: "UploadImage",
        props: ['value', 'multiple', 'type', 'required', 'error'],
        data() {

            const isMultiple = this.multiple;

            console.log(this.value)
            const files = arr(this.value).map((v => {
                return {
                    is_image: v.is_image,
                    extension: v.extension,
                    name: v.name,
                    id: v.id,
                    url: v.uri
                }
            }));

            return {
                isMultiple,
                files,
                showFileManager: false,
                showToolTip: false,
                errorMessage: ''
            }
        },
        mounted() {
            document.addEventListener('click', this.docClicked)
        },
        components: {
            FileManager
        },
        methods: {

            docClicked(ev) {
                if (this.$refs.button.contains(ev.target)) {
                } else {
                    this.showToolTip = false;
                }

            },
            toggleTooltip() {
                this.showToolTip = !this.showToolTip;
            },
            async fileChanged() {
                const files = this.$refs.uploader.files;
                if (files.length > 0) {
                    const res = await $upload('/xadmin/files/upload', files);
                    if (res.code !== 200) {
                        toastr.error(res.message);
                    } else {
                        toastr.success(res.message);
                        if (this.isMultiple) {
                            this.files.unshift(res.file);
                        } else {
                            this.files = [res.file];
                        }
                        this.emitInput();
                    }

                }

            },
            chooseFile() {
                this.$refs.uploader.click();
            },
            getIconClass(ext) {
                return 'fmi-file ' + (fileExtensionIcons[ext] || '');
            },
            remove(file, index) {
                this.files.splice(index, 1);
                this.emitInput();
            },
            validate() {
                this.emitInput();
                return !this.errorMessage;
            },
            emitInput() {
                let model, hasError = false;
                if (this.isMultiple) {
                    model = this.files.map(fileMap);
                    hasError = model.length === 0;
                } else {
                    model = this.files.length ? fileMap(this.files[0]) : null;
                    hasError = model === null;
                }

                if (this.required != null) {
                    if (hasError) {
                        this.errorMessage = this.error || 'Vui lòng chọn ảnh';
                    } else {
                        this.errorMessage = '';
                    }
                }

                this.$emit('input', model);
            },
            fileClicked(file) {

                if (this.isMultiple) {
                    this.files.push(...file);
                } else {
                    this.files = [file[0]];
                }

                this.emitInput();

                $(this.$refs.modal).modal('hide');
                this.showFileManager = false;
            },
            showModal() {
                this.showFileManager = true;
                $(this.$refs.modal).modal('show');
            }
        }
    }
</script>

<style scoped>
    .fm-img-preview{
        width:150px;
        height:150px;
        border-radius: 50%;
    }
    .fmi-preview {
        margin: 0px -50px 0px;
    }

    .fmi-dropdown {
        position: relative;
        margin-left:8px;
    }

    .fmi-dropdown-menu {
        z-index: 1000;
        position: absolute;
        top: 32px;
        left: 0;
        min-width: 10rem;
        padding: .5rem 0;
        margin: .125rem 0 0;
        font-size: 1rem;
        color: #212529;
        text-align: left;
        list-style: none;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, .15);
        border-radius: .25rem;
    }

    .fmi-dropdown-item {
        display: block;
        width: 100%;
        padding: .25rem 1.5rem;
        clear: both;
        font-weight: 400;
        color: #212529;
        text-align: inherit;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
    }

    .fmi-dropdown-item:hover {
        text-decoration: none;
        background-color: #1176db;
        color: white;
    }

    .fmi-preview-ul {
        margin-left: -16px;
        /*padding-left: initial;*/
        /*list-style: none;*/
        /*display: flex;*/
        /*margin: 0px 106px 10px;*/
    }

    .fmi-preview-ul li {
        display: inline-block;
        /*margin-left: 5px;*/
        position: relative;
        /*width: 80px;
        height: auto;*/
    }

    .fmi-close {
        position: absolute;
        top: -9px;
        right: -5px;
        color: #8b0000;
        cursor: pointer;
    }

    .fmi-file {
        font-size: 64px;
    }
</style>
