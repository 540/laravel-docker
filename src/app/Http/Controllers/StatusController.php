<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class StatusController extends BaseController
{
    public function __invoke(): JsonResponse
    {
        try {
            DB::connection()->getDatabaseName();
            DB::connection()->getPdo();
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Database is not available',
                'data' => $exception->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Systems are up and running',
        ], Response::HTTP_OK);
    }
}
