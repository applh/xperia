<?php

class xp_router
{
 
    static function query ()
    {
        // get the URI without the query string
        $uri = $_SERVER['REQUEST_URI'];
        extract(parse_url($uri));
        // manage special case for root
        $path ??= '/index.php';
        if ($path == "/") {
            $path = "/index.php";
        }
        extract(pathInfo($path));

        // split dirname into an array
        $dirname ??= "/";
        // trim /
        $dirname = trim($dirname, "/");
        $path_parts = explode("/", $dirname);
        // if empty array, set to default router
        // else set to first element of array
        $router = "default";
        if (!empty($path_parts)) {
            $router = $path_parts[0];
        }
        // if router is not a valid router, set to default
        if (!in_array($router, xp_router::get_routers())) {
            $router = "default";
        }
    }

    static function get_routers ()
    {
        static $routers = [];
        return $routers;
    }
}