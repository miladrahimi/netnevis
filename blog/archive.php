<?php

// Get Archive
$year     = empty($QUERY[1]) ? 0 : (int) $QUERY[1];
$month    = empty($QUERY[2]) ? 0 : (int) $QUERY[2];

// Check inputs
if(!$year || !$month) { // No month or No year!
    include dirname(__FILE__) . "/error_page.php";
    exit();
}
if($month < 1 || $month > 12) { // Wrong month
    include dirname(__FILE__) . "/error_page.php";
    exit();
}

// Set Archive
$time_beg = JDate::mktime(0, 0, 0, $month, 1, $year);
$time_end = JDate::mktime(0, 0, 0, $month, 31, $year);

// Posts
try {
    $QUERY[4] = empty($QUERY[4]) ? 1 : (int) trim($QUERY[4]);
    $posts_id = $blog->getPostsOfPeriod($time_beg, $time_end, $QUERY[4]);
    $posts    = array();
    $i        = 0;
    foreach ($posts_id as $id) {
        $post = new post();
        $post->setID($id);
        $post->connect();
        $posts[$i]            = array();
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
    $post_num = $blog->getNumberOfPeriod($time_beg, $time_end);
    $page_num = ceil($post_num / 20);
    $link_nav = "/archive/" . $year . "/" . $month . "/page/";
    $page_nav = $QUERY[4];
} catch (Exception $e) {
    Reporter::report($e, true);
    include dirname(__FILE__) . "/error_internal.php";
    exit();
}

// Set location
$menu_item = "archive";
$kind      = $year . "/" . $month;

// Load page
include dirname(__FILE__) . "/sidebar.php";
include dirname(__FILE__) . "/theme/plural.php";