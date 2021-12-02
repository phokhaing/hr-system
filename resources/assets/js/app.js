
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
const axios = require('axios');
import Vuex from 'vuex';
import VueRouter from "vue-router";
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import vSelect from 'vue-select'
import LaravelPermissionToVueJS from 'laravel-permission-to-vuejs';

// Install BootstrapVue
window.Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
window.Vue.use(LaravelPermissionToVueJS);
window.Vue.use(IconsPlugin)
window.Vue.use(Vuex);
window.Vue.use(VueRouter);

Vue.config.productionTip = false

import store from "./store";
import routes from './router'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('v-select', vSelect);

Vue.component('filter-form-report', require('./components/FilterFormReport.vue'));
Vue.component('company-selection', require('./components/CompanySelection.vue'));
Vue.component('branch-department-selection', require('./components/BranchDepartmentSelection.vue'));
Vue.component('position-selection', require('./components/PositionSelection.vue'));
Vue.component('pagination', require('laravel-vue-pagination'));

/**
 * Training Module Components
 */
Vue.component('create-course-content', require('./components/training_module/CreateCourseContent.vue'));

Vue.component('examination', require('./views/TrainingModule/Examination.vue'));
Vue.component('questionList', require('./components/training_module/examination/QuestionList.vue'));
Vue.component('detailExamination', require('./components/training_module/examination/DetailExamination.vue'));


// ===============================
//          Payroll Module      //
// ===============================
Vue.component('payroll-page', require('./views/Payroll/PayrollPage.vue'));
Vue.component('payroll-full-month-page', require('./views/Payroll/PayrollFullMonthPage.vue'));
Vue.component('view-posted-payroll-full-month-page', require('./components/payroll_module/ViewPostedPayrollFullMonth.vue'));

let app = new Vue({
    el: '#app1',
    store,
    router: new VueRouter(routes),
});

global.app = app;
