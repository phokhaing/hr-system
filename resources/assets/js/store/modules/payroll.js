import axios from 'axios';
import {ARE_YOU_AUDIT_ALREADY, FAILED, SUCCESSFUL} from "../constant";

// Initial state
const state = {
    checkingHalfPayroll: [],
    tempHalfPayroll: {},
    halfPayroll: {},
    checkingFullPayroll: [],
    tempFullPayroll: {},
    fullPayroll: {},
    fullPayrollPosted: [],
    halfPayrollPosted: [],
    exchangeRate: {}
}

// Get data from state after mutation.
const getters = {
    getCheckingHalfPayroll: state => state.checkingHalfPayroll,
    getTempHalfPayroll: state => state.tempHalfPayroll,
    getHalfPayroll: state => state.halfPayroll,
    getCheckingFullPayroll: state => state.checkingFullPayroll,
    getTempFullPayroll: state => state.tempFullPayroll,
    getFullPayroll: state => state.fullPayroll,
    getFullPayrollPosted: state => state.fullPayrollPosted,
    getHalfPayrollPosted: state => state.halfPayrollPosted,
    getExchangeRate: state => state.exchangeRate
};

// Mutate data of state
const mutations = {
    // function (state, payload) => return value state
    SET_CHECKING_HALF_PAYROLL(state, payload) {
        state.checkingHalfPayroll = payload.data.data
    },
    SET_TEMP_HALF_PAYROLL(state, payload) {
        state.tempHalfPayroll = payload.data.data
    },
    SET_HALF_PAYROLL(state, payload) {
        state.halfPayroll = payload.data.data
    },
    // Set to list of checking payroll.
    SET_CHECKING_FULL_PAYROLL(state, payload) {
        state.checkingFullPayroll = payload.data
    },
    // Save data into table temp_transaction.
    CREATE_TEMP_FULL_PAYROLL(state, payload) {
        state.tempFullPayroll = payload.data.data
    },
    // Save data into table transaction.
    CREATE_FULL_PAYROLL(state, payload) {
        state.fullPayroll = payload.data.data
    },
    SET_POSTED_PAYRLL_FULL_MONTH(state, payload){
        state.fullPayrollPosted = payload.data
    },
    SET_POSTED_PAYRLL_HALF_MONTH(state, payload){
        state.halfPayrollPosted = payload.data
    },
    SET_EXCHANGE_RATE(state, payload){
        state.exchangeRate = payload.data
    }
};

