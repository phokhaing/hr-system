<?php

namespace App\Http\Controllers\StaffInfo;

use App\Http\Requests\StaffInfo\StaffProfileStore;
use App\StaffInfoModel\StaffPersonalInfo;
use App\StaffInfoModel\StaffProfile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StaffProfileController extends Controller
{
    /**
     * Show form edit OR create new staff profile
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $staff = StaffPersonalInfo::findOrFail(decrypt($id));
            $companies = DB::table('companies')->whereNull('deleted_at')->orderBy('name_kh')->get();
            $departments = DB::table('departments')->whereNull('deleted_at')->orderBy('name_kh')->get();
            $branches = DB::table('branches')->whereNull('deleted_at')->orderBy('name_kh')->get();
            $positions = DB::table('positions')->whereNull('deleted_at')->orderBy('name_kh')->get();
            $currency = ['1' => 'រៀល', '2' => 'ដុល្លារ​អាមេរិក'];

            return view('staffs.edit-profile', compact(
                'staff', 'companies', 'departments', 'branches', 'positions', 'currency'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * @param StaffProfileStore $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(StaffProfileStore $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->except(['staff_token', '_token']);
            $input = $input['profile'];
            $input['created_by'] = Auth::id();
            $input['staff_personal_info_id'] = decrypt($request->staff_token);

            $raw = $input["base_salary"];
            $explode = str_replace(",", "", $raw);
            $salary = explode(".", $explode);
            $input['base_salary'] = $salary[0];

            $save = StaffProfile::create($input);

            if ($save == true) {
                DB::commit();
                return redirect()->back()->with(['success' => 1]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * @param StaffProfileStore $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(StaffProfileStore $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->except(['staff_token', '_token']);
            $input = $input['profile'];
            $input['created_by'] = Auth::id();
            $input['updated_by'] = Auth::id();
            $input['staff_personal_info_id'] = decrypt($request->staff_token);

            $raw = $input["base_salary"];
            $explode = str_replace(",", "", $raw);
            $salary = explode(".", $explode);
            $input['base_salary'] = $salary[0];

            $save = StaffProfile::where('staff_personal_info_id', decrypt($request->staff_token))->update($input);

            if ($save == true) {
                DB::commit();
                return redirect()->back()->with(['success' => 1]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }
}
