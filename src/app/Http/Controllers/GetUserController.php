<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class GetUserController extends BaseController
{
    public function __invoke(string $id): JsonResponse
    {
        $user = DB::table('users')->where('id', $id)->first();
        return response()->json([
            'name' => $user->name,
            'email' => $user->email
        ], Response::HTTP_OK);
    }
}
