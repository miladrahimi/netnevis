<?php

// Autoload
function admin_app_posts_autoload($class) {
    include_once dirname(__FILE__) . "/../../core/" . strtolower($class) . ".php";
}
spl_autoload_register("admin_app_posts_autoload");

// Get query
$q = empty($_GET["q"]) ? "" : trim($_GET["q"]);
$query = Security::JSONInput($q);

// GetArchive
if ($query->command == "getArchive") {
    if (!Member::detect())
        exit("identity_error");
    try {
        $blog = new Blog();
        $blog->identify();
        $archive = $blog->getArchive();
        exit(json_encode($archive));
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// GetPosts
if ($query->command == "getPosts") {
    if (!Member::detect())
        exit("identity_error");
    $year  = empty($query->year) ? 0 : trim($query->year);
    $month = empty($query->month) ? 0 : trim($query->month);
    $page  = empty($query->page) ? 0 : trim($query->page);
    try {
        $time_beg = JDate::mktime(0, 0, 0, $month, 1, $year);
        $time_end = JDate::mktime(0, 0, 0, $month, 31, $year);
        $blog     = new Blog();
        $blog->identify();
        $posts_id = $blog->getPostsOfPeriod($time_beg, $time_end, $page);
        $posts    = array();
        $i = 0;
        foreach ($posts_id as $post_id) {
            $post = new post();
            $post->setID($post_id);
            $post->connect();
            $posts[$i]          = array();
            $posts[$i]["id"]    = $post_id;
            $posts[$i]["title"] = $post->getTitle();
            $posts[$i]["likenum"] = (string) $post->getLikes();
            $posts[$i]["time"]  = JDate::date("Y/m/d @ H:i", $post->getMoment());
            $posts[$i]["commentnum"]  = $post->getNumberOfComments();
            $i++;
        }
        exit(json_encode($posts));
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// getPages
if ($query->command == "getPages") {
    if (!Member::detect())
        exit("identity_error");
    $year     = empty($query->year) ? 0 : (int) $query->year;
    $month    = empty($query->month) ? 0 : (int) $query->month;
    $time_beg = JDate::mktime(0, 0, 0, $month, 1, $year);
    $time_end = JDate::mktime(0, 0, 0, $month, 31, $year);
    try {
        $blog = new Blog();
        $blog->identify();
        $r = $blog->getNumberOfPeriod($time_beg, $time_end);
        $x = (int) ($r / 30) + (($r % 30) ? 1 : 0);
        exit((string) $x);
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// hasPermissionOfEdit
if ($query->command == "hasPermissionOfEdit") {
    if (!Member::detect())
        exit("identity_error");
    $post_id = empty($query->post) ? 0 : (int) $query->post;
    try {
        $member = new Member();
        $member->identify();
        $r      = $member->hasPermissionToEditPost($post_id);
        if ($r)
            exit("yes");
        exit("no");
    }
    catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// Delete
if ($query->command == "delete") {
    if (!Member::detect())
        exit("identity_error");
    $captcha = empty($query->captcha) ? "" : $query->captcha;
    if (!Captcha::check($captcha))
        exit("captcha_error");
    $post_id = empty($query->post) ? 0 : (int) $query->post;
    try {
        $member = new Member();
        $member->identify();
        $r      = $member->hasPermissionToDeletePost($post_id);
        if (!$r)
            exit("permission_error");
        $post = new Post();
        $post->setID($post_id);
        $post->delete();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}