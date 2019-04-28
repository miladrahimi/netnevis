<?php

    // Session
    if (!isset($_SESSION))
        session_start();

    // LookUp
    $_SESSION['lookup'] = 0;

    // Get News
    try {
        $site = parse_url($SITE, PHP_URL_HOST);
        $posts_id = Blog::getNews();
        $posts = array();
        $i = 0;
        foreach($posts_id as $post_id=>$blog_id) {
            $blog = new Blog();
            $blog->setID($blog_id);
            $blog->connect();
            $posts[$i] = array();
            $posts[$i]["subdomain"] = $blog->getSubdomain();
            $posts[$i]["logo"] = $blog->getLogo();
            $posts[$i]["url"] = $blog->getSubdomain() . '.' . $site;
            $post = new Post();
            $post->setID($post_id);
            $post->connect();
            $posts[$i]["title"] = $post->getTitle();
            $posts[$i]["content"] = $post->getDescription();
            $i++;
        }
    } catch(Exception $e) {
        $error = Reporter::report($e,true);
    }
?>
<!DOCTYPE html>
<html>

<head lang="fa">
    <meta charset="UTF-8">
    <title>نت نویس | سرویس وبلاگ دهی رایگان</title>
    <meta name="description" content="نت نویس، سرویس وبلاگ دهی رایگان برای همه پارسی زبانان">
    <meta name="keywords" content="نت نویس, سرویس وبلاگ دهی, وبلاگ دهی رایگان, ساخت وبلاگ رایگان">
    <link rel="stylesheet" href="/shared/pages/homepage.css">
    <link rel="author" href="https://plus.google.com/106945145782595448252">
    <link rel="publisher" href="https://google.com/+NetNevisOfficial">
    <link rel="canonical" href="http://netnevis.ir">
    <link rel="icon" href="/shared/image/favicon.ico">
    <!--GooglePlus-->
    <meta itemprop="name" content="نت نویس | سرویس وبلاگ دهی رایگان">
    <meta itemprop="description" content="نت نویس، سرویس وبلاگ دهی رایگان برای همه پارسی زبانان">
    <meta itemprop="image" content="/shared/image/logo.png">
    <!--Facebook-->
    <meta property="og:url" content="http://netnevis.ir">
    <meta property="og:image" content="/shared/image/logo.png">
    <meta property="og:description" content="نت نویس، سرویس وبلاگ دهی رایگان برای همه پارسی زبانان">
    <meta property="og:title" content="نت نویس | سرویس وبلاگ دهی رایگان">
    <meta property="og:site_name" content="نت نویس | سرویس وبلاگ دهی رایگان">
    <meta property="og:see_also" content="http://netnevis.ir">
    <!--Twitter-->
    <meta name="twitter:card" content="نت نویس، سرویس وبلاگ دهی رایگان برای همه پارسی زبانان">
    <meta name="twitter:url" content="http://netnevis.ir">
    <meta name="twitter:title" content="نت نویس | سرویس وبلاگ دهی رایگان">
    <meta name="twitter:description" content="نت نویس، سرویس وبلاگ دهی رایگان برای همه پارسی زبانان">
    <meta name="twitter:image" content="/shared/image/logo.png">
    <!--End-->
</head>

