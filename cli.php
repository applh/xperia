<?php

// only for CLI
if (php_sapi_name() != "cli") return;

class xp_cli
{
    static $config = [];
    static $args = [];
    static $dir_plugin = __DIR__;

    static function run ()
    {
        $dir_plugin = __DIR__;
        $dir_cli = "$dir_plugin/my-cli";
        $config_json = "$dir_cli/config.json";
        // if $dir_cli does not exist, create it
        if (!is_dir($dir_cli)) {
            mkdir($dir_cli, 0777, true);
            // create config.json
            $config = [
                "api_url" => "my-cli",
            ];
            $config = json_encode($config, JSON_PRETTY_PRINT);
            file_put_contents("$dir_cli/config.json", $config);
        }

        // load config file
        if (is_file($config_json)) {
            $config = file_get_contents($config_json);
            $config = json_decode($config, true) ?? [];
            static::$config = $config;
        }

        // get the args
        static::$args = $_SERVER["argv"];
        print_r(static::$args);
        $cmd = static::$args[1] ?? "";
        $cmd = "xp_cli::cmd_$cmd";
        if (is_callable("$cmd")) {
            $cmd();
        } else {
            echo "Command not found: $cmd";
        }
    }

    static function cmd_send ()
    {
        $api_url = xp_cli::$config["api_url"] ?? "";

        if (!$api_url) {
            echo "api_url not found in config.json";
            return;
        }

        // json file from static::$args[2]
        $file = static::$args[2] ?? "";
        if ($file) {
            $file = static::$dir_plugin . "/$file";
            if (is_file($file)) {
                $request_json = file_get_contents($file);
                $request_params = json_decode($request_json, true);
            }
        }
        else {
            $request_params = [];
        }
        // add time stamp
        $request_params["timestamp"] = time();

        print_r($request_params);

        $request_json = json_encode($request_params, JSON_PRETTY_PRINT);
        $cstringfile = new CURLStringFile($request_json, "request.json", "application/json");

        $api_key = static::$config["api_key"] ?? "";
        $api_key_time = time() + intval(static::$config["api_key_time"] ?? 3600);
        
        $api_key_hash = md5("$api_key/$api_key_time");

        $uc = $request_params["uc"] ?? "read";

        $post_fields = [
            "action" => "xpsubdomain",
            "uc" => $uc,
            "api_key_hash" => $api_key_hash,
            "api_key_time" => $api_key_time,
            "request_json" => $cstringfile,
        ];
        // send curl POST request iwth action="xpsubdomain"
        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        // echo $response;
        $data = json_decode($response, true);
        print_r($data);

    }

    static function cmd_md5 ()
    {
        $random = md5(password_hash(uniqid(), PASSWORD_DEFAULT));
        echo 
        <<<txt
        $random

        txt;
    }
}

xp_cli::run();


