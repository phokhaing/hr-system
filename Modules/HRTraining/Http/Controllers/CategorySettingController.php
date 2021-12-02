<?php

namespace Modules\HRTraining\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Modules\HRTraining\Entities\Categories;
use Modules\HRTraining\Http\Requests\StoreCategorySettingRequest;
use Modules\HRTraining\Http\Requests\UpdateCategorySettingRequest;

class CategorySettingController extends Controller
{

    /**
     * CategorySettingController constructor.
     */
    public function __construct()
    {
//        $this->middleware('permission:view_category');
//        $this->middleware('permission:add_category', ['only' => ['create', 'store']]);
//        $this->middleware('permission:edit_category', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:delete_category', ['only' => ['destroy']]);

    }

    public function index()
    {
        $categories = Categories::query()->latest()->paginate(PER_PAGE);
        return view('hrtraining::category_setting.index', compact('categories'));
    }

    public function create()
    {
        return view('hrtraining::category_setting.create');
    }

    public function store(StoreCategorySettingRequest $request)
    {
        try {
            $data = [
                'json_data' => $request->except('_token'),
            ];
            $save = (new Categories())->createRecord($data);
            if ($save) {
                return redirect('/hrtraining/category-setting')->with(['success' => 1]);
            }
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('hrtraining::show');
    }

    public function edit($id)
    {
        $category = Categories::findOrFail($id);
        return view('hrtraining::category_setting.edit', compact('category'));
    }

    public function update(UpdateCategorySettingRequest $request, $id)
    {
        try {
            $category = new Categories();
            $data = [ 'json_data' => $request->except('_token') ];
            $update = $category->updateRecord($id, $data);
            if ($update) {
                return redirect('/hrtraining/category-setting')->with(['success' => 1]);
            }
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function destroy($id)
    {
        $category = Categories::query()->findOrFail($id);
        $delete = $category->delete();
        if ($delete) {
            return redirect('/hrtraining/category-setting')->with(['success' => 1]);
        }
    }

    public function detail($id)
    {
        $category = Categories::findOrFail($id);
        return view('hrtraining::category_setting.show', compact('category'));
    }
}
