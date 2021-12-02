<?php

namespace App;

use App\StaffInfoModel\StaffPersonalInfo;
use App\Traits\CrudGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class RequestResign extends Model
{
    use SoftDeletes, CrudGenerator;

    protected $table = 'request_resign';

    protected $casts = [
        'resign_object' => 'object',
    ];

    protected $fillable = [
        'id',
        'staff_personal_info_id',
        'resign_object',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function staffPersonalInfo()
    {
        return $this->belongsTo(StaffPersonalInfo::class, 'staff_personal_info_id', 'id');
    }

    /**
     * @param $query
     * @param $keyword
     * @param $company_code
     * @param $branch_department_code
     * @param $position_code
     * @param $gender
     * @param $request_resign_from
     * @param $request_resign_to
     * @return mixed
     */
    public function scopeSearch(
        $query,
        $keyword,
        $company_code,
        $branch_department_code,
        $position_code,
        $gender,
        $request_resign_from,
        $request_resign_to
    )
    {
        return $query->whereHas('staffPersonalInfo', function ($q) use (
            $keyword, $company_code, $branch_department_code, $position_code, $gender, $request_resign_from, $request_resign_to
        )
        {
            if ($key = strtolower(trim(str_replace(" ", "",$keyword)))) {
                $q->where('staff_id', '=',  $key);
                $q->orWhereRaw('LOWER(CONCAT(last_name_kh,first_name_kh)) LIKE ?', ["%$key%"]);
                $q->orWhereRaw('LOWER(CONCAT(last_name_en,first_name_en)) LIKE ?', ["%$key%"]);
            }

            if ($request_resign_from && $request_resign_to) {
                $q->where('resign_object->request_date', '>=', date('Y-m-d', strtotime($request_resign_from)))
                    ->where('resign_object->request_date', '<=', date('Y-m-d', strtotime($request_resign_to)));
            }

            if ($request_resign_from) {
                $q->where('resign_object->request_date', '>=', date('Y-m-d', strtotime($request_resign_from)));
            }

            if ($request_resign_to) {
                $q->where('resign_object->request_date', '<=', date('Y-m-d', strtotime($request_resign_to)));
            }

            if ($company_code) {
                $q->where('resign_object->company->code', (int)$company_code);
            }

            if ($branch_department_code) {
                $q->where('resign_object->branch_department->code', (int)$branch_department_code);
            }

            if ($position_code) {
                $q->where('resign_object->position->code', (int)$position_code);
            }

            if ($gender != NULL) {
                $q->where('gender', '=', $gender);
            }
        });
    }

    /**
     * @param $query
     * @param $company_code
     * @return mixed
     */
    public function scopeByCompanyCode($query, $company_code)
    {
        return $query->where('resign_object->company->code', $company_code);
    }
}
