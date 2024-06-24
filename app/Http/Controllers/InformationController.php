<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class InformationController extends Controller
{

    public function getAll(): JsonResponse
    {
        $info = Information::select('uuid','tag','title','created_at')->orderBy('created_at', 'desc')->get();
        return response()->json($info, Response::HTTP_OK);
    }

    public function getDetail(Information $registeredInfo)
    {
        Log::info($registeredInfo);
        return response()->json($registeredInfo, Response::HTTP_OK);
    }

}
