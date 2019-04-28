<?php

// Autoload
function admin_bridge_comments_autoload($class) {
    include_once dirname(__FILE__) . "/../../core/" . strtolower($class) . ".php";
}
spl_autoload_register("admin_bridge_comments_autoload");

// Get query
$q = empty($_GET["q"]) ? "" : trim($_GET["q"]);
$query = Security::JSONInput($q);

// getBlogComments
if ($query->command == "getBlogComments") {
    if (!Member::detect())
        exit("identity_error");
    try {
        $blog = new Blog();
        $blog->identify();
        $c       = $blog->getComments("new");
        $comment = new Comment();
        $post    = new Post();
        $r       = array();
        $i = 0;
        foreach ($c as $id) {
            $comment->setID($id);
            $comment->connect();
            $post->setID($comment->getPost());
            try {
                $post->connect();
            } catch (Exception $e) {
                $comment->delete();
            }
            $r[$i]            = array();
            $r[$i]["id"]      = $comment->getID();
            $r[$i]["post"]    = $post->getTitle();
            $r[$i]["message"] = $comment->getMessage();
            $r[$i]["email"]   = $comment->getEmail();
            $r[$i]["author"]  = $comment->getAuthor();
            $r[$i]["time"]    = JDate::date("Y/m/d @ H:i", $comment->getMoment());
            $i++;
        }
        exit(json_encode($r));
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// getPostComments
if ($query->command == "getPostComments") {
    if (!Member::detect())
        exit("identity_error");
    $id = empty($query->id) ? 0 : trim($query->id);
    try {
        $blog = new Blog();
        $blog->identify();
        $post = new Post();
        $post->setID($id);
        $post->connect();
        if ($post->getBlog() != $blog->getID()) {
            exit("permission_error");
        }
        $comments_id = $post->getComments();
        $comment     = new Comment();
        $comments    = array();
        $i = 0;
        foreach ($comments_id as $comment_id) {
            $comment->setID($comment_id);
            $comment->connect();
            $comments[$i]            = array();
            $comments[$i]["id"]      = $comment->getID();
            $comments[$i]["post"]    = $post->getTitle();
            $comments[$i]["message"] = $comment->getMessage();
            $comments[$i]["email"]   = $comment->getEmail();
            $comments[$i]["author"]  = $comment->getAuthor();
            $comments[$i]["time"]    = JDate::date("Y/m/d @ H:i", $comment->getMoment());
            $comments[$i]["status"]  = $comment->getStatus();
            $i++;
        }
        exit(json_encode($comments));
    } catch (Exception $e) {
        if ($e->getMessage() == "msg.post_connect_error")
            exit("no_post");
        exit(Reporter::report($e, true));
    }
}

// Delete
if ($query->command == "delete") {
    if (!Member::detect())
        exit("identity_error");
    $id = empty($query->id) ? 0 : trim($query->id);
    try {
        $blog = new Blog();
        $blog->identify();
        $member = new Member();
        $member->identify();
        if (!$member->hasPermissionToDeleteComment($id))
            exit("permission_error");
        $comment = new Comment();
        $comment->setID($id);
        $comment->connect();
        if ($comment->getBlog() != $blog->getID()) {
            exit("permission_error");
        }
        $comment->delete();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// Confirm
if ($query->command == "confirm") {
    if (!Member::detect())
        exit("identity_error");
    $id = empty($query->id) ? 0 : trim($query->id);
    try {
        $blog = new Blog();
        $blog->identify();
        $member = new Member();
        $member->identify();
        if (!$member->hasPermissionToConfirmComment($id))
            exit("permission_error");
        $comment = new Comment();
        $comment->setID($id);
        $comment->connect();
        if ($comment->getBlog() != $blog->getID()) {
            exit("permission_error");
        }
        $comment->setStatus(1);
        $comment->edit();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}