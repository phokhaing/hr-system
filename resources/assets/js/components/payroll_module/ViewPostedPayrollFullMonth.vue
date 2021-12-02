<template>
  <div>
    <div class="row">
      <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3
              class="box-title"
              title="You can post payroll full month by branch / department or Individual."
            >
              <i class="fa fa-exclamation-circle"></i> Posted Payroll List
            </h3>
            <div class="box-tools pull-right">
              <button
                type="button"
                class="btn btn-box-tool"
                data-widget="collapse"
              >
                <i class="fa fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="box-body">
            <div class="form-group row">
              <div class="col-xs-6 col-sm-6 col-md-6">
                <input
                  placeholder="Search staff here..."
                  class="form-control"
                  type="text"
                  v-model="keyword"
                  id="keyword"
                />
              </div>
              <div class="col-sm-6 col-md-6 col-lg-6">
                <button
                  class="btn btn-primary margin-r-5"
                  id="btnSearch"
                  @click="searchPayroll()"
                >
                  <i class="fa fa-search"></i> Search
                </button>

                <button
                  class="btn btn-danger margin-r-5"
                  id="btnClearSearch"
                  @click="clearSearch()"
                >
                  <i class="fa fa-remove"></i> Clear
                </button>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-6 col-md-6 col-lg-6">
                <button
                  class="btn btn-primary margin-r-5"
                  id="btnSearch"
                  data-toggle="modal"
                  data-target="#filter_payroll"
                  v-if="can('un_post_payroll_full_month')"
                >
                  <i class="fa fa-filter"></i> Filter Un Post
                  <!-- {{ getCountFilters() }} -->
                </button>
                <button
                  :disabled="!this.getFullPayrollPosted.length > 0"
                  class="btn btn-primary margin-r-5"
                  id="btnPostPayroll"
                  @click="unPostPayroll()"
                  v-if="can('un_post_payroll_full_month')"
                >
                  <i class="fa fa-dollar"></i> Un Post Now
                </button>
              </div>
              <!-- @getCompanyCode="getClickCompanyCode"
                @getBranchDepartmentCode="getClickBranchDepartmentCode"
                @getStaffPersonalId="getStaffClickInBranchDepartmentCode" -->
            </div>

            <div class="form-group row">
              <div class="col-sm-6 col-md-6 col-lg-6">
                <filter-post-payroll
                  @clickToFilterPayroll="filterPayroll"
                ></filter-post-payroll>
              </div>
            </div>
            <!-- .row -->

            <div>
              <div
                class="overlay"
                v-bind:style="{ textAlign: 'center' }"
                v-show="isLoading"
              >
                <i
                  class="fa fa-refresh fa-spin"
                  v-bind:style="{ fontSize: '24px', color: '#3097D1' }"
                ></i>
                <p>Loading...</p>
              </div>

              <list-checking-payroll
                :temp-payrolls="this.getFullPayrollPosted"
                @toggleBlockUnBlock="blockOrUnBlock"
              >
              </list-checking-payroll>
              <h5
                v-bind:style="{ textAlign: 'center' }"
                v-if="!this.getFullPayrollPosted.length > 0"
              >
                Empty
              </h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ListCheckingPayroll from "../../components/payroll_module/ListCheckingPayroll";
import CompanySelection from "../../components/CompanySelection";
import BranchDepartmentSelection from "../../components/BranchDepartmentSelection";
import { mapActions, mapGetters } from "vuex";
import StaffInBranchDepartmentSelection from "../../components/StaffInBranchDepartmentSelection";
import FilterPostPayroll from "../../components/payroll_module/FilterPostPayroll";

function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

export default {
  name: "PayrollFullMonthPage",
  components: {
    ListCheckingPayroll,
    "company-selection": CompanySelection,
    "branch-department-selection": BranchDepartmentSelection,
    "staff-branch-department-selection": StaffInBranchDepartmentSelection,
    "filter-post-payroll": FilterPostPayroll,
  },
  computed: {
    // map `this.getCheckingHalfPayroll` to `this.$store.getters.['payroll/getCheckingHalfPayroll']`
    ...mapGetters({
      getFullPayrollPosted: "payroll/getFullPayrollPosted",
    }),
  },
  data() {
    return {
      isLoading: false,
      keyword: null,
      companyCode: null,
      branchDepartmentCode: null,
      staffPersonalInfoId: null,
    };
  },
  methods: {
    clearSearch() {
      this.keyword = null;
      this.companyCode = null;
      this.branchDepartmentCode = null;
      this.staffPersonalInfoId = null;
      this.searchReport();
    },
    searchPayroll() {
      this.companyCode = null;
      this.branchDepartmentCode = null;
      this.staffPersonalInfoId = null;

      this.searchReport();
    },
    filterPayroll(companyCode, branchDepartmentCode, staffPersonalInfoId) {
      $("#filter_payroll").modal("hide");
      this.keyword = null;

      this.companyCode = companyCode;
      this.branchDepartmentCode = branchDepartmentCode;
      this.staffPersonalInfoId = staffPersonalInfoId;
      this.searchReport();
    },
    getCountFilters() {
      let count = 0;

      if (this.companyCode != null) count += 1;
      if (this.branchDepartmentCode != null) count += 1;
      if (this.staffPersonalInfoId != null) count += 1;

      return "(" + count + ")";
    },
    // Get select value from child component
    getClickCompanyCode(code) {
      this.companyCode = code;
    },
    // Get select value from child component
    getClickBranchDepartmentCode(code) {
      this.branchDepartmentCode = code;
    },
    getStaffClickInBranchDepartmentCode(id) {
      this.staffPersonalInfoId = id;
    },
    ...mapActions({
      BLOCK_OR_UNBLOCK_PAYROLL: "payroll/BLOCK_OR_UNBLOCK_PAYROLL",
      UN_POST_PAYRLL_FULL_MONTH: "payroll/UN_POST_PAYRLL_FULL_MONTH",
      GET_POSTED_PAYRLL_FULL_MONTH: "payroll/GET_POSTED_PAYRLL_FULL_MONTH",
    }),
    async blockOrUnBlock(item) {
      await this.BLOCK_OR_UNBLOCK_PAYROLL({
        item: item,
        is_block_temp_transactin: false,
      });
    },
    async createTempFullPayroll() {
      await this.GET_POSTED_PAYRLL_FULL_MONTH({
        company_code: this.companyCode,
        branch_department_code: this.branchDepartmentCode,
        staff_personal_info_id: this.staffPersonalInfoId,
      });
    },
    async unPostPayroll() {
      await this.UN_POST_PAYRLL_FULL_MONTH({
        company_code: this.companyCode,
        branch_department_code: this.branchDepartmentCode,
        staff_personal_info_id: this.staffPersonalInfoId,
      });
    },
    async searchReport() {
      this.isLoading = true;
      await this.GET_POSTED_PAYRLL_FULL_MONTH({
        company_code: this.companyCode,
        branch_department_code: this.branchDepartmentCode,
        staff_personal_info_id: this.staffPersonalInfoId,
        keyword: this.keyword,
      }).then((res) => {
        $(".loading").fadeOut("fast");
        this.isLoading = false;
      });
    },
  },
  mounted() {
    this.searchReport();
    // console.log('mounted');
  },
};
</script>

<style scoped>
</style>