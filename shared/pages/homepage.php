<?php

// Autoload
function page_home_autoload($class) {
    include_once dirname(__FILE__) . "/../../core/" . strtolower($class) . ".php";
}
spl_autoload_register("page_home_autoload");

// Get query
$q = empty($_GET["q"]) ? "" : trim($_GET["q"]);
$query = Security::JSONInput($q);


// lookup
if ($query->command == "lookup") {
    $subdomain = empty($query->subdomain) ? "" : $query->subdomain;
    try {
        // Lookup security : Every 10 Request 10 Seconds Sleep!
        if (!isset($_SESSION))
            session_start();
        if (!isset($_SESSION['lookup']))
            exit('direct_access_error');
        $_SESSION['lookup'] = ((int) $_SESSION['lookup']) + 1;
        if ($_SESSION['lookup'] % 10 == 0)
            sleep(10);
        // Continue
        $blog = new Blog();
        $blog->setSubdomain($subdomain);
        $blog->connectBySubdomain();
        if ($blog->getID())
            exit("reserved");
        exit("ok");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}