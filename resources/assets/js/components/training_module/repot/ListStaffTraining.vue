<template>
  <div>
    <b-table
        responsive hover striped
        id="result"
        :items="resultStaffTraining"
        :per-page="perPage"
        :current-page="currentPage"
    ></b-table>
    <b-pagination
        v-show="rows > 1"
        v-model="currentPage"
        :total-rows="rows"
        :per-page="perPage"
        first-text="First"
        prev-text="Prev"
        next-text="Next"
        last-text="Last"
        :limit="localLimit"
        :hide-ellipsis="false"
    ></b-pagination>
    <span v-show="rows > 1">Total result: {{ rows }}</span>
  </div>
</template>

<script>

import {PER_PAGE} from "../../../store/constant";

export default {
  name: "ListStaffTraining",
  props: ['resultStaffTraining'],
  data() {
    return {
      perPage: PER_PAGE,
      currentPage: 1,
      localLimit: 3
    }
  },
  computed: {
    rows() {
      return this.resultStaffTraining.length
    }
  }
}
</script>

<style scoped>

</style>