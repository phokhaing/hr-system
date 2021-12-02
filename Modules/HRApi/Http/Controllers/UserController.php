<?php

namespace Modules\HRApi\Http\Controllers;

use App\Contract;
use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Http\Request;

class UserController extends BaseApiController
{

    /**
     * Show staff profile with position by staff's id-card.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|A
     */
    public function staffProfile(Request $request)
    {
        if (!$this->checkHintUser($request->hint)) {
            return $this->myResponse(0, 'Authentication failed!');
        }

        $company_code =  (string) $request->company_code;
        $id_card =  (string) $request->id_card;

        $staff_id_card =  $company_code . $id_card;
        // get staff's contract by id card
        $contract = Contract::select('contract_object', 'staff_personal_info_id')
            ->where('staff_id_card', $staff_id_card)
            ->orderBy('start_date', 'DESC')->first();

        if (!$contract) {
            return response()->json(
                [
                    'status' => 'failed',
                    'title'  => 'Sorry! ID card are not found.'
                ]
            );
        } else {
            $spi_id = $contract->staff_personal_info_id;
        }

        // get staff profile by staff's primary id
        $staff = StaffPersonalInfo::find($spi_id);
        $first_employment_date = Contract::select('start_date')->getFirstEmploymentDate($spi_id);
        $staff['first_employment_date'] = $first_employment_date->start_date;
        $staff['contract_object'] = $contract['contract_object'];

        $userData = [
            'full_name_en' => $staff['last_name_en'] . ' ' . $staff['first_name_en'],
            'full_name_kh' => $staff['last_name_kh'] . ' ' . $staff['first_name_kh'],
            'gender' => $staff['gender'],
            'dob'    => $staff['dob'],
            'phone'  => $staff['phone'],
            'first_employment_date'  => $staff['first_employment_date'],
            'email'  => $staff['email'],
            'avatar' => $staff['photo'],
            'contract' => [
                'company' => $staff['contract_object']['company'],
                'position' => $staff['contract_object']['position'],
                'branch_department' => $staff['contract_object']['branch_department']
            ]
        ];

        return $this->myResponse($userData);
    }

    /**
     * All all staff active.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ListStaffProfile(Request $request)
    {
        if (!$this->checkHintUser($request->hint)) {
            return $this->myResponse(0, 'Authentication failed!');
        }

        $company_code =  (string) $request->company_code;

        // get staff's contract by id card
        $contracts = Contract::select('contract_object', 'staff_personal_info_id')
            ->with('staffPersonalInfo')
            ->GetAllStaffActiveByCompany($company_code)
            ->orderBy('start_date', 'DESC')->get();

        if (!$contracts) {
            return $this->myResponse(0, 'Sorry! ID card are not found.');
        }

        $data = [];

        foreach ($contracts as $key => $contract) {
            $first_employment_date = Contract::select('start_date')->getFirstEmploymentDate(@$contract->staffPersonalInfo->id);
            $data[] = [
                'staff_personal_info_id' => @$contract->staffPersonalInfo->id,
                'full_name_en' => @$contract->staffPersonalInfo->last_name_en . ' ' . @$contract->staffPersonalInfo->first_name_en,
                'full_name_kh' => @$contract->staffPersonalInfo->last_name_kh . ' ' . @$contract->staffPersonalInfo->first_name_kh,
                'gender'    => @$contract->staffPersonalInfo->gender,
                'phone'    => @$contract->staffPersonalInfo->phone,
                'dob'    => @$contract->staffPersonalInfo->dob,
                'first_employment_date' => @$first_employment_date->start_date,
                'email'     => @$contract->staffPersonalInfo->email,
                'avatar'    => @$contract->staffPersonalInfo->photo,
                'contract' => [
                    'company'   => @$contract->contract_object['company'],
                    'position'  => @$contract->contract_object['position'],
                    'branch_department' => @$contract->contract_object['branch_department']
                ]
            ];
        }
        return $this->myResponse($data);
    }
}
