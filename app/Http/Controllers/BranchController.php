<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branch = Branch::latest()->paginate();
        return view('branch.index')->with(compact('branch'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        return view('branch.create')->with(compact('companies'));
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
                'code' => 'required|max:5|unique:branches,code',
                'short_name' => 'required|min:3|max:5',
                'company_id' => 'required|exists:companies,id',
                'name_en' => 'required|min:3|unique:branches,name_en',
                'name_kh' => 'required|min:3|unique:branches,name_kh',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $input['created_by'] = Auth::id();
            $input['updated_by'] = Auth::id();
            Branch::create($input);
            return redirect('/branch')->with(['success' => 1]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
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
                'id' => 'required|exists:branches,id',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $branch = Branch::find($id);
            return view('branch.show')->with(compact('branch'));

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
                'id' => 'required|exists:branches,id',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }
            $companies = Company::all();
            $branch = Branch::find($id);
            return view('branch.edit')->with(compact('branch', 'companies'));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request, int $id)
    {
        try {
            $validation = Validator::make($request->all(), [
                'code' => 'required|max:5|unique:branches,code,'.$id,
                'short_name' => 'required|min:3|max:5',
                'company_id' => 'required|exists:companies,id',
                'name_en' => 'required|min:3|unique:branches,name_en,'.$id,
                'name_kh' => 'required|min:3|unique:branches,name_kh,'.$id,
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            if (Branch::updateOrCreate(['id'=> $id], $request->all())) {
                return redirect('/branch')->with(['success' => 1]);
            }

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $validation = Validator::make(['id' => $id], [
            'id' => 'required|exists:branches,id',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        if (Branch::destroy($id)) {
            return redirect('/branch')->with(['success' => 1]);
        }
    }

    /**
     * @param $companyId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ajaxGetBranchSelectOptionByCompany(Request $request)
    {
        $companyId = $request->input('id');
        $branches = DB::table('branches')->where('company_id', $companyId)->whereNull('deleted_at')->get();
        return view('partials.branch_select_option')->with(compact('branches'));

    }
}
