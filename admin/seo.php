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
    <link href="view/seo.css" rel="stylesheet" type="text/css">
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
            <div class="tip" id="tip">
                <span>برای بدست آوردن جایگاه مناسب در موتور های جستجو می توانید در وب سایت آنها نام‌نویسی کنید</span>
                <span> و برای تایید هویت وبلاگ می توانید تگ های متای تایید هویت را در فیلد زیر (هر خط یک تگ)</span>
                <span>کپی کنید. نام‌نویسی در گوگل و بینگ (لینک های زیر) می تواند بسیار تاثیرگذار باشد.</span><br>
                <span>» <a href="http://google.com/webmasters" target="_blank">گوگل وبمستر</a></span>
                <span>، <a href="http://bing.com/webmasters" target="_blank">بینگ وبمستر</a></span>
            </div>
            <!--Metas-->
            <div class="field-box">
                <label for="metatags" style="height:70px">تگ های متا:</label>
                <textarea class="field" id="metatags" maxlength="250" style="height:70px;direction:ltr;text-align:left"
                    ><?php echo $blog->getMetatags(); ?></textarea>
            </div>
            <br>
            <label><br><input type="button" id="save" class="button2" value="ذخیره"> </label>
        </div>
    </div>
    <?php include_once dirname(__FILE__) . "/_footer.php"; ?>
    <!--Script-->
    <script type="text/javascript" src="/shared/library/json3.min.js"></script>
    <script type="text/javascript" src="/shared/library/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="view/_general.js"></script>
    <script type="text/javascript" src="view/_blog.js"></script>
    <script type="text/javascript" src="view/seo.js"></script>
    <!--end-->
</body>

</html>