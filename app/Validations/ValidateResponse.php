<?php


namespace App\Validations;

use App\Helper\HTTPStatus;
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
}
