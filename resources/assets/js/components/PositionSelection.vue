<template>
  <div>
    <select v-model="selectPosition" class="form-control" name="position_code" id="position_code"
            @click="$emit('clickPosition', selectPosition)" :required="true" >
      <option v-bind:value="null"> >> All << </option>
      <option v-for="position in getPosition" v-bind:value="position.code">{{ position.name_kh }}</option>
    </select>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  name: "PositionSelection",
  data() {
    return {
      selectPosition: null,
    }
  },
  computed: {
    ...mapGetters([
        'getPosition'
    ])
  },
  updated() {
    $("#position_code").select2();
  }
}
</script>

<style scoped>

</style>