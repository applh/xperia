<?php

/**
 * Plugin Name: Sample Plugin
 */

if (!function_exists('add_action')) die();

class sample_plugin
{
    static function start()
    {
        spl_autoload_register("sample_plugin::autoload");

        // add the setup method
        add_action('plugins_loaded', "sp_action::plugins_loaded");
    }

    static function autoload($classname)
    {
        $filename = __DIR__ . "/class/$classname.php";
        if (file_exists($filename)) {
            require_once($filename);
        }
    }
}

sample_plugin::start();
