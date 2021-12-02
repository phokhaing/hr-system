<template>
  <div>
    <div class="panel panel-default">
      <div class="panel-heading"><i class="fa fa-filter"></i> Filter Data</div>
      <div class="panel-body">
        <form @submit.prevent>
          <div class="row">
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label for="contract_start_date">Request date from </label>
              <input type="date" class="form-control"
                     id="contract_start_date" name="contract_start_date"
                     v-model="requestDateFrom"
                     placeholder="Contract Start Date">
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label for="contract_end_date">Request date to <span class="text-danger">*</span></label>
              <input type="date" class="form-control"
                     id="contract_end_date" name="contract_end_date"
                     v-model="requestDateTo"
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
                <select v-model="genderId" class="form-control">
                  <option v-bind:value="null"> >> All << </option>
                  <option v-for="gender in genders" v-bind:value="gender.id" :key="gender.id">{{ gender.text }}</option>
                </select>
              </div>
            </div>

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

        <list-staff-request-resign :result-staff-request-resign="staffResigns"></list-staff-request-resign>

      </div>
    </div>

  </div>
</template>

<script>

import CompanySelection from "./CompanySelection";
import BranchDepartmentSelection from "./BranchDepartmentSelection";
import PositionSelection from "./PositionSelection";
import ListStaffRequestResign from "./ListStaffRequestResign";

export default {
  name: "ReportRequestResign",
  components: {
    'list-staff-request-resign': ListStaffRequestResign,
    'company-selection': CompanySelection,
    'branch-department-selection': BranchDepartmentSelection,
    'position-selection': PositionSelection,
  },
  data() {
    return {
      genderId: null,
      companyCode: null,
      branchDepartmentCode: null,
      positionCode: null,
      requestDateFrom: null,
      requestDateTo: null,
      genders: [
        {id:'0', text: 'Male'},
        {id:'1', text: 'Female'},
      ],
      staffResigns: [],
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
    // Get select value from child component
    getClickPosition(code) {
      this.positionCode = code;
    },
    searchReport() {
      $(".loading").fadeIn("fast");
      axios.get('/report/staff-request-resign/search-api', {
        params: {
          company_code: this.companyCode,
          branch_department_code: this.branchDepartmentCode,
          position_code: $("#position_code").val(),
          gender: this.genderId,
          request_resign_from: this.requestDateFrom,
          request_resign_to: this.requestDateTo
        }
      })
      .then(response => {
        $(".loading").fadeOut("fast");
        console.log(response.data.data);
        this.staffResigns = response.data.data;
      }).catch(err => {
      console.log(err)
      })
    },
    downloadReport() {
      $(".loading").fadeIn("fast");
      axios({
        method: 'get',
        url: '/report/staff-request-resign/search-api/download',
        responseType: 'blob',
        contentType: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        params: {
          company_code: this.companyCode,
          branch_department_code: this.branchDepartmentCode,
          position_code: $("#position_code").val(),
          gender: this.genderId,
          request_resign_from: this.requestDateFrom,
          request_resign_to: this.requestDateTo
        }
      })
      .then(response => {
        $(".loading").fadeOut("fast");
        const url = window.URL.createObjectURL(new Blob([response.data], {type:'application/vnd.ms-excel'}));
        const link = document.createElement('a');

        link.href = url;
        link.setAttribute('download', 'Staff_Request_Resign_Active.xlsx');
        document.body.appendChild(link);
        link.click();
      })
      .catch(error => console.log(error))
    }
  },
}
</script>

<style scoped>

</style>