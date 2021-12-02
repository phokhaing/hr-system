<?php

namespace App\Http\Controllers;

use App\Helper\HTTPStatus;

class BaseResponseController extends Controller
{
    public function response($data = null, $status = HTTPStatus::HTTP_FAIL, $message = ''){
        // if($status ==  HTTPStatus::HTTP_SUCCESS && $data == null){
        //     $message = 'Data is empty!';
        // }
        return response()->json(['status' => $status, 'message' => $message, 'data' => $data]);
    }
}
