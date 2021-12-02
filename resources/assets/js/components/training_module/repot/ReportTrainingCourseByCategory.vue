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
                            <label>Keyword <span class="text-danger">*</span></label>
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

                <list-staff-training :result-staff-training="staffTrainings"></list-staff-training>
            </div>
        </div>

    </div>
</template>

<script>

    import BranchDepartmentSelection from "../../BranchDepartmentSelection";
    import CompanySelection from "../../CompanySelection";
    import ListStaffRequestResign from "../../ListStaffRequestResign";
    import ListStaffTraining from "./ListStaffTraining";

    export default {
        name: "ReportStaffTrainingResult",
        components: {
            ListStaffTraining,
            'list-staff-request-resign': ListStaffRequestResign,
            'company-selection': CompanySelection,
            'branch-department-selection': BranchDepartmentSelection,
        },
        data() {
            return {
                companyCode: null,
                branchDepartmentCode: null,
                keyword: null,
                requestDateFrom: null,
                requestDateTo: null,
                staffTrainings: [],
            }
        },
        methods: {
            // Get select value from child component
            getClickCompany(code) {
                this.companyCode = code;
            },
            // Get select value from child component
            getClickBranchDepartment(code) {
                console.log(code);
                this.branchDepartmentCode = code;
            },

            searchReport() {
                $(".loading").fadeIn("fast");
                axios.get('staff-training-course-by-category', {
                    params: {
                        keyword: this.keyword,
                        request_date_from: this.requestDateFrom,
                        request_date_to: this.requestDateTo,
                        company_code: this.companyCode,
                        branch_department_code: $("#branch_department_code").val()
                    }
                })
                    .then(response => {
                        $(".loading").fadeOut("fast");
                        console.log(response.data.data);
                        this.staffTrainings = response.data.data;
                    }).catch(err => {
                    console.log(err)
                })
            },
            downloadReport() {
                $(".loading").fadeIn("fast");
                axios({
                    method: 'get',
                    url: 'staff-training-course-by-category',
                    responseType: 'blob',
                    contentType: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    params: {
                        keyword: this.keyword,
                        request_date_from: this.requestDateFrom,
                        request_date_to: this.requestDateTo,
                        company_code: this.companyCode,
                        branch_department_code: $("#branch_department_code").val(),
                        download: 1
                    }
                })
                    .then(response => {
                        $(".loading").fadeOut("fast");
                        const url = window.URL.createObjectURL(new Blob([response.data], {type: 'application/vnd.ms-excel'}));
                        const link = document.createElement('a');

                        link.href = url;
                        link.setAttribute('download', 'staff_training_course_by_category_report.xlsx');
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