<?php

// Autoload
function admin_bridge_seo_autoload($class) {
    include_once dirname(__FILE__) . "/../../core/" . strtolower($class) . ".php";
}
spl_autoload_register("admin_bridge_seo_autoload");

$query = Security::filterInput();

// Save
if ($query['command'] == "save") {
    if (!Member::detect())
        exit("identity_error");
    $metatags = empty($query["metatags"]) ? '' : $query["metatags"];
    try {
        $member = new Member();
        $member->identify();
        if (!$member->hasPermissionToSetting())
            exit("permission_error");
        $blog = new Blog();
        $blog->identify();
        $blog->setMetatags(htmlspecialchars_decode($metatags));
        $blog->edit();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}