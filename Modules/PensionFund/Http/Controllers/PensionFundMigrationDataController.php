<?php


namespace Modules\PensionFund\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PensionFund\Imports\PensionFundMigrationCheckStaffNotRegisterYetImport;
use Modules\PensionFund\Imports\PensionFundMigrationImport;

class PensionFundMigrationDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:upload_pension_found_migration');
    }

    public function upload()
    {
        return view('pensionfund::migrate.upload');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xls,xlsx',
        ]);

        $path1 = $request->file('excel_file')->store('temp');
        $path = storage_path('app') . '/' . $path1;
        $save = Excel::import(new PensionFundMigrationImport, $path);

        if ($save) {
            return redirect()->back()->with(['success' => 1]);
        }
    }

    /**
     * Check all staff not yet register in system yet before migrate data
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importCheck(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xls,xlsx',
        ]);

        $path1 = $request->file('excel_file')->store('temp');
        $path = storage_path('app') . '/' . $path1;
        Excel::import(new PensionFundMigrationCheckStaffNotRegisterYetImport, $path);
    }

}