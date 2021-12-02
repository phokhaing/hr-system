<template>
  <div>
    <div class="row">
      <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3
                class="box-title"
                title="You need to upload deduction file first after checking payroll."
            >
              <i class="fa fa-exclamation-circle"></i> Checking Payroll
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
              <div class="col-sm-6 col-md-6 col-lg-6" v-if="can('post_back_date_half_month')">
                <label>Date<span class="text-danger">*</span></label>
                <input
                    type="date"
                    class="form-control pull-right"
                    id="payroll_date"
                    name="payroll_date"
                    max=""
                    v-model="payrollDate"
                />
              </div>
              <div class="col-sm-6 col-md-6 col-lg-6">
                <label>Company <span class="text-danger">*</span></label>
                <div>
                  <company-selection
                      @clickCompany="getClickCompany"
                  ></company-selection>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-6 col-md-6 col-lg-6">
                <label
                >Department / Branch <span class="text-danger">*</span></label
                >
                <div>
                  <branch-department-selection
                      :company-code="this.companyCode"
                      @clickBranchDepartment="getClickBranchDepartment"
                  ></branch-department-selection>
                </div>
              </div>

              <div class="col-sm-6 col-md-6 col-lg-6">
                <label>Staff <span class="text-danger">*</span></label>
                <div>
                  <staff-branch-department-selection
                      @clickStaff="getStaffClickInBranchDepartment"
                  ></staff-branch-department-selection>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-12 col-md-12 col-lg-12">
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="createTempHalfPayroll()"
                    v-if="can('checking_half_payroll')"
                >
                  <i class="fa fa-calculator"> </i> Checking Now
                </button>

                <button
                    :disabled="!this.getCheckingHalfPayroll.length > 0"
                    type="button"
                    class="btn btn-danger margin-r-5"
                    @click="clearCheckingList()"
                    v-if="can('clear_checking_list_half_month')"
                >
                  <i class="fa fa-remove"></i> Clear Checking List
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="box box-info">
          <div class="box-header with-border">
            <h3
                class="box-title"
                title="You can post payroll full month by branch / department or Individual."
            >
              <i class="fa fa-exclamation-circle"></i> Checking Payroll List
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
                <button
                    type="button"
                    class="btn btn-primary margin-r-5"
                    @click="downloadReport()"
                >
                  <i class="fa fa-download"></i> Download
                </button>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-xs-12 col-sm-12 col-md-12">
                <button
                    class="btn btn-primary margin-r-5"
                    id="btnSearch"
                    data-toggle="modal"
                    data-target="#filter_payroll"
                    v-if="can('post_half_payroll')"
                >
                  <i class="fa fa-filter"></i> Filter Post
                </button>
                <button
                    :disabled="!this.getCheckingHalfPayroll.length > 0"
                    class="btn btn-primary margin-r-5"
                    id="btnPostPayroll"
                    @click="postPayroll()"
                    v-if="can('post_half_payroll')"
                >
                  <i class="fa fa-dollar"></i> Post Now
                </button>
                <button
                    @click="navigateToViewPostedPayroll()"
                    type="button"
                    class="btn btn-success margin-r-5"
                    v-if="can('view_posted_list_payroll_half_month')"
                >
                  <i class="fa fa-eye" aria-hidden="true"> </i> View Posted
                  Payroll
                </button>
                <!-- Trigger the modal with a button -->
                <button
                    class="btn btn-primary margin-r-5"
                    id="btnReportByStaff"
                    data-toggle="modal"
                    @click="onExportClick(1)"
                    v-if="can('export_report_consolidate_half_payroll_by_staff')"
                >
                  <i class="fa fa-user"></i> Consolidate By Staff
                </button>
                <!-- Trigger the modal with a button -->
                <button
                    type="button"
                    id="btnReporfByBranch"
                    data-toggle="modal"
                    @click="onExportClick(2)"
                    class="btn btn-primary margin-r-5"
                    v-if="can('export_report_consolidate_half_payroll_by_branch')"
                >
                  <i class="fa fa-building"></i> Consolidate By Branch
                </button>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-xs-12 col-sm-12 col-md-12">
                <filter-post-payroll
                    @clickToFilterPayroll="filterPayroll"
                ></filter-post-payroll>
              </div>
            </div>

            <company-multi-selection
                @onDownloadClick="onDownloadClick">
            </company-multi-selection>

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
                :temp-payrolls="this.getCheckingHalfPayroll"
                @toggleBlockUnBlock="blockOrUnBlock"
            ></list-checking-payroll>

            <h5
                v-bind:style="{ textAlign: 'center' }"
                v-if="!this.getCheckingHalfPayroll.length > 0"
            >
              Empty
            </h5>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import CompanySelection from "../../components/CompanySelection.vue";
