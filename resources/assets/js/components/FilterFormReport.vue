<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-filter"></i> Filter Report</div>
            <div class="panel-body">
                <label class="col-sm-2 col-md-2">Please Select Report <span class="text-danger">*</span></label>
                <div class="col-sm-10 col-md-10">
                    <select v-model="selected" class="form-control" v-on:change="showFilter()">
                        <option v-for="option in options" :key="option.value" v-bind:value="option.value">
                            {{ option.text }}
                        </option>
                    </select>
                </div>
            </div> <!-- .panel-body -->
        </div> <!-- /.panel -->
        <component v-bind:is="component"></component>
    </div>
</template>

<script>
    import ReportStaffActive from "./ReportStaffActive";
    import ReportEndContract from "./ReportEndContract";
    import ReportStaffMovement from "./ReportStaffMovement";
    import ReportRequestResign from "./ReportRequestResign";
    import ReportStaffTraining from "./training_module/repot/ReportStaffTraining";
    import ReportStaffTrainingResult from "./training_module/repot/ReportStaffTrainingResult";
    import ReportStaffDetailPensionFund from "./pension_fund_module/ReportStaffDetailPensionFund";
    import ReportPensionFundSummary from "./pension_fund_module/ReportPensionFundSummary";
    import ReportPensionFundCurrentStaff from "./pension_fund_module/ReportPensionFundCurrentStaff";
    import ReportPayrollHalfMonth from "./payroll_module/ReportPayrollHalfMonth";
    import ReportPayrollFullMonth from "./payroll_module/ReportPayrollFullMonth";
    import ReportTrainingCourseByCategory from "./training_module/repot/ReportTrainingCourseByCategory";
    import AgeRangAndGender from "./summary_report/AgeRangAndGender";
    import ContractStatus from "./summary_report/ContractStatus";
    import Seniority from "./summary_report/Seniority";
    import StaffTurnOver from "./summary_report/StaffTurnOver";
    import StaffTurnOverEachMonth from "./summary_report/StaffTurnOverEachMonth";
    import ReportFinalPay from "./ReportFinalPay";
    import ReportLastDayOrBlockSalary from "./ReportLastDayOrBlockSalary";


    export default {
        components: {
            'report-staff-active': ReportStaffActive,
            'report-staff-movement': ReportStaffMovement,
            'report-end-contract': ReportEndContract,
            'report-request-resign': ReportRequestResign,
            'report-training-by-category': ReportTrainingCourseByCategory,
            'report-staff-training': ReportStaffTraining,
            'report-staff-training-result': ReportStaffTrainingResult,
            'report-pension-fund-summary': ReportPensionFundSummary,
            'report-current-staff-pension-fund': ReportPensionFundCurrentStaff,
            'report-by-staff-pension-fund': ReportStaffDetailPensionFund,
            'report-payroll-half-month': ReportPayrollHalfMonth,
            'report-payroll-full-month': ReportPayrollFullMonth,
            'report-final-pay': ReportFinalPay,
            'report-last-day-block-salary': ReportLastDayOrBlockSalary,
            AgeRangAndGender,
            ContractStatus,
            Seniority,
            StaffTurnOver,
            StaffTurnOverEachMonth,
        },
        name: "FilterFormReport",
        data() {
            return {
                selected: null,
                options: [
                    {text: '>> Please Select Form <<', value: null},
                    {text: 'Staff Active', value: 'report-staff-active'},
                    {text: 'Staff Movement', value: 'report-staff-movement'},
                    {text: 'Staff End Contract', value: 'report-end-contract'},
                    {text: 'Staff Request Resign', value: 'report-request-resign'},
                    {text: 'Staff Training Course by Category', value: 'report-training-by-category'},
                    {text: 'Staff Training', value: 'report-staff-training'},
                    {text: 'Staff Training Result', value: 'report-staff-training-result'},
                    {text: 'Pension Fund Summary', value: 'report-pension-fund-summary'},
                    {text: 'Pension Fund By Staff', value: 'report-by-staff-pension-fund'},
                    {text: 'Pension Fund Current Staff', value: 'report-current-staff-pension-fund'},
                    { text: 'Payroll Half Month', value: 'report-payroll-half-month' },
                    { text: 'Payroll Full Month', value: 'report-payroll-full-month' },
                    { text: 'Final Pay', value: 'report-final-pay' },
                    { text: 'Last Day/Block Salary', value: 'report-last-day-block-salary' },
                    { text: 'Summary Age Range And Gender', value: 'age-rang-and-gender' },
                    { text: 'Summary Contract Status', value: 'contract-status' },
                    { text: 'Summary Seniority', value: 'seniority' },
                    { text: 'Summary Staff Turn Over', value: 'staff-turn-over' },
                    { text: 'Summary Staff Turn Over Each Month', value: 'staff-turn-over-each-month' },

                ],
                component: null,
            }
        },
        methods: {
            showFilter: function () {
                this.component = this.selected;
            }
        }
    }
</script>
