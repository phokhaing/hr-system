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
                            <label>Search Staff</label>
                            <div>
                                <input type="text" name="keyword" id="keyword" placeholder="Keyword..." class="form-control" v-model="keyword"/>
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

    import ListStaffClaimRequestPensionFund from "./pension_fund_module/ListStaffClaimRequestPensionFund";

    export default {
        name: "ReportFinalPay",
        components: {
            'list-staff-claim-request-pension-fund': ListStaffClaimRequestPensionFund,
        },
        data() {
            return {
                keyword: null,
                claimRequests: [],
                companyCode: null,
                branchDepartmentCode: null,
                requestDateFrom: null,
                requestDateTo: null,
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
                axios.get('final-pay', {
                    params: {
                        company_code: this.companyCode,
                        branch_department_code: this.branchDepartmentCode,
                        request_date_from: this.requestDateFrom,
                        request_date_to: this.requestDateTo,
                        keyword: this.keyword,
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
                    url: 'final-pay',
                    responseType: 'blob',
                    contentType: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    params: {
                        company_code: this.companyCode,
                        branch_department_code: this.branchDepartmentCode,
                        request_date_from: this.requestDateFrom,
                        request_date_to: this.requestDateTo,
                        keyword: this.keyword,
                        download: 1
                    }
                })
                    .then(response => {
                        $(".loading").fadeOut("fast");
                        const url = window.URL.createObjectURL(new Blob([response.data], {type: 'application/vnd.ms-excel'}));
                        const link = document.createElement('a');

                        link.href = url;
                        link.setAttribute('download', 'Report_Final_Pay.xlsx');
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