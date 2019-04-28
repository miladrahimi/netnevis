<?php

// Autoload
function admin_bridge_setting_autoload($class) {
    include_once dirname(__FILE__) . "/../../core/" . strtolower($class) . ".php";
}
spl_autoload_register("admin_bridge_setting_autoload");

// Get query
$q = empty($_GET["q"]) ? "" : trim($_GET["q"]);
$query = Security::JSONInput($q);

// GetSetting
if ($query->command == "getSetting") {
    if (!Member::detect())
        exit("identity_error");
    try {
        $blog = new Blog();
        $blog->identify();
        $setting = array(
            "subdomain" => $blog->getSubdomain(),
            "title" => $blog->getTitle(),
            "desc" => $blog->getDescription(),
            "email" => $blog->getEmail(),
            "about" => $blog->getAbout(),
            "g_author" => $blog->getGAuthor(),
            "g_publisher" => $blog->getGPublisher()
        );
        exit(json_encode($setting));
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// Save
if ($query->command == "save") {
    if (!Member::detect())
        exit("identity_error");
    $subdomain   = empty($query->subdomain) ? "" : $query->subdomain;
    $title       = empty($query->title) ? "" : $query->title;
    $description = empty($query->description) ? "" : $query->description;
    $email       = empty($query->email) ? "" : $query->email;
    $gauthor     = empty($query->gauthor) ? "https://google.com/+NetNevisOfficial" : $query->gauthor;
    $gpublisher  = empty($query->gpublisher) ? "https://google.com/+NetNevisOfficial" : $query->gpublisher;
    $email       = empty($query->email) ? "" : $query->email;
    $about       = empty($query->about) ? "" : $query->about;
    try {
        $member = new Member();
        $member->identify();
        if (!$member->hasPermissionToSetting())
            exit("permission_error");
        $blog = new Blog();
        $blog->identify();
        if ($blog->getSubdomain() != $subdomain) {
            $blog->setSubdomain($subdomain);
            $blog->connectBySubdomain();
            if ($blog->getID())
                exit("subdomain_exists");
            $blog->identify();
            $blog->setSubdomain($subdomain);
        }
        $blog->setTitle($title);
        $blog->setDescription($description);
        $blog->setGAuthor(str_replace(" ", "+", $gauthor));
        $blog->setGPublisher(str_replace(" ", "+", $gpublisher));
        $blog->setEmail($email);
        $blog->setAbout($about);
        $blog->edit();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// deletePermission
if ($query->command == "deletePermission") {
    if (!Member::detect())
        exit("identity_error");
    try {
        $member = new Member();
        $member->identify();
        if($member->hasPermissionToDeleteBlog())
            exit("yes");
        exit("no");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// deleteBlog
if ($query->command == "deleteBlog") {
    if (!Member::detect())
        exit("identity_error");
    $captcha = empty($query->captcha) ? "" : $query->captcha;
    if (!Captcha::check($captcha))
        exit("captcha_error");
    $password = empty($query->password) ? "" : $query->password;
    try {
        $blog = new Blog();
        $blog->identify();
        $member = new Member();
        $member->identify();
        if(!$member->hasPermissionToDeleteBlog())
            exit("permission_error");
        if ($member->getPassword() != Security::hashPassword($password)) {
            exit("password_error");
        }
        $blog->delete();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}