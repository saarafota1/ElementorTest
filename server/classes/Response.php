<?php

class Response {
    public static function msg($success,$status,$message,$extra = []){
        return array_merge([
            'success' => $success,
            'status' => $status,
            'message' => $message
        ],$extra);
    }

    public static function send($res){
        echo json_encode($res);
        http_response_code (isset($res["status"])?$res["status"]:500);
        exit();
    }
}