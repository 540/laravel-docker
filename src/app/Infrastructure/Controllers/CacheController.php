<?php

namespace App\Infrastructure\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;

class CacheController extends BaseController
{
    public function __invoke(): JsonResponse
    {
        $value = Cache::get('isEarly');
        $user = Cache::get('user');
        return response()->json([
            'status' => 'Success',
            'message' => $value,
            'user' => $user->getId()
        ], Response::HTTP_OK);
    }
}
