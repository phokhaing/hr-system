import Vue from "vue";
import Vuex from "vuex";
Vue.use(Vuex);

import utility from './modules/utility';
import examination from './modules/examination';
import payroll from "./modules/payroll";

export default new Vuex.Store({
    state: {},
    mutations: {},
    actions: {},
    modules: {
        utility,
        exam: examination,
        payroll: payroll
    }
})