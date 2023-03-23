<?php

$wp_version = '6.0.0';

if (!function_exists('add_filter')) {
    function add_filter ($in)
    {
        return $in;
    }
    
}

if (!function_exists('is_blog_installed')) {
    function is_blog_installed ()
    {
        return true;
    }
    
}

