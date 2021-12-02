<template>
  <div>
    <form id="reportEndContract" @submit.prevent>
      <div class="panel panel-default">
        <div class="panel-heading"><i class="fa fa-filter"></i> Filter</div>
        <div class="panel-body">
          <div class="row">
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label>Select Contract Type <span class="text-danger">*</span></label>
              <contract-type-selection :contracts="contractTypes" @clickContractType="getClickContractType"></contract-type-selection>
            </div>
          </div>

          <div class="row">
             <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label for="contract_start_date">Contract start date</label>
              <input type="date" class="form-control pull-right contract_start_date"
                     id="contract_start_date" v-model="report.contractStartDate">
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label for="contract_end_date">Contract end date <span
                  class="text-danger">*</span></label>
              <input type="date" class="form-control pull-right contract_end_date"
                     id="contract_end_date" v-model="report.contractEndDate">
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

    </form>

    <list-staff-active :result-staff-active="staffEndContract"></list-staff-active>

  </div>
</template>

<script>
import {CONTRACT_END_TYPE} from "../store/constant";
import CompanySelection from "./CompanySelection";
import BranchDepartmentSelection from "./BranchDepartmentSelection";
import PositionSelection from "./PositionSelection";
import ContractTypeSelection from "./ContractTypeSelection";
import ListStaffActive from "./ListStaffActive";

export default {
  name: "ReportEndContract",
  components: {
    CompanySelection,
    BranchDepartmentSelection,
    PositionSelection,
    ContractTypeSelection,
    ListStaffActive
  },
  data() {
    return {
      contractTypes: CONTRACT_END_TYPE,
      genders: [
        {id:'0', text: 'Male'},
        {id:'1', text: 'Female'},
      ],
      report: {
        contractTypeId: null,
        contractStartDate: null,
        contractEndDate: null,
        companyCode: null,
        branchDepartmentCode: null,
        positionCode: null,
        genderId: null
      },
      staffEndContract: []
    }
  },
  methods: {
    // Get select value from child component
    getClickCompany(code) {
      this.report.companyCode = code;
    },
    // Get select value from child component
    getClickBranchDepartment(code) {
      this.report.branchDepartmentCode = code;
    },
    // Get select value from child component
    getClickPosition(code) {
      this.report.positionCode = code;
    },
    getClickContractType(code) {
      this.report.contractTypeId = code;
    },
    searchReport() {
      $(".loading").fadeIn("fast");
      axios.get('/report/staff-end-contract/search-api', {
        params: {
          company_code: this.report.companyCode,
          branch_department_code: this.report.branchDepartmentCode,
          position_code: $("#position_code").val(),
          gender: this.report.genderId,
          contract_start_date: this.report.contractStartDate,
          contract_end_date: this.report.contractEndDate,
          contract_type: this.report.contractTypeId
        }
      })
        .then(response => {
          $(".loading").fadeOut("fast");
          console.log(response.data.data);
          this.staffEndContract = response.data.data;
        }).catch(err => {
        console.log(err)
      })
    },
    downloadReport() {
      $(".loading").fadeIn("fast");
      axios({
        method: 'get',
        url: '/report/staff-end-contract/search-api/download',
        responseType: 'blob',
        contentType: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        params: {
          company_code: this.report.companyCode,
          branch_department_code: this.report.branchDepartmentCode,
          position_code: $("#position_code").val(),
          gender: this.report.genderId,
          contract_start_date: this.report.contractStartDate,
          contract_end_date: this.report.contractEndDate,
          contract_type: this.report.contractTypeId
        }
      })
      .then(response => {
        $(".loading").fadeOut("fast");
        const url = window.URL.createObjectURL(new Blob([response.data], {type:'application/vnd.ms-excel'}));
        const link = document.createElement('a');

        link.href = url;
        link.setAttribute('download', 'staff_end_contract.xlsx');
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