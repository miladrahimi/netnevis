<?php

// Autoload
function admin_bridge_appearance_autoload($class) {
    include_once dirname(__FILE__) . "/../../core/" . strtolower($class) . ".php";
}
spl_autoload_register("admin_bridge_appearance_autoload");

// Get query
$q = empty($_GET["q"]) ? "" : trim($_GET["q"]);
$query = Security::JSONInput($q);

// load
if ($query->command == "load") {
    if (!Member::detect())
        exit("identity_error");
    try {
        $blog = new Blog();
        $blog->identify();
        $appearance = array(
            "logo" => $blog->getLogo(),
            "cover" => $blog->getCover(),
            "favicon" => $blog->getFavicon()
        );
        exit(json_encode($appearance));
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// Save
if ($query->command == "save") {
    if (!Member::detect())
        exit("identity_error");
    $logo    = empty($query->logo) ? "http://netnevis.ir/shared/image/sample_logo.png" : $query->logo;
    $cover   = empty($query->cover) ? "http://netnevis.ir/shared/image/sample_cover.jpg" : $query->cover;
    $favicon = empty($query->favicon) ? "http://netnevis.ir/shared/image/sample_favicon.ico" : $query->favicon;
    try {
        $member = new Member();
        $member->identify();
        if (!$member->hasPermissionToSetting())
            exit("permission_error");
        $blog = new Blog();
        $blog->identify();
        $blog->setLogo($logo);
        $blog->setCover($cover);
        $blog->setFavicaon($favicon);
        $blog->edit();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}