<?php

class xp_task
{
    static function mail($part = [])
    {
        extract($part);
        $mailfrom ??= "";
        $mailto ??= "";
        $subject ??= "";
        $message ??= "";
        $headers = [];
        $attachments ??= [];
        // dev
        $clean_dir ??= true;
        $email_send ??= true;

        if ($mailto && $subject && $message) {
            if ($mailfrom) {
                $headers[] = "From: $mailfrom";
            }
            $mailfiles = [];
            foreach ($attachments as $attachment) {
                $attachment = trim($attachment);
                if ($attachment) {
                    // get the uploaded file tmp_name
                    $tmp_name = $_FILES["$attachment"]["tmp_name"] ?? "";
                    $attach_name = $_FILES["$attachment"]["name"] ?? "";
                    if ($attach_name && $tmp_name && file_exists($tmp_name)) {
                        $data_dir = xp_subdomain::v("plugin_data_dir");
                        $tmp_dir = md5(password_hash(uniqid(), PASSWORD_DEFAULT));
                        $tmp_dir = "$data_dir/$tmp_dir";

                        // create tmp dir
                        mkdir("$tmp_dir", 0777, true);
                        touch("$tmp_dir/index.php");
                        $target_file = "$tmp_dir/$attach_name";
                        // fixme: can be dangerous 🔥
                        move_uploaded_file($tmp_name, $target_file);
                        $mailfiles[] = $target_file;
                    }
                }
            }

            // send mail
            if ($email_send) {
                wp_mail($mailto, $subject, $message, $headers, $mailfiles);
            }

            if ($clean_dir) {
                // clean files
                foreach (glob("$tmp_dir/*") as $tmpfile) {
                    unlink($tmpfile);
                }
                // remove tmp dir
                rmdir("$tmp_dir");
            }

            xp_subdomain::$api_json_data["mail"] = $part;
        }
    }

    static function update_options($part = [])
    {
        extract($part);
        $options ??= [];
        foreach ($options as $option => $value) {
            // hack: check if value is array
            if (is_array($value)) {
                // get key "name"
                $name = $value["name"] ?? "";
                if ($name) {
                    // search page by name
                    $post_type = $value["post_type"] ?? "page";
                    $post_found = get_page_by_path($name, post_type: $post_type);
                    if ($post_found) {
                        // get the ID
                        $value = $post_found->ID;
                        xp_subdomain::$api_json_data["update_options"][] = $post_found;    

                    }
                }
            }
            update_option($option, $value);
        }
        xp_subdomain::$api_json_data["options"] = $options;
    }

    static function test()
    {
        // feedback
        $feedback = "test / " . time();
        xp_subdomain::$api_json_data["test"] = $feedback;
    }

    static function add_posts ($part = [])
    {
        extract($part);
        $posts ??= [];

        // function will isolate the scope and reset local vars
        array_map(function($post) {
            extract($post);
            $post_title ??= "";
            $post_content ??= "";
            $post_status ??= "publish";
            $post_type ??= "post";
            // sanitize post_slug and lower
            $post_name ??= strtolower(preg_replace('/[^a-z0-9\-]/i', '', $post_title));
            
            $post_found = get_page_by_path($post_name, post_type: $post_type);
            if (!$post_found) {
                // fill post data
                $post["post_author"] ??= 1;
                // load content from file
                $post_content_file ??= "";
                if ($post_content_file) {
                    $post_content_upload = $_FILES["$post_content_file"]["tmp_name"] ?? "";
                    if ($post_content_upload && file_exists($post_content_upload)) {
                        $post_content = file_get_contents($post_content_upload);
                        $post["post_content"] = $post_content;
                    }
                }
                $post_id = wp_insert_post($post);
                
                // load featured image from file
                $featured_image ??= "";
                if ($featured_image) {
                    $post_image_upload = $_FILES["$featured_image"]["tmp_name"] ?? "";
                    if ($post_image_upload && file_exists($post_image_upload)) {
                        $hash = md5_file($post_image_upload);
                        $post_image = get_page_by_path($hash, OBJECT, "attachment");
                        if ($post_image) {
                            $post_image_id = $post_image->ID;
                            set_post_thumbnail($post_id, $post_image_id);
                        }
                    }

                }

                xp_subdomain::$api_json_data["add_posts"][] = $post_id;    
            }
            else {
                xp_subdomain::$api_json_data["add_posts_exists"][] = $post_found;    
            }
        }, $posts);

    }

