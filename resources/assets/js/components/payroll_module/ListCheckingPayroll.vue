<template>
  <div>
    <b-table
      responsive
      hover
      striped
      id="result"
      :items="tempPayrolls"
      :per-page="perPage"
      :current-page="currentPage"
      :fields="fields"
    >
      <template #cell(action)="data">
        <template v-if="data.item.is_block == 'true'">
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
import { PER_PAGE } from "../../store/constant";

export default {
  name: "ListCheckingPayroll",
  props: ["tempPayrolls"],
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
        "D.O.E",
        "effective_date",
        "gross_base_salary",
        "retroactive_salary",
        // "total_allowance",
        // "total_deduction",
        // "seniority_pay",
        "fringe_allowance",
        "tax_on_fringe_allowance",
        "spouse",
        "salary_before_tax",
        "tax_on_salary",
        "total_tax_payable",
        "salary_after_tax",
        // "staff_loan_paid",
        // "insurance_pay",
        "pension_fund",
        // "nssf",
        "half_month",
        "net_salary",
      ],
    };
  },
  methods: {
    // onRowSelected(items) {
    //   this.selected = items
    //   this.$emit('onRowSelected', this.selected)
    // },
    // rowClass(item, type) {
    //   console.log("rowClass: " + item + ", " + type);
    //   if (!item || type !== 'row') return
    //   if (item.is_block) return 'table-success'
    // },
    // getButtonLabel(is_block){
    //   console.log('getButtonLabel: ' + is_block)
    //   if(is_block == 1){
    //     return 'Un-Block';
    //   }else{
    //     return 'Block';
    //   }
    // }
  },
  computed: {
    rows() {
      return this.tempPayrolls.length;
    },
  },
};
</script>

<style scoped>
</style>