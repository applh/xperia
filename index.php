<?php

/*
 * Plugin Name: XPeria 🔥
 */

// security
if (!function_exists("add_action")) return;

class xperia
{
    static function start()
    {
        $myclass = __CLASS__;

        // store the class
        static::v("class", $myclass);

        // store the plugin dir
        static::v("plugin_dir", __DIR__);

        // store the plugin data dir
        $data_dir = dirname(__DIR__) . "/xp-data";
        static::v("plugin_data_dir", $data_dir);
        // create the data dir if it does not exist
        if (!is_dir($data_dir)) {
            mkdir($data_dir, 0777, true);
            // add index.php to prevent directory listing
            $index = "$data_dir/index.php";
            if (!is_file($index)) {
                $secret  = md5(password_hash(uniqid(), PASSWORD_DEFAULT));
                // get model file from media/index.php
                $code = file_get_contents(__DIR__ . "/media/index.php");
                // replace YOUR_SECRET with the secret
                $code = str_replace("YOUR_SECRET", $secret, $code);

                file_put_contents($index, $code);
            }
        }

        // store the plugin templates dir
        static::v("plugin_templates_dir", __DIR__ . "/templates");

        $plugin_url = plugin_dir_url(__FILE__);
        // store the plugin url
        static::v("plugin_url", $plugin_url);
        // store the media url
        static::v("media_url", $plugin_url . "media");

        // add autoloader
        spl_autoload_register("$myclass::autoload");

        // add the setup method
        add_action('plugins_loaded', "xp_action::plugins_loaded");
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

    // store key/value
    static function v($key, $value = null)
    {
        static $data = array();

        if ($value == null) {
            // read
            return $data[$key] ?? null;
        } else {
            // write
            $data[$key] = $value;
            return $value;
        }
    }

    static function e($key, $default = "")
    {
        echo static::v($key) ?? $default;
    }
}

xperia::start();
