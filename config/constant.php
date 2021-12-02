<?php

define("CURRENCY", array(
    "KHR" => "Khmer",
    "USD" => "Dollar"
));

const STORE_CURRENCY_KHR = 'KHR';
const STORE_CURRENCY_USD = 'USD';

const CONTRACT_TYPE = [
    // 'PROBATION' => 1,
    'FDC' => 2, // REGULAR it is change to FDC
    'RESIGN' => 3,
    'DEATH' => 4,
    'TERMINATE' => 5,
    'DEMOTE' => 6,
    'PROMOTE' => 7,
    'LAY_OFF' => 8,
    'CHANGE_LOCATION' => 9,
    'UDC' => 10
];

const CONTRACT_ACTIVE_TYPE = [
    // 'PROBATION' => 1,
    'FDC' => 2, // REGULAR it is change to FDC
    'DEMOTE' => 6,
    'PROMOTE' => 7,
    'UDC' => 10,
    'CHANGE_LOCATION' => 9
];

const CONTRACT_END_TYPE = [
    'RESIGN' => 3,
    'DEATH' => 4,
    'TERMINATE' => 5,
    'LAY_OFF' => 8
];

const REPORT_EXPORT_TYPE = [
    'ACTIVE' => 0,
    'END_CONTRACT' => 1,
    'MOVEMENT' => 2
];

const PER_PAGE = 15;

const MARITAL_STATUS = [
    "1" => "រៀបការរួច",
    "2" => "នៅលីវ",
    "3" => "ពោះម៉ាយ",
    "4" => "មេម៉ាយ"
];

const GENDER = [
    "0" => "ប្រុស",
    "1" => "ស្រី"
];

const GENDER_EN = [
    "0" => "Male",
    "1" => "Female"
];

const REQUEST_VALUE = 'REQUEST';
const APPROVED_VALUE = 'APPROVED';
const  REJECTED_VALUE = 'REJECTED';

const REQUEST_KEY = 1;
const APPROVED_KEY = 2;
const  REJECTED_KEY = 3;

const FORM_REPORTS = [
    'form_active' => 'Staff Active',
    'form_end_contract' => 'Staff End Contract',
    'form_movement' => 'Staff Movement'
];

const SELECT_ALL = '<option>>> All <<</option>';

const BRANCH = 1;
const DEPARTMENT = 2;
const HEADOFFICE = 3;

// Range of department code.
const MIN_DEPARTMENT_CODE = 80; // X80 EX: 580
const MAX_DEPARTMENT_CODE = 99; // X99 EX: 599

const HEAD_OFFICE = 00; // X00 EX: 500, 700

const ANSWER_STATUS = [
    'CORRECT' => 1,
    'WRONG' => 2
];

const ANSWER_STATUS_KEY = [
    1 => 'Correct',
    2 => 'Wrong'
];

// Day for calculation half payroll
define("START_DATE_FULL_PAYROLL", 6);
define("END_DATE_FULL_PAYROLL", 19);
define("NEW_STAFF_START_FROM_IN_20_OF_MONTH", 20);

const DEFAULT_EXCHANGE = 4100;
const DEDUCTION_PENSION_FUND = 0.05;
const FRINGE_ALLOWANCE_TAX = 0.2;

/* Transaction Code */
const TRANSACTION_CODE = [
    'HALF_SALARY' => 1,
    'FULL_SALARY' => 2,
    'NET_SALARY' => 3,
    'SALARY_BEFORE_TAX' => 4,
    'SALARY_AFTER_TAX' => 5,
    'SPOUSE' => 6,
    'OVERTIME' => 7,
    'RETROACTIVE_SALARY' => 8,
    'BONUS_PCHUM_BEN_AND_NEW_YEAR' => 9,
    'UNPAID_LEAVE' => 10,
    'STAFF_LOAN_PAID' => 11,
    'TAX_ON_SALARY' => 12,
    'INCENTIVE' => 13,
    'PENSION_FUND' => 14,
    'LOCATION_ALLOWANCE' => 15,
    'BASE_SALARY' => 16,
    'FRINGE_ALLOWANCE' => 17,
    'INSURANCE_PAY' => 18,
    'SENIORITY_PAY' => 19,
    'MATERNITY_LEAVE' => 20,
    'FOOD_ALLOWANCE' => 21,
    'SALARY_DEDUCTION' => 22,
    'THIRD_SALARY_BONUS' => 23,
    'DEGREE_ALLOWANCE' => 24,
    'POSITION_ALLOWANCE' => 25,
    'NSSF' => 26,
    'ATTENDANCE_ALLOWANCE' => 27,
    'TAX_ON_FRINGE_ALLOWANCE' => 28,
];

const TRANSACTION_BEFORE_OR_AFTER_TAX = [
    'BEFORE' => 1,
    'AFTER' => 2
];

const TRANSACTION_CALCULATE_TYPE = [
    'ADDITION' => 1,
    'DEDUCTION' => 2,
    'SPACIAL' => 3
];

/*Company Code*/
const COMPANY_CODE = [
    'NGO' => 100,
    'ORD' => 200,
    'MHT' => 300,
    'PWS' => 400,
    'MFI' => 500,
    'TSP' => 600,
    'MMI' => 700,
    'ST' => 800,
    'STSK' => 900,
    'MDN' => 1100,
    'PETIT_K' => 1200,
];

const SPOUSE_CHILD_TAX_INCLUDE_AMOUNT = 150000;// this is will move to table payroll setting next step

//Payroll Setting
const PAYROLL_SETTING_TYPE = [
    'EXCHANGE_RATE' => 1,
    'PENSION_FOUND' => 2,
    'PAYROLL_HALF_MONTH' => 3,
    'PAYROLL_DATE_BETWEEN' => 4,
    'FRINGE_ALLOWANCE_TAX_RATE' => 5,
    'SENIORITY' => 6,
];

//Final Pay Const
const FINAL_PAY_STATUS = [
    'CHECKING' => 0,
    'POSTED' => 1
];