<template>
    <div ref="root">
        <div class="">
            <div class="input-group">
                <span class="input-group-append">
			  	                    <button type="button" class="btn btn-primary " @click="openElfinder"><i
                                        class="la la-cloud-upload"></i> Chọn file</button>
			                        </span>
            </div>

        </div>

            <!-- Modal-->
        <div>
            <div ref="modal" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document" style="    max-width: 80%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Quản lí file</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" ref="elfinder">

                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
export default {
    name: "UploadFile",
    props: ['value', 'check', 'type'],
    data() {
        return {

            url: '',

        }
    },
    mounted() {


    },
    methods: {

        clearInput: function (){
          this.value = '';
          this.emitInput();
        },
        saveInput: function () {
            this.value = this.url;
            this.emitInput();
            $(this.$refs.modal).modal('hide');
        },
        openElfinder: function () {
            var self = this;
            $(this.$refs.modal).modal('show');

            $(this.$refs.elfinder).elfinder({
                debug: false,
                //lang: 'en',
                width: '100%',
                height: '70%',
                customData: {},
                /*commandsOptions: {
                    getfile: {
                        onlyPath: true,
                        folders: false,
                        multiple: false,
                        oncomplete: 'destroy'
                    },
                    ui: 'uploadbutton'
                },*/
                //mimeDetect: 'internal',
                /*onlyMimes: [
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                    'image/gif'
                ],*/
                url: '/elfinder/connector',
                soundPath: '/packages/barryvdh/elfinder/sounds',
                getFileCallback: function (file) {
                    self.value = file.url;
                    self.emitInput();
                    $(self.$refs.modal).modal('hide');
                },
                resizable: false
            }).elfinder('instance');
        },


        emitInput() {
            if(this.check){
                this.$emit('change_path', this.value);
            }

            this.$emit('input', this.value);
        },

    }
}
</script>

