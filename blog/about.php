<?php

// Authors
try {
    $authors_id = $blog->getAuthors();
    $authors    = array();
    $i          = 0;
    foreach ($authors_id as $job_id=>$id) {
        $member = new Member();
        $member->setID($id);
        $member->connectByID();
        $authors[$i]           = array();
        $authors[$i]["name"]   = $member->getNickname();
        $authors[$i]["avatar"] = $member->getAvatar();
        $authors[$i]["link"]   = "/author/" . $member->getID() . "/" . $member->getNickname();
        $authors[$i]["posts"]  = $member->getNumberOfPosts($blog->getID());
        $authors[$i]["about"]  = $member->getAbout();
        $job = new Job();
        $job->setID($job_id);
        $job->connectByID();
        $authors[$i]["role"]  = $job->getRole();
        $i++;
    }
} catch (Exception $e) {
    Reporter::report($e, true);
    include dirname(__FILE__) . "/error_internal.php";
    exit();
}

// Set location
$menu_item = "about";
$kind      = "";

// Load page
include dirname(__FILE__) . "/sidebar.php";
include dirname(__FILE__) . "/theme/about.php";