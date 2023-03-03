<?php

// only for CLI
if (php_sapi_name() != "cli") return;

/**
 * usage:
 * php send cli.php my-site-vitrine/tasks-test.json
 */
class xp_cli
{
    static $config = [];
    static $args = [];
    static $dir_plugin = __DIR__;
    static $v = [];

    static function run()
    {
        // add autoloader
        spl_autoload_register("xp_cli::autoload");

        // get the args
        static::$args = $_SERVER["argv"];
        print_r(static::$args);

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

        $cmd = static::$args[1] ?? "";
        $cmd = "xp_cli::cmd_$cmd";
        if (is_callable("$cmd")) {
            $cmd();
        } else {
            echo "Command not found: $cmd";
        }
    }

    static function cmd_send()
    {

        // json file from static::$args[2]
        $file = static::$args[2] ?? "";
        if ($file) {
            $file = static::$dir_plugin . "/$file";
            if (is_file($file)) {

                $local_dir = dirname($file);
                static::$v["local_dir"] = $local_dir;

                $request_json = file_get_contents($file);
                $request_params = json_decode($request_json, true);

                // load local config file
                $config_json = trim($request_params["config_json"] ?? "");
                if ($config_json) {
                    $config_json = dirname($file) . "/$config_json";
                    if (is_file($config_json)) {
                        $config = file_get_contents($config_json);
                        $config = json_decode($config, true) ?? [];
                        // override common config with local config
                        static::$config = $config + static::$config;
                    }
                }
            }
        } else {
            $request_params = [];
        }

        $api_url = static::$config["api_url"] ?? "";

        if (!$api_url) {
            echo "api_url not found in config.json";
            return;
        } else {
            echo "(api_url: $api_url)";
        }
        // add time stamp
        $request_params["timestamp"] = time();

        print_r($request_params);

        static::request_json($request_params);
    }

    static function request_json($request_params)
    {
        xp_cli::$v["request_params"] = $request_params;
        
        // get before tasks
        $local_tasks = $request_params["local_tasks"] ?? [];
        $local_tasks = static::build_tasks($local_tasks);
        foreach($local_tasks as $local_task) {
            $local_todo = $local_task["todo"] ?? "";
            if (is_callable($local_todo)) {
                $local_todo($local_task);
            }
        }        
    }

    static function build_tasks($request_tasks = [])
    {
        $build_tasks = [];
        // loop each task
        foreach ($request_tasks as $task) {
            // get json file
            // path is relative to the json file
            $local_dir = static::$v["local_dir"] ?? "";
            $task_file = "$local_dir/$task.json";
            if (is_file($task_file)) {
                $task_data = file_get_contents($task_file);
                $task_data = json_decode($task_data, true) ?? [];
                // add task data
                $build_tasks[] = $task_data;
            }
        }
        return $build_tasks;
    }

    static function cmd_md5()
    {
        $random = md5(password_hash(uniqid(), PASSWORD_DEFAULT));
        echo
        <<<txt
        $random

        txt;
    }

    // autoloader
    static function autoload($class)
    {
        // get the class file
        $file = __DIR__ . "/class/$class.php";

        // check if the file exists
        if (file_exists($file)) {
            require $file;
        }
    }
    
}

xp_cli::run();
