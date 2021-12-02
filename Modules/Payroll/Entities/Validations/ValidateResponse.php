<?php


namespace Modules\Payroll\Entities\Validations;

use Illuminate\Support\Facades\Auth;

class ValidateResponse
{
    /**
     * Check user permission be for use function
     */
    public function checkPermission($permissions)
    {
        $user = Auth::user();
        return @$user->can($permissions);
    }

    public function response($data = [], $status, $message){
        return response()->json(['status' => $status, 'message' => $message, 'data' => $data]);
    }
}
