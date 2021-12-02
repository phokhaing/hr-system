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
              <label for="filterFromMonth"
              >Select From Month <span class="text-danger">*</span></label
              >
              <div>
                <input
                    type="month"
                    id="filterFromMonth"
                    class="form-control"
                    v-model="filterFromMonth"
                />
              </div>
            </div>

            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label for="filterEndMonth"
              >Select End Month <span class="text-danger">*</span></label
              >
              <div>
                <input
                    type="month"
                    id="filterEndMonth"
                    class="form-control"
                    v-model="filterEndMonth"
                />
              </div>
            </div>

            <div class="form-group col-sm-6 col-md-6 col-lg-6">
              <label>Select Staff <span class="text-danger">*</span></label>
              <div>
                <staff-selection @clickStaff="getClickStaff"></staff-selection>
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
            :result-staff-claim-request-pension-fund="claimRequests"></list-staff-claim-request-pension-fund>

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
    let fromMonth = today.getFullYear() + "-" + monthNames[today.getMonth()];
    let endMonth = today.getFullYear() + "-" + monthNames[today.getMonth()];

    return {
      keyword: null,
      claimRequests: [],
      staffId: null,
      filterFromMonth: fromMonth,
      filterEndMonth: endMonth,
      companyCode: null,
      branchDepartmentCode: null,
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
    getClickStaff(staff_id) {
      console.log('getClickStaff: ' + staff_id);
      this.staffId = staff_id;
    },
    searchReport() {
      $(".loading").fadeIn("fast");
      axios.get('pension-fund/staff-detail', {
        params: {
          staff_id: this.staffId,
          company_code: this.companyCode,
          branch_department_code: this.branchDepartmentCode,
          filter_from_month: this.filterFromMonth,
          filter_end_month: this.filterEndMonth
        }
      })
          .then(response => {
            $(".loading").fadeOut("fast");
            console.log(response.data.data);
            this.claimRequests = response.data.data;
          }).catch(err => {
        console.log(err)
      })
    },
    downloadReport() {
      $(".loading").fadeIn("fast");
      axios({
        method: 'get',
        url: 'pension-fund/staff-detail',
        responseType: 'blob',
        contentType: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        params: {
          staff_id: this.staffId,
          company_code: this.companyCode,
          branch_department_code: this.branchDepartmentCode,
          filter_from_month: this.filterFromMonth,
          filter_end_month: this.filterEndMonth,
          is_download: 1
        }
      })
          .then(response => {
            $(".loading").fadeOut("fast");
            const url = window.URL.createObjectURL(new Blob([response.data], {type: 'application/vnd.ms-excel'}));
            const link = document.createElement('a');

            link.href = url;
            link.setAttribute('download', 'Pension_Fund_By_Staff.xlsx');
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