import axios from 'axios';

// Initial state
const state = {
    courses: [],
    courseContents: [],
    questions: {},
    addQuestion: false,
    updateInputExam: false,
    exam: null,
    exams: {},
}

// Get data from state after mutation.
const getters = {
    getCourses: (state) => state.courses,
    getCourseContents: state => state.courseContents,
    getAddQuestion: state => state.addQuestion,
    getUpdateInputExam: state => state.updateInputExam,
    getExam: state => state.exam,
    GET_EXAMS: state => state.exams,
    GET_QUESTIONS: state => state.questions,
};

// Mutate data of state
const mutations = {
    // function (state, payload) => return value state
    setCourses (state, payload) {
        // mutate state
        state.courses = payload.data.data
    },
    setCourseContents(state, payload) {
        state.courseContents = payload.data.data
    },
    setQuestions(state, payload) {
        state.questions = payload.data.data
    },
    setAddQuestion(state, payload) {
        state.addQuestion = payload.data
    },
    setUpdateInputExam(state, payload) {
        state.updateInputExam = payload.data
    },
    setExam(state, payload) {
        state.exam = payload.data
    },
    SET_EXAMS(state, payload) {
        state.exams = payload.data
    },
    SET_QUESTIONS(state, payload) {
        state.questions = payload.data
    }
};

// Fetch data and then commit to mutation
const actions = {
    async setCourses({ commit }) {
        const response = await axios.get("/hrtraining/course-lists");
        commit('setCourses', {
            data: response.data
        });
    },
    async setCourseContents({ commit }, courseId) {
        await axios.get("/hrtraining/course-detail-api/" + courseId)
            .then(response => {
                commit('setCourseContents', {
                    data: response.data
                })
            }).catch(err => {
            console.log(err)
        })
    },
    setAddQuestion({ commit }, value) {
        commit('setAddQuestion', {
            data: value
        });
    },
    setUpdateInputExam({ commit }, value) {
        commit('setUpdateInputExam', {
            data: value
        });
    },
    setExam({ commit }, exam_object) {
        commit('setExam', {
            data: exam_object
        });
    },
    CREATE_EXAM({commit}, exam) {
        try {
            return axios.post('/hrtraining/examination-setting/store', {
                data: exam
            })
            .then(function (response) {
                console.log(response.data.data);
                if (response.data.status === "success") {
                    commit('setExam', {
                        data: response.data.data
                    })
                    Swal.fire({
                        type: 'success',
                        title: 'Create exam successfully.',
                        showConfirmButton: false,
                        timer: 2500,
                        showCloseButton: true,
                    });
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        } catch (error) {
            console.error(error);
        }
    },
    UPDATE_EXAM({commit}, exam) {
        try {
            return axios.post('/hrtraining/examination-setting/update', {
                data: exam
            })
            .then(function (response) {
                console.log(response.data.data);
                if (response.data.status === "success") {
                    commit('setExam', {
                        data: response.data.data
                    })
                    Swal.fire({
                        type: 'success',
                        title: 'Exam was updated successfully.',
                        showConfirmButton: false,
                        timer: 2500,
                        showCloseButton: true,
                    });
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        } catch (error) {
            console.error(error);
        }
    },
    async SET_EXAMS({ commit }, page) {
        await axios.get("/hrtraining/examination-setting/list-api?page=" + page)
            .then(response => {
                commit('SET_EXAMS', {
                    data: response.data.data
                })
            }).catch(err => {
            console.log(err)
        })
    },
    async CREATE_QUESTION({ commit }, question) {
        try {
            return axios.post('/hrtraining/examination-setting/question/store', {
                data: question
            })
            .then(function (response) {
                console.log(response.data.data);
                if (response.data.status === "success") {
                    commit('setExam', {
                        data: response.data.data
                    })
                    Swal.fire({
                        type: 'success',
                        title: 'Create question successfully.',
                        showConfirmButton: false,
                        timer: 2500,
                        showCloseButton: true,
                    });
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        } catch (error) {
            console.error(error);
        }
    },
    async SET_QUESTIONS({ commit }, exam_id) {
        await axios.get("/hrtraining/examination-setting/question/"+exam_id)
            .then(response => {
                commit('SET_QUESTIONS', {
                    data: response.data.data
                })
            }).catch(err => {
                console.log(err)
            })
    },
    async DELETE_QUESTION({commit}, question_id) {
        try {
            return axios.post('/hrtraining/examination-setting/question/destroy', {
                data: question_id
            })
                .then(function (response) {
                    console.log(response.data.data);
                    if (response.data.status === "deleted") {
                        Swal.fire({
                            type: 'success',
                            title: 'Question was updated successfully.',
                            showConfirmButton: false,
                            timer: 2500,
                            showCloseButton: true,
                        });
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        } catch (error) {
            console.error(error);
        }
    },

};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}




