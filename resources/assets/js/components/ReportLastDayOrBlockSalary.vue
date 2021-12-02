<template>
  <div>
    <div class="panel panel-default">
      <div class="panel-heading"><i class="fa fa-filter"></i> Filter Data</div>
      <div class="panel-body">
        <form @submit.prevent>
          <div class="row">
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label for="contract_start_date">Date From<span class="text-danger">*</span></label>
              <input type="date" class="form-control pull-right"
                     id="contract_start_date" name="contract_start_date" v-model="startDate">
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label>To Date <span class="text-danger">*</span></label>
              <input type="date" class="form-control pull-right"
                     id="contract_end_date" name="contract_start_date" v-model="endDate">
            </div>

            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label>Company <span class="text-danger">*</span></label>
              <div>
                <company-selection @clickCompany="getClickCompany"></company-selection>
              </div>
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label>Department / Branch <span class="text-danger">*</span></label>
              <div>
                <branch-department-selection
                    @clickBranchDepartment="getClickBranchDepartment"></branch-department-selection>
              </div>
            </div>

            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label>Position <span class="text-danger">*</span></label>
              <div>
                <position-selection @clickPosition="getClickPosition"></position-selection>
              </div>
            </div>

            <div class="form-group col-sm-12 col-md-12 col-lg-12">
              <button class="btn btn-primary margin-r-5" id="btnSearch" @click="searchReport()">
                <i class="fa fa-search"></i> Search
              </button>
              <button type="button" class="btn btn-success margin-r-5" @click="downloadReport()">
                <i class="fa fa-download"></i> Download
              </button>
            </div>
          </div>
        </form>

        <list-staff-active :result-staff-active="items"></list-staff-active>

      </div>
    </div>

  </div>
</template>

<script>

import CompanySelection from "./CompanySelection";
import BranchDepartmentSelection from "./BranchDepartmentSelection";
import PositionSelection from "./PositionSelection";
import ContractTypeSelection from "./ContractTypeSelection";
import ListStaffActive from "./ListStaffActive";

const constants = require('../store/constant');

export default {
  name: "ReportLastDayOrBlockSalary",
  components: {
    ListStaffActive,
    ContractTypeSelection,
    'company-selection': CompanySelection,
    'branch-department-selection': BranchDepartmentSelection,
    'position-selection': PositionSelection,
  },
  data() {
    return {
      startDate: null,
      endDate: null,
      companyCode: null,
      branchDepartmentCode: null,
      positionCode: null,
      items: [],
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
      axios.get('/report/last-day-or-block-salary', {
        params: {
          company_code: this.companyCode,
          branch_department_code: this.branchDepartmentCode,
          position_code: $("#position_code").val(),
          start_date: this.startDate,
          end_date: this.endDate,
          is_download: 0
        }
      })
          .then(response => {
            $(".loading").fadeOut("fast");
            console.log(response.data.data);
            this.items = response.data.data;
          }).catch(err => {
        console.log(err)
      })
    },
    downloadReport() {
      $(".loading").fadeIn("fast");
      axios({
        method: 'get',
        url: '/report/last-day-or-block-salary',
        responseType: 'blob',
        contentType: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        params: {
          company_code: this.companyCode,
          branch_department_code: this.branchDepartmentCode,
          position_code: $("#position_code").val(),
          start_date: this.startDate,
          end_date: this.endDate,
          is_download: 1
        }
      })
          .then(response => {
            $(".loading").fadeOut("fast");
            const url = window.URL.createObjectURL(new Blob([response.data], {type: 'application/vnd.ms-excel'}));
            const link = document.createElement('a');

            link.href = url;
            link.setAttribute('download', 'Last_Day_And_Block_Salary.xlsx');
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