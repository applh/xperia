<?php

/*
 * Plugin Name: xp-subdomain
 */

class xp_subdomain
{

    static function start()
    {

        $host = $_SERVER["SERVER_NAME"];

        header("xp-sub: $host");

        if (in_array($host, ["wp2.applh.com", "wp3.applh.com"])) {

            // https://developer.wordpress.org/reference/hooks/robots_txt/
            add_filter('robots_txt', function ($output, $public) {
                $output =
                <<<txt
                User-agent: *
                Disallow: /
                txt;
                return $output;
            }, 99, 2);
        }
    }
}

xp_subdomain::start();
