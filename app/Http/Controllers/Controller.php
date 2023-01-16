<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseSuccess($code, $message, $data = null){
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    public function responseFailed($code, $message){
        return response()->json([
            'code' => $code,
            'message' => $message
        ], 500);
    }
}
