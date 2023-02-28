<?php

class xpi_admin 
{

    static function subdomains ()
    {
        $uc = trim($_REQUEST["uc"] ?? "read");
        if ($uc == "read") {
            xpi_admin_helper::read();
        }
        elseif ($uc == "update") {
            xpi_admin_helper::update();

        }
        elseif ($uc == "api_key") {
            xpi_admin_helper::api_key();
        }
        elseif ($uc == "request_json") {
            xpi_admin_helper::request_json();
        }
    }
}

class xpi_admin_helper
{
    static function read ()
    {
        // get option "xp_subdomain" from db
        $option = get_option("xp_subdomain", []);
        xp_subdomain::v("api/json/subdomains", $option);
        // feedback
        $feedback = "subdomains read";
        xp_subdomain::v("api/json/feedback", $feedback);

    }

    static function update ()
    {
        // feedback
        $feedback = "subdomains update";
        // get request data subdomains as json code
        $subdomains = stripslashes($_REQUEST["subdomains"] ?? "");
        // json decode subdomains
        $subdomains = json_decode($subdomains, true);

        if (!is_array($subdomains)) {
            $feedback = "subdomains is not an array";
            $subdomains = [];
        }

        // update option "xp_subdomain" in db
        update_option("xp_subdomain", $subdomains);

        // get option "xp_subdomain" from db
        // $option = get_option("xp_subdomain", []);

        xp_subdomain::v("api/json/subdomains", $subdomains);
        xp_subdomain::v("api/json/feedback", $feedback);

    }

    static function api_key ()
    {
        // get request data api_key as json code
        $api_key = trim($_REQUEST["api_key"] ?? "");
        // update option "xp_subdomain_api_key" in db
        update_option("xp_subdomain_api_key", $api_key);
        // feedback
        $feedback = "api_key updated";
        xp_subdomain::v("api/json/feedback", $feedback);

    }

    static function request_json ()
    {
        // get uploaded file request_json
        $file = $_FILES["request_json"] ?? [];
        $tmp_name = $file["tmp_name"] ?? "";
        $name = $file["name"] ?? "";
        $feedback = "";
        if ($tmp_name) {
            // read file
            $content = file_get_contents($tmp_name);
            // json decode
            $data = json_decode($content, true) ?? [];
            if (is_array($data)) {
                $nb_parts = 0;
                // store data for later usage
                xp_subdomain::v("xpi_admin/request_json/data", $data);

                foreach($data as $index => $part) {
                    // get request data subdomains as json code
                    $todo = $part["todo"] ?? "";
                    if ($todo && is_callable("$todo")) {
                        // warning: can be dangerous 🔥
                        $todo($part);
                        $nb_parts++;
                    }                    
                }
                // feedback
                $feedback = "subdomains request processed / $nb_parts part(s)";
            }
            else {
                $feedback = "request_json is not an array";
            }
        }
        else {
            $feedback = "request_json is empty";
        } 
        xp_subdomain::$api_json_data["request_json"] = $data;

        xp_subdomain::v("api/json/feedback", $feedback);

    }

    static function test ()
    {
        // feedback
        $feedback = "test / " . time();
        xp_subdomain::$api_json_data["test"] = $feedback;
    }

    static function mail ($part)
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
            foreach($attachments as $attachment) {
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
                foreach(glob("$tmp_dir/*") as $tmpfile) {
                    unlink($tmpfile);
                }
                // remove tmp dir
                rmdir("$tmp_dir");
            }

            xp_subdomain::$api_json_data["mail"] = $part;
        }
    }
}