<body>
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
    <!--Header-->
    <div class="header">
        <div class="header-main">
            <div class="header-logo">
                <img src="/shared/image/logo60.png" title="لوگوی نت نویس" alt="لوگوی نت نویس"></div>
            <a href="/admin/account.php" class="header-signin-link" title="ورود کاربران">
                <div class="header-signin" id="header_signin">ورود</div></a>
            <a href="/admin/account.php?signup" class="header-signup-link" title="نام نویسی کاربر تازه">
                <div class="header-signup" id="header_signup">نام‌نویسی</div></a>
        </div>
    </div>
    <!--Main-->
    <div class="main">
        <div class="main-main">
            <div class="main-titles">
                <div class="main-title"><h1>نِت نویس</h1></div>
                <div class="main-desc"><h2>سرویس وبلاگ دهی رایگان</h2></div>
            </div>
            <div class="main-whois">
                <div class="main-offer">همین الان زیردامنه مناسب وبلاگ خود را پیدا کنید:</div>
                <label><input type="text" id="main_subdomain" class="main-subdomain" value="example"></label>
                <label><input type="text" class="main-netnevis" value=".netnevis.ir" disabled></label>
                <label><input type="text" id="main_lookup" class="main-lookup" value=" " maxlength="0"></label>
                <div class="main-message" id="main_ok">زیردامنه آزاد است! برای ساخت آن <a
                        href="/admin/account.php?signup" title="نام نویسی کاربر تازه">نام‌نویسی</a> کنید.</div>
                <div class="main-message" id="main_err"></div>
            </div>
        </div>
    </div>
    <!--Tools-->
    <div class="tools">
        <div class="tools-main">
            <div class="tools-section">
                <h2>از امکانات پایه</h2>
                <p>یک زیردامنه رایگان برای هر وبلاگ</p>
                <p>کنترل پنل کاربرپسند و پیشرفته برای هر وبلاگ</p>
                <p>پست آسان نوشته، عکس، فیلم و انواع رسانه‌ها</p>
                <p>امکان دسته‌بندی پست‌ها با استفاده از موضوع‌ها</p>
                <p>امکان معرفی وبلاگ های دوستان با نام پیوند‌ها</p>
                <p>مدیریت آسان دیدگاه‌ها</p>
            </div>
            <div class="tools-section">
                <h2>از امکانات کاربری</h2>
                <p>امکان ساخت چندین وبلاگ با یک اکانت</p>
                <p>مدریت آسان وبلاگ‌ها</p>
                <p>وبلاگ نویسی گروهی</p>
                <p>جایگاه های گوناگون کاربری در وبلاگ</p>
                <p>مدیریت آسان نویسندگان و اعضای وبلاگ</p>
                <p>تصویر کاربری و اطلاعات جداگانه برای هر نویسنده</p>
            </div>
            <div class="tools-section">
                <h2>از امکانات پیشرفته</h2>
                <p>صفحه درباره‌ما و ارتباط‌باما پیشرفته برای هر وبلاگ</p>
                <p>نقشه سایت و خبرخوان برای هر وبلاگ</p>
                <p>امکان ساخت نظرسنجی به شکل ابزارک (بزودی)</p>
                <p>آمار بازدید در کنترل پنل و ابزارک‌ها (بزودی)</p>
                <p>ابزارک جستجو (بزودی)</p>
                <p>امکان پیوند دامنه به وبلاگ (بزودی)</p>
            </div>
            <div style="clear: both;"></div>
            <div class="tools-section2">
                <h2>پوسته وبلاگ</h2>
                <p>همه چیز آماده است تا شما از نوشتن لذت ببرید!</p>
                <p>
                    <span>دیگر درگیر برگزیدن و ویرایش پوسته نباشید، </span>
                    <span>بهترین آن برای شما آماده شده است، </span>
                    <span>پوسته‌ای مناسب برای وبلاگ های حرفه‌ای </span>
                    <span>که از بررسی هزاران وبلاگ و الگو برداری از بهترین شبکه های اجتماعی تهیه شده است.</span>
                </p>
                <p>
                    <span>می‌خواهید وبلاگی متمایز داشته باشید؟ می‌توانید از کاور و لوگوی دلخواه خود استفاده کنید، </span>
                    <span>چینش پیشفرض ابزارک‌ها را دوست ندارید؟ می‌توانید آنها را به دلخواه خود بچینید (بزودی)، </span>
                    <span>امکانات بیشتر می‌خواهید؟ </span>
                    <span>اسکریپت های خود را در هدر و یا بصورت ابزارک در وبلاگ قرار دهید (بزودی).</span>
                </p>
                <p>
                    <span>پوسته و دیگر امکانات نت نویس به‌گونه‌ای طراحی شده‌اند </span>
                    <span>تا شما در کمال‌آرامش ایده‌های خود را منتشر کنید...</span>
                </p>
            </div>
            <div class="tools-section3">
                <h2>از امکانات ویژه</h2>
                <p>استفاده از هشتگ‌ها بجای برچسب‌گذاری سنتی</p>
                <p>تفکیک هوشمند موضوعات وبلاگ</p>
                <p>سیستم پسندیدن پست‌ها</p>
                <p>بهینه سازی وبلاگ برای موتور های جستجوگر</p>
                <p>امکان دنبال کردن وبلاگ‌ها (بزودی)</p>
                <p>گالری عکس، فیلم و آهنگ (بزودی)</p>
                <p>آپلود فایل (بزودی)</p>
            </div>
        </div>
    </div>
    <!--offer-->
    <div class="offer">
        <div class="offer-main">
            <div class="offer-message">برای ساختن وبلاگ خود آماده اید؟</div>
            <a href="/admin/account.php?signup" title="نام نویسی"><div class="offer-signup">نام نویسی</div></a>
        </div>
    </div>
    <!--News-->
    <div class="news">
        <div class="news-title"><h2>تازه های دنیای وبلاگ نویسی...</h2></div>
        <div class="news-main">
            <div class="news-column">
                <?php
                if(empty($error) && !empty($posts)) {
                    for($i = 0; $i<15 && $posts[$i]; $i= $i + 3) {
                        echo "\n\r";
                        echo '<div class="news-item">';
                        echo ' <div class="news-item-logo" style="background-image: url('.$posts[$i]["logo"].')"></div>';
                        echo ' <div class="news-item-text" onclick="goWeblog(\'http://' . $posts[$i]["url"] . '\')">';
                        echo '  <div class="news-item-title"><h3>' . $posts[$i]["title"] . '</h3></div>';
                        echo '  <div class="news-item-url">'.$posts[$i]["url"].'</div>';
                        echo '  <div class="news-item-content"><p>'.$posts[$i]["content"].'</p></div>';
                        echo ' </div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>یک خطای سیستمی رخ داده است و هم اکنون فهرست وبلاگ های بروز شده در دسترس نیست...</p>';
                }
                ?>
            </div>
            <div class="news-column">
                <?php
                if(empty($error) && !empty($posts)) {
                    for($i = 1; $i<15 && $posts[$i]; $i= $i + 3) {
                        echo "\n\r";
                        echo '<div class="news-item">';
                        echo ' <div class="news-item-logo" style="background-image: url('.$posts[$i]["logo"].')"></div>';
                        echo ' <div class="news-item-text" onclick="goWeblog(\'http://' . $posts[$i]["url"] . '\')">';
                        echo '  <div class="news-item-title"><h3>' . $posts[$i]["title"] . '</h3></div>';
                        echo '  <div class="news-item-url">'.$posts[$i]["url"].'</div>';
                        echo '  <div class="news-item-content"><p>'.$posts[$i]["content"].'</p></div>';
                        echo ' </div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>یک خطای سیستمی رخ داده است و هم اکنون فهرست وبلاگ های بروز شده در دسترس نیست...</p>';
                }
                ?>
            </div>
            <div class="news-column">
                <?php
                if(empty($error) && !empty($posts)) {
                    for($i = 2; $i<15 && $posts[$i]; $i= $i + 3) {
                        echo "\n\r";
                        echo '<div class="news-item">';
                        echo ' <div class="news-item-logo" style="background-image: url('.$posts[$i]["logo"].')"></div>';
                        echo ' <div class="news-item-text" onclick="goWeblog(\'http://' . $posts[$i]["url"] . '\')">';
                        echo '  <div class="news-item-title"><h3>' . $posts[$i]["title"] . '</h3></div>';
                        echo '  <div class="news-item-url">'.$posts[$i]["url"].'</div>';
                        echo '  <div class="news-item-content"><p>'.$posts[$i]["content"].'</p></div>';
                        echo ' </div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>یک خطای سیستمی رخ داده است و هم اکنون فهرست وبلاگ های بروز شده در دسترس نیست...</p>';
                }
                ?>
            </div>
        </div>
    </div>
    <!--Footer-->
    <div class="footer-fake"></div>
    <div class="footer" id="footer">
        <div class="footer-main">
            <div class="footer-links">
                <a href="http://admin.netnevis.ir" title="وبلاگ">وبلاگ</a>
                <a href="/ads" title="تبلیغات در نت نویس">تبلیغات</a>
                <a href="/help" title="راهنمای وبلاگ نویسی">راهنما</a>
                <a href="/terms" title="قوانین ( شرایط) استفاده">قوانین</a>
                <a href="/about" title="درباره">درباره</a>
                <a href="/contact" title="ارتباط">ارتباط</a>
            </div>
            <span class="footer-rights">&copy تمام حقوق این وب‌سایت متعلق به نت نویس می باشد.</span>
        </div>
    </div>
    <!--Script-->
    <script type="text/javascript" src="/shared/library/json3.min.js"></script>
    <script type="text/javascript" src="/shared/library/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/shared/pages/homepage.js"></script>
    <!--End-->
</body>

</html>