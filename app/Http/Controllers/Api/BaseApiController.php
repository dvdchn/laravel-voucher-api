<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

/**
 * Base API Controller.
 *
 * This class provides common methods for handling API responses,
 * including success, error, and resource creation responses.
 *
 * @category API
 * @package  App\Http\Controllers\Api
 * @author   David Chan <dvdchn@github.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/dvdchn/laravel-voucher-api
 */
class BaseApiController extends BaseController
{
    /**
     * Send a successful response.
     *
     * @param mixed  $data    The data to include in the response.
     * @param string $message The message to include in the response.
     * @param int    $status  The HTTP status code for the response.
     * 
     * @return JsonResponse    The JSON response.
     */
    protected function success(
        $data = null,
        string $message = 'Request was successful.',
        int $status = 200
    ): JsonResponse {
        return response()->json(
            [
            'success' => true,
            'data'    => $data,
            'message' => $message,
            ], $status
        );
    }

    /**
     * Send an error response.
     *
     * @param string $error         The error message.
     * @param array  $errorMessages An array of error messages.
     * @param int    $status        The HTTP status code for the response.
     * 
     * @return JsonResponse           The JSON response.
     */
    protected function error(
        string $error,
        array $errorMessages = [],
        int $status = 400
    ): JsonResponse {
        return response()->json(
            [
            'success' => false,
            'message' => $error,
            'errors'  => $errorMessages,
            ], $status
        );
    }

    /**
     * Send a resource created response.
     *
     * @param mixed  $data    The data to include in the response.
     * @param string $message The message to include in the response.
     * 
     * @return JsonResponse    The JSON response.
     */
    protected function created(
        $data,
        string $message = 'Resource created successfully.'
    ): JsonResponse {
        return response()->json(
            [
            'success' => true,
            'data'    => $data,
            'message' => $message,
            ], 201
        );
    }
}
