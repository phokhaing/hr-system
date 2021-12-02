<?php

namespace Modules\Payroll\Http\Controllers;

use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\DB;
use Modules\Payroll\Entities\Payroll;
use Modules\Payroll\Entities\TempPayroll;

class UnitTestController extends PostFullMonthlyPayrollController
{
    public function nb_mois($date1, $date2)
    {
        $begin = new DateTime($date1);
        $end = new DateTime($date2);
        $end = $end->modify('+1 month');

        $interval = DateInterval::createFromDateString('1 month');

        $period = new DatePeriod($begin, $interval, $end);
        $counter = 0;
        foreach ($period as $dt) {
            $counter++;
        }

        return $counter;
    }

    public function testFullReport()
    {
        $startDate = date('Y-m-d', strtotime('2021-03-01'));
        $currentDate = date('Y-m-d');
        
        $startDate = Carbon::parse('2021-03-02');
        $currentDate = Carbon::now();
        
        dd(nb_mois($startDate, $currentDate), $startDate->diffInMonths($currentDate));
        
        dd(100.4345, number_format(100.4345, 2), round(160.5345, 0));
        dd(round(775886.8));
        // round(56098390.80, -2) to 56098400
        echo $this->nb_mois(date('Y-m-d', strtotime('2020-11-24')), date('Y-m-d')) . '\n';
        echo $this->nb_mois(Carbon::parse('2020-11-24'), Carbon::now()) . '\n';
        echo Carbon::parse('2020-11-24')->diffInMonths(Carbon::now());
        die;
        // $transaction = new Payroll();
        // $collection = $transaction->payrollReport(0, 0, "2021-02-09");
        // dd($collection);
        // $startMont = date('m', strtotime('2020-12-09'));
        // $current = date('m');

        $arrayMonth = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $startMonth = Carbon::parse('2020-1-24');
        $currentMonth = Carbon::now();
        // $diff = $startMonth->floatDiffInMonths("2021-02-24");
        dd($startMonth, $currentMonth,  Carbon::parse('2000-01-15')->floatDiffInMonths('2000-02-24'));

        // if($startMonth > $currentMonth){
        //     $count = $startMonth + $currentMonth;
        // }else{

        // }

        // if($currentMonth < $startMonth){
        $maxValue = [
            1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12,
            1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12
        ];

        // dd($startMonth);
        // $firstIndex = $maxValue[$startMonth-1];
        $firstSlice = array_slice($maxValue, $startMonth->month - 1, 24);
        $searchLastIndex = array_search($currentMonth->month, $firstSlice);
        $endSlice =  array_slice($firstSlice, 0, $searchLastIndex + 1);

        dd($startMonth->month, $currentMonth->month, $searchLastIndex, $maxValue,  $firstSlice, $endSlice, $startMonth->diffInMonths($currentMonth));

        $start = $maxValue[$startMonth];
        $index = $currentMonth;
        do {
            $start++;
        } while ($index < count($maxValue));
        // }
        dd($startMonth, $currentMonth);
    }

    public function saveTempTransHalfMonth()
    {
        $payroll1 = new TempPayroll();
        $payroll1->createRecord(
            [
                'staff_personal_info_id' => 1,
                'contract_id' => 2,
                'transaction_code_id' => TRANSACTION_CODE['UNPAID_LEAVE'],
                'transaction_object' => [
                    "ccy" => "KHR",
                    "amount" => 200000,
                    "before_or_after_tax" => 1,
                    "company" => 'Sahakrinpheap Microfinance Plc.',
                    "branch_department" => 'Pursat Branch'
                ]
            ]
        );

        $payroll1 = new TempPayroll();
        $payroll1->createRecord(
            [
                'staff_personal_info_id' => 1,
                'contract_id' => 2,
                'transaction_code_id' => TRANSACTION_CODE['STAFF_LOAN_PAID'],
                'transaction_object' => [
                    "ccy" => "KHR",
                    "amount" => 50000,
                    "before_or_after_tax" => 2,
                    "company" => 'Sahakrinpheap Microfinance Plc.',
                    "branch_department" => 'Pursat Branch'
                ]
            ]
        );

        $payroll1 = new TempPayroll();
        $payroll1->createRecord(
            [
                'staff_personal_info_id' => 1,
                'contract_id' => 2,
                'transaction_code_id' => TRANSACTION_CODE['OVERTIME'],
                'transaction_object' => [
                    "ccy" => "KHR",
                    "amount" => 20000,
                    "before_or_after_tax" => 1,
                    "company" => 'Sahakrinpheap Microfinance Plc.',
                    "branch_department" => 'Pursat Branch'
                ]
            ]
        );

        $payroll1 = new TempPayroll();
        $payroll1->createRecord(
            [
                'staff_personal_info_id' => 1,
                'contract_id' => 2,
                'transaction_code_id' => TRANSACTION_CODE['POSITION_ALLOWANCE'],
                'transaction_object' => [
                    "ccy" => "KHR",
                    "amount" => 230000,
                    "before_or_after_tax" => 1,
                    "company" => 'Sahakrinpheap Microfinance Plc.',
                    "branch_department" => 'Pursat Branch'
                ]
            ]
        );

        $payroll1 = new TempPayroll();
        $payroll1->createRecord(
            [
                'staff_personal_info_id' => 1,
                'contract_id' => 2,
                'transaction_code_id' => TRANSACTION_CODE['DEGREE_ALLOWANCE'],
                'transaction_object' => [
                    "ccy" => "KHR",
                    "amount" => 370000,
                    "before_or_after_tax" => 1,
                    "company" => 'Sahakrinpheap Microfinance Plc.',
                    "branch_department" => 'Pursat Branch'
                ]
            ]
        );
        echo 'success saveTempTransHalfMonth' . '\n';
    }

