<template>
    <div class="container-fluid">
        <ActionBar title="Quản lý file"/>
        <div id="elfinder" ref="elfinder"></div>

    </div>

</template>

<script>

    export default {
        name: "ElfinderIndex.vue",

        data() {
            return {

            }
        },
        mounted() {
            $(this.$refs.elfinder).elfinder({
                debug: false,
                //lang: 'en',
                width: '100%',
                height: '60%',
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

                url: '/elfinder/connector',
                soundPath: '/packages/barryvdh/elfinder/sounds',
                resizable: false
            }).elfinder('instance');
        },
        methods: {

        }
    }
</script>

<style scoped>

</style>
