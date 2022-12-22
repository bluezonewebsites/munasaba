<?php namespace App\Traits;
//use Response;

use Auth;

trait apiResponse
{
    protected function apiResponse($request, $message, $data, $successStatus, $statusCode = null)
    {

        $response['message'] = $message;
        if ($data != null)
            $response['data'] = $data;
        $response['success'] = $successStatus;
        if (!$statusCode) {
            $statusCode = 200;
            $response['statusCode'] = $statusCode;
        } else {
            $response['statusCode'] = $statusCode;
        }
        return \Response::json($response, $statusCode);
    }


    protected function sendResponse($request, $message, $data, $successStatus, $accessToken, $statusCode = null)
    {

        $response['message'] = $message;
        if ($data != null)
            $response['data'] = $data;
        $response['success'] = $successStatus;
        $response['accessToken'] = $accessToken;
        $response['tokenType'] = "Bearer";
        if (!$statusCode) {
            $statusCode = 200;
            $response['statusCode'] = $statusCode;
        } else {
            $response['statusCode'] = $statusCode;
        }
        return \Response::json($response, $statusCode);
    }



    //new
    function success_response(array $extra = [])
    {
        return array_merge([
            'result' => true,
        ], $extra);
    }
    function fail_response(array $extra = [])
    {
        return array_merge([
            'result' => false,
        ], $extra);
    }
}