    public function saveTempTransactionWithCompanyPaidTax()
    {
        //        $a = 0;
        //        for($i =0; $i < 10; $i++){
        //            $this->testA($a);
        //        }
        //        dd($a);
        //        die;

        //        $payroll1 = new Payroll();
        //        $payroll1->createRecord(
        //            [
        //                'staff_personal_info_id' => 3092,
        //                'contract_id' => 779,
        //                'transaction_code_id' => TRANSACTION_CODE['HALF_SALARY'],
        //                "transaction_date" => date('Y-m-d H:m:s'),
        //                'transaction_object' => [
        //                    "ccy" => "KHR",
        //                    "amount" => 1600000,
        //                    "company" => 'Sahakrinpheap Microfinance Plc.',
        //                    "branch_department" => 'Pursat Branch'
        //                ]
        //            ]
        //        );
        //        echo 'success saveTempTransHalfMonth' . '\n';
        //        die;
        //        dd((float)number_format(12.8934, 2));
        //        $halfAmount = Payroll::select(['transaction_object->amount as half_amount'])
        //            ->where([
        //                'staff_personal_info_id' => 3092,
        //                'transaction_code_id'=> TRANSACTION_CODE['HALF_SALARY']
        //            ])
        //            ->orderBy('id', 'DESC')
        //            ->first();
        //        dd($halfAmount);

        //        $payroll1 = new TempPayroll();
        //        $payroll1->createRecord(
        //            [
        //                'staff_personal_info_id' => 3092,
        //                'contract_id' => 1528,
        //                'transaction_code_id' => TRANSACTION_CODE['BASE_SALARY'],
        //                "exchange" => 4000,
        //                'transaction_object' => [
        //                    "ccy" => "KHR",
        //                    "amount" => 3600000,
        //                    "company" => 'Sahakrinpheap Microfinance Plc.',
        //                    "branch_department" => 'Pursat Branch'
        //                ]
        //            ]
        //        );

        $payroll2 = new TempPayroll();
        $payroll2->createRecord(
            [
                'staff_personal_info_id' => 36,
                'contract_id' => 7,
                'transaction_code_id' => TRANSACTION_CODE['SPOUSE'],
                "exchange" => 4000,
                'transaction_object' => [
                    "ccy" => "KHR",
                    "amount" => 1000000,
                    "company" => 'Sahakrinpheap Microfinance Plc.',
                    "branch_department" => 'Pursat Branch'
                ]
            ]
        );

        //        $payroll3 = new TempPayroll();
        //        $payroll3->createRecord(
        //            [
        //                'staff_personal_info_id' => 3092,
        //                'contract_id' => 779,
        //                'transaction_code_id' => TRANSACTION_CODE['HALF_SALARY'],
        //                "exchange" => 4000,
        //                'transaction_object' => [
        //                    "ccy" => "KHR",
        //                    "amount" => 1600000,
        //                    "company" => 'Sahakrinpheap Microfinance Plc.',
        //                    "branch_department" => 'Pursat Branch'
        //                ]
        //            ]
        //        );
        echo 'success saveTempTransactionWithCompanyPaidTax' . '\n';
    }

