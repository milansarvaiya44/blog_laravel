<?php

namespace App\Traits;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponser
{
    /**
     * Send a JSON response with success data.
     *
     * @param mixed  $data
     * @param string $message
     * @param int    $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data = null, $message = 'Success', $statusCode = 200)
    {
        return response()->json([
            'error' => false,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Send a JSON response with error message.
     *
     * @param mixed  $errors
     * @param int    $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($errors, $statusCode = 200)
    {
        return response()->json([
            'error' => true,
            'message' => $errors
        ], $statusCode);
    }

    /**
     * Send a JSON response with resource data.
     *
     * @param \Illuminate\Http\Resources\Json\JsonResource $resource
     * @param int                                          $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showResponse(JsonResource $resource, $statusCode = 200)
    {
        return response()->json($resource, $statusCode);
    }

    /**
     * Send a JSON response with collection of resources.
     *
     * @param \Illuminate\Http\Resources\Json\ResourceCollection $collection
     * @param int                                                $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function listResponse(ResourceCollection $collection, $statusCode = 200)
    {
        return response()->json($collection, $statusCode);
    }
}
