<template>
  <!-- Modal Export By Staff -->
  <div id="byStaffModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Select Filter</h4>
        </div>
        <div class="modal-body row">
          <div class="form-group col-sm-12 col-md-12 col-lg-12">
            <label>Company</label>
            <select v-model="selectCompanies" name="company_by_staff" id="companyByStaff"
                    style="width: 100%"

                    class="form-control" multiple>
              <option :value="null"> -- All --</option>
              <option v-for="com in getAllCompanies" :value="com.code" :key="com.id">
                {{ com.code + " - " + com.name_kh + " (" + com.short_name + ")" }}
              </option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" @click="onDownloadClick">
            <i class="fa fa-download" aria-hidden="true"></i> Download
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';

export default {
  name: "CompanyMultiSelection",
  props: ['inputName', 'mySelection'],
  data() {
    return {
      selectCompanies: [],
    }
  },
  computed: {
    // map `this.getCompany` to `this.$store.getters.getCompany`
    ...mapGetters([
      'getAllCompanies',
    ]),
  },
  created() {
    console.log("mySelection: " + this.mySelection);
  },
  methods: {
    // onChange(event) {
    //   let code = event.target.value;
    //   console.log('onSelectCompanies: ' + code)
    //   this.selectCompanies.push(code);
    // },
    onDownloadClick() {
      this.$emit('onDownloadClick', this.selectCompanies)
    },
    // map this.setCompany() to this.$store.dispatch('setCompany')
    ...mapActions([
      'setAllCompanies',
    ]),
  },
  mounted() {
    // dispatch action
    this.setAllCompanies()
  },
}
</script>

<style scoped>

</style>