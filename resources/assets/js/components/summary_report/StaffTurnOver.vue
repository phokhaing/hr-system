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
                <company-selection @clickCompany="getClickCompany"></company-selection>
              </div>
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label>Department / Branch <span class="text-danger">*</span></label>
              <div >
                <branch-department-selection @clickBranchDepartment="getClickBranchDepartment"></branch-department-selection>
              </div>
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label for="filterDate">Select Maximum Date <span class="text-danger">*</span></label>
              <div>
                <input type="date" id="filterDate" class="form-control" v-model="filterDate" placeholder="dd-mm-yyyy">
              </div>
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label for="reportType">Select Count By <span class="text-danger">*</span></label>
              <div>
                <select class="form-control" name="report_type" id="reportType" v-model="reportType">
                  <option value="branch">Branch / Department</option>
                  <option value="position">Position Level</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <button class="btn btn-primary margin-r-5" id="btnSearch" @click="searchReport()">
                <i class="fa fa-search"></i> Search
              </button>
              <button type="button" class="btn btn-success margin-r-5" @click="downloadReport()">
                <i class="fa fa-download"></i> Download
              </button>
            </div>
          </div>
        </form>

        <table-component :data-source="summaryReport"></table-component>

      </div>
    </div>

  </div>
</template>
<script>
import CompanySelection from "../CompanySelection";
import BranchDepartmentSelection from "../BranchDepartmentSelection";
import TableComponent from "../TableComponent";

export default {
    name:"StaffTurnOverEachMonth",
    components: {
    TableComponent,
    'company-selection': CompanySelection,
    'branch-department-selection': BranchDepartmentSelection,
  },
  data() {
    return {
      companyCode: null,
      branchDepartmentCode: null,
      filterDate: null,
      summaryReport: [],
      reportType: "branch",
    }
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
    searchReport() {
      $(".loading").fadeIn("fast");
      axios.get('/report/summary/staff-turn-over', {
        params: {
          company_code: this.companyCode,
          branch_code: this.branchDepartmentCode,
          filter_date: this.filterDate,
          report_type: this.reportType,
        }
      })
      .then(response => {
        $(".loading").fadeOut("fast");
        this.summaryReport = response.data.data;
      }).catch(err => {
        console.log(err)
      })
    },
    downloadReport() {
      $(".loading").fadeIn("fast");
      axios({
        method: 'get',
        url: '/report/summary/staff-turn-over/export',
        responseType: 'blob',
        contentType: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        params: {
          company_code: this.companyCode,
          branch_code: this.branchDepartmentCode,
          filter_date: this.filterDate,
          report_type: this.reportType,
        }
      })
        .then(response => {
          $(".loading").fadeOut("fast");
          const url = window.URL.createObjectURL(new Blob([response.data], {type:'application/vnd.ms-excel'}));
          const link = document.createElement('a');

          link.href = url;
          link.setAttribute('download', 'summary_report_staff_turn_over.xlsx');
          document.body.appendChild(link);
          link.click();
        })
        .catch(error => console.log(error))
    }
  },
}
</script>