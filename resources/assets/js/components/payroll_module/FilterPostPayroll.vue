<template>
  <div>
    <div class="modal" tabindex="-1" role="dialog" id="filter_payroll">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">
              <i class="fa fa-filter" /> Advance Filter
            </h4>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="form-group col-sm-12 col-md-12 col-lg-12">
                <label>Company <span class="text-danger">*</span></label>
                <div>
                  <company-selection
                    @clickCompany="getClickCompanyPost"
                  ></company-selection>
                </div>
              </div>
              <div class="form-group col-sm-12 col-md-12 col-lg-12">
                <label
                  >Department / Branch <span class="text-danger">*</span></label
                >
                <div>
                  <branch-department-selection
                    @clickBranchDepartment="getClickBranchDepartmentPost"
                    :company-code="this.companyCode"
                  ></branch-department-selection>
                </div>
              </div>
              <div class="form-group col-sm-12 col-md-12 col-lg-12">
                <label>Staff <span class="text-danger">*</span></label>
                <div>
                  <staff-branch-department-selection
                    @clickStaff="getStaffClickInBranchDepartmentPost"
                  ></staff-branch-department-selection>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary"
              @click="clickToFilterPayroll"
            >
              View Filter Post
            </button>
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CompanySelection from "../../components/CompanySelection";
import BranchDepartmentSelection from "../../components/BranchDepartmentSelection";
import StaffInBranchDepartmentSelection from "../../components/StaffInBranchDepartmentSelection";

export default {
  name: "FilterPostPayroll",
  components: {
    "company-selection": CompanySelection,
    "branch-department-selection": BranchDepartmentSelection,
    "staff-branch-department-selection": StaffInBranchDepartmentSelection,
  },

  data() {
    return {
      companyCode: null,
      branchDepartmentCode: null,
      staffPersonalInfoId: null,
    };
  },
  methods: {
    getClickCompanyPost(code) {
      this.companyCode = code;
      // this.$emit("getCompanyCode", code);
    },
    getClickBranchDepartmentPost(code) {
      this.branchDepartmentCode = code;
      // this.$emit("getBranchDepartmentCode", code);
    },
    getStaffClickInBranchDepartmentPost(id) {
      this.staffPersonalInfoId = id;
      // this.$emit("getStaffPersonalId", id);
    },
    clickToFilterPayroll(){
      console.log('clickToFilterPayroll: '+ this.companyCode + ", "+ this.branchDepartmentCode + ", " + this.staffPersonalInfoId)
      this.$emit('clickToFilterPayroll', this.companyCode, this.branchDepartmentCode, this.staffPersonalInfoId)
    }
  },
};
</script>