<?php

if (!function_exists('send_response1')) {
    function send_response($status, $message, $data = null, $extra = null, $success = true)
    {
        $empty_object = new \stdClass();
        
        $response = [
            'status' => $status,
            'success' => $success,
            'message' => $message,
            'data' => $data ?? $empty_object,
        ];

        if ($extra) {
            $response = array_merge($response, $extra);
        }

        return response()->json($response, $status);
    }
}

if (!function_exists('send_error')) {
    function send_error($message = null, $data = null, $status = 400, $extra = null)
    {
        $empty_object = new \stdClass();

        $response = [
            'status' => $status,
            'success' => false,
            'message' => $message ?? __('api.err_something_went_wrong'),
            'data' => $data ?? $empty_object,
        ];

        if ($extra) {
            $response = array_merge($response, $extra);
        }

        return response()->json($response, $status);
    }
}
