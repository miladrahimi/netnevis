<?php

// Autoload
function __autoload($class) {
    include_once dirname(__FILE__) . '/core/' . strtolower($class) . '.php';
}

// Cron Jobs! :))
// PostLike::cleanExpireds();
// MemberConfirm::cleanExpireds();

// Essentials
$_GET['q'] = empty($_GET['q']) ? 'home' : trim($_GET['q'], '/');
$QUERY     = explode('/', $_GET['q']);

// Current website Address
$general = Config::load("general");
$SITE    = $general['site'];

// Website
if ($_SERVER['SERVER_NAME'] == $general['host']) {
    switch($QUERY[0]) {
        case 'home':
            include_once dirname(__FILE__) . '/pages/home.php';
            break;
        case 'about':
            include_once dirname(__FILE__) . '/pages/about.php';
            break;
        case 'ads':
            include_once dirname(__FILE__) . '/pages/ads.php';
            break;
        case 'contact':
            include_once dirname(__FILE__) . '/pages/contact.php';
            break;
        case 'help':
            include_once dirname(__FILE__) . '/pages/help.php';
            break;
        case 'sitemap.xml':
            include_once dirname(__FILE__) . '/pages/sitemap.php';
            break;
        case 'terms':
            include_once dirname(__FILE__) . '/pages/terms.php';
            break;
        case 'index':
            include_once dirname(__FILE__) . '/pages/index.php';
            break;
        default:
            include_once dirname(__FILE__) . '/pages/404.php';
            break;
    }
    exit();
}

// Blog
try {
    // Connect
    $blog = new Blog();
    $blog->detect();
    $blog->connect();
    $visit = new Visit();
    $visit->setBlog($blog->getID());
    $visit->run();
} catch (Exception $e) {
    $error = Reporter::report($e);
    if ($error == 'blog_connect_error') {
        include(dirname(__FILE__) . '/blog/error_blog.php');
    } else {
        Reporter::report($e, true);
        include(dirname(__FILE__) . '/blog/error_internal.php');
    }
    exit();
}

// Home
if ($QUERY[0] == 'home') {
    include(dirname(__FILE__) . '/blog/home.php');
}
// Home > Pages
else if ($QUERY[0] == 'page') {
    include(dirname(__FILE__) . '/blog/home.php');
}
// Post
else if ($QUERY[0] == 'post') {
    include(dirname(__FILE__) . '/blog/post.php');
}
// Tag
else if ($QUERY[0] == 'tag') {
    include(dirname(__FILE__) . '/blog/tag.php');
}
// Cat
else if ($QUERY[0] == 'cat') {
    include(dirname(__FILE__) . '/blog/cat.php');
}
// Auhtor
else if ($QUERY[0] == 'author') {
    include(dirname(__FILE__) . '/blog/author.php');
}
// Arhcive
else if ($QUERY[0] == 'archive' && empty($QUERY[1])) {
    include(dirname(__FILE__) . '/blog/archive_home.php');
}
// Archive > Month
else if ($QUERY[0] == 'archive') {
    include(dirname(__FILE__) . '/blog/archive.php');
}
// About
else if ($QUERY[0] == 'about') {
    include(dirname(__FILE__) . '/blog/about.php');
}
// Contact
else if ($QUERY[0] == 'contact') {
    include(dirname(__FILE__) . '/blog/contact.php');
}
// RSS
else if ($QUERY[0] == 'rss') {
    include(dirname(__FILE__) . '/blog/rss.php');
}
// Sitemap.xml
else if ($QUERY[0] == 'sitemap.xml') {
    include(dirname(__FILE__) . '/blog/sitemap.php');
}
// Error 404!
else {
    include(dirname(__FILE__) . '/blog/error_page.php');
}