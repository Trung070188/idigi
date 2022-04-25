<template>
    <div class="d-flex flex-column flex-root">
        <SideBar/>
        <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
            <NavBar/>
            <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                <div class="d-flex flex-column-fluid" style="min-height: 600px">
                    <Main/>
                </div>
            </div>
            <Footer/>
        </div>

    </div>
</template>

<script>
    import SideBar from '../includes/SideBar';
    import NavBar from "../includes/NavBar";
    import registry from "../../registry";
    import {botWarn} from "../../utils";
    import Footer from "../includes/Footer";


    const componentName = window.$componentName;

    if (!componentName) {
        botWarn('You need to set component in controller');
    }

    const component = registry[componentName];

    export default {
        name: "AppLayout.vue",
        components: {Footer, NavBar, SideBar, Main: component},
        mounted() {
           /* $('.dropdown').click(function(){

                $('.dropdown-menu').toggleClass('show');

            });*/
        },
        data() {
            return {}
        }
    }
</script>

<style scoped>

</style>
