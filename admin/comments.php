<?php

// Autoload
function admin_comments_autoload($class) {
    include_once dirname(__FILE__)."/../core/".strtolower($class).".php";
} spl_autoload_register("admin_comments_autoload");
// Detect Member
if(!Member::detect()) {
    ob_start();
    header("Location: account.php");
    ob_flush();
}
// Detect Blog
try {
    $blog = new Blog();
    $blog->identify();
    $job = new Job();
    $job->setBlog($blog->getID());
    $job->setMember(Member::detect());
    $job->checkRole();
} catch (Exception $e) {
    ob_start();
    header("Location: account.php");
    ob_flush();
}

?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>نت نویس | مدیریت وبلاگ</title>
    <link rel="shortcut icon" href="/shared/image/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/shared/image/favicon.ico" type="image/x-icon">
    <link href="view/_general.css" rel="stylesheet" type="text/css">
    <link href="view/_header.css" rel="stylesheet" type="text/css">
    <link href="view/_blog.css" rel="stylesheet" type="text/css">
    <link href="view/_menu.css" rel="stylesheet" type="text/css">
    <link href="view/_footer.css" rel="stylesheet" type="text/css">
    <link href="view/comments.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="loading" id="loading"></div>
    <?php include_once dirname(__FILE__) . "/_header.php"; ?>
    <!--page-->
    <div class="page">
        <?php include_once dirname(__FILE__) . "/_blog.php"; ?>
        <?php include_once dirname(__FILE__) . "/_menu.php"; ?>
        <!--main-->
        <div class="main" id="main">
            <div class="message" id="message"></div>
            <div class="tip" id="tip">
                <span>در این بخش شما می توانید دیدگاه‌های تازه وبلاگ را ببنید.</span><br>
                <span>برای دیدن همه دیدگاه‌های یک پست می توانید در بخش پست‌ها روی آیکون دیدگاه کلیک کنید.</span><br>
            </div>
            <div id="comments"></div>
        </div>
    </div>
    <?php include_once dirname(__FILE__) . "/_footer.php"; ?>
    <!--Script-->
    <script type="text/javascript" src="/shared/library/json3.min.js"></script>
    <script type="text/javascript" src="/shared/library/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="view/_general.js"></script>
    <script type="text/javascript" src="view/_blog.js"></script>
    <script type="text/javascript" src="view/comments.js"></script>
    <!--end-->
</body>

</html>