// Fetch data and then commit to mutation
const actions = {

    // Save data into table temp_transaction.
    async CREATE_TEMP_HALF_PAYROLL({commit, dispatch}, data) {
        Swal.fire({
            title:"តើអ្នកចង់ឆែក Payroll មែនទេ?",
            text: "Checking Payroll អាចធ្វើបានតែម្តងគត់ក្នុង១ខែ!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'បាទ/ចាស៎ មែនហើយ',
            cancelButtonText: 'អត់ទេ'
        }).then(response => {
            if (response.value) {
                try {
                    axios.post('/payroll/checking-half-month', data)
                        .then(function (response) {
                            if (response.data.status) {

                                dispatch('SET_TEMP_HALF_PAYROLL') // refresh SET_TEMP_HALF_PAYROLL

                                Swal.fire({
                                    title: SUCCESSFUL,
                                    text: "Checking half payroll successfully.",
                                    type: 'success',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }else{
                                $(".loading").fadeOut("fast");
                                Swal.fire({
                                    title: FAILED,
                                    text: "Sorry, Something went wrong while checking payroll! Please try again later. \nError: " + response.data.message,
                                    type: 'warning',
                                    showConfirmButton: false,
                                    showCancelButton: true,
                                    cancelButtonColor: '#d33',
                                    cancelButtonText: 'ពិនិត្យឡើងវិញ'
                                });
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                } catch (e) {
                    console.log(e);
                }
            }
        })
    },
    
    //Clear checking list half month
    async CLEAR_CHECKING_LIST_HALF_MONTH({commit, dispatch}, data) {
        Swal.fire({
            title:"Warining!",
            text: "តើអ្នកពិតជាចង់លុបចោលទិន្នន័យដែលបាន Checking ហើយមែនឬ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'បាទ/ចាស៎ មែនហើយ',
            cancelButtonText: 'អត់ទេ'
        }).then(response => {
            if (response.value) {
                try {
                    axios.post('/payroll/clear-checking-list-half-month')
                        .then(function (response) {
                            if (response.data.status) {

                                dispatch('SET_TEMP_HALF_PAYROLL') // refresh SET_TEMP_HALF_PAYROLL

                                Swal.fire({
                                    title: SUCCESSFUL,
                                    text: "Data were cleared!",
                                    type: 'success',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }else{
                                $(".loading").fadeOut("fast");
                                Swal.fire({
                                    title: FAILED,
                                    text: "Sorry, Something went wrong while clear checking list! Please try again later. \nError: " + response.data.message,
                                    type: 'warning',
                                    showConfirmButton: false,
                                    showCancelButton: true,
                                    cancelButtonColor: '#d33',
                                    cancelButtonText: 'ពិនិត្យឡើងវិញ'
                                });
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                } catch (e) {
                    console.log(e);
                }
            }
        })
    },

    //Clear checking list half month
    async CLEAR_CHECKING_LIST_FULL_MONTH({commit, dispatch}, data) {
        Swal.fire({
            title:"Warining!",
            text: "តើអ្នកពិតជាចង់លុបចោលទិន្នន័យដែលបាន Checking ហើយមែនឬ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'បាទ/ចាស៎ មែនហើយ',
            cancelButtonText: 'អត់ទេ'
        }).then(response => {
            if (response.value) {
                try {
                    axios.post('/payroll/clear-checking-list-full-month')
                        .then(function (response) {
                            if (response.data.status) {

                                dispatch('SET_TEMP_FULL_MONTH_PAYROLL') // refresh SET_TEMP_HALF_PAYROLL

                                Swal.fire({
                                    title: SUCCESSFUL,
                                    text: "Data were cleared!",
                                    type: 'success',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }else{
                                $(".loading").fadeOut("fast");
                                Swal.fire({
                                    title: FAILED,
                                    text: "Sorry, Something went wrong while clear checking list! Please try again later. \nError: " + response.data.message,
                                    type: 'warning',
                                    showConfirmButton: false,
                                    showCancelButton: true,
                                    cancelButtonColor: '#d33',
                                    cancelButtonText: 'ពិនិត្យឡើងវិញ'
                                });
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                } catch (e) {
                    console.log(e);
                }
            }
        })
    },

    // Save data into table transaction.
    async CREATE_HALF_PAYROLL({commit, dispatch}, params) {
        Swal.fire({
            title: ARE_YOU_AUDIT_ALREADY,
            text: "Payroll ដែលអ្នក Confirmed រួចហើយគឺមិនអាចត្រឡប់ក្រោយបានទេ!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'បាទ/ចាស៎ រួចហើយ',
            cancelButtonText: 'មិនទាន់'
        }).then((result) => {
            if (result.value) {
                axios.post('/payroll/post-half-payroll', params)
                    .then(response => {
                        $(".loading").fadeOut("fast");
                        if (response.data.status === 1) {
                            dispatch('SET_TEMP_HALF_PAYROLL') // refresh SET_TEMP_HALF_PAYROLL
                            Swal.fire({
                                title: SUCCESSFUL,
                                text: "Payrolls were posted successfully.",
                                type: 'success',
                                showConfirmButton: false,
                                timer: 2000
                            })
                        }else{
                            Swal.fire({
                                title: FAILED,
                                text: response.data.message,
                                type: 'warning',
                                showConfirmButton: false,
                                showCancelButton: true,
                                cancelButtonColor: '#d33',
                                cancelButtonText: 'ពិនិត្យឡើងវិញ'
                            })
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        })
    },

    // Set to list of checking payroll.
    async SET_TEMP_HALF_PAYROLL({ commit }, filter_params) {
        await axios.get('/payroll/get-checking-half-month', {
            params: filter_params
        })
        .then(response => {
            $(".loading").fadeOut("fast");
            console.log("1111"+response.status)
            if(response.data.status === 1){
                console.log(response.data.status + " "  + response.data.message + ""+ response.data.data)
                commit('SET_CHECKING_HALF_PAYROLL', {
                    data: response.data
                })
            }else{
            
                            Swal.fire({
                                title: FAILED,
                                text: response.data.message,
                                type: 'warning',
                                showConfirmButton: false,
                                showCancelButton: true,
                                cancelButtonColor: '#d33',
                                cancelButtonText: 'ពិនិត្យឡើងវិញ'
                            })
            }
           
        })
        .catch(err => {
            console.log(err)
        })
    },

    // Block or UnBlock Half Temp Transaction Payroll
    async BLOCK_OR_UNBLOCK_HALF_PAYROLL({commit, dispatch}, payload) {
        console.log('BLOCK_OR_UNBLOCK_HALF_PAYROLL: '+ payload);
        let message = '';
        let messageComplete = '';
        if(payload.item.is_block == 'true'){
            message = 'Are you sure want to UnBlock this payroll?';
            messageComplete = 'UnBlock payroll successfully';
        }else{
            message = 'Are you sure want to Block this payroll?';
            messageComplete = 'Block payroll successfully';
        }

        Swal.fire({
            title: 'Block/UnBlock Payroll',
            text: message,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'បាទ/ចាស៎ មែនហើយ',
            cancelButtonText: 'អត់ទេ'
        }).then(response => {
            if (response.value) {
                try {
                    $(".loading").fadeIn("fast");
                    axios.post('/payroll/block-un-block-transaction-half-month-api', payload)
                        .then(function (response) {
                            if (response.data.status) {

                                if(payload.is_block_temp_transactin){
                                    dispatch('SET_TEMP_HALF_PAYROLL')
                                }else{
                                    dispatch('GET_POSTED_PAYRLL_HALF_MONTH')
                                }

                                Swal.fire({
                                    title: SUCCESSFUL,
                                    text: messageComplete,
                                    type: 'success',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }else{
                                Swal.fire({
                                    title: FAILED,
                                    text: "Something went wrong!",
                                    type: 'warning',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                } catch (e) {
                    console.log(e);
                }
            }
        })
    },
    
    // Block or UnBlock Temp Transaction Payroll
    async BLOCK_OR_UNBLOCK_PAYROLL({commit, dispatch}, payload) {
        console.log('BLOCK_OR_UNBLOCK_PAYROLL: '+ payload);
        let message = '';
        let messageComplete = '';
        if(payload.item.is_block == 'true'){
            message = 'Are you sure want to UnBlock this payroll?';
            messageComplete = 'UnBlock payroll successfully';
        }else{
            message = 'Are you sure want to Block this payroll?';
            messageComplete = 'Block payroll successfully';
        }

        Swal.fire({
            title: 'Block/UnBlock Payroll',
            text: message,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'បាទ/ចាស៎ មែនហើយ',
            cancelButtonText: 'អត់ទេ'
        }).then(response => {
            if (response.value) {
                try {
                    $(".loading").fadeIn("fast");
                    axios.post('/payroll/block-un-block-transaction-api', payload)
                        .then(function (response) {
                            if (response.data.status) {

                                if(payload.is_block_temp_transactin){
                                    dispatch('SET_TEMP_FULL_MONTH_PAYROLL')
                                }else{
                                    dispatch('GET_POSTED_PAYRLL_FULL_MONTH')
                                }

                                Swal.fire({
                                    title: SUCCESSFUL,
                                    text: messageComplete,
                                    type: 'success',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }else{
                                Swal.fire({
                                    title: FAILED,
                                    text: "Something went wrong!",
                                    type: 'warning',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                } catch (e) {
                    console.log(e);
                }
            }
        })
    },

    // Save data into table temp_transaction.
    async CREATE_TEMP_FULL_MONTH_PAYROLL({commit, dispatch}, data) {
        Swal.fire({
            title:"តើអ្នកចង់ឆែក Payroll មែនទេ?",
            text: "Checking Payroll អាចធ្វើបានតែម្តងគត់ក្នុង១ខែ!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'បាទ/ចាស៎ មែនហើយ',
            cancelButtonText: 'អត់ទេ'
        }).then(response => {
            if (response.value) {
                try {
                    $(".loading").fadeIn("fast");
                    axios.post('/payroll/checking-payroll-full-month', data)
                        .then(function (response) {
                            if (response.data.status) {
                                dispatch('SET_TEMP_FULL_MONTH_PAYROLL')

                                Swal.fire({
                                    title: SUCCESSFUL,
                                    text: "Checking full month payroll successfully.",
                                    type: 'success',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }else{
                                $(".loading").fadeOut("fast");
                                Swal.fire({
                                    title: FAILED,
                                    text: "Sorry, Something went wrong while checking payroll! Please try again later. \nError: " + response.data.message,
                                    type: 'warning',
                                    showConfirmButton: false,
                                    showCancelButton: true,
                                    cancelButtonColor: '#d33',
                                    cancelButtonText: 'ពិនិត្យឡើងវិញ'
                                });
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                } catch (e) {
                    console.log(e);
                }
            }
        })
    },

    // Get temp_transaction and set to list of checking payroll.
    async SET_TEMP_FULL_MONTH_PAYROLL({ commit }, filter_params) {
        await axios.get('/payroll/get-temp-full-month-payroll', {
            params: filter_params
        })
        .then(response => {
            $(".loading").fadeOut("fast");
            console.log("1111"+response.status)
            if(response.data.status === 1){
                console.log(response.data.status + " "  + response.data.message + ""+ response.data.data)
                commit('SET_CHECKING_FULL_PAYROLL', {
                    data: response.data.data.items
                });
                //Set exchange rate
                commit('SET_EXCHANGE_RATE', {
                    data: response.data.data.exchange_rate
                })
            }else{
            
                            Swal.fire({
                                title: FAILED,
                                text: response.data.message,
                                type: 'warning',
                                showConfirmButton: false,
                                showCancelButton: true,
                                cancelButtonColor: '#d33',
                                cancelButtonText: 'ពិនិត្យឡើងវិញ'
                            })
            }
           
        }).catch(err => {
            $(".loading").fadeOut("fast");
            console.log(err)
        })
    },

    // Save data into table transaction.
    async CREATE_FULL_PAYROLL({commit, dispatch}, post_body) {
        Swal.fire({
            title: ARE_YOU_AUDIT_ALREADY,
            text: "Payroll ដែលអ្នក Confirmed រួចហើយគឺមិនអាចត្រឡប់ក្រោយបានទេ!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'បាទ/ចាស៎ រួចហើយ',
            cancelButtonText: 'មិនទាន់'
        }).then((result) => {
            if (result.value) {
                $(".loading").fadeIn("fast");
                axios.post('/payroll/set-full-payroll', post_body)
                    .then(response => {
                        if (response.data.status === 1) {

                            dispatch('SET_TEMP_FULL_MONTH_PAYROLL') // refresh SET_TEMP_HALF_PAYROLL
                            $(".loading").fadeOut("fast");
                            Swal.fire({
                                title: SUCCESSFUL,
                                text: "Payrolls were posted successfully.",
                                type: 'success',
                                showConfirmButton: false,
                                timer: 2000
                            })
                        }else{
                            $(".loading").fadeOut("fast");
                            Swal.fire({
                                title: FAILED,
                                text: response.data.message,
                                type: 'warning',
                                showConfirmButton: false,
                                showCancelButton: true,
                                cancelButtonColor: '#d33',
                                cancelButtonText: 'ពិនិត្យឡើងវិញ'
                            })
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        })
    },
    
    async GET_POSTED_PAYRLL_HALF_MONTH({ commit }, filter_params) {
        await axios.get('/payroll/view-posted-half-payroll-api', {
            params: filter_params
        })
        .then(response => {
            $(".loading").fadeOut("fast");
            if(response.data.status === 1){
                commit('SET_POSTED_PAYRLL_HALF_MONTH', {
                    data: response.data.data
                })
            }else{
                Swal.fire({
                    title: FAILED,
                    text: response.data.message,
                    type: 'warning',
                    showConfirmButton: false,
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'ពិនិត្យឡើងវិញ'
                })
            }
          
        }).catch(err => {
            console.log(err)
        })
    },

    async GET_POSTED_PAYRLL_FULL_MONTH({ commit }, filter_params) {
        await axios.get('/payroll/view-posted-payroll-api', {
            params: filter_params
        })
        .then(response => {
            $(".loading").fadeOut("fast");
            if(response.data.status === 1){
                commit('SET_POSTED_PAYRLL_FULL_MONTH', {
                    data: response.data.data
                })
            }else{
                Swal.fire({
                    title: FAILED,
                    text: response.data.message,
                    type: 'warning',
                    showConfirmButton: false,
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'ពិនិត្យឡើងវិញ'
                })
            }
          
        }).catch(err => {
            console.log(err)
        })
    },

    async UN_POST_PAYRLL_FULL_MONTH({commit, dispatch}, postBody){
        Swal.fire({
            title: 'Un Post Payroll',
            text: "Are you sure want to Un Post payroll?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'បាទ/ចាស៎ រួចហើយ',
            cancelButtonText: 'មិនទាន់'
        }).then((result) => {
            if (result.value) {
                $(".loading").fadeIn("fast");
                axios.post('/payroll/un-post-payroll-full-mont-api', postBody)
                    .then(response => {
                        console.log('success' + response.data.status);
                        if (response.data.status === 1) {

                            dispatch('GET_POSTED_PAYRLL_FULL_MONTH')
                            $(".loading").fadeOut("fast");
                            Swal.fire({
                                title: SUCCESSFUL,
                                text: "Payrolls have been un posted successfully.",
                                type: 'success',
                                showConfirmButton: false,
                                timer: 2000
                            })
                        }else{
                            $(".loading").fadeOut("fast");
                            Swal.fire({
                                title: FAILED,
                                text: response.data.message,
                                type: 'warning',
                                showConfirmButton: false,
                                showCancelButton: true,
                                cancelButtonColor: '#d33',
                                cancelButtonText: 'ពិនិត្យឡើងវិញ'
                            })
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        })
    },
    
    async UN_POST_PAYRLL_HALF_MONTH({commit, dispatch}, postBody){
        Swal.fire({
            title: 'Un Post Payroll',
            text: "Are you sure want to Un Post payroll?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'បាទ/ចាស៎ រួចហើយ',
            cancelButtonText: 'មិនទាន់'
        }).then((result) => {
            if (result.value) {
                $(".loading").fadeIn("fast");
                axios.post('/payroll/un-post-payroll-half-month-api', postBody)
                    .then(response => {
                        console.log('success' + response.data.status);
                        if (response.data.status === 1) {

                            dispatch('GET_POSTED_PAYRLL_HALF_MONTH')
                            $(".loading").fadeOut("fast");
                            Swal.fire({
                                title: SUCCESSFUL,
                                text: "Payrolls have been un posted successfully.",
                                type: 'success',
                                showConfirmButton: false,
                                timer: 2000
                            })
                        }else{
                            $(".loading").fadeOut("fast");
                            Swal.fire({
                                title: FAILED,
                                text: response.data.message,
                                type: 'warning',
                                showConfirmButton: false,
                                showCancelButton: true,
                                cancelButtonColor: '#d33',
                                cancelButtonText: 'ពិនិត្យឡើងវិញ'
                            })
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        })
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}




