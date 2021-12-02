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
              <i class="fa fa-exclamation-circle"></i> Checking Payroll <span v-if="can('view_exchange_rate')">: ({{this.getExchangeRate}} $-៛)</span>
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
                <form method="POST" action="" enctype="multipart/form-data">
                  <label for="file"
                  >Upload Addition/Deduction File:</label
                  >
                  <div class="input-group">
                    <input
                        class="form-control"
                        type="file"
                        id="file"
                        ref="file"
                        v-on:change="handleFileUpload()"
                    />
                    <span class="input-group-btn">
                      <button
                          :disabled="!file"
                          type="button"
                          class="btn btn-info"
                          v-on:click="submitFile()"
                      >
                        <i class="fa fa-upload"></i> Upload
                      </button>
                    </span>
                  </div>
                </form>
              </div>

              <div class="col-xs-6 col-sm-6 col-md-6" v-if="can('post_back_date_full_month')">
                <label>Date<span class="text-danger">*</span></label>
                <input
                    type="date"
                    class="form-control pull-right"
                    id="payroll_date"
                    name="payroll_date"
                    v-model="payrollDate"
                />
              </div>

            </div>

            <div class="form-group row">
              <div class="col-xs-6 col-sm-6 col-md-6">
                <label>Company <span class="text-danger">*</span></label>
                <div>
                  <company-selection
                      @clickCompany="getClickCompany"
                  ></company-selection>
                </div>
              </div>

              <div class="col-xs-6 col-sm-6 col-md-6">
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
            </div>

            <div class="form-group row">
              <div class="col-xs-6 col-sm-6 col-md-6">
                <label>Staff <span class="text-danger">*</span></label>
                <div>
                  <staff-branch-department-selection
                      @clickStaff="getStaffClickInBranchDepartment"
                  ></staff-branch-department-selection>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <div class="fcol-sm-12 col-md-12 col-lg-12">
                <button
                    @click="createTempFullPayroll()"
                    type="button"
                    class="btn btn-primary margin-r-5"
                    v-if="can('checking_payroll_full_month')"
                >
                  <i class="fa fa-calculator"> </i> Checking Now
                </button>

                <button
                    :disabled="!this.getCheckingFullPayroll.length > 0"
                    type="button"
                    class="btn btn-danger margin-r-5"
                    @click="clearCheckingList()"
                    v-if="can('clear_checking_list_full_month')"
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
                    v-if="can('post_payroll_full_month')"
                >
                  <i class="fa fa-filter"></i> Filter Post
                </button>
                <button
                    :disabled="!this.getCheckingFullPayroll.length > 0"
                    class="btn btn-primary margin-r-5"
                    id="btnPostPayroll"
                    @click="postPayroll()"
                    v-if="can('post_payroll_full_month')"
                >
                  <i class="fa fa-dollar"></i> Post Now
                </button>
                <button
                    @click="navigateToViewPostedPayroll()"
                    type="button"
                    class="btn btn-success margin-r-5"
                    v-if="can('view_posted_list_payroll_full_month')"
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
                    v-if="can('export_report_consolidate_full_payroll_by_staff')"
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
                    v-if="can('export_report_consolidate_full_payroll_by_branch')"
                >
                  <i class="fa fa-building"></i> Consolidate By Branch
                </button>
              </div>

            </div>

            <div class="form-group row">
              <div class="col-xs-6 col-sm-6 col-md-6">
                <filter-post-payroll
                    @clickToFilterPayroll="filterPayroll"
                ></filter-post-payroll>
              </div>
            </div>

            <company-multi-selection
                @onDownloadClick="onDownloadClick">
            </company-multi-selection>
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
                  :temp-payrolls="this.getCheckingFullPayroll"
                  @toggleBlockUnBlock="blockOrUnBlock"
              >
              </list-checking-payroll>
              <h5
                  v-bind:style="{ textAlign: 'center' }"
                  v-if="!this.getCheckingFullPayroll.length > 0"
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
import {mapActions, mapGetters} from "vuex";
import axios from "axios";
import StaffInBranchDepartmentSelection from "../../components/StaffInBranchDepartmentSelection";
import FilterPostPayroll from "../../components/payroll_module/FilterPostPayroll";
import CompanyMultiSelection from "../../components/CompanyMultiSelection";

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
    "company-multi-selection": CompanyMultiSelection,
  },
  computed: {
    // map `this.getCheckingHalfPayroll` to `this.$store.getters.['payroll/getCheckingHalfPayroll']`
    ...mapGetters({
      getCheckingFullPayroll: "payroll/getCheckingFullPayroll",
      getExchangeRate: "payroll/getExchangeRate"
    }),
  },
  data() {
    return {
      companyCode: null,
      branchDepartmentCode: null,
      staffPersonalInfoId: null,
      listTempPayrolls: [],
      file: null,
      isLoading: false,
      columnLists: [
        {key: "id", label: "ID"},
        {key: "contract_id", label: "Contract ID"},
        {key: "transaction_code", label: "Transaction Code"},
        {key: "staff_full_name", label: "Staff Full Name"},
        {key: "company", label: "Company Name"},
        {key: "branch_department", label: "Branch/Department Name"},
        {key: "amount", label: "Amount"},
        {key: "currency", label: "Currency"},
        {key: "transaction_date", label: "Transaction Date"},
        {key: "posted_by", label: "Posted By"},
        {key: "action", label: "Action"},
      ],
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
        name: "view-posted-payroll-full-month-page",
      });
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
    // Get select value from child component
    getClickCompanyPost(code) {
      this.companyCodePost = code;
    },
    // Get select value from child component
    getClickBranchDepartmentPost(code) {
      this.branchDepartmentCodePost = code;
    },
    getStaffClickInBranchDepartmentPost(id) {
      this.staffPersonalInfoIdPost = id;
    },
    ...mapActions({
      BLOCK_OR_UNBLOCK_PAYROLL: "payroll/BLOCK_OR_UNBLOCK_PAYROLL",
      CREATE_TEMP_FULL_MONTH_PAYROLL: "payroll/CREATE_TEMP_FULL_MONTH_PAYROLL",
      SET_TEMP_FULL_MONTH_PAYROLL: "payroll/SET_TEMP_FULL_MONTH_PAYROLL",
      CREATE_FULL_PAYROLL: "payroll/CREATE_FULL_PAYROLL",
      CLEAR_CHECKING_LIST_FULL_MONTH: "payroll/CLEAR_CHECKING_LIST_FULL_MONTH",
    }),
    async clearCheckingList() {
      await this.CLEAR_CHECKING_LIST_FULL_MONTH();
    },
    async blockOrUnBlock(item) {
      await this.BLOCK_OR_UNBLOCK_PAYROLL({
        item: item,
        is_block_temp_transactin: true,
      });
    },
    async createTempFullPayroll() {
      await this.CREATE_TEMP_FULL_MONTH_PAYROLL({
        company_code: this.companyCode,
        branch_department_code: this.branchDepartmentCode,
        staff_personal_info_id: this.staffPersonalInfoId,
        payroll_date: this.payrollDate
      });
    },
    async postPayroll() {
      await this.CREATE_FULL_PAYROLL({
        company_code: this.companyCodePost,
        branch_department_code: this.branchDepartmentCodePost,
        staff_personal_info_id: this.staffPersonalInfoIdPost,
      });
    },
    async searchReport() {
      this.isLoading = true;
      await this.SET_TEMP_FULL_MONTH_PAYROLL({
        company_code: this.companyCodePost,
        branch_department_code: this.branchDepartmentCodePost,
        staff_personal_info_id: this.staffPersonalInfoIdPost,
        keyword: this.keyword,
      }).then((res) => {
        $(".loading").fadeOut("fast");
        this.isLoading = false;
        this.selectedItems = [];
      });
    },
    downloadReport() {
      $(".loading").fadeIn("fast");
      axios({
        method: "get",
        url: "/payroll/get-temp-full-month-payroll",
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
            link.setAttribute("download", "Checking_full_Payroll.xlsx");
            document.body.appendChild(link);
            link.click();
          })
          .catch((error) => console.log(error));
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
    reportByStaff() {
      $(".loading").fadeIn("fast");
      console.log('frmReportByStaff: ' + this.frmReportByStaff.selectCompany)
      axios({
        method: "get",
        url: "/payroll/full-month/export-by-staff",
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
            link.setAttribute("download", "Report_payroll_full_month_get_by_staff.xlsx");
            document.body.appendChild(link);
            link.click();
          })
          .catch((error) => console.log(error));
    },
    reportByBranch() {
      $(".loading").fadeIn("fast");
      axios({
        method: "get",
        url: "/payroll/full-month/export-by-branch",
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
            link.setAttribute("download", "Report_payroll_full_month_get_by_branch.xlsx");
            document.body.appendChild(link);
            link.click();
          })
          .catch((error) => console.log(error));
    },
    async submitFile() {
      $(".loading").fadeIn("fast");
      /* Initialize the form data */
      let formData = new FormData();

      /* Add the form data we need to submit */
      formData.append("excel_file", this.file);

      /*  Make the request to the POST /single-file URL */
      await axios
          .post("/payroll/import-deduction", formData, {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          })
          .then((response) => {
            $(".loading").fadeOut("fast");
            if (response.data.status === "success") {
              Swal.fire({
                type: "success",
                title: "Successfully!",
                text: "File upload successfully",
                showConfirmButton: true,
                showCloseButton: true,
              });

              this.$refs.file.value = null; // Reset attached file
              this.file = null;
              this.searchReport();
            }
          })
          .catch((err) => {
            $(".loading").fadeOut("fast");
            console.log(err);
            Swal.fire({
              type: "error",
              title: "Something went wrong!",
              text: "សូមមេត្តាឆែកមើល Format នៅក្នុង File ដែលបាន Upload.",
              showConfirmButton: true,
              showCloseButton: true,
            });
          });
    },
    /* Handles a change on the file upload */
    handleFileUpload() {
      $(".loading").fadeIn("fast");
      let ext = this.$refs.file.files[0]["name"].split(".");

      // Validation extension of file uploaded
      if (ext[1] === "xlsx" || ext[1] === "xls") {
        this.file = this.$refs.file.files[0];

        /* Initialize the form data */
        let formData = new FormData();

        /* Add the form data we need to submit */
        formData.append("excel_file", this.file);

        /*  Make the request to the POST /single-file URL */
        axios
            .post("/payroll/preview-import-deduction", formData, {
              headers: {
                "Content-Type": "multipart/form-data",
              },
            })
            .then((response) => {
              $(".loading").fadeOut("fast");
              if (response.data.status === 1) {
                Swal.fire({
                  type: "info",
                  title: "សូមផ្ទៀងផ្ទាត់ទិន្នន័យខាងក្រោម",
                  html: `
               <p>Total Record: <b>${response.data.data.total_row}</b></p>
               <p>Total Staff: <b>${response.data.data.total_staff}</b></p>
               <p>Total Amount: <b>${formatNumber(
                      response.data.data.total_amount.toFixed(2)
                  )}</b></p>
              `,
                  showConfirmButton: true,
                  showCloseButton: true,
                  confirmButtonText: "បានពិនិត្យរួចហើយ អគុណ",
                });
              } else {
                Swal.fire({
                  type: "Warining",
                  title: "File Excel មានបញ្ហា, សូមពិនិត្យ file ឡើងវិញ",
                  showConfirmButton: true,
                  showCloseButton: true,
                  confirmButtonText: "ពិនិត្យឡើងវិញ",
                });
              }
            });
      } else {
        $(".loading").fadeOut("fast");
        Swal.fire({
          type: "error",
          title: "Invalid File!",
          text: "File ដែល Upload ត្រូវតែជា File Excel.",
          showConfirmButton: true,
          showCloseButton: true,
        });

        this.$refs.file.value = null;
      }
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