<template>
  <div>
    <v-select :options="getBranchDepartment" :reduce="branch => branch.code" label="name_km" style="margin-bottom: 3px"
              v-on:input="getAllStaffInBranchDepartment"
              v-model="selectDepartment" :value="$store.selectDepartment"
              @input="$emit('clickBranchDepartment', selectDepartment)" :required="true" id="branch_department_code">
    </v-select>
    <input type="hidden" name="branch_department_code" v-model="selectDepartment">
  </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';

export default {
  name: "BranchDepartmentSelection",
  props: ["companyCode", "mySelection"],
  data() {
    return {
      selectDepartment: null,
    }
  },
  computed: {
    // map `this.getBranchDepartment` to `this.$store.getters.getBranchDepartment`
    ...mapGetters([
      'getBranchDepartment',
    ]),
  },
  created () {
    console.log("mySelection: "+ this.mySelection);
  },
  methods: {
    ...mapActions([
      'setBranchDepartment',
      'setStaffInBranchDepartment'
    ]),
    getAllStaffInBranchDepartment(branchDepartmentCode) {
      console.log("getAllStaffInBranchDepartment: "+ this.companyCode + ", " + branchDepartmentCode);
      this.setStaffInBranchDepartment({
        company: this.companyCode,
        branchDepartment: branchDepartmentCode
      });
    }
  },
}
</script>

<style scoped>

</style>