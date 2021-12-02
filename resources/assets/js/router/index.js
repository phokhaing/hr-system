
import Examination from "../views/TrainingModule/Examination"
import CreateExam from '../components/training_module/examination/CreateExam.vue'
import EditExam from '../components/training_module/examination/EditExam.vue'
import PayrollPage from "../views/Payroll/PayrollPage";
import PayrollFullMonthPage from "../views/Payroll/PayrollFullMonthPage";
import ViewPostedPayrollFullMonth from "../components/payroll_module/ViewPostedPayrollFullMonth";
import ViewPostedPayrollHalfMonth from "../components/payroll_module/ViewPostedPayrollHalfMonth";


export default {
    mode: 'history',

    routes: [
        {
            path: '/hrtraining/examination-setting',
            name: 'examination-setting',
            component: Examination,
        },
        {
            path: '/hrtraining/examination-setting',
            name: 'examination-setting-create',
            component: CreateExam,
        },
        {
            path: '/hrtraining/examination-setting/:exam',
            name: 'examination-setting-edit',
            component: EditExam,
        },
        {
            path: '/payroll',
            name: 'payroll-page',
            component: PayrollPage,
        },
        {
            path: '/payroll/full-month',
            name: 'payroll-full-month-page',
            component: PayrollFullMonthPage,
        },
        {
            path: '/payroll/view-posted-list',
            name: 'view-posted-payroll-full-month-page',
            component: ViewPostedPayrollFullMonth,
        },
        {
            path: '/payroll/view-posted-list-half-month',
            name: 'view-posted-payroll-half-month-page',
            component: ViewPostedPayrollHalfMonth,
        }
    ]
}