<?php

class xpi_admin
{

    static function subdomains()
    {
        $uc = trim($_REQUEST["uc"] ?? "read");
        if ($uc == "read") {
            xpi_admin_helper::read();
        } elseif ($uc == "update") {
            xpi_admin_helper::update();
        } elseif ($uc == "api_key") {
            xpi_admin_helper::api_key();
        } elseif ($uc == "request_json") {
            xpi_admin_helper::request_json();
        }
    }
}

class xpi_admin_helper
{
    static function read()
    {
        // get option "xperia" from db
        $option = get_option("xperia", []);
        xperia::v("api/json/subdomains", $option);
        // feedback
        $feedback = "subdomains read";
        xperia::v("api/json/feedback", $feedback);
    }

    static function update()
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

        // update option "xperia" in db
        update_option("xperia", $subdomains);

        // get option "xperia" from db
        // $option = get_option("xperia", []);

        xperia::v("api/json/subdomains", $subdomains);
        xperia::v("api/json/feedback", $feedback);
    }

    static function api_key()
    {
        // get request data api_key as json code
        $api_key = trim($_REQUEST["api_key"] ?? "");
        // update option "xperia_api_key" in db
        update_option("xperia_api_key", $api_key);
        // feedback
        $feedback = "api_key updated";
        xperia::v("api/json/feedback", $feedback);
    }

    static function request_json()
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
                xperia::v("xpi_admin/request_json/data", $data);

                foreach ($data as $index => $part) {
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
            } else {
                $feedback = "request_json is not an array";
            }
        } else {
            $feedback = "request_json is empty";
        }
        
        xp_action::$api_json_data["request_json"] = $data;

        xperia::v("api/json/feedback", $feedback);
    }
}
