<?php

// Autoload
function admin_post_autoload($class) {
    include_once dirname(__FILE__)."/../core/".strtolower($class).".php";
} spl_autoload_register("admin_post_autoload");
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
    <link href="view/post.css" rel="stylesheet" type="text/css">
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
            <div class="box">
                <label for="post_title" class="label">عنوان:</label>
                <input type="text" id="post_title" class="field" maxlength="70" value="">
            </div>
            <div class="box">
                <label for="post_cat" class="label">دسته:</label>
                <input type="text" id="post_cat" class="field" maxlength="40" value="">
            </div>
            <div class="content" id="post_content"></div>
            <div style="clear: both; height: 10px;"></div>
            <div class="box">
                <label for="post_comment"></label>
                <select id="post_comment" class="comment-method">
                    <option>دیدگاه‌ها فورا منتشر شوند.</option>
                    <option>دیدگاه‌ها پس از تایید منتشر شوند.</option>
                    <option>دیدگاه پذیرفته نشود.</option>
                </select>
            </div>
            <div>
                <label><input type="text" class="field1 captcha" id="publish_captcha"></label>
                <label for="publish"></label>
                <input type="text" class="button2 button-x" id="publish" data-id="0" value="انتشار" maxlength="0">
            </div>
        </div>
    </div>
    <?php include_once dirname(__FILE__) . "/_footer.php"; ?>
    <!--Script-->
    <script type="text/javascript" src="/shared/library/json3.min.js"></script>
    <script type="text/javascript" src="/shared/library/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/shared/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="view/_general.js"></script>
    <script type="text/javascript" src="view/_blog.js"></script>
    <script type="text/javascript" src="view/post.js"></script>
    <!--end-->
</body>

</html>