/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('./bootstrap');

import Vue from 'vue';
import { botInfo, botWarn } from "./utils";
import AppLayout from "./admin/layouts/AppLayout";
window.Vue = require('vue');
window.toastr = require('toastr');

Vue.component('Paginate', require('./components/Paginate').default);
Vue.component('SwitchButton', require('./components/SwitchButton').default);
Vue.component('Daterangepicker', require('./components/Daterangepicker').default);
Vue.component('RichtextEditor', require('./components/RichtextEditor').default);
Vue.component('ErrorLabel', require('./components/ErrorLabel').default);
Vue.component('SaveButton', require('./components/SaveButton').default);
Vue.component('ActionBar', require('./admin/includes/ActionBar').default);
Vue.component('QSelect', require('./components/QSelect').default);
Vue.component('QImage', require('./components/QImage').default);

require('./mixin');

function main() {
    const el = document.getElementById('root-app');
    if (!el) {
        botWarn('#root-app not found');
        return;
    }

    const app = new Vue({
        el: el,
        mounted() {
            console.log('App Mounted');

            KTApp.init();
            KTMenu.createInstances();
            KTDrawer.createInstances();
            KTScroll.createInstances();
            KTScrolltop.createInstances();
            KTSticky.createInstances();
            KTDialer.createInstances();
            KTImageInput.createInstances();
            KTPasswordMeter.createInstances();
            KTSwapper.createInstances();
            KTToggle.createInstances();
        },
        render(createElement) {
            return createElement('AppLayout');
        },
        components: {
            AppLayout
        }
    });
}

try {
    document.addEventListener('DOMContentLoaded', main);
} catch (err) {
    console.error(err);
}
