<?php

class xp_os
{
    static $curl_response = null;
    static $curl_ch = null;

    static function test ($task = [])
    {
        echo "xp_os::test()";
        print_r($task);
    }

    static function curl_request ()
    {
        $request_params = xp_cli::$v["request_params"] ?? [];

        $api_url = xp_cli::$config["api_url"] ?? "";
        $api_key = xp_cli::$config["api_key"] ?? "";
        $api_key_time = time() + intval(xp_cli::$config["api_key_time"] ?? 3600);
        $api_key_hash = md5("$api_key/$api_key_time");

        $action = $request_params["action"] ?? "xperia";
        $uc = $request_params["uc"] ?? "read";

        $post_fields = [
            "action" => $action,
            "uc" => $uc,
            "api_key_hash" => $api_key_hash,
            "api_key_time" => $api_key_time,
        ];

        // build request.json
        $request_json = "";

        // get uploads
        $request_uploads = $request_params["uploads"] ?? [];
        if (!empty($request_uploads)) {
            $request_json = json_encode($request_uploads, JSON_PRETTY_PRINT);
        }

        // get tasks
        $request_tasks = $request_params["server_tasks"] ?? [];
        $build_tasks = xp_cli::build_tasks($request_tasks);
        if (!empty($build_tasks)) {
            $request_json = json_encode($build_tasks, JSON_PRETTY_PRINT);
        }

        if ($request_json) {
            $cstringfile = new CURLStringFile($request_json, "request.json", "application/json");
            $post_fields["request_json"] = $cstringfile;
        }

        // add attachments
        $request_attachments = $request_params["attachments"] ?? [];
        foreach ($request_attachments as $key => $value) {
            $attach_file = xp_cli::$dir_plugin . "/$value";
            if (is_file($attach_file)) {
                $cfile = new CURLFile($attach_file);
                $post_fields[$key] = $cfile;
            }
        }
        xp_cli::$v["curl_send"] = compact("api_url", "post_fields");

    }

    static function curl_send ()
    {
        extract(xp_cli::$v["curl_send"] ?? []);
        
        // send curl POST request iwth action="xperia"
        $ch = curl_init($api_url);
        static::$curl_ch = $ch;

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        static::$curl_response = curl_exec($ch);
        curl_close($ch);
        
    }

    static function curl_response ($task = [])
    {
        $ch = static::$curl_ch;
        $response = static::$curl_response;

        if (!$response) {
            echo "Error: " . curl_error($ch);
        } else {
            $info = curl_getinfo($ch);
            print_r($info);
        }
        // echo $response;
        $data = json_decode($response, true);
        print_r($data);
    }
}