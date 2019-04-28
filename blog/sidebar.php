<?php

// Cats
try {
    $cats = $blog->getCats();
    $sidebar_cats    = array();
    $i       = 0;
    foreach ($cats as $cat) {
        $sidebar_cats[$i]           = array();
        $sidebar_cats[$i]["title"]  = $cat;
        $sidebar_cats[$i]["number"] = $blog->getNumberOfCats($cat);
        $i++;
    }
} catch (Exception $e) {
    Reporter::report($e, true);
    include dirname(__FILE__) . "/error_internal.php";
    exit();
}

// Authors
try {
    $sidebar_authors_id = $blog->getAuthors();
    $sidebar_authors    = array();
    $i          = 0;
    foreach ($sidebar_authors_id as $job_id=>$id) {
        $author = new member();
        $author->setID($id);
        $author->connectByID();
        $job = new Job();
        $job->setID($job_id);
        $job->connectByID();
        $sidebar_authors[$i]["name"]   = $author->getNickname();
        $sidebar_authors[$i]["role"]   = $job->getRole();
        $sidebar_authors[$i]["posts"]  = $author->getNumberOfPosts($blog->getID());
        $sidebar_authors[$i]["avatar"] = $author->getAvatar();
        $sidebar_authors[$i]["link"]   = "/author/" . $author->getID() . "/" . $author->getNickname();
        $i++;
    }
} catch (Exception $e) {
    Reporter::report($e, true);
    include dirname(__FILE__) . "/error_internal.php";
    exit();
}

// Favorites
try {
    $sidebar_favorites_id = $blog->getFavorites();
    $sidebar_favorites    = array();
    $i = 0;
    foreach ($sidebar_favorites_id as $id) {
        $favorite = new post();
        $favorite->setID($id);
        $favorite->connect();
        $sidebar_favorites[$i]          = array();
        $sidebar_favorites[$i]["title"] = $favorite->getTitle();
        $sidebar_favorites[$i]["link"]  = "/post/" . $favorite->getID() . "/" . $favorite->getTitle();
        $i++;
    }
} catch (Exception $e) {
    Reporter::report($e, true);
    include dirname(__FILE__) . "/error_internal.php";
    exit();
}

// Recents
try {
    $sidebar_recents_id = $blog->getPosts();
    $sidebar_recents    = array();
    $i          = 0;
    foreach ($sidebar_recents_id as $id) {
        $recent = new post();
        $recent->setID($id);
        $recent->connect();
        $sidebar_recents[$i]          = array();
        $sidebar_recents[$i]["title"] = $recent->getTitle();
        $sidebar_recents[$i]["link"]  = "/post/" . $recent->getID() . "/" . $recent->getTitle();
        $i++;
    }
} catch (Exception $e) {
    Reporter::report($e, true);
    include dirname(__FILE__) . "/error_internal.php";
    exit();
}

// Visits
try {
    $visit = new Visit();
    $visit->setBlog($blog->getID());
    // Yesterday
    $visit->setDay(date("Y/m/d",mktime(0,0,0,date('m'),date('d')-1,date('Y'))));
    $visit->ofDay();
    $sidebar_statistics_yesterday_pages = $visit->getPages();
    // Today
    $visit->setDay(date('Y/m/d'));
    $visit->ofDay();
    $sidebar_statistics_today_pages = $visit->getPages();
    // This Month
    $visit->ofMonth();
    $sidebar_statistics_thismonth_pages = $visit->getPages();
    // Last Month
    $visit->setDay(date("Y/m/d",mktime(0,0,0,date('m')-1,date('d'),date('Y'))));
    $visit->ofMonth();
    $sidebar_statistics_lastmonth_pages = $visit->getPages();
    // All visits
    $visit->ofBlog();
    $sidebar_statistics_blog_visits = $visit->getPages();
    // Part2
    $sidebar_statistics_blog_born = JDate::date('Y/m/d',$blog->getMoment());
    $sidebar_statistics_blog_update = JDate::date('Y/m/d',$blog->getLastUpdate());
    $sidebar_statistics_blog_authors = $blog->getNumberOfAuthors();
    $sidebar_statistics_blog_posts = $blog->getNumberOfPosts();
} catch (Exception $e) {
    Reporter::report($e, true);
    include dirname(__FILE__) . "/error_internal.php";
    exit();
}

// Links
try {
    $sidebar_links_id = $blog->getLinks();
    $sidebar_links    = array();
    $i        = 0;
    foreach ($sidebar_links_id as $id) {
        $link     = new link();
        $link->setID($id);
        $link->connect();
        $sidebar_links[$i]["title"] = $link->getTitle();
        $sidebar_links[$i]["url"]   = $link->getURL();
        $i++;
    }
} catch (Exception $e) {
    Reporter::report($e, true);
    include dirname(__FILE__) . "/error_internal.php";
    exit();
}