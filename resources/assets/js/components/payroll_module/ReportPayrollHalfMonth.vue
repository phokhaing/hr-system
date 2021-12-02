<template>
  <div>
    <div class="panel panel-default">
      <div class="panel-heading"><i class="fa fa-filter"></i> Filter Data</div>
      <div class="panel-body">
        <form @submit.prevent>
          <div class="row">
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label>Company <span class="text-danger">*</span></label>
              <div>
                <company-selection
                  @clickCompany="getClickCompany"
                ></company-selection>
              </div>
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label
                >Department / Branch <span class="text-danger">*</span></label
              >
              <div>
                <branch-department-selection
                  @clickBranchDepartment="getClickBranchDepartment"
                ></branch-department-selection>
              </div>
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label for="filterMonth"
                >Select Month <span class="text-danger">*</span></label
              >
              <div>
                <input
                  type="month"
                  id="filterMonth"
                  class="form-control"
                  v-model="filterMonth"
                />
              </div>
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label>Keyword <span class="text-danger">*</span></label>
              <div>
                <input
                  type="text"
                  name="keyword"
                  id="keyword"
                  placeholder="Keyword..."
                  class="form-control"
                  v-model="keyword"
                />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <button
                class="btn btn-primary margin-r-5"
                id="btnSearch"
                @click="searchReport()"
              >
                <i class="fa fa-search"></i> Search
              </button>
              <button
                type="button"
                class="btn btn-success margin-r-5"
                @click="downloadReport()"
              >
                <i class="fa fa-download"></i> Download
              </button>
            </div>

            <div class="form-group col-sm-6 col-md-6 col-lg-6">

              <!-- Trigger the modal with a button -->
              <button
                  type="button"
                  id="btnReportToBank"
                  data-toggle="modal"
                  data-target="#toBankModal"
                  class="btn btn-primary margin-r-5 pull-right"
                  v-if="can('export_report_half_payroll_to_bank')"
              >
                <i class="fa fa-university"></i> Report to Bank
              </button>

              <!-- Trigger the modal with a button -->
              <button
                  type="button"
                  id="btnReporfByBranch"
                  data-toggle="modal"
                  data-target="#byBranchModal"
                  class="btn btn-primary margin-r-5 pull-right"
                  v-if="can('export_report_consolidate_half_payroll_by_branch')"
              >
                <i class="fa fa-building"></i>Consolidate By Branch
              </button>

              <!-- Trigger the modal with a button -->
              <button
                  class="btn btn-primary margin-r-5 pull-right"
                  id="btnReportByStaff"
                  data-toggle="modal"
                  data-target="#byStaffModal"
                  v-if="can('export_report_consolidate_half_payroll_by_staff')"
              >
                <i class="fa fa-user"></i> Consolidate By Staff
              </button>
            </div>
          </div>
        </form>



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
                    <label>Choose date </label>
                    <input
                      placeholder="Select month and year"
                      type="month"
                      id="frmReportByStaffFilterMonth"
                      class="form-control"
                      v-model="frmReportByStaff.filterMonth"
                    />
                  </div>
                  <div class="form-group col-sm-12 col-md-12 col-lg-12">
                      <label>Company</label>
                      <select v-model="frmReportByStaff.selectCompany" name="company_by_staff" id="companyByStaff" class="form-control js-select2-single" multiple>
                          <option :value="null"> -- All -- </option>
                          <option v-for="com in getCompany" :value="com.code" :key="com.id">
                            {{ com.code + " - " + com.name_kh + " (" + com.short_name + ")" }}
                          </option>
                      </select>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success" @click="reportByStaff()" data-dismiss="modal">
                  <i class="fa fa-download" aria-hidden="true"></i> Download
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Export By Branch -->
        <div id="byBranchModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select Filter</h4>
              </div>
              <div class="modal-body row">
                  <div class="form-group col-sm-12 col-md-12 col-lg-12">
                    <label>Choose date </label>
                    <input
                      placeholder="Select month and year"
                      type="month"
                      id="frmReportByStaffFilterMonth"
                      class="form-control"
                      v-model="frmReportByStaff.filterMonth"
                    />
                  </div>
                  <div class="form-group col-sm-12 col-md-12 col-lg-12">
                      <label>Company</label>
                      <select v-model="frmReportByStaff.selectCompany" name="company_by_staff" id="companyByStaff" class="form-control js-select2-single" multiple>
                          <option :value="null"> -- All -- </option>
                          <option v-for="com in getCompany" :value="com.code" :key="com.id">
                            {{ com.code + " - " + com.name_kh + " (" + com.short_name + ")" }}
                          </option>
                      </select>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success" @click="reportByBranch()" data-dismiss="modal">
                  <i class="fa fa-download" aria-hidden="true"></i> Download
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Export to Bank -->
        <div id="toBankModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select Filter</h4>
              </div>
              <div class="modal-body row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12">
                  <label>Choose date </label>
                  <input
                      placeholder="Select month and year"
                      type="month"
                      class="form-control"
                      v-model="reportToBank.filterMonth"
                  />
                </div>
                <div class="form-group col-sm-12 col-md-12 col-lg-12">
                  <label>Company</label>
                  <select v-model="reportToBank.selectCompany" name="company_by_staff" id="companyToBank" class="form-control js-select2-single" multiple>
                    <option :value="null"> -- All -- </option>
                    <option v-for="com in getCompany" :value="com.code" :key="com.id">
                      {{ com.code + " - " + com.name_kh + " (" + com.short_name + ")" }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success" @click="funReportToBank()" data-dismiss="modal">
                  <i class="fa fa-download" aria-hidden="true"></i> Download
                </button>
              </div>
            </div>
          </div>
        </div>

        <b-table
          responsive
          hover
          striped
          id="result"
          :items="Payrolls"
          :per-page="perPage"
          :current-page="currentPage"
          :fields="fields"
        >
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
    </div>
  </div>
</template>

<script>
import CompanySelection from "../CompanySelection";
import BranchDepartmentSelection from "../BranchDepartmentSelection";
import PositionSelection from "../PositionSelection";
import TableComponent from "../TableComponent";
import {PER_PAGE} from "../../store/constant";
import { mapActions, mapGetters } from 'vuex';

export default {
  name: "ReportPayrollHalfMonth",
  components: {
    TableComponent,
    "company-selection": CompanySelection,
    "branch-department-selection": BranchDepartmentSelection,
    "position-selection": PositionSelection,
  },
  data() {
    const today = new Date();
    const monthNames = [
      "01",
      "02",
      "03",
      "04",
      "05",
      "06",
      "07",
      "08",
      "09",
      "10",
      "11",
      "12",
    ];
    let yearMonth = today.getFullYear() + "-" + monthNames[today.getMonth()];
    console.log("filterMonth" + yearMonth);

    return {
      perPage: PER_PAGE,
      currentPage: 1,
      localLimit: 3,
      genderId: null,
      companyCode: null,
      branchDepartmentCode: null,
      positionCode: null,
      filterMonth: yearMonth,
      genders: [
        { id: "0", text: "Male" },
        { id: "1", text: "Female" },
      ],
      Payrolls: [],
      keyword: null,
      fields: [
        "staff_id",
        "name_in_english",
        "position",
        "location",
        "base_salary",
        "half_month",
        "currency",
      ],
      // Report By Staff
      frmReportByStaff : {
        filterMonth: null,
        selectCompany: []
      },

      reportToBank : {
        filterMonth: null,
        selectCompany: []
      }
    };
  },
  methods: {
    // Get select value from child component
    getClickCompany(code) {
      this.companyCode = code;
    },
    // Get select value from child component
    getClickBranchDepartment(code) {
      this.branchDepartmentCode = code;
    },
    // Get select value from child component
    getClickPosition(code) {
      this.positionCode = code;
    },
    searchReport() {
      $(".loading").fadeIn("fast");
      axios
        .get("/payroll/get-half-payroll", {
          params: {
            company_code: this.companyCode,
            branch_department_code: this.branchDepartmentCode,
            year_month: this.filterMonth,
            keyword: this.keyword,
          },
        })
        .then((response) => {
          $(".loading").fadeOut("fast");
          this.Payrolls = response.data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    downloadReport() {
      $(".loading").fadeIn("fast");
      axios({
        method: "get",
        url: "/payroll/get-half-payroll/export",
        responseType: "blob",
        contentType:
          "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        params: {
          company_code: this.companyCode,
          branch_department_code: this.branchDepartmentCode,
          transaction_date: this.filterMonth,
          keyword: this.keyword,
        },
      })
        .then((response) => {
          $(".loading").fadeOut("fast");
          const url = window.URL.createObjectURL(
            new Blob([response.data], { type: "application/vnd.ms-excel" })
          );
          const link = document.createElement("a");

          link.href = url;
          link.setAttribute("download", "Report_Half_Payroll.xlsx");
          document.body.appendChild(link);
          link.click();
        })
        .catch((error) => console.log(error));
    },

    reportByStaff() {
      $(".loading").fadeIn("fast");
      axios({
        method: "get",
        url: "/payroll/get-half-payroll/export-by-staff",
        responseType: "blob",
        contentType:
          "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        params: {
          company_codes: this.frmReportByStaff.selectCompany,
          transaction_date: this.frmReportByStaff.filterMonth,
          is_temp_payroll: 0,
        },
      })
        .then((response) => {
          $(".loading").fadeOut("fast");
          const url = window.URL.createObjectURL(
            new Blob([response.data], { type: "application/vnd.ms-excel" })
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
          is_temp_payroll: 0,
        },
      })
        .then((response) => {
          $(".loading").fadeOut("fast");
          const url = window.URL.createObjectURL(
            new Blob([response.data], { type: "application/vnd.ms-excel" })
          );
          const link = document.createElement("a");

          link.href = url;
          link.setAttribute("download", "Report_payroll_half_month_get_by_branch.xlsx");
          document.body.appendChild(link);
          link.click();
        })
        .catch((error) => console.log(error));
    },
    funReportToBank() {
      $(".loading").fadeIn("fast");
      axios({
        method: "get",
        url: "/payroll/get-half-payroll/export-to-bank",
        responseType: "blob",
        contentType:
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        params: {
          company_codes: this.reportToBank.selectCompany,
          transaction_date: this.reportToBank.filterMonth,
        },
      })
          .then((response) => {
            $(".loading").fadeOut("fast");
            const url = window.URL.createObjectURL(
                new Blob([response.data], { type: "application/vnd.ms-excel" })
            );
            const link = document.createElement("a");

            link.href = url;
            link.setAttribute("download", "Report_payroll_half_month_to_bank.xlsx");
            document.body.appendChild(link);
            link.click();
          })
          .catch((error) => console.log(error));
    },
  },
  computed: {
    ...mapGetters([
      'getCompany',
    ]),
    rows() {
      return this.Payrolls.length
    }
  },
  mounted() {
    // dispatch action
    // this.setCompany()
  },
};
</script>

<style scoped>
</style>