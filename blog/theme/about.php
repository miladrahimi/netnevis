<!doctype html>

<html>

<head lang="fa">
    <meta charset="UTF-8">
    <title><?php echo $blog->getTitle(); ?> | درباره</title>
    <meta name="description" content="<?php echo $blog->getAbout(); ?>">
    <meta name="keywords" content="<?php echo $blog->getKeywords(); ?>">
    <link rel="author" href="<?php echo $blog->getGAuthor(); ?>">
    <link rel="publisher" href="<?php echo $blog->getGPublisher(); ?>">
    <link rel="canonical" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>">
    <link rel="icon" href="<?php echo $blog->getFavicon(); ?>">
    <link rel="shortcut icon" href="<?php echo $blog->getFavicon(); ?>">
    <link href="<?php echo $SITE; ?>/shared/theme/header.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $SITE; ?>/shared/theme/sidebar.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $SITE; ?>/shared/theme/about.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $SITE; ?>/shared/theme/footer.css" rel="stylesheet" type="text/css">
    <!--GooglePlus-->
    <meta itemprop="name" content="<?php echo $blog->getTitle(); ?>">
    <meta itemprop="description" content="<?php echo $blog->getAbout(); ?>">
    <meta itemprop="image" content="<?php echo $blog->getLogo(); ?>">
    <!--Facebook-->
    <meta property="og:url" content="http://<?php echo $_SERVER['HTTP_HOST']; ?>">
    <meta property="og:image" content="<?php echo $blog->getLogo(); ?>">
    <meta property="og:description" content="<?php echo $blog->getAbout(); ?>">
    <meta property="og:title" content="<?php echo $blog->getTitle(); ?>">
    <meta property="og:site_name" content="<?php echo $blog->getTitle(); ?>">
    <meta property="og:see_also" content="http://netnevis.ir">
    <!--Twitter-->
    <meta name="twitter:card" content="<?php echo $blog->getAbout(); ?>">
    <meta name="twitter:url" content="http://<?php echo $_SERVER['HTTP_HOST']; ?>">
    <meta name="twitter:title" content="<?php echo $blog->getTitle(); ?>">
    <meta name="twitter:description" content="<?php echo $blog->getAbout(); ?>">
    <meta name="twitter:image" content="<?php echo $blog->getLogo(); ?>">
    <!--MetaTags-->
    <?php echo $blog->getMetatags(); ?>
    <!--End-->
</head>

<body>
<?php include dirname(__FILE__) . "/header.php"; ?>
<div class="blog-main">
    <?php include dirname(__FILE__) . "/sidebar.php"; ?>
    <!--about-->
    <div class="about-bar">
        <div class="about">
            <h2>درباره وبلاگ</h2>
            <p class="about-text"><?php echo $blog->getAbout(); ?></p>
            <br>
            <h2>نویسندگان</h2>
            <?php for($i=0; !empty($authors[$i]); $i++) { ?>
                <?php

                switch($authors[$i]['role']) {
                    case "admin":
                        $authors[$i]['role'] = "مدیر";
                        break;
                    case "assistant":
                        $authors[$i]['role'] = "معاون";
                        break;
                    case "editor":
                        $authors[$i]['role'] = "ویراستار";
                        break;
                    case "writer":
                        $authors[$i]['role'] = "نویسنده";
                        break;
                    default:
                        $authors[$i]['role'] = "نویسنده";
                }
                
                ?>
            <div class="author-item">
                <div class="author-avatar" style="background-image: url(<?php echo $authors[$i]["avatar"]; ?>)"></div>
                <div class="author-title">
                    <h2><a href="<?php echo $authors[$i]["link"]; ?>"><?php echo $authors[$i]["name"]; ?></a></h2>
                    <span class="author-info"> (<?php echo $authors[$i]["role"]; ?>) ::
                        <?php echo $authors[$i]["posts"]; ?> پست</span>
                </div>
                <div class="author-about"><p><?php echo $authors[$i]["about"]; ?></p></div>
            </div>
            <?php } ?>
        </div>
    </div>
    <!--/about-->
    <?php include dirname(__FILE__) . "/footer.php"; ?>
    <!--Script-->
    <script src="<?php echo $SITE; ?>/shared/library/json3.min.js" type="text/javascript"></script>
    <script src="<?php echo $SITE; ?>/shared/library/jquery-1.11.1.min.js" type="text/javascript"></script>
</div>
</body>

</html>