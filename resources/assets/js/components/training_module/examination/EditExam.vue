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
                      @change="allCoursesContents(examData.selectCourse)" disabled>
                <option v-bind:value="null">  All  </option>
                <option v-for="course in getCourses" v-bind:value="course.id"
                        :key="course.id">{{ course.title }}</option>
              </select>
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label for="selectCourseContent">Select Course Content <span class="text-danger">*</span></label>
              <select v-model="examData.selectCourseContent" class="form-control" name="select_course_content"
                      id="selectCourseContent" :required="true" disabled>
                <option v-bind:value="null">  All  </option>
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
              <textarea name="description" id="description" rows="5" v-model="examData.description"
                        class="form-control"></textarea>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-sm-12 col-md-12 col-lg-12">
              <button class="btn btn-sm btn-default" id="saveExam" @click="updateExam()">
                <i class="fa fa-refresh" aria-hidden="true"> </i> Update
              </button>
              <button class="btn btn-sm btn-primary" id="btn-add-course-section"
                      data-toggle="modal"
                      data-target="#form-modal-create">
                <i class="fa fa-plus" aria-hidden="true"> </i> Add Question
              </button>
            </div>
          </div>

          <div class="row">
            <div v-for="QUESTION in GET_QUESTIONS" :key="QUESTION.id" class="form-group col-sm-6 col-md-6 col-lg-6">
              <question-list v-on:deleteQuestion="deleteQuestion" :question="QUESTION"></question-list>
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
                          <input type="text" v-model="question.open.title" class="form-control"
                                 id="openTitle" placeholder="Question title">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="openPoint" class="col-sm-2 control-label">Point</label>

                        <div class="col-sm-10">
                          <input type="number" v-model="question.open.point" class="form-control"
                                 id="openPoint" placeholder="Point of answer">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="button" id="open" v-on:click="addQuestion('open')"
                                  class="btn btn-success">Add</button>
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
                          <input type="text" v-model="question.multipleChoice.title"
                                 class="form-control" id="multipleChoiceTitle" placeholder="Question title">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="multipleChoicePoint" class="col-sm-2 control-label">Point </label>

                        <div class="col-sm-10">
                          <input type="number" v-model="question.multipleChoice.point" class="form-control"
                                 id="multipleChoicePoint" placeholder="Point">
                        </div>
                      </div>
                     
                      <div class="form-group">
                        <label for="multipleChoiceAnswer" class="col-sm-2 control-label">Answer </label>

                        <div class="col-sm-6">
                            <input id="multipleChoiceAnswer" type="text" v-model="question.multipleChoice.answerTitle"
                                   class="form-control" placeholder="Description answer">
                        </div>
                        <div class="col-sm-2">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" v-model="question.multipleChoice.answer"> Correct
                            </label>
                          </div>
                        </div>
                        <div class="col-sm-2">
                          <button type="button" @click="addMoreAnswer()" class="btn btn-default pull-right"> Add Answer</button>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <ul class="todo-list ui-sortable" >
                            <li class="list-group-item" v-for="(ans, index) in question.multipleChoice.answerLists" :key="ans.id">
                              <span class="text"> {{ ans.id }}- {{ ans.title }} </span>
                              <small class="label" v-bind:class="{ 'label-danger': !ans.answer, 'label-success': ans.answer }"> {{ (ans.answer) ? 'Correct' : 'Incorrect' }}</small>
                              <small class="pull-right badge badge-warning"><i class="fa fa-trash" @click="removeAnswer(index)" ></i></small>
                            </li>
                          </ul>
                        </div>
                      </div>
                    
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="button" @click="addQuestion('multipleChoice')" class="btn btn-success">Submit</button>
                    
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
                          <input type="text" v-model="question.close.title" class="form-control"
                                 id="closeTitle" placeholder="Question title">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="closePoint" class="col-sm-2 control-label">Point</label>

                        <div class="col-sm-10">
                          <input type="number" v-model="question.close.point"
                                 class="form-control" id="closePoint" placeholder="Point of answer">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="openPoint" class="col-sm-2 control-label">Correct answer is</label>
                        <div class="col-sm-2">
                          <div class="radio">
                            <label>
                              <input type="radio" name="correct_answer" value="yes"
                                     v-model="question.close.answer"> Yes
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
                          <button type="button" @click="addQuestion('close')" class="btn btn-success">Submit</button>
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
  name: "EditExam",
  computed: {
    // map `this.getCompany` to `this.$store.getters.['exam/getCourses']`
    ...mapGetters({
      getCourses: 'exam/getCourses',
      getCourseContents: 'exam/getCourseContents',
      getAddQuestion: 'exam/getAddQuestion',
      GET_QUESTIONS: 'exam/GET_QUESTIONS',
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
          to: {
            path: '/hrtraining/examination-setting',
            name: 'examination-setting',
          }
        },
        {
          text: 'Edit',
          href: '#'
        }
      ],
      examData: {
        exam_id: null,
        selectCourse: null,
        selectCourseContent: null,
        duration: null,
        grade: null,
      },
      question: {
        question_type: null,
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
          title: null,
          point: null,
          answer: null,
          answerTitle: null,
          answerLists: []
        }
      },
      id: 1
    }
  },
  methods: {
    // map this.setCompany() to this.$store.dispatch('setCompany')
    ...mapActions({
      setCourses: 'exam/setCourses',
      setCourseContents: 'exam/setCourseContents',
      setUpdateInputExam: 'exam/setUpdateInputExam',
      UPDATE_EXAM: 'exam/UPDATE_EXAM',
      CREATE_QUESTION: 'exam/CREATE_QUESTION',
      SET_QUESTIONS: 'exam/SET_QUESTIONS', // Get question by exam id
      DELETE_QUESTION: 'exam/DELETE_QUESTION',
    }),
    async updateExam() {
      await this.UPDATE_EXAM(this.examData)
    },
    // call course content by course_id
    allCoursesContents(courseId) {
      console.log(courseId);
      this.setCourseContents(courseId);
    },
    addQuestion: function(questionType) {
      this.question.question_type = questionType
      this.question.exam = this.$route.params.exam

      this.CREATE_QUESTION(this.question)
      .then( (res) => {
        console.log(res);
        this.question.multipleChoice.answerTitle = null
        this.question.multipleChoice.answer = null
        this.question.multipleChoice.point = null
        this.question.multipleChoice.title = null
        this.question.multipleChoice.answerLists = []
        this.question.question_type = null
        this.SET_QUESTIONS(this.$route.params.exam.id) // Re-retrieve question after create.
      })
    },
    addMoreAnswer() {
      let list = {
        'id': this.id,
        'title': this.question.multipleChoice.answerTitle,
        'answer': this.question.multipleChoice.answer
      }
      this.question.multipleChoice.answerLists.unshift(list);
      this.id += 1
    },
    removeAnswer(id) {
      this.question.multipleChoice.answerLists.splice(id, 1);
    },
    deleteQuestion(id) {
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
          this.DELETE_QUESTION(id)
          .then(res => {
            this.SET_QUESTIONS(this.examData.exam_id)
          })
        }
      })
    }
  },
  mounted() {
    // dispatch action
    this.setCourses();
    console.log(this.$route.params);
    this.examData.exam_id = this.$route.params.exam.id
    this.examData.selectCourse = this.$route.params.exam.course_content.course_id
    this.examData.selectCourseContent = this.$route.params.exam.course_contents_id
    this.examData.duration = this.$route.params.exam.json_data.duration
    this.examData.description = this.$route.params.exam.json_data.description
    this.examData.grade = this.$route.params.exam.json_data.grade
    this.setCourseContents(this.examData.selectCourse)
    this.SET_QUESTIONS(this.examData.exam_id)
  },
}
</script>

<style scoped>

</style>