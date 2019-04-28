<!doctype html>

<html>

<head lang="fa">
    <meta charset="UTF-8">
    <title><?php echo $blog->getTitle(); ?> | آرشیو وبلاگ</title>
    <meta name="description" content="<?php echo $blog->getDescription(); ?>">
    <meta name="keywords" content="<?php echo $blog->getKeywords(); ?>">
    <link rel="author" href="<?php echo $blog->getGAuthor(); ?>">
    <link rel="publisher" href="<?php echo $blog->getGPublisher(); ?>">
    <link rel="canonical" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>">
    <link rel="icon" href="<?php echo $blog->getFavicon(); ?>">
    <link rel="shortcut icon" href="<?php echo $blog->getFavicon(); ?>">
    <link href="<?php echo $SITE; ?>/shared/theme/header.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $SITE; ?>/shared/theme/sidebar.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $SITE; ?>/shared/theme/archive.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $SITE; ?>/shared/theme/footer.css" rel="stylesheet" type="text/css">
    <!--GooglePlus-->
    <meta itemprop="name" content="<?php echo $blog->getTitle(); ?>">
    <meta itemprop="description" content="<?php echo $blog->getDescription(); ?>">
    <meta itemprop="image" content="<?php echo $blog->getLogo(); ?>">
    <!--Facebook-->
    <meta property="og:url" content="http://<?php echo $_SERVER['HTTP_HOST']; ?>">
    <meta property="og:image" content="<?php echo $blog->getLogo(); ?>">
    <meta property="og:description" content="<?php echo $blog->getDescription(); ?>">
    <meta property="og:title" content="<?php echo $blog->getTitle(); ?>">
    <meta property="og:site_name" content="<?php echo $blog->getTitle(); ?>">
    <meta property="og:see_also" content="http://netnevis.ir">
    <!--Twitter-->
    <meta name="twitter:card" content="<?php echo $blog->getDescription(); ?>">
    <meta name="twitter:url" content="http://<?php echo $_SERVER['HTTP_HOST']; ?>">
    <meta name="twitter:title" content="<?php echo $blog->getTitle(); ?>">
    <meta name="twitter:description" content="<?php echo $blog->getDescription(); ?>">
    <meta name="twitter:image" content="<?php echo $blog->getLogo(); ?>">
    <!--MetaTags-->
    <?php echo $blog->getMetatags(); ?>
    <!--End-->
</head>

<body>
<?php include dirname(__FILE__) . "/header.php"; ?>
<div class="blog-main">
    <?php include dirname(__FILE__) . "/sidebar.php"; ?>
    <!--postbar-->
    <div class="archive-bar">
        <?php foreach($archive as $item) { ?>
        <a href="/archive/<?php echo $item["year"]; ?>/<?php echo $item["month"]; ?>">
        <div class="archive-section"><?php echo $item["date"]; ?> (<?php echo $item["num"]; ?> پست)</div>
        </a>
        <?php } ?>
        <?php if(empty($archive)) { ?>
            <div class="archive-section">در این زمان هیچ پستی منتشر نشده است...</div>
        <?php } ?>
    </div>
    <!--/postbar-->
    <?php include dirname(__FILE__) . "/footer.php"; ?>
    <!--Script-->
    <script src="<?php echo $SITE; ?>/shared/library/json3.min.js" type="text/javascript"></script>
    <script src="<?php echo $SITE; ?>/shared/library/jquery-1.11.1.min.js" type="text/javascript"></script>
</div>
</body>

</html>