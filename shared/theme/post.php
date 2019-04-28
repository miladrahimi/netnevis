<?php

// Autoload
function ajax_post_autoload($class) {
    include_once dirname(__FILE__) . "/../../core/" . strtolower($class) . ".php";
}
spl_autoload_register("ajax_post_autoload");

// Get query
$q = empty($_GET["q"]) ? "" : trim($_GET["q"]);
$query = Security::JSONInput($q);


// Like
if ($query->command == "like") {
    try {
        $blog = new Blog();
        $blog->detect();
        $post = new Post();
        $post->setID($query->id);
        $post->connect();
        if($post->getBlog() != $blog->getID())
            exit("permission_error");
        $l = (int) $post->getLikes();
        $like = new PostLike($post->getID());
        if($like->run()==1) {
            $l++;
            $status = "unlike";
        } else {
            $l--;
            $status = "like";
        }
        $post->setLikes($l);
        $post->edit(0);
        exit($status);
    } catch (Exception $e) {
        exit(Reporter::report($e, true));
    }
}

// Comment
if ($query->command == "comment") {
    try {
        if (!Captcha::check($query->captcha))
            exit("captcha_wrong");
        if (!strlen($query->message))
            exit("message_empty");
        if (!strlen($query->author))
            exit("author_empty");
        $blog = new Blog();
        $blog->detect();
        $post = new Post();
        $post->setID($query->post);
        $post->connect();
        if($post->getBlog() != $blog->getID())
            exit("permission_error");
        $comment = new Comment();
        $comment->setPost($query->post);
        $comment->setMessage($query->message);
        $comment->setAuthor($query->author);
        $comment->setBlog($blog->getID());
        $comment->setEmail(trim($query->email));
        if ($post->getComment() == 0)
            $comment->setStatus(1);
        else
            $comment->setStatus(0);
        $comment->create();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// Comment method
if ($query->command == "comment_method") {
    try {
        $post = new Post();
        $post->setID($query->post);
        $post->connect();
        if ($post->getComment() == 0)
            exit("yes");
        exit("no");
    }
    catch (Exception $e) {
        exit(Reporter::report($e, true));
    }
}