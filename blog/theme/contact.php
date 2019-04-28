<!doctype html>

<html>

<head lang="fa">
    <meta charset="UTF-8">
    <title><?php echo $blog->getTitle(); ?> | ارتباط با ما</title>
    <meta name="description" content="<?php echo $blog->getAbout(); ?>">
    <meta name="keywords" content="<?php echo $blog->getKeywords(); ?>">
    <link rel="author" href="<?php echo $blog->getGAuthor(); ?>">
    <link rel="publisher" href="<?php echo $blog->getGPublisher(); ?>">
    <link rel="canonical" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>">
    <link rel="icon" href="<?php echo $blog->getFavicon(); ?>">
    <link rel="shortcut icon" href="<?php echo $blog->getFavicon(); ?>">
    <link href="<?php echo $SITE; ?>/shared/theme/header.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $SITE; ?>/shared/theme/sidebar.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $SITE; ?>/shared/theme/contact.css" rel="stylesheet" type="text/css">
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
    <div class="loading" id="loading"></div>
    <?php include dirname(__FILE__) . "/header.php"; ?>
    <div class="blog-main">
        <?php include dirname(__FILE__) . "/sidebar.php"; ?>
        <div class="contact-bar">
            <div class="contact">
                <h2>ارتباط با ما</h2>
                <div class="message" id="message"></div>
                <p>با استفاده از فرم زیر می توانید پیام خود را برای مدیر وبلاگ ایمیل کنید.</p>
                <p>
                    <label for="contact_author"></label>
                    <input type="text" id="contact_author" class="contact-author" value="نام"><br>
                    <label for="contact_email"></label>
                    <input type="text" id="contact_email" class="contact-email" value="ایمیل"><br>
                    <label for="contact_message"></label>
                    <textarea id="contact_message" class="contact-message">پیام</textarea><br>
                    <label for="contact_captcha"></label>
                    <input type="text" id="contact_captcha" class="contact-captcha" value="کد امنیتی"><br>
                    <input type="button" id="contact_submit" class="contact-submit" value="فرستادن">
                </p>
            </div>
        </div>
    </div>
    <?php include dirname(__FILE__) . "/footer.php"; ?>
    <!--Script-->
    <script src="<?php echo $SITE; ?>/shared/library/json3.min.js" type="text/javascript"></script>
    <script src="<?php echo $SITE; ?>/shared/library/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="<?php echo $SITE; ?>/shared/theme/contact.js" type="text/javascript"></script>
</body>

</html>