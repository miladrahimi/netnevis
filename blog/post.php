<?php

// Post
try {
    $post = new post();
    $post->setID(empty($QUERY[1]) ? 0 : $QUERY[1]);
    $post->connect();
    if($post->getBlog()!=$blog->getID())
        throw new Exception("msg.post_connect_error");
    $thepost["id"]          = $post->getID();
    $thepost["content"]     = $post->getContent();
    $thepost["description"] = $post->getDescription();
    $thepost["title"]       = $post->getTitle();
    $thepost["cat"]         = $post->getCat();
    $thepost["comment"]     = $post->getComment();
    $thepost["link"]        = "/post/" . $post->getID() . "/" . $post->getTitle();
    $like = new PostLike($post->getID());
    $thepost["like"]        = $like->check();
    $thepost["likes"]       = (string) $post->getLikes();
    $thepost["date"]        = (string) JDate::date("Y/m/d", $post->getMoment());
    $thepost["time"]        = (string) JDate::date("H:i", $post->getMoment());
    $thepost["archive"]     = (string) JDate::date("Y/m", $post->getMoment());
    // Tags
    $thepost["tags"]        = "";
    $tags                   = $post->getTags();
    foreach ($tags as $tag)
        $thepost["tags"] .= strip_tags($tag) . ", ";
    $thepost["comments"] = (string) $post->getNumberOfComments();
    $member              = new member();
    $member->setID($post->getAuthor());
    $member->connectByID();
    $thepost["author"] = "/author/" . $post->getAuthor() . "/" . $member->getNickname();
    $thepost["writer"] = $member->getNickname();
    $thepost["avatar"] = $member->getAvatar();
}
catch (Exception $e) {
    $error = Reporter::report($e);
    if ($error == "post_connect_error") {
        include dirname(__FILE__) . "/error_page.php";
    } else {
        Reporter::report($e, true);
        include dirname(__FILE__) . "/error_internal.php";
    }
    exit();
}

// Comments
try {
    $comments_id = $post->getComments();
    $comments    = array();
    $i           = 0;
    foreach ($comments_id as $id) {
        $comment = new comment();
        $comment->setID($id);
        $comment->connect();
        if (!$comment->getStatus())
            continue;
        $comments[$i]["rdate"]   = JDate::date("Y/m/d", (int) $comment->getMoment());
        $comments[$i]["rtime"]   = JDate::date("H:i", (int) $comment->getMoment());
        $comments[$i]["message"] = $comment->getMessage();
        $comments[$i]["author"]  = $comment->getAuthor();
        $i++;
    }
} catch (Exception $e) {
    Reporter::report($e, true);
    include dirname(__FILE__) . "/error_internal.php";
    exit();
}

// Set location
$menu_item = "post";
$kind      = "";

// Load page
include dirname(__FILE__) . "/sidebar.php";
include dirname(__FILE__) . "/theme/single.php";