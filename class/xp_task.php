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
}
