<?php

namespace App\Http\Controllers;

use App\Company;
use App\Contract;
use App\StaffInfoModel\StaffMovement;
use App\StaffInfoModel\StaffPersonalInfo;
use App\StaffInfoModel\StaffResign;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $staffActive = Contract::getAllStaffActive()->get()->count();
        $endContract = Contract::getAllEndContract()->get()->count();
        $movement = Contract::getAllMovementContract()->get()->count();

        return view('index', compact('staffActive', 'endContract', 'movement'));
    }
}
