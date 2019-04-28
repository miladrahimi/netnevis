<!DOCTYPE html>
<html>

<head lang="fa">
    <meta charset="UTF-8">
    <title>نت نویس | لیست وبلاگ ها</title>
    <meta name="description" content="لیست همه وبلاگ های نت نویس">
    <meta name="keywords" content="نت نویس, وبلاگ, لیست, فهرست">
    <link rel="author" href="https://plus.google.com/106945145782595448252">
    <link rel="publisher" href="https://google.com/+NetNevisOfficial">
    <link rel="canonical" href="http://netnevis.ir">
    <link rel="icon" href="/shared/image/favicon.ico">
    <!--GooglePlus-->
    <meta itemprop="name" content="نت نویس | لیست وبلاگ ها">
    <meta itemprop="description" content="لیست همه وبلاگ های نت نویس">
    <meta itemprop="image" content="/shared/image/logo.png">
    <!--Facebook-->
    <meta property="og:url" content="http://netnevis.ir/page/ads.php">
    <meta property="og:image" content="/shared/image/logo.png">
    <meta property="og:description" content="لیست همه وبلاگ های نت نویس">
    <meta property="og:title" content="نت نویس | لیست وبلاگ ها">
    <meta property="og:site_name" content="نت نویس">
    <meta property="og:see_also" content="http://netnevis.ir">
    <!--Twitter-->
    <meta name="twitter:card" content="لیست همه وبلاگ های نت نویس">
    <meta name="twitter:url" content="http://netnevis.ir/page/ads.php">
    <meta name="twitter:title" content="نت نویس | لیست وبلاگ ها">
    <meta name="twitter:description" content="لیست همه وبلاگ های نت نویس">
    <meta name="twitter:image" content="/shared/image/logo.png">
    <!--End-->
</head>

<body id="body">
    <!-- Google Tag Manager -->
    <noscript>
        <iframe src="//www.googletagmanager.com/ns.html?id=GTM-W43R6V" height="0" width="0"
                style="display:none;visibility:hidden"></iframe>
    </noscript>
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-W43R6V');</script>
    <!-- End Google Tag Manager -->
    <!--Begin-->
    <h1>نت نویس</h1>
    <h2>لیست وبلاگ ها</h2>
    <?php
    $blogs = Blog::getAll();
    foreach($blogs as $id) {
        $blog = new Blog();
        $blog->setID($id);
        $blog->connect();
        echo '<p><a href="'.$blog->getURL().'" title="'.$blog->getTitle().'">'.$blog->getURL().'</a>';
        echo '<br><a href="'.$blog->getURL().'/sitemap.xml" title="'.$blog->getTitle().'">'.
            $blog->getURL().'/sitemap.xml</a></p>';
    }
    ?>
    <!--Script-->
    <script type="text/javascript" src="/shared/library/json3.min.js"></script>
    <script type="text/javascript" src="/shared/library/jquery-1.11.1.min.js"></script>
    <!--End-->
</body>

</html>