<?php

/*
 * Plugin Name: xp-subdomain
 */

// security
if (!function_exists("add_action")) return;


class xp_subdomain
{

    static function start()
    {
        $myclass = __CLASS__;

        // store the class
        static::v("class", $myclass);

        // store the plugin dir
        static::v("plugin_dir", __DIR__);
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
        add_action('plugins_loaded', "$myclass::plugins_loaded");
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

    static function e($key, $default="") {
        echo static::v($key) ?? $default;
    }

    static function plugins_loaded()
    {
        $class = static::v("class");
        // add the setup method
        add_action('init', "$class::init");

        // https://developer.wordpress.org/reference/functions/add_menu_page/
        if (is_admin()) {
            add_action("admin_menu", "$class::admin_init");
        }
    }

    static function init()
    {
        $class = static::v("class");

        $host = $_SERVER["SERVER_NAME"];
        static::v("host", $host);

        header("xp-sub-host: $host");

        if (in_array($host, ["wp2.applh.com", "wp3.applh.com"])) {
            if (!is_admin()) {
                // https://developer.wordpress.org/reference/hooks/robots_txt/
                add_filter('robots_txt', "$class::robots_txt", 99, 2);

                add_filter("template_include", "$class::template_include");
            }
        }
    }

    static function robots_txt($output, $public)
    {
        $output =
            <<<txt
        User-agent: *
        Disallow: /
        txt;
        return $output;
    }

    static function template_include($template)
    {
        // debug
        if (is_file($template)) {
            ob_start();
            include $template;
            $code = ob_get_clean();
            // replace the domain by the subdomain
            $host = static::v("host");
            $home = get_home_url();
            $code = str_replace($home, "https://$host", $code);

            // etc
            $plugin_templates_dir = static::v("plugin_templates_dir");
            $empty = "$plugin_templates_dir/empty.php";
            if (is_file($empty)) {
                $template = $empty;
            } else {
                $template = "";
            }
            // headers before echo
            // header("xp-sub-template: $template");

            echo $code;
        }

        return $template;
    }

    static function admin_init()
    {
        $class = static::v("class");

        // https://developer.wordpress.org/reference/functions/add_plugins_page/
        add_plugins_page(
            "XP Sub-Domains",
            "XP Sub-Domains",
            "edit_plugins",
            "xp-subdomains-admin",
            "$class::admin_page",
        );
    }

    static function admin_page()
    {
        $plugin_templates_dir = static::v("plugin_templates_dir");
        $admin = "$plugin_templates_dir/admin.php";
        if (is_file($admin)) {
            include $admin;
        }
    }
}

xp_subdomain::start();
