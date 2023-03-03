<?php

class xp_action
{
    static $api_json_data = [];

    static function plugins_loaded()
    {
        $class = __CLASS__;

        // add the setup method
        add_action('init', "$class::init");

        // https://developer.wordpress.org/reference/functions/add_menu_page/
        if (is_admin()) {
            add_action("admin_menu", "$class::admin_init");
        }
        // add page template
        add_filter("theme_page_templates", "$class::theme_page_templates", 99, 3);
        add_filter("template_include", "$class::page_template_include");

        // AJAX
        // add new ajax action (not logged in) action="xperia"
        // warning: POST request only
        // curl -v -X POST -d "action=xperia" https://YOUSITE.COM/wp-admin/admin-ajax.php -o ajax.json
        add_action("wp_ajax_nopriv_xperia", "$class::api_json");
        // also needed if user logged in
        add_action("wp_ajax_xperia", "$class::api_json");

        // add filter page_on_front
        add_filter('option_page_on_front', "$class::option_page_on_front");
    }

    static function init()
    {
        $class = __CLASS__;

        $host = $_SERVER["SERVER_NAME"];
        xperia::v("host", $host);

        header("xp-sub-host: $host");
        $subdomains = get_option("xperia", []);
        if (!is_array($subdomains)) {
            $subdomains = [];
        } else {
            // extract the subdomains names
            $subdomains = array_map(function ($subdomain) {
                return $subdomain["name"] ?? "";
            }, $subdomains);
        }

        if (in_array($host, $subdomains)) {
            if (!is_admin()) {
                // https://developer.wordpress.org/reference/hooks/robots_txt/
                add_filter('robots_txt', "$class::robots_txt", 99, 2);

                add_filter("template_include", "$class::template_include");

                // hack to avoid redirection 301
                remove_action('template_redirect', 'redirect_canonical');
            }
        }

        // register blocks
        static::init_blocks();
    }

    static function init_blocks()
    {
        $plugin_dir = xperia::v("plugin_dir");
        // https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/writing-your-first-block-type/
        // register block
        register_block_type("$plugin_dir/block-a");
        register_block_type("$plugin_dir/block-i");
        register_block_type("$plugin_dir/block-r");
        
        include("$plugin_dir/block-d/register.php");

        // register dynamic blocks
        $block_names = ["block-dynamic"];
        foreach($block_names as $block_name) {
            static::register_block($block_name);
        }
        
    }

    static function register_block ($block_name)
    {
        // dynamic block
        // https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/


        // automatically load dependencies and version
        // $asset_file = include(plugin_dir_path(__FILE__) . 'block.asset.php');
        $asset_file = array(
            'dependencies' =>
            array(
                'wp-blocks',
                'wp-element',
                'wp-polyfill'
            ),
            'version' => '0.1'
        );
    
        $block_js_url = xperia::v("plugin_url") . "/templates/block_js.php?block=$block_name";
        header("xp-block-js-url: $block_js_url");

        wp_register_script(
            "xperia/$block_name",
            $block_js_url,
            $asset_file['dependencies'],
            $asset_file['version']
        );

        register_block_type("xperia/$block_name", array(
            'api_version' => 2,
            'editor_script' => "xperia/$block_name", // The script name we gave in the wp_register_script() call.
            'render_callback' => 'xp_action::block_render',
            // 'supports' => array('color' => true, 'align' => true),
        ));

    }

    static function block_render ($attributes, $content)
    {
        $now = date("Y-m-d H:i:s");
        // debug
        header("xp-block-render: $now");
        $wrapper_attributes = get_block_wrapper_attributes();

        $html =
        <<<html
        <div $wrapper_attributes>
            <h1>Dynamic block</h1>
            <p>Current time: $now</p>
        </div>
        html;

        return $html;
    }

    static function block_d_render_callback($attributes, $content)
    {
        $recent_posts = wp_get_recent_posts(array(
            'numberposts' => 1,
            'post_status' => 'publish',
        ));
        if (count($recent_posts) === 0) {
            return 'No posts';
        }
        $post = $recent_posts[0];
        $post_id = $post['ID'];
        $wrapper_attributes = get_block_wrapper_attributes();

        return sprintf(
            '<a %1s href="%1$s">%2$s</a>',
            $wrapper_attributes,
            esc_url(get_permalink($post_id)),
            esc_html(get_the_title($post_id))
        );
    }

    static function admin_init()
    {
        $class = __CLASS__;

        // https://developer.wordpress.org/reference/functions/add_plugins_page/
        add_plugins_page(
            "XPeria",
            "XPeria",
            "edit_plugins",
            "xp-subdomains-admin",
            "$class::admin_page",
        );
    }

    static function admin_page()
    {
        $plugin_templates_dir = xperia::v("plugin_templates_dir");
        $admin = "$plugin_templates_dir/admin.php";
        if (is_file($admin)) {
            include $admin;
        }
    }

    static function check_api_key()
    {
        $res = false;
        // get the request api_key_hash and api_key_time
        $api_key_hash = trim($_REQUEST["api_key_hash"] ?? "");
        $api_key_time = intval(trim($_REQUEST["api_key_time"] ?? ""));
        // check if the request is recent
        $now = time();
        if ($api_key_time > $now) {
            // get the api_key
            $api_key = trim(get_option("xperia_api_key", ""));
            // basic security
            if ($api_key) {
                $hash = md5("$api_key/$api_key_time");
                if ($hash == $api_key_hash) {
                    $res = true;
                }
            }
        } else {
            xperia::v("api/json/feedback", "token expired");
        }
        return $res;
    }

    static function api_json()
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
        if (static::check_api_key() || current_user_can("edit_plugins")) {
            $c = "xpi_admin";
            $m = "subdomains";
            $callback = "$c::$m";
            if (is_callable($callback)) {
                $callback();
            }
        }

        $infos['data'] = xperia::v("api/json/data") ?? static::$api_json_data ?? [];
        $infos['subdomains'] = xperia::v("api/json/subdomains") ?? [];
        $infos['feedback'] = xperia::v("api/json/feedback") ?? "";

        // check callback
        if (function_exists("wp_send_json")) {
            // debug header
            // header("X-Xpress-debug: wp_json_send");
            wp_send_json($infos, 200); //use wp_json_send to return some data to the client.
            wp_die(); //use wp_die() once you have completed your execution.
        }
    }

    static function theme_page_templates($templates, $theme, $post)
    {
        // add our page template
        $templates["xp-page-template"] = "XP Page Template";
        return $templates;
    }

    static function page_template_include($template)
    {
        // page template
        $page_template_slug = get_page_template_slug();

        if ($page_template_slug == "xp-page-template") {
            $template = xperia::v("plugin_templates_dir") . "/page-template.php";
        }
        return $template;
    }

    static function template_include($template)
    {
        // debug
        if (is_file($template)) {
            ob_start();
            include $template;
            $code = ob_get_clean();
            // replace the domain by the subdomain
            $host = xperia::v("host");
            $home = get_home_url();
            $code = str_replace($home, "https://$host", $code);

            // etc
            $plugin_templates_dir = xperia::v("plugin_templates_dir");
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

    static function robots_txt($output, $public)
    {
        $output =
            <<<txt
        User-agent: *
        Disallow: /
        txt;
        return $output;
    }


    static function option_page_on_front($value)
    {
        $host = xperia::v("host");
        $subfront = null;
        $subdomains = get_option("xperia", []);
        if (!is_array($subdomains)) {
            $subdomains = [];
        }
        foreach ($subdomains as $i => $subdomain) {
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