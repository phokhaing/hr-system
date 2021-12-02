<?php

namespace App\Http\Controllers;

use App\BranchesAndDepartments;
use App\Company;
use App\Http\Resources\CompanyResource;
use App\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * CompanyController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:view_company', ['except' => ['current', 'getCompanyForTraining']]);
        $this->middleware('permission:add_company', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_company', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_company', ['only' => ['destroy']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Company::with('branchAndDepartments')->getCompanyDependOnUser()->latest()->paginate(PER_PAGE);
        return view('company.index', compact('company'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('company.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $validation = Validator::make($input, [
                'company_code' => 'required|unique:companies,company_code',
                'short_name' => 'required|min:2|max:10',
                'name_en' => 'required|min:3|unique:companies,name_en',
                'name_kh' => 'required|min:3|unique:companies,name_kh',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $input['created_by'] = Auth::id();
            $input['updated_by'] = Auth::id();
            Company::create($input);
            return redirect('/company')->with(['success' => 1]);

        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show(int $id)
    {
        try {
            $validation = Validator::make(['id' => $id], [
                'id' => 'required|exists:companies,id',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $company = Company::find($id);
            return view('company.show')->with(compact('company'));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        try {
            $validation = Validator::make(['id' => $id], [
                'id' => 'required|exists:companies,id',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $company = Company::find($id);
            return view('company.edit')->with(compact('company'));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        try {
            $validation = Validator::make($request->all(), [
                'company_code' => 'required|unique:companies,company_code,' . $id,
                'short_name' => 'required|min:2|max:10',
                'name_en' => 'required|min:3',
                'name_kh' => 'required|min:3',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            if (Company::updateOrCreate(['id' => $id], $request->all())) {
                return redirect('/company')->with(['success' => 1]);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $validation = Validator::make(['id' => $id], [
            'id' => 'required|exists:companies,id',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        if (Company::destroy($id)) {
            return redirect('/company')->with(['success' => 1]);
        }
    }

    public function getBranchDepartmentByCompany($companyCode)
    {
        $data = BranchesAndDepartments::where('company_code', $companyCode)->get();
        return view('partials/branch_department_option', compact('data'));
    }

    public function getPositionByCompany($companyCode)
    {
        $data = Position::where('company_code', $companyCode)->get();
        return view('partials/positions_option', compact('data'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function current()
    {
        $currentUser = \auth()->user();
        if (@$currentUser->is_admin) {
            $data = Company::latest()->get();
        } else {
            $company_id = $currentUser->company->id;
            $data = Company::where('id', $company_id)->get();
        }
        return response()->json(['data' => CompanyResource::collection(@$data)]);
    }

    public function all()
    {
        $data = Company::latest()->get();
        return response()->json(['data' => CompanyResource::collection(@$data)]);
    }

    public function getCompanyForTraining()
    {
        $currentUser = \auth()->user();
        if (@$currentUser->is_admin || @$currentUser->can('manage_all_training_company')) {
            $data = Company::latest()->get();
        } else {
            $company_id = $currentUser->company->id;
            $data = Company::where('id', $company_id)->get();
        }
        return response()->json(['data' => CompanyResource::collection(@$data)]);
    }
}
