<?php


namespace Modules\PensionFund\Http\Controllers;

use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class PensionFundReportUnitTestController extends Controller
{
    public function summaryReport()
    {
        $pensionFunds = DB::select("
               select
                    c.contract_object->'$.company.id' company_id,
                    c.contract_object->>'$.company.name_kh' company_name,
                    c.contract_object->>'$.branch_department.name_kh' department_branch,
                    
                    sum(pf.json_data->'$.acr_balance_staff') total_pension_fund
                    from pension_funds pf 
                    inner join contracts c
                      on c.id=pf.contract_id
                     group by company_id,company_name,department_branch
            ");
        dd($pensionFunds);
    }

    public function currentStaff(){
        $items = DB::select("
            select
                        spi.staff_id staff_id,
                        spi.first_name_kh first_name_kh,
                        spi.last_name_kh last_name_kh,
                        spi.first_name_en first_name_en,
                        spi.last_name_en last_name_en,
                        c.staff_id_card company_id_card, 
                        c.contract_object->>'$.company.id' company_id, 
                        c.contract_object->>'$.company.name_kh' company,
                        c.contract_object->>'$.branch_department.name_kh' department_branch,
                        
                        sum(pf.json_data->'$.acr_balance_staff') total_pension_fund
                            from pension_funds pf 
                            inner join contracts c
                              on c.id=pf.contract_id
                            inner join staff_personal_info spi
                              on spi.staff_id=pf.staff_personal_id
                              
                              group by staff_id,
                              company_id, 
                              company, 
                              department_branch,
                              first_name_kh,
                              last_name_kh,
                              first_name_en,
                              last_name_en,
                              company_id_card
                              
                         order by company_id asc       
        ");
        dd($items);
    }

    public function getAllStaffPersonal(){
        return print_r(StaffPersonalInfo::get());
    }
}