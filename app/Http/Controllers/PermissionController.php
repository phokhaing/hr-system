<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:view_permission');
        $this->middleware('permission:edit_permission', ['only' => ['edit','update']]);
        $this->middleware('permission:add_permission', ['only' => ['create','store']]);
        $this->middleware('permission:delete_permission', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permissions = Permission::get();

        return view('permissions.index', compact('permissions'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $this->validate($request, [
                'name' => 'required|unique:permissions,name',
            ]);

            $data = [
                'name' => $request->input('name'),
                'created_by' => Auth::id(),
                'guard_name' => 'web'
            ];
            Permission::create($data);

            DB::commit();

            return redirect()->route('permissions.index')
                ->with('success','Permission created successfully');

        } catch(\Exception $exception) {
            DB::rollBack();
            return $exception;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        return view('permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $permission->name = $request->input('name');
        $permission->updated_by = Auth::id();
        $permission->save();

        return redirect()->route('permissions.index')
            ->with('success','Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Permission $permission
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Permission $permission)
    {
        DB::beginTransaction();

        try {
            $permission->delete();
            $permission->updated_by = Auth::id();
            $permission->save();
            DB::commit();
            return response()->json(['status' => 1]);

        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }
}
