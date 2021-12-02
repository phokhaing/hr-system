<template>
  <div>
    <select v-model="selectContract" class="form-control" @click="$emit('clickContractType', selectContract)">
      <option v-bind:value="null"> >> All << </option>
      <option v-for="contract in contracts" v-bind:value="contract.value">{{ contract.title }}</option>
    </select>
  </div>
</template>

<script>
export default {
  name: "ContractTypeSelection",
  data() {
    return {
      selectContract: null,
    }
  },
  props: {
    contracts: {
      type: Array,
      default: []
    }
  }
}
</script>

<style scoped>

</style>