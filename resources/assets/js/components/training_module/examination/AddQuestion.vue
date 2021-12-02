<template>
  <div>
    <b-breadcrumb :items="items"></b-breadcrumb>

    <div class="panel panel-default">
      <div class="panel-heading"><i class="fa fa-file"></i> Edit Exam</div>
      <div class="panel-body">
        <form @submit.prevent>
          <div class="row">
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label for="selectCourse">Select Course <span class="text-danger">*</span></label>
              <select v-model="examData.selectCourse" class="form-control" id="selectCourse" :required="true"
                      @change="allCoursesContents(examData.selectCourse)">
                <option v-bind:value="null"> >> All << </option>
                <option v-for="course in getCourses" v-bind:value="course.id" :key="course.id">{{ course.title }}</option>
              </select>
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label for="selectCourseContent">Select Course Content <span class="text-danger">*</span></label>
              <select v-model="examData.selectCourseContent" class="form-control" name="select_course_content"
                      id="selectCourseContent" :required="true" >
                <option v-bind:value="null"> >> All << </option>
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

          <div class="row">
            <div class="form-group col-sm-12 col-md-12 col-lg-12" v-show="getAddQuestion">
              <button class="btn btn-sm btn-primary" id="btn-add-course-section"
                      data-toggle="modal"
                      data-target="#form-modal-create">
                <i class="fa fa-plus" aria-hidden="true"> </i> Add Question
              </button>
            </div>
            <div class="form-group col-sm-12 col-md-12 col-lg-12" v-show="! getAddQuestion">
              <button class="btn btn-sm btn-primary" id="saveExam" @click="createExam()">
                <i class="fa fa-save" aria-hidden="true"> </i> Submit
              </button>
            </div>
          </div>

          <div class="row">
            <div v-for="questionList in questionLists" class="form-group col-sm-6 col-md-6 col-lg-6">
              <question-list :question="questionList"></question-list>
            </div>
          </div>

        </form>
      </div>
    </div>

    <div class="modal fade in form-create" id="form-modal-create" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Add New Question</h4>
          </div>
          <div class="modal-body" id="section-body">
            <div class="large-bottom-space">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#openQuestion" data-toggle="tab" aria-expanded="true">Open Question</a></li>
                  <li class=""><a href="#closeQuestion" data-toggle="tab" aria-expanded="false">Close Question</a></li>
                  <li class=""><a href="#multipleChoice" data-toggle="tab" aria-expanded="false">Multiple Choice</a></li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="openQuestion">
                    <form class="form-horizontal">
                      <div class="form-group">
                        <label for="openTitle" class="col-sm-2 control-label">Title </label>

                        <div class="col-sm-10">
                          <input type="text" v-model="question.open.title" class="form-control" id="openTitle" placeholder="Question title">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="openPoint" class="col-sm-2 control-label">Point</label>

                        <div class="col-sm-10">
                          <input type="number" v-model="question.open.point" class="form-control" id="openPoint" placeholder="Point of answer">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="button" id="submitOpenQuestion" class="btn btn-success">Add</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="multipleChoice">
                    <form class="form-horizontal">
                      <div class="form-group">
                        <label for="multipleChoiceTitle" class="col-sm-2 control-label">Title </label>

                        <div class="col-sm-10">
                          <input type="text" v-model="question.multipleChoice.title" class="form-control" id="multipleChoiceTitle" placeholder="Question title">
                        </div>
                      </div>
                     
                      <div class="form-group">
                        <label for="multipleChoiceAnswer" class="col-sm-2 control-label">Answer </label>

                        <div class="col-sm-6">
                            <input type="text" class="form-control" placeholder="Description answer">
                        </div>
                        <div class="col-sm-2">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox"> Correct
                            </label>
                          </div>
                        </div>
                        <div class="col-sm-2">
                          <button type="button" @click="addMoreAnswer()" class="btn btn-default pull-right"> Add Answer</button>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          
                        </div>
                      </div>
                    
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="button" id="submitMultipleQuestion" class="btn btn-success">Add</button>
                    
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="closeQuestion">
                    <form class="form-horizontal">
                      <div class="form-group">
                        <label for="closeTitle" class="col-sm-2 control-label">Title </label>

                        <div class="col-sm-10">
                          <input type="text" v-model="question.close.title" class="form-control" id="closeTitle" placeholder="Question title">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="closePoint" class="col-sm-2 control-label">Point</label>

                        <div class="col-sm-10">
                          <input type="number" v-model="question.close.point" class="form-control" id="closePoint" placeholder="Point of answer">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="openPoint" class="col-sm-2 control-label">Correct answer is</label>
                        <div class="col-sm-2">
                          <div class="radio">
                            <label>
                              <input type="radio" name="correct_answer" value="yes" v-model="question.close.answer"> Yes
                            </label>
                          </div>
                        </div>
                        <div class="col-sm-2">
                          <div class="radio">
                            <label>
                              <input type="radio" name="correct_answer" value="no" v-model="question.close.answer"> No
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="button" id="submitCloseQuestion" class="btn btn-success">Add</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
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

export default {
  name: "AddQuestion",
  computed: {
    // map `this.getCompany` to `this.$store.getters.['exam/getCourses']`
    ...mapGetters({
      getCourses: 'exam/getCourses',
      getCourseContents: 'exam/getCourseContents',
      getAddQuestion: 'exam/getAddQuestion',
      getQuestions: 'exam/getQuestions',
    }),
  },
  data() {
    return {
      questionLists: [
        {
          title: "testing",
          description: "my description"
        },
        {
          title: "testing 001",
          description: "my description 001"
        }
      ],
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
          href: '/hrtraining/examination-setting'
        },
        {
          text: 'AddQuestion',
          href: '#'
        }
      ],
      examData: {
        selectCourse: null,
        selectCourseContent: null,
        duration: null,
        grade: null,
      },
      question: {
        open: {
          title: null,
          point: null,
        },
        close: {
          title: null,
          point: null,
          answer: null
        },
        multipleChoice: {

        }
      }
    }
  },
  methods: {
    // map this.setCompany() to this.$store.dispatch('setCompany')
    ...mapActions({
      setCourses: 'exam/setCourses',
      setCourseContents: 'exam/setCourseContents',
      setAddQuestion : 'exam/setAddQuestion',
      setUpdateInputExam: 'exam/setUpdateInputExam',
      CREATE_EXAM: 'exam/CREATE_EXAM',
    }),
    addMoreAnswer() {
      alert(123);
    },
    async createExam() {
      this.setAddQuestion(true);
      await this.CREATE_EXAM(this.examData)
          .then(function (response) {
            console.log(response);
            if (response.data.status === "success") {
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