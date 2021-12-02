<?php

namespace App\Http\Controllers;

use App\Company;
use App\Contract;
use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::with(['company']);

        $keyword = $request->get('keyword');
        if (@$keyword) {
            $data->where(function ($query) use ($keyword) {
                @$query->where('username', 'like', '%' . @$keyword . '%')
                    ->orWhere('full_name', 'like', '%' . @$keyword . '%')
                    ->orWhere('email', 'like', '%' . @$keyword . '%');
            });
        }

        $role = $request->get('role');
        if (@$role) {
            $data->role($role);
        }

        $companyCode = $request->get('company_code');
        if (@$companyCode) {
            $data->where('company_code', $companyCode);
        }

        $data = $data->orderBy('id', 'DESC')
            ->getDependOnUser()
            ->paginate(10);

        $roles = Role::get();
        $companies = Company::getCompanyDependOnUser()->get();
        return view('users.index', compact('data', 'roles', 'companies'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function create()
    {
        //User role as hr training can create user only trainee
        if (@auth::user()->hasRole(['HR Manage All Training Company'])) {
            $roles = Role::where('name', 'Trainee')->get();
        } else {
            $roles = Role::all();
        }

        $companies = Company::getCompanyDependOnUser()->get();
        $staffs = StaffPersonalInfo::with('currentContract')->select('id', 'last_name_en', 'first_name_en')
            ->whereHas('contract', function ($sub_query) {
                return $sub_query->getDependOnUser();
            })->get();

        return view('users.create', compact('roles', 'companies', 'staffs'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
            'company_code' => 'required',
            'staff_name' => 'required'
        ]);
        $input = $request->all();
        $company = Company::where('company_code', $request->input('company_code'))->first();
        
        if (@$request->input('is_admin') != null) {
            $input['is_admin'] = 1;
        } else {
            $input['is_admin'] = 0;
        }

        $input['password'] = Hash::make($input['password']);
        $input['created_by'] = Auth()->user()->id;
        $input['company_object'] = [
            'company_object' => [
                'code' => $company->company_code,
                'name_en' => $company->name_en,
                'name_kh' => $company->name_kh,
                'short_name' => $company->short_name
            ]
        ];
        $input['staff_personal_info_id'] = $input['staff_name'];

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Check id user, If current user equal id request Edit will skip
        if (auth()->id() == $id) {
            return redirect()->back()->with(['message' => 'You can\'t edit your self. ']);
        }
        $user = User::find($id);

        //User role as hr training can create user only trainee
        if (@auth::user()->hasRole(['HR Manage All Training Company'])) {
            $roles = Role::where('name', 'Trainee')->get();
        } else {
            $roles = Role::all();
        }

        $userRole = $user->roles->all();
        $companies = Company::getCompanyDependOnUser()->get();

        return view('users.edit', compact('user', 'roles', 'userRole', 'companies'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = array_except($input, array('password'));
        }

        $input['updated_by'] = Auth()->user()->id;
        $company = Company::where('company_code', $request->input('company_code'))->first();
        if (@$request->input('is_admin') != null) {
            $input['is_admin'] = 1;
        } else {
            $input['is_admin'] = 0;
        }
        $input['company_object'] = [
            'company_object' => [
                'code' => $company->company_code,
                'name_en' => $company->name_en,
                'name_kh' => $company->name_kh,
                'short_name' => $company->short_name
            ]
        ];

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['status' => 1]);
    }

    public function viewProfile()
    {
        $user = auth()->user();
        return view('users.view_profile', compact('user'));
    }

    public function updateProfile(Request $request, $id)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => 'same:confirm_password|min:6|string',
        ]);
        $input = $request->all();

        if (!empty($input['new_password']) && !empty($input['current_password'])) {

            if (!(Hash::check($input['current_password'], Auth::user()->password))) {
                // The passwords matches
                return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
            }

            if (strcmp($input['current_password'], $input['new_password']) == 0) {
                //Current password and new password are same
                return redirect()->back()->with("error", "New Password cannot be same as your current password. Please choose a different password.");
            }

            $input['password'] = Hash::make($input['new_password']);
        }
        $input['updated_by'] = Auth()->user()->id;
        $user = User::find($id);
        $user->update($input);

        return redirect()->route('user.viewProfile')->with('success', 'Change password successfully !');
    }
}