import CompanyMultiSelection from "../../components/CompanyMultiSelection.vue";
import BranchDepartmentSelection from "../../components/BranchDepartmentSelection.vue";
import ListCheckingPayroll from "../../components/payroll_module/ListCheckingHalfMonthPayroll";
import StaffInBranchDepartmentSelection from "../../components/StaffInBranchDepartmentSelection";
import {mapActions, mapGetters} from "vuex";
import axios from "axios";
import FilterPostPayroll from "../../components/payroll_module/FilterPostPayroll";

export default {
  name: "PayrollPage",
  components: {
    ListCheckingPayroll,
    "company-selection": CompanySelection,
    "company-multi-selection": CompanyMultiSelection,
    "branch-department-selection": BranchDepartmentSelection,
    "staff-branch-department-selection": StaffInBranchDepartmentSelection,
    "filter-post-payroll": FilterPostPayroll,
  },
  computed: {
    // map `this.getCheckingHalfPayroll` to `this.$store.getters.['payroll/getCheckingHalfPayroll']`
    ...mapGetters({
      getCheckingHalfPayroll: "payroll/getCheckingHalfPayroll",
    }),
  },
  data() {
    return {
      exchange: 4100,
      companyCode: null,
      branchDepartmentCode: null,
      staffPersonalInfoId: null,
      positionCode: null,
      listTempPayrolls: [],
      isLoading: false,

      keyword: null,
      companyCodePost: null,
      branchDepartmentCodePost: null,
      staffPersonalInfoIdPost: null,
      payrollDate: new Date().toISOString().substr(0, 10),
      // Report By Staff
      frmReportByStaff: {
        exportStatus: null,
        filterMonth: null,
        selectCompany: []
      },
    };
  },
  methods: {
    filterPayroll(companyCode, branchDepartmentCode, staffPersonalInfoId) {
      $("#filter_payroll").modal("hide");
      this.keyword = null;

      this.companyCodePost = companyCode;
      this.branchDepartmentCodePost = branchDepartmentCode;
      this.staffPersonalInfoIdPost = staffPersonalInfoId;
      this.searchReport();
    },
    navigateToViewPostedPayroll() {
      this.$router.push({
        name: "view-posted-payroll-half-month-page",
      });
    },
    clearSearch() {
      this.keyword = null;
      this.companyCodePost = null;
      this.branchDepartmentCodePost = null;
      this.staffPersonalInfoIdPost = null;
      this.searchReport();
    },
    searchPayroll() {
      this.companyCodePost = null;
      this.branchDepartmentCodePost = null;
      this.staffPersonalInfoIdPost = null;
      this.searchReport();
    },
    // Get select value from child component
    getClickCompany(code) {
      this.companyCode = code;
    },
    // Get select value from child component
    getClickBranchDepartment(code) {
      this.branchDepartmentCode = code;
    },
    getStaffClickInBranchDepartment(id) {
      this.staffPersonalInfoId = id;
    },
    onExportClick(status) {
      this.frmReportByStaff.exportStatus = status;
      $("#byStaffModal").modal('show')
    },
    onDownloadClick(companies) {
      $("#byStaffModal").modal('hide')
      console.log('onDownloadClick: ' + companies);
      this.frmReportByStaff.selectCompany = companies;
      if (this.frmReportByStaff.exportStatus == 1) {
        this.reportByStaff();
      } else if (this.frmReportByStaff.exportStatus == 2) {
        this.reportByBranch();
      }
    },
    ...mapActions({
      CREATE_TEMP_HALF_PAYROLL: "payroll/CREATE_TEMP_HALF_PAYROLL",
      CREATE_HALF_PAYROLL: "payroll/CREATE_HALF_PAYROLL",
      SET_TEMP_HALF_PAYROLL: "payroll/SET_TEMP_HALF_PAYROLL",
      BLOCK_OR_UNBLOCK_HALF_PAYROLL: "payroll/BLOCK_OR_UNBLOCK_HALF_PAYROLL",
      CLEAR_CHECKING_LIST_HALF_MONTH: "payroll/CLEAR_CHECKING_LIST_HALF_MONTH",
    }),
    async createTempHalfPayroll() {
      await this.CREATE_TEMP_HALF_PAYROLL({
        company_code: this.companyCode,
        branch_department_code: this.branchDepartmentCode,
        staff_personal_info_id: this.staffPersonalInfoId,
        payroll_date: this.payrollDate
      });
    },
    async clearCheckingList() {
      await this.CLEAR_CHECKING_LIST_HALF_MONTH();
    },
    async postPayroll() {
      await this.CREATE_HALF_PAYROLL({
        company_code: this.companyCodePost,
        branch_department_code: this.branchDepartmentCodePost,
        staff_personal_info_id: this.staffPersonalInfoIdPost,
      });
    },
    async searchReport() {
      this.isLoading = true;
      await this.SET_TEMP_HALF_PAYROLL({
        keyword: this.keyword,
        company_code: this.companyCodePost,
        branch_department_code: this.branchDepartmentCodePost,
        staff_personal_info_id: this.staffPersonalInfoIdPost,
      }).then((res) => {
        this.isLoading = false;
      });
    },
    downloadReport() {
      $(".loading").fadeIn("fast");
      axios({
        method: "get",
        url: "/payroll/get-checking-half-month",
        responseType: "blob",
        contentType:
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        params: {
          company_code: this.companyCodePost,
          branch_department_code: this.branchDepartmentCodePost,
          staff_personal_info_id: this.staffPersonalInfoIdPost,
          keyword: this.keyword,
          is_download: 1,
        },
      })
          .then((response) => {
            $(".loading").fadeOut("fast");
            const url = window.URL.createObjectURL(
                new Blob([response.data], {type: "application/vnd.ms-excel"})
            );
            const link = document.createElement("a");

            link.href = url;
            link.setAttribute("download", "Checking_Half_Payroll.xlsx");
            document.body.appendChild(link);
            link.click();
          })
          .catch((error) => console.log(error));
    },
    reportByStaff() {
      $(".loading").fadeIn("fast");
      console.log('frmReportByStaff: ' + this.frmReportByStaff.selectCompany)
      axios({
        method: "get",
        url: "/payroll/get-half-payroll/export-by-staff",
        responseType: "blob",
        contentType:
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        params: {
          company_codes: this.frmReportByStaff.selectCompany,
          transaction_date: this.frmReportByStaff.filterMonth,
          is_temp_payroll: 1,
        },
      })
          .then((response) => {
            $(".loading").fadeOut("fast");
            const url = window.URL.createObjectURL(
                new Blob([response.data], {type: "application/vnd.ms-excel"})
            );
            const link = document.createElement("a");

            link.href = url;
            link.setAttribute("download", "Report_payroll_half_month_get_by_staff.xlsx");
            document.body.appendChild(link);
            link.click();
          })
          .catch((error) => console.log(error));
    },
    reportByBranch() {
      $(".loading").fadeIn("fast");
      axios({
        method: "get",
        url: "/payroll/get-half-payroll/export-by-branch",
        responseType: "blob",
        contentType:
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        params: {
          company_codes: this.frmReportByStaff.selectCompany,
          transaction_date: this.frmReportByStaff.filterMonth,
          is_temp_payroll: 1,
        },
      })
          .then((response) => {
            $(".loading").fadeOut("fast");
            const url = window.URL.createObjectURL(
                new Blob([response.data], {type: "application/vnd.ms-excel"})
            );
            const link = document.createElement("a");

            link.href = url;
            link.setAttribute("download", "Report_payroll_half_month_get_by_branch.xlsx");
            document.body.appendChild(link);
            link.click();
          })
          .catch((error) => console.log(error));
    },
    async blockOrUnBlock(item) {
      console.log("blockOrUnBlock: " + item.is_block);
      await this.BLOCK_OR_UNBLOCK_HALF_PAYROLL({
        item: item,
        is_block_temp_transactin: true,
      });
    },
  },
  mounted() {
    this.searchReport();

    let today = new Date().toISOString().substr(0, 10);
    document.getElementById("payroll_date").setAttribute("max", today);

    console.log("mounted: " + today);

  },
};
</script>

<style scoped>
</style>