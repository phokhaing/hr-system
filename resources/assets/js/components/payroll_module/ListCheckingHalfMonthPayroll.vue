<template>
  <div>
    <b-table
        responsive hover striped
        id="result"
        :items="tempPayrolls"
        :per-page="perPage"
        :current-page="currentPage"
        :fields="fields"
    >
      <template #cell(action)="data">
        <template v-if="data.item.is_block == true">
          <button
            class="btn btn-sm btn-danger"
            title="Click to UnBlock"
            @click="$emit('toggleBlockUnBlock', data.item)"
          >
            <span class="fa fa-lock"></span>
          </button>
        </template>

        <template v-else>
          <button
            class="btn btn-sm btn-primary"
            title="Click to Block"
            @click="$emit('toggleBlockUnBlock', data.item)"
          >
            <span class="fa fa-unlock"></span>
          </button>
        </template>
      </template>
    </b-table>
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
      <h5 v-show="rows > 1">Total result: {{ rows }}</h5>
  </div>
</template>

<script>
import {PER_PAGE} from "../../store/constant";

export default {
  name: "ListCheckingPayroll",
  props: ['tempPayrolls'],
  data() {
    return {
      perPage: PER_PAGE,
      currentPage: 1,
      localLimit: 3,
      fields: [
        "action",
        "staff_id",
        "name_in_english",
        "position",
        "location",
        "base_salary",
        "half_month",
        "currency"
      ]
    }
  },
  methods: {
  },
  computed: {
    rows() {
      return this.tempPayrolls.length
    }
  }
}
</script>

<style scoped>

</style>