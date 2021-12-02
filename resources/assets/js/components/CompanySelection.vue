<template>
  <div>
    <select v-model="selectCompany" class="form-control" name="company_code" id="company_code"
            @change="allBranchDepartment(selectCompany)"
            @click="$emit('clickCompany', selectCompany)">
      <option v-bind:value="null"> >> All << </option>
      <!-- <option :key="getCompany.id" v-bind:value="getCompany.code">{{ getCompany.name_kh }}</option> -->
      <option v-for="company in getCompany" :value="company.code" :key="company.id" :selected="company.code === mySelection">{{ company.code + " - " + company.name_kh + " (" + company.short_name + ")" }}</option>
    </select>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
  name: "CompanySelection",
  props: ['inputName', 'mySelection'],
  data() {
    return {
      selectCompany: 0,
    }
  },
  computed: {
    // map `this.getCompany` to `this.$store.getters.getCompany`
    ...mapGetters([
        'getCompany',
    ]),
  },
  created () {
    console.log("mySelection: "+ this.mySelection);
  },
  methods: {
    // map this.setCompany() to this.$store.dispatch('setCompany')
    ...mapActions([
        'setCompany',
        'setBranchDepartment',
        'setPosition'
    ]),
    allBranchDepartment(companyCode) {
      // dispatch action from store
      console.log("allBranchDepartment: "+ companyCode)
      this.setBranchDepartment(companyCode);
      this.setPosition(companyCode);
    }
  },
  mounted() {
    // dispatch action
    this.setCompany()
  },
}
</script>

<style scoped>

</style>