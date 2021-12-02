<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Resources\PositionCollection;
use App\Http\Resources\PositionResource;
use App\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_position');
        $this->middleware('permission:add_position', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_position', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_position', ['only' => ['destroy']]);

    }

    protected $current_user;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->current_user = \auth()->user();
        $keyword = @$request->get('keyword');

        $position = Position::with('company');
        $companies = Company::getCompanyDependOnUser()->orderBy('company_code', 'asc')->get();
        $position_level = Position::select('group_level')->groupBy('group_level')->get();

        if (!@$this->current_user->is_admin) {
            $position->getByCompanyCode($this->current_user->company_code);
        }

        if(@$request->company){
            $position->getByCompanyCode(@$request->company);
        }

        if (@$request->level) {
            $position->getByLevel(@$request->level);
        }

        if (@$keyword) {
            @$position->where('name_en', 'like', '%' . @$keyword . '%')
                ->orWhere('name_kh', 'like', '%' . @$keyword . '%')
                ->orWhere('short_name', 'like', '%' . @$keyword . '%');
        }

        $position = $position->orderBy('company_code')->latest()->paginate();
        return view('position.index')->with(compact('position', 'companies', 'position_level'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::getCompanyDependOnUser()->orderBy('company_code', 'asc')->get();
        $position_level = Position::select('group_level')->orderBy('group_level')->groupBy('group_level')->get();
        return view('position.create', compact('companies', 'position_level'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            $validation = Validator::make($input, [
                'short_name' => 'nullable|min:2|max:15',
                'name_en' => 'required|min:2|max:190',
                'name_kh' => 'required|min:2|max:190',
                'company_code' => 'required',
                'position_level' => 'required',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $isExist = Position::where(function ($query) use ($request) {
                $query->where('name_kh', $request->get('name_kh'))
                    ->OrWhere('name_en', $request->get('name_en'));
            })
                ->where('company_code', $request->get('company_code'))
                ->first();
            if ($isExist) {
                return redirect()->back()->withErrors(['0' => 'Name En or KH is already token with current company code[' . $request->get('company_code') . '], Please check again!']);
            }

            $max_code = Position::max('code');
            $length = 3;
            $string = $max_code + 1;
            $input['code'] = str_pad($string, $length, "0", STR_PAD_LEFT);
            $input['created_by'] = Auth::id();
            $input['updated_by'] = Auth::id();
            $input["group_level"] = @$input["position_level"];
            Position::create($input);
            DB::commit();
            return redirect('/position')->with(['success' => 1]);

        } catch (\Exception $e) {
            DB::rollBack();
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
                'id' => 'required|exists:positions,id',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $position = Position::find($id);
            return view('position.show')->with(compact('position'));

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
                'id' => 'required|exists:positions,id',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $position = Position::find($id);
            $position_level = Position::select('group_level')->orderBy('group_level')->groupBy('group_level')->get();
            $companies = Company::getCompanyDependOnUser()->orderBy('company_code', 'asc')->get();
            return view('position.edit')->with(compact('position', 'companies', 'position_level'));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();
            $validation = Validator::make($input, [
                'short_name' => 'nullable|min:2|max:15',
                'name_en' => 'required|min:2|max:190',
                'name_kh' => 'required|min:2|max:190',
                'company_code' => 'required',
                'position_level' => 'required',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $isExist = Position::where(function ($query) use ($request) {
                $query->where('name_kh', $request->get('name_kh'))
                    ->OrWhere('name_en', $request->get('name_en'));
            })
                ->where('id', '!=', $id)
                ->where('company_code', $request->get('company_code'))
                ->first();
            if ($isExist) {
                return redirect()->back()->withErrors(['0' => 'Name En or KH is already token with current company code[' . $request->get('company_code') . '], Please check again!']);
            }

            $input['company_code'] = @$input['company_code'];
            $input["group_level"] = @$input["position_level"];
            if (Position::updateOrCreate(['id' => $id], $input)) {
                return redirect('/position')->with(['success' => 1]);
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
            'id' => 'required|exists:positions,id',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        if (Position::destroy($id)) {
            return redirect('/position')->with(['success' => 1]);
        }
    }

    /**
     * List position by company code.
     *
     * @param $companyCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByCompany($companyCode)
    {
        $data = Position::GetByCompanyCode($companyCode)->get();
        return \response()->json(['data' => new PositionCollection($data)]);
    }
}
