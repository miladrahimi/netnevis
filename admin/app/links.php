<?php

// Autoload
function admin_bridge_links_autoload($class) {
    include_once dirname(__FILE__) . "/../../core/" . strtolower($class) . ".php";
}
spl_autoload_register("admin_bridge_links_autoload");

// Get query
$q = empty($_GET["q"]) ? "" : trim($_GET["q"]);
$query = Security::JSONInput($q);

// GetLinks
if ($query->command == "getLinks") {
    if (!Member::detect())
        exit("identity_error");
    try {
        $blog = new Blog();
        $blog->identify();
        $links = $blog->getLinks();
        $link  = new Link();
        $r     = array();
        foreach ($links as $id) {
            $link->setID($id);
            $link->connect();
            $r[$id]["title"] = $link->getTitle();
            $r[$id]["url"]   = $link->getURL();
        }
        exit(json_encode($r));
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// Delete
if ($query->command == "delete") {
    if (!Member::detect())
        exit("identity_error");
    $id = empty($query->id) ? 0 : trim($query->id);
    try {
        $member = new Member();
        $member->identify();
        if (!$member->hasPermissionToDeleteLink($id))
            exit("permission_error");
        $link = new Link();
        $link->setID($id);
        $link->connect();
        $blog = new Blog();
        $blog->identify();
        if($link->getBlog() != $blog->getID())
            exit("permission_error");
        $link->delete();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// Add
if ($query->command == "add") {
    if (!Member::detect())
        exit("identity_error");
    $title = empty($query->title) ? 0 : trim($query->title);
    $url   = empty($query->url) ? 0 : trim($query->url);
    try {
        $member = new Member();
        $member->identify();
        if (!$member->hasPermissionToAddLink())
            exit("permission_error");
        $blog = new Blog();
        $blog->identify();
        $link = new Link();
        $link->setTitle($title);
        $link->setURL($url);
        $link->setBlog($blog->getID());
        $link->create();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}