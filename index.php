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
                $code = '<?php $secret = "' . $secret . '";';
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

        // AJAX
        // add new ajax action (not logged in) action="xpsubdomain"
        // warning: POST request only
        // curl -v -X POST -d "action=xpsubdomain" https://YOUSITE.COM/wp-admin/admin-ajax.php -o ajax.json
        add_action("wp_ajax_nopriv_xpsubdomain", "$class::api_json");
        // also needed if user logged in
        add_action("wp_ajax_xpsubdomain", "$class::api_json");

        // add filter page_on_front
        add_filter('option_page_on_front', "$class::option_page_on_front");

    }

    static function init()
    {
        $class = static::v("class");

        $host = $_SERVER["SERVER_NAME"];
        static::v("host", $host);

        header("xp-sub-host: $host");
        $subdomains = get_option("xp_subdomain", []);
        if (!is_array($subdomains)) {
            $subdomains = [];
        }
        else {
            // extract the subdomains names
            $subdomains = array_map(function($subdomain) {
                return $subdomain["name"] ?? "";
            }, $subdomains);
        }

        if (in_array($host, $subdomains)) {
            if (!is_admin()) {
                // https://developer.wordpress.org/reference/hooks/robots_txt/
                add_filter('robots_txt', "$class::robots_txt", 99, 2);

                add_filter("template_include", "$class::template_include");

                // hack to avoid redirection 301
                remove_action( 'template_redirect', 'redirect_canonical' );

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

    static function api_json ()
    {
        // return json
        $infos = [];
        // time
        $infos['time'] = time();
        // date
        $infos['date'] = date("Y-m-d H:i:s");
        // request
        $infos['request'] = $_REQUEST;
        // files
        $infos['files'] = $_FILES;

        // process the request
        // if user is admin
        if (current_user_can("edit_plugins")) {
            $c = "xpi_admin";
            $m = "subdomains";
            $callback = "$c::$m";
            if (is_callable($callback)) {
                $callback();
            }
        }

        $infos['subdomains'] = xp_subdomain::v("api/json/subdomains") ?? [];
        $infos['feedback'] = xp_subdomain::v("api/json/feedback") ?? "";

        // check callback
        if (function_exists("wp_send_json")) {
            // debug header
            // header("X-Xpress-debug: wp_json_send");
            wp_send_json($infos, 200); //use wp_json_send to return some data to the client.
            wp_die(); //use wp_die() once you have completed your execution.
        }        
    }

    static function option_page_on_front($value)
    {
        $host = static::v("host");
        $subfront = null;
        $subdomains = get_option("xp_subdomain", []);
        if (!is_array($subdomains)) {
            $subdomains = [];
        }
        foreach($subdomains as $i => $subdomain) {
            // check if the name if the host
            if ($host == ($subdomain["name"] ?? "")) {
                // get the page_on_front
                $sfi = intval($subdomain["page_on_front"] ?? 0);
                if ($sfi > 0) {
                    $subfront = $sfi;
                    // debug
                    header("xp-sub-front: $subfront");
                }
            }
        }

        return $subfront ?? $value;
    }
}

xp_subdomain::start();