    /**
     * Test save temp transaction
     */
    public function saveTempTransaction()
    {
        ///Group1////
        $payroll1 = new TempPayroll();
        $payroll1->createRecord(
            [
                'staff_personal_info_id' => 3070,
                'contract_id' => 1527,
                'transaction_code_id' => TRANSACTION_CODE['BASE_SALARY'],
                "exchange" => 4000,
                'transaction_object' => [
                    "ccy" => "KHR",
                    "amount" => 750,
                    "company" => 'Sahakrinpheap Microfinance Plc.',
                    "branch_department" => 'Pursat Branch'
                ]
            ]
        );

        $payroll2 = new TempPayroll();
        $payroll2->createRecord(
            [
                'staff_personal_info_id' => 3263,
                'contract_id' => 1527,
                'transaction_code_id' => TRANSACTION_CODE['SPOUSE'],
                "exchange" => 4000,
                'transaction_object' => [
                    "ccy" => "KHR",
                    "amount" => 15,
                    "company" => 'Sahakrinpheap Microfinance Plc.',
                    "branch_department" => 'Pursat Branch'
                ]
            ]
        );

        $payroll5 = new TempPayroll();
        $payroll5->createRecord(
            [
                'staff_personal_info_id' => 3263,
                'contract_id' => 1527,
                'transaction_code_id' => TRANSACTION_CODE['FOOD_ALLOWANCE'],
                "exchange" => 4000,
                'transaction_object' => [
                    "ccy" => "KHR",
                    "amount" => 6.25,
                    "company" => 'Sahakrinpheap Microfinance Plc.',
                    "branch_department" => 'Pursat Branch'
                ]
            ]
        );

        $payroll3 = new TempPayroll();
        $payroll3->createRecord(
            [
                'staff_personal_info_id' => 3263,
                'contract_id' => 1527,
                'transaction_code_id' => TRANSACTION_CODE['STAFF_LOAN_PAID'],
                "exchange" => 4000,
                'transaction_object' => [
                    "ccy" => "KHR",
                    "amount" => 2.5,
                    "company" => 'Sahakrinpheap Microfinance Plc.',
                    "branch_department" => 'Pursat Branch'
                ]
            ]
        );


        $payroll4 = new TempPayroll();
        $payroll4->createRecord(
            [
                'staff_personal_info_id' => 3263,
                'contract_id' => 1527,
                'transaction_code_id' => TRANSACTION_CODE['UNPAID_LEAVE'],
                "exchange" => 4000,
                'transaction_object' => [
                    "ccy" => "KHR",
                    "amount" => 30,
                    "company" => 'Sahakrinpheap Microfinance Plc.',
                    "branch_department" => 'Pursat Branch'
                ]
            ]
        );
        echo 'success1' . '\n';
        ///Group1////
    }

    /**
     * Test save temp transaction for staff resign in current month
     */
    public function testSaveStaffResign()
    {
        ///Group1////
        $payroll1 = new TempPayroll();
        $payroll1->createRecord(
            [
                'staff_personal_info_id' => 3268,
                'contract_id' => 1526,
                'transaction_code_id' => TRANSACTION_CODE['FULL_SALARY'],
                "exchange" => 4100,
                'transaction_object' => [
                    "ccy" => "KHR",
                    "amount" => 800000,
                    "company" => 'Sahakrinpheap Microfinance Plc.',
                    "branch_department" => 'Pursat Branch'
                ]
            ]
        );

        $payroll2 = new TempPayroll();
        $payroll2->createRecord(
            [
                'staff_personal_info_id' => 3268,
                'contract_id' => 1526,
                'transaction_code_id' => TRANSACTION_CODE['UNPAID_LEAVE'],
                "exchange" => 4100,
                'transaction_object' => [
                    "ccy" => "KHR",
                    "amount" => 50000,
                    "company" => 'Sahakrinpheap Microfinance Plc.',
                    "branch_department" => 'Pursat Branch'
                ]
            ]
        );
        echo 'testSaveStaffResign' . '\n';
        ///Group1////
    }

    /**
     * Test save temp transaction for new staff
     */
    public function testSaveStaffNewStaff()
    {
        ///Group1////
        $payroll1 = new TempPayroll();
        $payroll1->createRecord(
            [
                'staff_personal_info_id' => 3263,
                'contract_id' => 1527,
                'transaction_code_id' => TRANSACTION_CODE['FULL_SALARY'],
                "exchange" => 4100,
                'transaction_object' => [
                    "ccy" => "KHR",
                    "amount" => 800000,
                    "company" => 'Sahakrinpheap Microfinance Plc.',
                    "branch_department" => 'Pursat Branch'
                ]
            ]
        );

        // $payroll2 = new TempPayroll();
        // $payroll2->createRecord(
        //     [
        //         'staff_personal_info_id' => 3268,
        //         'contract_id' => 1526,
        //         'transaction_code_id' => TRANSACTION_CODE['UNPAID_LEAVE'],
        //         "exchange" => 4100,
        //         'transaction_object' => [
        //             "ccy" => "KHR",
        //             "amount" => 50000,
        //             "company" => 'Sahakrinpheap Microfinance Plc.',
        //             "branch_department" => 'Pursat Branch'
        //         ]
        //     ]
        // );
        echo 'testSaveStaffNewStaff' . '\n';
        ///Group1////
    }
}
