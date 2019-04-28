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
    <link href="view/setting.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="loading" id="loading"></div>
    <?php include_once dirname(__FILE__) . "/_header.php"; ?>
    <!--page-->
    <div class="page">
        <?php include_once dirname(__FILE__) . "/_blog.php"; ?>
        <?php include_once dirname(__FILE__) . "/_menu.php"; ?>
        <!--main-->
        <div class="main">
            <div id="message" class="message"></div>
            <div class="field-box">
                <label for="subdomain">زیردامنه</label>
                <input type="text" id="subdomain" value="" class="field" maxlength="20" style="direction:ltr;">
            </div>
            <div class="field-box">
                <label for="title">عنوان وبلاگ</label>
                <input type="text" id="title" value="" class="field" maxlength="40">
            </div>
            <div class="field-box">
                <label for="description">چکیده وبلاگ</label>
                <input type="text" id="description" value="" class="field" maxlength="70">
            </div>
            <div class="field-box">
                <label for="email">ایمیل ارتباطی</label>
                <input type="text" id="email" value="" class="field" maxlength="40" style="direction:ltr;">
            </div>
            <div class="field-box">
                <label for="gauthor">گوگل+ مدیر</label>
                <input type="text" id="gauthor" value="" class="field" maxlength="250" style="width:400px">
            </div>
            <div class="field-box">
                <label for="gpublisher">گوگل+ وبلاگ</label>
                <input type="text" id="gpublisher" value="" class="field" maxlength="250" style="width:400px">
            </div>
            <div class="field-box">
                <label for="about" style="height:70px">درباره وبلاگ</label>
                <textarea class="field" id="about" maxlength="250" style="height:70px"></textarea>
            </div>
            <div class="delete-link" id="delete_link">[حذف وبلاگ]</div>
            <div class="delete-main" id="delete_main">
                <label><b>» حذف وبلاگ:</b></label><br><br>
                <label for="delete_password">گذرواژه مدیریت:</label><br>
                <input type="password" class="field1" id="delete_password"><br>
                <label><input type="text" class="field1 captcha" id="delete_captcha"></label><br><br>
                <label for="delete"></label>
                <input type="text" class="button1 button-x" id="delete" value="حذف" maxlength="0">
            </div>
            <label><input type="button" id="save" class="button2" value="بایگانی"> </label>
        </div>
    </div>
    <?php include_once dirname(__FILE__) . "/_footer.php"; ?>
    <!--Script-->
    <script type="text/javascript" src="/shared/library/json3.min.js"></script>
    <script type="text/javascript" src="/shared/library/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="view/_general.js"></script>
    <script type="text/javascript" src="view/_blog.js"></script>
    <script type="text/javascript" src="view/setting.js"></script>
    <!--end-->
</body>

</html>