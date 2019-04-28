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
    <link href="view/appearance.css" rel="stylesheet" type="text/css">
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
            <!--Cover-->
            <div class="cover" style="background-image: url('<?php echo $blog->getCover(); ?>')"></div>
            <div class="field-box">
                <label for="cover">کاور (850x250):</label>
                <input type="text" id="cover" value="" class="field" maxlength="250">
            </div>
            <br><br>
            <!--Logo-->
            <div class="logo" style="background-image: url('<?php echo $blog->getLogo(); ?>')"></div>
            <div class="field-box">
                <label for="logo">لوگو (150x150):</label>
                <input type="text" id="logo" value="" class="field" maxlength="250">
            </div>
            <br><br>
            <!--Favicon-->
            <div class="favicon" style="background-image: url('<?php echo $blog->getFavicon(); ?>')"></div>
            <div class="field-box">
                <label for="favicon">ایکون (ico.):</label>
                <input type="text" id="favicon" value="" class="field" maxlength="250">
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
    <script type="text/javascript" src="view/appearance.js"></script>
    <!--end-->
</body>

</html>