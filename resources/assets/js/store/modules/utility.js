import axios from 'axios';

// Initial state
const state = {
    staffs : [],
    companies: [],
    branchDepartment: [],
    positions: [],
    staffsInBranchDepartment : [],
    allCompanies : []
}

// Get data from state after mutation.
const getters = {
    getStaff: (state) => state.staffs,
    getCompany: (state) => state.companies,
    getBranchDepartment: state => state.branchDepartment,
    getPosition: state => state.positions,
    getStaffInBranchDepartment : state => state.staffsInBranchDepartment,
    getAllCompanies: (state) => state.allCompanies
};

// Mutate data of state
const mutations = {
    // function (state, payload) => return value state
    setStaff (state, payload) {
        // mutate state
        state.staffs = payload.data.data
    },
    // function (state, payload) => return value state
    setCompany (state, payload) {
        // mutate state
        state.companies = payload.data.data
    },
    setBranchDepartment(state, payload) {
        state.branchDepartment = payload.data.data
    },
    setPosition(state, payload) {
        state.positions = payload.data.data
    },
    setStaffInBranchDepartment(state, payload){
        state.staffsInBranchDepartment = payload.data.data
    },
    setAllCompanies (state, payload) {
        // mutate state
        state.allCompanies = payload.data.data
    },
};

// Fetch data and then commit to mutation
const actions = {
    async setStaff({ commit }) {
        const response = await axios.get("pension-fund/staff-list");
        commit('setStaff', {
            data: response.data
        });
    },
    async setCompany({ commit }) {
        const response = await axios.get("/company/current");
        commit('setCompany', {
            data: response.data
        });
    },
    async setAllCompanies({ commit }) {
        const response = await axios.get("/company/all");
        commit('setAllCompanies', {
            data: response.data
        });
    },
    setBranchDepartment({ commit }, companyCode) {
        axios.get("/branch-and-department/"+companyCode+"/all")
            .then(response => {
                commit('setBranchDepartment', {
                    data: response.data
                })
            }).catch(err => {
            console.log(err)
        })
    },
    setPosition({ commit }, companyCode) {
        axios.get("/position/"+companyCode+"/all")
            .then(response => {
                commit('setPosition', {
                    data: response.data
                })
            }).catch(err => {
            console.log(err)
        })
    },
    setStaffInBranchDepartment({ commit }, code) {
        axios.get("/branch-and-department/"+code.company + "/" +code.branchDepartment+"/staffs")
            .then(response => {
                console.log('setStaffInBranchDepartment success: '+ response.data);
                commit('setStaffInBranchDepartment', {
                    data: response.data
                })
            }).catch(err => {
            console.log(err)
        })
    },
};

export default {
    state,
    getters,
    mutations,
    actions
}




