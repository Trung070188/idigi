<template>
    <img v-if="src" class="q-image" @click="showImage()" ref="img" :src="src" :alt="alt" :class="className" :style="styles">
</template>

<script>
    import Viewer from 'viewerjs';

    function showPhoto(url) {
        const doc = document;
        let ul = doc.createElement('ul');
        let li = doc.createElement('li');
        li.innerHTML = '<img src="' + url + '"/>';
        ul.appendChild(li);

        var viewer = new Viewer(ul, {
            zIndex: 10000,
            hidden: function () {
                viewer.destroy();
            },
        });
        viewer.show();
    }

    export default {
        name: "QImage",
        props: ['src', 'alt', 'className', 'styles'],
        methods: {
            showImage() {
                showPhoto(this.src);
            }
        },
        mounted() {
            // View an image.
            /*const viewer = new Viewer(this.$refs.img, {
                inline: true,
                viewed() {
                    viewer.zoomTo(1);
                },
            });*/
        }
    }
</script>

<style >
    .q-image {
        cursor: pointer;
    }
</style>
