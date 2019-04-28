<?php

// Autoload
function admin_bridge_post_autoload($class) {
    include_once dirname(__FILE__) . "/../../core/" . strtolower($class) . ".php";
}
spl_autoload_register("admin_bridge_post_autoload");

$query = Security::filterInput();

// newPost
if ($query['command'] == "newPost") {
    if (!Member::detect())
        exit("identity_error");
    $captcha = empty($query['captcha']) ? "" : $query['captcha'];
    if (!Captcha::check($captcha))
        exit("captcha_error");
    $title   = empty($query['title']) ? "" : trim($query['title']);
    $cat     = empty($query['cat']) ? "" : trim($query['cat']);
    $content = empty($query['content']) ? "" : trim($query['content']);
    $comment = empty($query['comment']) ? 0 : (int) trim($query['comment']);
    try {
        $member = new Member();
        $member->identify();
        if (!$member->hasPermissionToPost())
            exit("permission_error");
        $post = new Post();
        $post->setTitle($title);
        $post->setCat($cat);
        $post->setContent($content);
        $post->setComment($comment);
        $blog = new Blog();
        $blog->identify();
        $post->setBlog($blog->getID());
        $post->setAuthor(Member::detect());
        $post->create();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// getPost
if ($query['command'] == "getPost") {
    if (!Member::detect())
        exit("identity_error");
    $id = empty($query['id']) ? 0 : (int) trim($query['id']);
    try {
        $post = new Post();
        $post->setID($id);
        $post->connect();
        $blog = new Blog();
        $blog->identify();
        if ($post->getBlog() != $blog->getID())
            exit("permission_error");
        $member = new Member();
        $member->identify();
        if (!$member->hasPermissionToEditPost($id))
            exit("permission_error");
        $p            = array();
        $p["title"]   = $post->getTitle();
        $p["cat"]     = $post->getCat();
        $p["content"] = $post->getRawContent();
        exit(json_encode($p));
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// Edit
if ($query['command'] == "editPost") {
    if (!Member::detect())
        exit("identity_error");
    $captcha = empty($query['captcha']) ? "" : $query['captcha'];
    if (!Captcha::check($captcha))
        exit("captcha_error");
    $title   = empty($query['title']) ? "" : trim($query['title']);
    $cat     = empty($query['cat']) ? "" : trim($query['cat']);
    $content = empty($query['content']) ? "" : trim($query['content']);
    $comment = empty($query['comment']) ? 0 : (int) trim($query['comment']);
    $id      = empty($query['id']) ? 0 : (int) trim($query['id']);
    try {
        $member = new Member();
        $member->identify();
        if (!$member->hasPermissionToEditPost($id))
            exit("permission_error");
        $blog = new Blog();
        $blog->identify();
        $post = new Post();
        $post->setID($id);
        $post->connect();
        if ($post->getBlog() != $blog->getID())
            exit("permission_error");
        $post->setTitle($title);
        $post->setCat($cat);
        $post->setContent($content);
        $post->setComment($comment);
        $post->setAuthor(Member::detect());
        $post->edit();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}