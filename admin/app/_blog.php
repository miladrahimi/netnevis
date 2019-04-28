<?php

// Autoload
function admin_app_general_autoload($class) {
    include_once dirname(__FILE__) . "/../../core/" . strtolower($class) . ".php";
}
spl_autoload_register("admin_app_general_autoload");

// Get query
$q = empty($_GET["q"]) ? "" : trim($_GET["q"]);
$query = Security::JSONInput($q);

// getBlogAddress
if ($query->command == "getBlogAddress") {
    if (!Member::detect())
        exit("account_error");
    try {
        $blog = new Blog();
        $blog->identify();
        $subdomain = $blog->getSubdomain();
        $general = Config::load("general");
        exit("http://" . $subdomain . '.' . $general['host']);
    }
    catch (Exception $e) {
        exit(Reporter::report($e, true));
    }
}