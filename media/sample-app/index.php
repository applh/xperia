<?php

$rootdir = dirname(__DIR__);
// load class/xp_app.php
$path_app = "$rootdir/class/xp_app.php";
require $path_app;
// run app
xp_app::run();

