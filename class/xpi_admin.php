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
                // get request data subdomains as json code
                $subdomains = $data["subdomains"] ?? [];
                // update option "xp_subdomain" in db
                // update_option("xp_subdomain", $subdomains);
                // feedback
                $feedback = "subdomains request processed";
            }
            else {
                $feedback = "request_json is not an array";
            }
        }
        else {
            $feedback = "request_json is empty";
        } 
        xp_subdomain::v("api/json/data", $data);

        xp_subdomain::v("api/json/feedback", $feedback);

    }
}