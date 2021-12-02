<template>
  <div>
    <v-select :options="getStaffInBranchDepartment" :reduce="contract => contract.staff_personal_info_id"
              label="staff_full_name" style="margin-bottom: 3px"
              v-model="selectStaff" :value="$store.selectStaff"
              @input="$emit('clickStaff', selectStaff)" :required="true" id="staff">
    </v-select>
    <input type="hidden" name="staff" v-model="selectStaff">
  </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';

export default {
  name: "StaffBranchDepartmentSelection",
  data() {
    return {
      selectStaff: null,
    }
  },
  computed: {
    // map `this.getBranchDepartment` to `this.$store.getters.getBranchDepartment`
    ...mapGetters([
      'getStaffInBranchDepartment',
    ]),
  },
  methods: {

  },
}
</script>

<style scoped>

</style>