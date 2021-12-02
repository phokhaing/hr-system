<?php

namespace App\Http\Controllers;

use App\ContractActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractActionsController extends Controller
{
    /**
     * ContractController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function store(Request $request)
    {
        $contractType = $request->get("contract_type");
        $contractId = $request->get("contract_id");
        $staffPersonalId = $request->get("staff_personal_id");
        $approvedDate = $request->get("approved_date");
        $lastDay = $request->get("last_day");

        DB::beginTransaction();
        try {
            $objects = array(
                'contract_type' => $contractType,
                'approval_date' => date('Y-m-d H:i:s', strtotime($approvedDate)),
                'last_day' => date('Y-m-d H:i:s', strtotime($lastDay))
            );

            ContractActions::create([
                'staff_personal_id' => $staffPersonalId,
                'contract_id' => $contractId,
                'objects' => json_encode($objects)
            ]);

            DB::commit();
            return redirect()->back()->with(['success' => 1]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
}
