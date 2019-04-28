<?php

// Autoload
function admin_posts_autoload($class) {
    include_once dirname(__FILE__)."/../core/".strtolower($class).".php";
} spl_autoload_register("admin_posts_autoload");
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
    Reporter::report($e,true);
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
    <link href="view/posts.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="loading" id="loading"></div>
    <?php include_once dirname(__FILE__) . "/_header.php"; ?>
    <!--page-->
    <div class="page">
        <?php include_once dirname(__FILE__) . "/_blog.php"; ?>
        <?php include_once dirname(__FILE__) . "/_menu.php"; ?>
        <div class="main" id="main">
            <div class="message" id="message"></div>
            <div class="delete-confirm" id="delete_confirm">
                <p>برای پاک کردن پست، پس از نوشتن کد امنیتی، روی دکمه پاک کردن کلیک کنید:</p>
                <div>
                    <label for="delete_captcha"></label>
                    <input type="text" class="field1 captcha" id="delete_captcha">
                    <label for="delete_button"></label>
                    <input type="text" class="button1 button-x" id="delete_button" data-post=""
                           maxlength="0" value="پاک کردن" >
                </div>
            </div>
            <div><label for="archive"></label><select id="archive" class="archive"></select></div>
            <div class="posts" id="posts"></div>
            <div class="pages" id="pages"></div>
        </div>
    </div>
    <?php include_once dirname(__FILE__) . "/_footer.php"; ?>
    <!--Script-->
    <script type="text/javascript" src="/shared/library/json3.min.js"></script>
    <script type="text/javascript" src="/shared/library/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="view/_general.js"></script>
    <script type="text/javascript" src="view/_blog.js"></script>
    <script type="text/javascript" src="view/posts.js"></script>
    <!--end-->
</body>

</html>