<?php

if (!function_exists('res')) {
    function res($status = SUCCESS, $msg = '', $data = [])
    {
        return response()->json([
            'status' => $status,
            'message' => $msg,
            'data' => (object) $data
        ]);
    }
}

if (!function_exists('res_success')) {
    function res_success($msg = 'Success!', $data = [])
    {
        return response()->json([
            'status' => SUCCESS,
            'message' => $msg,
            'data' => (object) $data
        ]);
    }
}

if (!function_exists('res_failed')) {
    function res_failed($msg = 'Failed!')
    {
        return response()->json([
            'status' => FAILED,
            'message' => $msg,
            'data' => (object) []
        ]);
    }
}

if (!function_exists('res_exception')) {
    function res_exception($msg = 'Server error!')
    {

        if (!config('app.debug'))
            $msg = 'Server error!';

        return response()->json([
            'status' => EXCEPTION_500,
            'message' => $msg,
            'data' => (object) []
        ]);
    }
}
