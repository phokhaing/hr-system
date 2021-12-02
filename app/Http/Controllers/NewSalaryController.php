<?php


namespace App\Http\Controllers;


use App\Contract;
use App\NewSalary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewSalaryController extends Controller
{
    public function index($id)
    {
        $currency = CURRENCY;
        $contract = Contract::find($id);
        $newSalaries = NewSalary::where('contract_id', $id)->orderBy('effective_date', 'DESC')->get();
        return view('contract.new_salary', compact('currency', 'contract', 'newSalaries'));
    }

    public function delete(Request $request)
    {
        $newSalary = NewSalary::find($request->id);
        $deleted = @$newSalary->delete();
        return response()->json([
            'status' => isset($deleted),
            'data' => $deleted
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'contract_id' => 'required',
            'amount' => 'required',
            'effective_date' => 'required'
        ]);

        $contract = Contract::find($request->contract_id);
        $newSalary = new NewSalary();
        $data = [
            "contract_id" => $request->contract_id,
            "object" => [
                "old_salary" => @$contract->contract_object['salary'],
                "new_salary" => $request->amount,
                "notice" => $request->notice
            ],
            "created_by" => Auth::id(),
            "created_at" => date('Y-m-d H:i:s'),
            "updated_by" => Auth::id(),
            "updated_at" => date('Y-m-d H:i:s'),
            "effective_date" => date('Y-m-d H:i:s', strtotime($request->effective_date)),
        ];
        $save = $newSalary->createRecord($data);
        if (isset($save)) {
            //Update current contract salary to new salary
            $data = $contract->contract_object;
            $data['salary'] = $request->amount;
            $contract->contract_object = $data;
            $contract->save();

            return redirect()->back()->with(['success' => 1]);
        } else {
            return redirect()->back()->withErrors(['Something went wrong!, please try again']);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'contract_id' => 'required',
            'amount' => 'required',
            'effective_date' => 'required'
        ]);

        $contract = Contract::find($request->contract_id);
        $newSalary = NewSalary::find($request->id);
        $data = [
            "contract_id" => $request->contract_id,
            "object" => [
                "old_salary" => @$newSalary->object->old_salary,
                "new_salary" => $request->amount,
                "notice" => $request->notice
            ],
            "effective_date" => date('Y-m-d H:i:s', strtotime($request->effective_date)),
        ];
        $save = $newSalary->updateMyRecord($data);

        if (isset($save)) {
            //Update current contract salary to new salary
            $data = $contract->contract_object;
            $data['salary'] = $request->amount;
            $contract->contract_object = $data;
            $contract->save();

            return redirect()->back()->with(['success' => 1]);
        } else {
            return redirect()->back()->withErrors(['Something went wrong!, please try again']);
        }
    }

}