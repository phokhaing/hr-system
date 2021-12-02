<template>
  <div>
    <b-breadcrumb :items="items"></b-breadcrumb>

    <div class="row">
      <div class="col-sm-12 col-md-12">
        <div class="breadcrumb">
          <router-link class="btn btn-sm btn-success" :to="{ name: 'examination-setting-create' }">CREATE EXAM</router-link>
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="panel-body">
              <div style="overflow-x: auto">
                <table class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>Action</th>
                      <th>Course Content</th>
                      <th>Duration</th>
                      <th>Grade</th>
                      <th>Description</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="EXAM in GET_EXAMS.data" :key="EXAM.id">
                      <td>
                        <button class="btn btn-sm btn-danger" @click="deleteExam(EXAM.id)"><i class="fa fa-trash"></i></button>
                        <button class="btn btn-sm btn-default" @click="editExam(EXAM)"><i class="fa fa-edit"></i></button>
                      </td>
                      <td>{{ (EXAM.course_content == null) ? '' : EXAM.course_content.json_data.title }}</td>
                      <td>{{ EXAM.json_data.duration }}</td>
                      <td>{{ EXAM.json_data.grade }}</td>
                      <td>{{ EXAM.json_data.description }}</td>
                    </tr>
                  </tbody>
                </table>
              
                <pagination :data="GET_EXAMS" @pagination-change-page="resultExam"></pagination>
                
          
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import axios from 'axios';

export default {
  name: "Examination",
  computed: {
    // map `this.GET_EXAMS` to `this.$store.getters.['exam/GET_EXAMS']`
    ...mapGetters({
      GET_EXAMS: 'exam/GET_EXAMS',
    }),
  },
  data() {
    return {
      items: [
        {
          text: 'Dashboard',
          href: '/dashboard'
        },
        {
          text: 'Hrtraining',
          href: '/hrtraining'
        },
        {
          text: 'Examination-setting',
          href: '/examination-setting'
        }
      ]
    }
  },
  methods: {
    // map this.setCompany() to this.$store.dispatch('setCompany')
    ...mapActions({
      SET_EXAMS: 'exam/SET_EXAMS',
    }),
    resultExam(page) {
      this.SET_EXAMS(page);
    },
    deleteExam(exam_id) {
      Swal.fire({
            title: 'Are you sure?',
            text: "You will delete this record!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                axios.delete(`/hrtraining/examination-setting/${exam_id}`)
                .then(res => {
                    if (res.data === 'deleted') {
                      // After delete we call get state again to reload data
                      this.resultExam(this.GET_EXAMS);
                       Swal.fire({
                        type: 'success',
                        title: 'Your record has been deleted.',
                        showConfirmButton: false,
                        timer: 2500
                      })
                    }
                        
                }).catch(err => {
                    console.log(err)
                })
            }
        });
    },
    editExam(exam) {
      this.$router.push({
        name: "examination-setting-edit",
        params: {
          exam
        }
      })
    }
  },
  mounted() {
    // dispatch action
    this.resultExam();
  },
  beforeUpdate() {
    console.log("beforeUpdate");
  },
  updated () {
    console.log("updated");
  }
}
</script>

<style scoped>

</style>