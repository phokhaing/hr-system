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
              <div>
                <branch-department-selection
                    @clickBranchDepartment="getClickBranchDepartment"></branch-department-selection>
              </div>
            </div>

            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label>Search Staff</label>
              <div>
                <input type="text" name="keyword" id="keyword" placeholder="Keyword..."
                       class="form-control" v-model="keyword"/>
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

        <list-staff-claim-request-pension-fund
            :result-staff-claim-request-pension-fund="items"></list-staff-claim-request-pension-fund>

      </div>
    </div>

  </div>
</template>

<script>

import ListStaffClaimRequestPensionFund from "./ListStaffClaimRequestPensionFund";
import StaffSelection from "./StaffSelection";

export default {
  name: "ReportClaimRequestPensionFund",
  components: {
    'staff-selection': StaffSelection,
    'list-staff-claim-request-pension-fund': ListStaffClaimRequestPensionFund,
  },
  data() {
    return {
      items: [],
      companyCode: null,
      branchDepartmentCode: null,
      keyword: null,
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
      axios.get('pension-fund/current-staff', {
        params: {
          company_code: this.companyCode,
          branch_department_code: this.branchDepartmentCode,
          keyword: this.keyword,
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
        url: 'pension-fund/current-staff',
        responseType: 'blob',
        contentType: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        params: {
          company_code: this.companyCode,
          branch_department_code: this.branchDepartmentCode,
          download: 1,
          keyword: this.keyword,
        }
      })
          .then(response => {
            $(".loading").fadeOut("fast");
            const url = window.URL.createObjectURL(new Blob([response.data], {type: 'application/vnd.ms-excel'}));
            const link = document.createElement('a');

            link.href = url;
            link.setAttribute('download', 'Pension_Fund_Current_Staff.xlsx');
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