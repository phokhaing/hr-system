<?php


class Permissions
{
    //Payroll half month
    const CHECKING_PAYROLL_FULL_MONTH = 'checking_payroll_full_month';
    const VIEW_CHECKING_LIST_PAYROLL_HALF_MONTH = 'view_checking_list_payroll_half_month';
    const POST_PAYROLL_HALF_MONTH = 'post_half_payroll';
    const VIEW_POSTED_PAYROLL_HALF_MONTH = 'view_posted_list_payroll_half_month';
    const UN_POST_PAYROLL_HALF_MONTH = 'un_post_payroll_half_month';
    
    //Payroll full month
    const CHECKING_PAYROLL_HALF_MONTH = 'checking_half_payroll';
    const VIEW_CHECKING_LIST_PAYROLL_FULL_MONTH = 'view_checking_list_payroll_full_month';
    const POST_PAYROLL_FULL_MONTH = 'post_payroll_full_month';
    const UN_POST_PAYROLL_FULL_MONTH = 'un_post_payroll_full_month';
    const VIEW_POSTED_PAYROLL_FULL_MONTH = 'view_posted_list_payroll_full_month';
    const VIEW_PAYROLL_FULL_MONTH_REPORT = 'view_payroll_full_month_report';
    
    //Payroll Setting
    const VIEW_PAYROLL_SETTING = 'view_payroll_setting';
    const SET_EXCHANGE_RATE = 'set_exchange_rate';
    const SET_PENSION_FUND = 'set_pension_fund';
    const SET_PAYROLL_HALF_MONTH = 'set_payroll_half_month';
    const SET_PAYROLL_DATE_BETWEEN = 'set_payroll_date_between';
    const SET_RINGE_ALLOWANCE_TAX_RATE = 'set_fring_allowance_tax_rate';
    
    const SET_TAX_ON_SALARY = 'set_tax_on_salary';
    const UPDATE_TAX_ON_SALARY = 'update_tax_on_salary';
    const DELETE_TAX_ON_SALARY = 'delete_tax_on_salary';
    const ADD_TAX_ON_SALARY = 'add_tax_on_salary';
    
    const SET_PENSION_FUND_RATE_FROM_COMPANY = 'set_pension_fund_rate_from_company';
    const UPDATE_PENSION_FUND_RATE_FROM_COMPANY = 'update_pension_fund_rate_from_company';
    const DELETE_PENSION_FUND_RATE_FROM_COMPANY = 'delete_pension_fund_rate_from_company';
    const ADD_PENSION_FUND_RATE_FROM_COMPANY = 'add_pension_fund_rate_from_company';

    const SET_SENIORITY = 'set_seniority';

    const VIEW_TRANSACTION_CODE = 'view_transaction_code';
    
    const CLEAR_CHECKING_LIST_HALF_MONTH = 'clear_checking_list_half_month';
    const CLEAR_CHECKING_LIST_FULL_MONTH = 'clear_checking_list_full_month';
    
    const POST_BACK_DATE_HALF_MONTH = 'post_back_date_half_month';
    const POST_BACK_DATE_FULL_MONTH = 'post_back_date_full_month';
}