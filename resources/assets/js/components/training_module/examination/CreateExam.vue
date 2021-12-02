<template>
  <div>
    <b-breadcrumb :items="items"></b-breadcrumb>

    <div class="panel panel-default">
      <div class="panel-heading"><i class="fa fa-file"></i> Create Exam</div>
      <div class="panel-body">
        <form @submit.prevent>
          <div class="row">
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label for="selectCourse">Select Course <span class="text-danger">*</span></label>
              <select v-model="examData.selectCourse" class="form-control" id="selectCourse" :required="true"
                      @change="allCoursesContents(examData.selectCourse)">
                <option v-bind:value="null">  All  </option>
                <option v-for="course in getCourses" v-bind:value="course.id" :key="course.id">{{ course.title }}</option>
              </select>
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label for="selectCourseContent">Select Course Content <span class="text-danger">*</span></label>
              <select v-model="examData.selectCourseContent" class="form-control" name="select_course_content"
                      id="selectCourseContent" :required="true" >
                <option v-bind:value="null"> All </option>
                <option v-for="courseContent in getCourseContents" v-bind:value="courseContent.id"
                :key="courseContent.id">{{ courseContent.title }}</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label for="duration">Duration <span class="text-danger">*</span></label>
              <input type="number" v-model="examData.duration" id="duration" class="form-control" value="0">
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label for="grade">Grade <span class="text-danger">*</span></label>
              <input type="number" v-model="examData.grade" id="grade" class="form-control" value="0">
            </div>
          </div>

          <div class="row">
            <div class="form-group col-sm-12 col-md-12 col-lg-12">
              <label for="description">Description <span class="text-danger">*</span></label>
              <textarea name="description" id="description" rows="5" v-model="examData.description" class="form-control"></textarea>
            </div>
          </div>

          
          <div v-if="errors.length"> 
            <div v-for="error in errors" :key="error" role="alert" class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              {{ error }}
            </div>
          </div>
          

          <div class="row">
            <div class="form-group col-sm-12 col-md-12 col-lg-12">
              <button class="btn btn-sm btn-primary" id="saveExam" @click="createExam()">
                <i class="fa fa-save" aria-hidden="true"> </i> CREATE
              </button>
            </div>
          </div>

        </form>
      </div>
    </div>

  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
  name: "CreateExam",
  computed: {
    ...mapGetters({
      getCourses: 'exam/getCourses',
      getCourseContents: 'exam/getCourseContents',
      getExam: 'exam/getExam',
    }),
  },
  data() {
    return {
      exam: null,
      errors: [],
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
          to: '/hrtraining/examination-setting'
        },
        {
          text: 'Create',
          href: '#'
        }
      ],
      examData: {
        selectCourse: null,
        selectCourseContent: null,
        duration: null,
        grade: null,
        description: null
      },
    }
  },
  methods: {
    // map this.setCompany() to this.$store.dispatch('setCompany')
    ...mapActions({
      setCourses: 'exam/setCourses',
      setCourseContents: 'exam/setCourseContents',
      CREATE_EXAM: 'exam/CREATE_EXAM',
    }),
    async createExam() {

      this.errors = [];

      if (this.examData.selectCourse == null) {
        this.errors.push('សូមជ្រើសរើស Course.');
      }

      if (this.examData.selectCourseContent == null) {
        this.errors.push('សូមជ្រើសរើស Course Content.');
      }

      if (this.examData.duration == null) {
        this.errors.push('សូមបញ្ចូល Duration.');
      } else if (Number(this.examData.duration) < 1) {
          this.errors.push('Duration ត្រូវតែធំជាង 1.');
      } else if (Number(this.examData.duration) > 99999) {
        this.errors.push('Duration ត្រូវតែតូចជាង 99999.');
      } 
      
      if (this.examData.grade == null) {
        this.errors.push('សូមបញ្ចូល Grade.');
      } else if (Number(this.examData.grade) < 1) {
        this.errors.push('Grade ត្រូវតែធំជាង 1.');
      } else if (Number(this.examData.grade) > 99999) {
        this.errors.push('Grade ត្រូវតែតូចជាង 99999.');
      } 

      if (this.examData.description == null) {
        this.errors.push('សូមបញ្ចូល Description.');
      }

      // If have no errors
      if (! this.errors.length) {
        await this.CREATE_EXAM(this.examData);
        this.$router.push({
          name: 'examination-setting',
        })
      }
      
    },
    allCoursesContents(courseId) {
      console.log(courseId);
      this.setCourseContents(courseId);
    }
  },
  mounted() {
    // dispatch action
    this.setCourses();
  },
}
</script>

<style scoped>

</style>