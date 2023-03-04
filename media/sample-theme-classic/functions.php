<?php

if (!function_exists('add_action')) die();

class stc_theme
{
    static function setup()
    {
        spl_autoload_register("stc_theme::autoload");
    }

    static function autoload($classname)
    {
        $filename = __DIR__ . "/class/$classname.php";
        if (file_exists($filename)) {
            require_once($filename);
        }
    }
}

stc_theme::setup();
