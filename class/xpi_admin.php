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

}