    /**
     * Delete posts by post_name
     * warning: post_name is not unique
     */
    static function delete_posts ($part = [])
    {
        extract($part);
        $posts ??= [];
        $simulate ??= false;

        $founds = get_posts([
            "post_name__in" => $posts,
            "post_type" => "any",
            "post_status" => "any",
            "numberposts" => -1,
        ]);
        xp_subdomain::$api_json_data["delete_posts_founds"][] = $founds;

        foreach($founds as $found) {
            if (!$simulate) {
                xp_subdomain::$api_json_data["delete_posts"][] = $found;
                wp_delete_post($found->ID, true);
            }
        }
    }

    static function add_menus ($parts = [])
    {
        extract($parts);
        $menus ??= [];
        foreach($menus as $menu) {
            // reset vars
            $menu_name = null;
            $menu_items = null;

            extract($menu);
            $menu_name ??= "";
            $menu_found = get_page_by_path($menu_name, OBJECT, "wp_navigation");
            if (!$menu_found) {
                // create menu
                $menu_id = wp_insert_post([
                    'post_title' => $menu_name,
                    'post_name' => $menu_name,
                    'post_type' => 'wp_navigation',
                    'post_status' => 'publish',
                    // 'comment_status' => 'closed',
                    // 'ping_status' => 'closed',
                ]);
                xp_subdomain::$api_json_data["add_menus"][] = $menu_id;

                // add menu items
                $menu_items ??= [];
                $menu_htmls = [];
                foreach($menu_items as $menu_item) {
                    // reset vars
                    $label = null;
                    $uri = null;
                    $post_type = null;
                    extract($menu_item);
                    $label ??= "";
                    $uri ??= "";
                    $post_type ??= "page";

                    $page_found = get_page_by_path($uri, post_type: $post_type);

                    if ($page_found) {
                        $page_id = $page_found->ID;
                        if ($label == "") {
                            $label = $page_found->post_title;
                        }

                        // build html code
                        $menu_item_html = <<<html
                        <!-- wp:navigation-link {"label":"{$label}","type":"page","id":{$page_found->ID},"url":"{$page_found->guid}","kind":"post-type","isTopLevelLink":true} /-->   
                        html;
                        $menu_htmls[] = $menu_item_html;
                        
                        xp_subdomain::$api_json_data["add_menu_items"][] = $page_id;
                    }
                    
                }
                // update menu items
                $menu_html = implode("\n", $menu_htmls);
                wp_update_post([
                    'ID' => $menu_id,
                    'post_content' => $menu_html,
                ]);
    

            }
            else {
                xp_subdomain::$api_json_data["add_menus_exists"][] = $menu_found;    
            }
        }
    }

    static function add_media ($part = [])
    {
        extract($part);
        $media ??= [];
        array_map(function($m){
            extract($m);
            $file ??=  "";
            if ($file) {
                // get the uploaded file tmp_name
                $tmp_name = $_FILES["$file"]["tmp_name"] ?? "";
                $attach_name = $_FILES["$file"]["name"] ?? "";

                if ($tmp_name) {
                    $hash = md5_file($tmp_name);
                    $post_found = get_page_by_path($hash, OBJECT, "attachment");
                    if (!$post_found) {
                        // upload file
                        $title ??= $attach_name;
                        $caption ??= $title;
                        $description ??= $title; 
                        $page_id = media_handle_upload($file, 0, [
                            'post_title' => $title,
                            'post_name' => $hash,
                            'post_excerpt' => $caption,
                            'post_content' => $description,
                        ]);
                        $post = get_post($page_id, ARRAY_A);
                        xp_subdomain::$api_json_data["add_media"][] = $post;    
                    }
                    else {
                        xp_subdomain::$api_json_data["add_media_exists"][] = $post_found;    
                    }
                }

            }

        }, $media);

    }
}
