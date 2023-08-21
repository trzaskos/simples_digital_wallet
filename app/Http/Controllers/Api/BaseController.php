<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * Default return success
     *
     * @param array $result
     * @param string $message
     * @return JsonResponse
     */
    public function sendSuccess(array $result = [], string $message = ''): JsonResponse
    {
        $response = [
            'success' => true,
        ];

        if (!empty($result)) {
            $response['data'] = $result;
        }

        if (!empty($message)) {
            $response['message'] = $message;
        }

        return response()->json($response);
    }

    /**
     * Default return error
     *
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($error, array $errorMessages = [], int $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
