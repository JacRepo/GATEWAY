<?php

namespace App\Traits;
use Illuminate\Http\Response;

trait ApiResponser
{
    /**
     * Build success response
     * @param  string|array $data
     * @param  int $code
     * @return Illuminate\Http\JsonResponse
     */
    
    public function successResponse($data, $code = Response::HTTP_OK)
    {
        // old code 
        // // return response()->json(['data' => $data, 'site' => 1], $code);
        // // this code is changes since the message to return is already formatted by API responser of each site
        return response($data, $code)->header('Content-Type', 'application/json');
    }
    
    /**
     * Build error responses
     * @param  string|array $message
     * @param  int $code
     * @return Illuminate\Http\JsonResponse
     */
    public function errorResponse($message, $code)
    {
        return response($message, $code)->header('Content-Type', 'application/json');
    }
}
