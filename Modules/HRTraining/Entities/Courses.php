<?php

namespace Modules\HRTraining\Entities;

use App\BranchesAndDepartments;
use App\Position;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\HRTraining\Entities\Filters\CourseFilter;
use Modules\HRTraining\Traits\CRUDable;

class Courses extends Model
{
    use SoftDeletes, CRUDable;

    protected $casts = ['json_data' => 'object'];

    protected $table = 'courses';

    protected $fillable = [
        'id',
        'category_id',
        'branch_department_id',
        'json_data',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    //***Relationship block
    public function branchDepartment()
    {
        return $this->belongsTo(BranchesAndDepartments::class, 'branch_department_id', 'id');
    }

    public function enrollment()
    {
        return $this->hasOne(Enrollments::class, 'course_id', 'id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, "position_id", "id");
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, "category_id", "id");
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function content()
    {
        return $this->hasOne(CourseContents::class, 'course_id', 'id');
    }

    public function contents()
    {
        return $this->hasMany(CourseContents::class, 'course_id', 'id');
    }
    //***End Relationship block

    //***Scope block
    public function scopeFilter($query, CourseFilter $filter)
    {
        return $filter->apply($query);
    }

    public function scopeWithPosition($q)
    {
        return $q->with('position')->select([
            '*',
            DB::raw("cast(json_extract(courses.json_data, '$.position') as SIGNED) as position_id")
        ]);
    }

    public function scopeWithDetail($q)
    {
        return $q
            ->withPosition()
            ->with(['category', 'createdBy']);
    }

    public function scopeWithContent($q)
    {
        return $q->with(['content' => function ($query) {
            return $query->select([
                '*'
            ]);
        }]);
    }

    public function scopeCourseObject($q, $id)
    {
        return $q->select('json_data')->find($id);
    }

    public function scopeWithCourseInfo($q)
    {
        return $q->select([
            'id',
            DB::raw('json_data->>"$.title" as title')
        ])
            ->orderBy('id', 'DESC');
    }

    public function scopeLastContent($query)
    {
        return $query->with(['content' => function ($q) {
            return $q->orderBy('id', 'DESC')->limit(1);
        }]);
    }

    public function scopeGetCourseByUser($query)
    {
        $user = Auth::user();
        if (!@$user->is_admin && !@$user->can('manage_all_training_company')) {
            $query->where('created_by', @$user->id);
        }
        return $query;
    }
    //***End Scope block
}
