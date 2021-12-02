<template>
  <div>
    <form id="reportMovement">
      <div class="panel panel-default">
        <div class="panel-heading"><i class="fa fa-filter"></i> Filter Data</div>
          <div class="panel-body">
            <div class="row">
               <div class="form-group col-sm-6 col-md-6 col-lg-6">
                <label for="contract_start_date">Contract start date</label>
                <input type="date" class="form-control"
                       id="contract_start_date" name="contract_start_date"
                       v-model="report.contractStartDate"
                       placeholder="Contract Start Date">
              </div>
              <div class="form-group col-sm-6 col-md-6 col-lg-6">
                <label for="contract_end_date">Contract end date <span class="text-danger">*</span></label>
                <input type="date" class="form-control"
                       id="contract_end_date" name="contract_end_date"
                       v-model="report.contractEndDate"
                       placeholder="Contract End Date">
              </div>
              <div class="form-group col-sm-6 col-md-6 col-lg-6">
                <label>Company <span class="text-danger">*</span></label>
                <div >
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
                <label>Position <span class="text-danger">*</span></label>
                <div >
                  <position-selection @clickPosition="getClickPosition"></position-selection>
                </div>
              </div>
              <div class="form-group col-sm-6 col-md-6 col-lg-6">
                <label>Gender <span class="text-danger">*</span></label>
                <div >
                  <select v-model="report.genderId" class="form-control">
                    <option v-bind:value="null"> >> All << </option>
                    <option v-for="gender in genders" v-bind:value="gender.id" :key="gender.id">{{ gender.text }}</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <button type="button" class="btn btn-primary margin-r-5" id="btnSearch" @click="searchReport()">
                <i class="fa fa-search"></i> Search
              </button>
              <button type="button" class="btn btn-success margin-r-5" id="btnDownload" @click="downloadReport()">
                <i class="fa fa-download"></i> Download
              </button>
            </div>

            <br>

            <list-staff-active :result-staff-active="staffMovement"></list-staff-active>

          </div> <!-- .panel-body -->
        </div>
    </form>
  </div>
</template>

<script>
import CompanySelection from "./CompanySelection";
import BranchDepartmentSelection from "./BranchDepartmentSelection";
import PositionSelection from "./PositionSelection";
import ListStaffActive from "./ListStaffActive";

const constants = require('../store/constant');

export default {

  name: "ReportStaffMovement",
  components: {
    'company-selection': CompanySelection,
    'branch-department-selection': BranchDepartmentSelection,
    'position-selection': PositionSelection,
    ListStaffActive,
  },
  data() {
    return {
      genders: [
        {id:'0', text: 'Male'},
        {id:'1', text: 'Female'},
      ],
      report: {
        contractStartDate: null,
        contractEndDate: null,
        companyCode: null,
        branchDepartmentCode: null,
        positionCode: null,
        genderId: null
      },
      staffMovement: []
    }
  },
  methods: {
    // Get value from child component
    getClickCompany(code) {
      this.report.companyCode = code;
    },
    getClickBranchDepartment(code) {
      this.report.branchDepartmentCode = code;
    },
    getClickPosition(code) {
      this.report.positionCode = code;
    },
    searchReport() {
      $(".loading").fadeIn("fast");
      axios.get('/report/staff-movement/search-api', {
        params: {
          company_code: this.report.companyCode,
          branch_department_code: this.report.branchDepartmentCode,
          position_code: $("#position_code").val(),
          gender: this.report.genderId,
          contract_start_date: this.report.contractStartDate,
          contract_end_date: this.report.contractEndDate,
        }
      })
      .then(response => {
        $(".loading").fadeOut("fast");
        console.log(response.data.data);
        this.staffMovement = response.data.data;
      }).catch(err => {
      console.log(err)
      })
    },
    downloadReport() {
      $(".loading").fadeIn("fast");
      axios({
        method: 'get',
        url: '/report/staff-movement/search-api/download',
        responseType: 'blob',
        contentType: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        params: {
          company_code: this.report.companyCode,
          branch_department_code: this.report.branchDepartmentCode,
          position_code: $("#position_code").val(),
          gender: this.report.genderId,
          contract_start_date: this.report.contractStartDate,
          contract_end_date: this.report.contractEndDate,
        }
      })
      .then(response => {
        $(".loading").fadeOut("fast");
        const url = window.URL.createObjectURL(new Blob([response.data], {type:'application/vnd.ms-excel'}));
        const link = document.createElement('a');

        link.href = url;
        link.setAttribute('download', 'Staff_Movement.xlsx');
        document.body.appendChild(link);
        link.click();
      })
      .catch(error => console.log(error))
    }
  }
}
</script>

<style scoped>

</style>