<?php

namespace App\Http\Controllers;

use App\Services\Wallet\PostWalletOpenService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class PostWalletOpenController extends BaseController
{
    /**
     * @var postWalletOpenService
     */
    private $postWalletOpenService;

    /**
     * PostWalletOpenController constructor.
     * @param PostWalletOpenService $postWalletOpenService
     */
    public function __construct(PostWalletOpenService $postWalletOpenService)
    {
        $this->postWalletOpenService = $postWalletOpenService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $userId = $request->user_id;
            if ($userId == null) {
                throw new Exception('Insufficient arguments in the POST');
            }

            $walletId = $this->postWalletOpenService->execute($userId);
        } catch (Exception $exception) {
            return $this->exceptionHandler($exception);
        }
        return response()->json(
            [
                'wallet_id' => $walletId
            ],
            Response::HTTP_OK
        );
    }

    private function exceptionHandler($exception): JsonResponse
    {
        if ($exception->getMessage() === "User not found") {
            return response()->json(
                [
                    'status' => 'User with the specified ID was not found', 'message' => $exception->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        return response()->json(
            [
                'status' => 'Bad Request Error', 'message' => $exception->getMessage()
            ],
            Response::HTTP_BAD_REQUEST
        );
    }
}
