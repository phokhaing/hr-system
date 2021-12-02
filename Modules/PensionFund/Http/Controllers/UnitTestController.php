<?php


namespace Modules\PensionFund\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Modules\PensionFund\Entities\AutoCalculatePensionFund;

class UnitTestController extends Controller
{
    public function calculatePF(){
        $startDate = Carbon::parse('2019-01-25');
        $endDate = Carbon::now();
//        dd($endDate->diffInYears($startDate));
//        dd($endDate->greaterThan($startDate));

//        $calculate = new AutoCalculatePensionFund(3092);
//        dd($calculate->calculatePFFromCompany());
    }

}