<?php

// Posts
try {
    $QUERY[1] = empty($QUERY[1]) ? 1 : (int) trim($QUERY[1]);
    $posts_id = $blog->getPosts($QUERY[1]);
    $posts    = array();
    $i        = 0;
    foreach ($posts_id as $id) {
        $post = new post();
        $post->setID($id);
        $post->connect();
        $posts[$i]["id"]      = $id;
        $posts[$i]["content"] = $post->getSummary();
        $posts[$i]["title"]    = $post->getTitle();
        $posts[$i]["cat"]      = $post->getCat();
        $posts[$i]["link"]     = "/post/" . $post->getID() . "/" . $post->getTitle();
        $posts[$i]["likes"]    = (string) $post->getLikes();
        $posts[$i]["date"]     = (string) JDate::date("Y/m/d", $post->getMoment());
        $posts[$i]["time"]     = (string) JDate::date("H:i", $post->getMoment());
        $posts[$i]["archive"]  = (string) JDate::date("Y/m", $post->getMoment());
        $posts[$i]["comments"] = (string) $post->getNumberOfComments();
        $member                = new member();
        $member->setID($post->getAuthor());
        $member->connectByID();
        $posts[$i]["author"] = "/author/" . $post->getAuthor() . "/" . $member->getNickname();
        $posts[$i]["writer"] = $member->getNickname();
        $posts[$i]["avatar"] = $member->getAvatar();
        $like = new PostLike($id);
        $posts[$i]["like"]     = $like->check();
        $i++;
    }
} catch (Exception $e) {
    Reporter::report($e, true);
    include dirname(__FILE__) . "/error_internal.php";
    exit();
}

// Nav
try {
    $post_num = $blog->getNumberOfPosts();
    $page_num = ceil($post_num / 20);
    $link_nav = "/page/";
    $page_nav = $QUERY[1];
} catch (Exception $e) {
    Reporter::report($e, true);
    include dirname(__FILE__) . "/error_internal.php";
    exit();
}

// Set location
$menu_item = "home";
$kind      = "";

// Load page
include dirname(__FILE__) . "/sidebar.php";
include dirname(__FILE__) . "/theme/plural.php";