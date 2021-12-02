<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Company;
use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $department = Department::latest()->paginate();
        return view('department.index')->with(compact('department'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        return view('department.create')->with(compact('companies'));
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
                'name_en' => 'required|min:2|max:300|unique:departments,name_en',
                'name_kh' => 'required|min:2|max:300|unique:departments,name_kh',
                'short_name' => 'required|min:2|max:5',
                'company_id' => 'required|exists:companies,id',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $input['created_by'] = Auth::id();
            $input['updated_by'] = Auth::id();
            Department::create($input);
            return redirect('/department')->with(['success' => 1]);

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
                'id' => 'required|exists:companies,id',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $department = Department::find($id);
            return view('department.show')->with(compact('department'));

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
                'id' => 'required|exists:departments,id',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }
            $companies = Company::all();
            $department = Department::find($id);
            return view('department.edit')->with(compact('department', 'companies'));

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
            $input = $request->all();
            $validation = Validator::make($input, [
                'name_en' => 'required|min:2|max:300',
                'name_kh' => 'required|min:2|max:300',
                'short_name' => 'required|min:2|max:5',
                'company_id' => 'required|exists:companies,id',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            if (Department::updateOrCreate(['id'=> $id], $request->all())) {
                return redirect('/department')->with(['success' => 1]);
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
            'id' => 'required|exists:departments,id',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        if (Department::destroy($id)) {
            return redirect()->back()->with(['success' => 1]);
        }
    }
}
