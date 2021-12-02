<?php


namespace Modules\HRTraining\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\HRTraining\Entities\Resources\StaffTrainingResource;
use Modules\HRTraining\Entities\Resources\StaffTrainingResultResource;
use Modules\HRTraining\Entities\Resources\TrainingCourseByCategoryResource;
use Modules\HRTraining\Entities\Trainees;
use Modules\HRTraining\Exports\StaffTrainingCourseByCategoryExport;
use Modules\HRTraining\Exports\StaffTrainingExport;
use Modules\HRTraining\Exports\StaffTrainingResultExport;

class TrainingReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_training_report');
    }

    public function staffTraining(Request $request)
    {
        try {
            //Training Param from client
            $isDownload = $request->input('download');
            $companyCode = $request->input('company_code');
            $branchCode = $request->input('branch_department_code');
            $startDate = $request->input('request_date_from');
            $endDate = $request->input('request_date_to');
            $keyword = $request->input('keyword');

            $trainees = Trainees::with([
                'staff',
                'contract',
                'enrollment'
            ]);

            if ($keyword) {
                $trainees->whereHas('staff', function ($q) use ($keyword) {
                    $q->where(DB::raw("CONCAT(last_name_kh, ' ', first_name_kh)"), 'LIKE', "%$keyword%")
                        ->orWhere(DB::raw("CONCAT(last_name_en, ' ', first_name_en)"), 'LIKE', "%$keyword%");
                });
            }

            if ($startDate && $endDate) {
                $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
                $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
                $trainees->whereHas('enrollment', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                });
            }

            if ($companyCode) {
                $trainees->whereHas('contract', function ($q) use ($companyCode) {
                    $q->where(DB::raw("contract_object->>'$.company.code'"), $companyCode);
                });
            }

            if ($branchCode) {
                $trainees->whereHas('contract', function ($q) use ($branchCode) {
                    $q->where(DB::raw("contract_object->>'$.branch_department.code'"), $branchCode);
                });
            }

            $trainees = $trainees
                ->orderBy('id', 'desc')
                ->get();

            if ($isDownload) {
                return Excel::download(new StaffTrainingExport($trainees), 'staff_training_report.xlsx');
            } else {
                return StaffTrainingResource::collection($trainees);
            }

        } catch (\Exception $e) {
            return $e;
        }
    }

    public function staffTrainingResult(Request $request)
    {
        try {
            //Training Param from client
            $isDownload = $request->input('download');
            $companyCode = $request->input('company_code');
            $branchCode = $request->input('branch_department_code');
            $startDate = $request->input('request_date_from');
            $endDate = $request->input('request_date_to');
            $keyword = $request->input('keyword');

            $trainees = Trainees::with([
                'staff',
                'contract',
                'enrollment',
                'traineeResult'
            ]);

            if ($keyword) {
                $trainees->whereHas('staff', function ($q) use ($keyword) {
                    $q->where(DB::raw("CONCAT(last_name_kh, ' ', first_name_kh)"), 'LIKE', "%$keyword%")
                        ->orWhere(DB::raw("CONCAT(last_name_en, ' ', first_name_en)"), 'LIKE', "%$keyword%");
                });
            }

            if ($startDate && $endDate) {
                $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
                $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
                $trainees->whereHas('enrollment', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                });
            }

            if ($companyCode) {
                $trainees->whereHas('contract', function ($q) use ($companyCode) {
                    $q->where(DB::raw("contract_object->>'$.company.code'"), $companyCode);
                });
            }

            if ($branchCode) {
                $trainees->whereHas('contract', function ($q) use ($branchCode) {
                    $q->where(DB::raw("contract_object->>'$.branch_department.code'"), $branchCode);
                });
            }

            $trainees = $trainees
                ->orderBy('id', 'desc')
                ->get();

            if ($isDownload) {
                return Excel::download(new StaffTrainingResultExport($trainees), 'staff_training_result_report.xlsx');
            } else {
                return StaffTrainingResultResource::collection($trainees);
            }

        } catch (\Exception $e) {
            return $e;
        }
    }

    public function trainingCourseByCategory(Request $request)
    {
        //Training Param from client
        $isDownload = $request->input('download');
        $companyCode = $request->input('company_code');
        $branchCode = $request->input('branch_department_code');
        $startDate = $request->input('request_date_from');
        $endDate = $request->input('request_date_to');
        $keyword = $request->input('keyword');
        $traineeTakeTrainingCode = TRAINING_CONSTANT_TYPE['TRAINEE_TAKE_TRAINING'];

        $sql = "
            select 
                co.id,
                en.json_data->>'$.start_date' as start_date,
                en.json_data->>'$.end_date' as end_date,
                ca.json_data->>'$.title_en' as category_title,
                co.json_data->>'$.title' as course_title,
                com.short_name as company_short_name,
                bd.name_en as branch_department_title,
          
                spi.first_name_en,
                spi.last_name_en,
                spi.staff_id,
                case 
                    when th.status=$traineeTakeTrainingCode then 1
                    else 0
                end as is_trained	
            
            from courses co
                inner join categories ca
                    on ca.id=co.category_id
                inner join enrollments en
                    on en.course_id=co.id
                inner join trainees tr
                    on tr.enrollment_id=en.id
                inner join staff_personal_info spi
                    on spi.id=tr.staff_personal_id
                left join trainee_histories th
                    on tr.id=th.trainee_id
                left join branches_and_departments bd
                    on bd.id=co.branch_department_id
                left join companies com
                    on com.company_code=bd.company_code
                 where co.deleted_at is null 
                    and en.deleted_at is null
        ";

        if(@$keyword){
            $sql .= " and (spi.staff_id LIKE '%$keyword%' or spi.first_name_en LIKE '%$keyword%' or spi.last_name_en LIKE '%$keyword%')";
        }

        if(@$companyCode){
            $sql .= " and com.company_code='$companyCode'";
        }

        if(@$branchCode){
            $sql .= " and bd.code='$branchCode'";
        }

        $sql .= " group by spi.id,co.id,company_short_name,th.status,start_date,end_date
        order by ca.id asc";
        $results = DB::select($sql);

        if ($isDownload) {
            return Excel::download(new StaffTrainingCourseByCategoryExport(collect($results)), 'staff_training_course_by_category.xlsx');
        } else {
            return TrainingCourseByCategoryResource::collection(collect($results));
        }
    }
}