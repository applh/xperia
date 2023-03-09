<?php

class xp_app 
{
    static function run()
    {
        $curdir = __DIR__;
        $rootdir = dirname($curdir);

        // load templates/app.php
        $path_app = "$rootdir/templates/app.php";

        include $path_app;

    }
}