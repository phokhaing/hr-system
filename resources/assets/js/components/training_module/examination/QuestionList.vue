<template>
  <div>
    <div class="callout callout-default">
      <h4>
        {{ question.json_data.title }}
        <button type="button" class="btn btn-box-tool pull-right" data-widget="remove">
          <i @click="$emit('deleteQuestion', question.id)" class="fa fa-times"></i></button>
      </h4>
      <label>Point: {{ question.json_data.point }}</label>
      <p><i aria-hidden="true" class="fa fa-tag"></i> {{ question.question_type }}</p>
    </div>
  </div>
</template>

<script>
export default {
  name: "QuestionList",
  props: ['question']
}
</script>

<style scoped>
.callout-default {
  border-radius: 5px;
  border-left: 4px solid #0d6aad;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  transition: 0.3s;
  margin-top: 16px;
  overflow: hidden;
  text-overflow: ellipsis;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
}
</style>