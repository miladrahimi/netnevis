<!DOCTYPE html>

<html>

<head lang="fa">
    <meta charset="UTF-8">
    <title><?php echo $blog->getTitle(); ?></title>
    <meta name="description" content="<?php echo $blog->getDescription(); ?>">
    <meta name="keywords" content="<?php echo $blog->getKeywords(); ?>">
    <link rel="author" href="<?php echo $blog->getGAuthor(); ?>">
    <link rel="publisher" href="<?php echo $blog->getGPublisher(); ?>">
    <link rel="canonical" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>">
    <link rel="icon" href="<?php echo $blog->getFavicon(); ?>">
    <link rel="shortcut icon" href="<?php echo $blog->getFavicon(); ?>">
    <link href="<?php echo $SITE; ?>/shared/theme/header.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $SITE; ?>/shared/theme/sidebar.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $SITE; ?>/shared/theme/plural.css" rel="stylesheet" type="text/css">
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
    <!--Kind-->
    <?php if(!empty($kind)) { ?><div class="kind"><?php echo $kind; ?></div><?php } ?>
    <!--Main-->
    <div class="blog-main">
    <?php include dirname(__FILE__) . "/sidebar.php"; ?>
        <!--postbar-->
        <div class="post-bar">
            <?php for($i=0; !empty($posts[$i]); $i++) { ?>
            <div class="post-item" id="post_<?php echo $posts[$i]["id"]; ?>">
                <div class="post-header">
                    <a href="<?php echo $posts[$i]["author"]; ?>" title="<?php echo $posts[$i]["writer"]; ?>">
                        <img class="post-header-avatar" src="<?php echo $posts[$i]["avatar"]; ?>"
                             alt="<?php echo $posts[$i]["writer"]; ?>">
                    </a>
                    <div class="post-header-title">
                        <h2><a href="<?php echo $posts[$i]["link"]; ?>"><?php echo $posts[$i]["title"]; ?></a></h2>
                    </div>
                    <div class="post-header-info">
                        <p>نویسنده: <a href="<?php echo $posts[$i]["author"]; ?>">
                                <?php echo $posts[$i]["writer"]; ?></a> - زمان انتشار:
                            <?php echo $posts[$i]["time"]; ?> @
                        <a href="/archive/<?php echo $posts[$i]["archive"]; ?>"><?php echo $posts[$i]["date"]; ?>
                        </a></p>
                    </div>
                </div>
                <div class="post-content"><?php echo $posts[$i]["content"]; ?></div>
                <div class="post-link">
                    <a href="<?php echo $posts[$i]["link"]; ?>" title="<?php echo $posts[$i]["title"]; ?>">
                        <span>مشاهده پست</span>
                    </a>
                </div>
                <div class="post-cats">
                    <?php $cat = empty($posts[$i]["cat"]) ? "دسته‌بندی‌نشده" : $posts[$i]["cat"] ?>
                    <a href="/cat/<?php echo $cat; ?>"><?php echo $cat; ?></a>
                    <img src="/shared/image/cat.png">
                </div>
                <div class="post-footer">
                    <div class="post-footer-options">
                        <?php $like = (empty($posts[$i]["like"])) ? "پسندیدن" : "نمی‌پسندم"; ?>
                        <span onclick="like(<?php echo $posts[$i]["id"]; ?>)"
                            id="like_<?php echo $posts[$i]["id"]; ?>"><?php echo $like; ?></span> .
                        <span onclick="goComments('<?php echo $posts[$i]["link"]; ?>')">ديدگاه</span> .
                        <span onclick="goShare('<?php echo $posts[$i]["link"]; ?>')">به‌اشتراک‌گذاشتن</span>
                    </div>
                    <div class="post-footer-statistics">
                        <div class="post-footer-statistics-likes" id="like_num_<?php echo $posts[$i]["id"]; ?>">
                            <?php echo $posts[$i]["likes"]; ?></div>
                        <div class="post-footer-statistics-likes-image"></div>
                        <div class="post-footer-statistics-comments"><?php echo $posts[$i]["comments"]; ?></div>
                        <div class="post-footer-statistics-comments-image"></div>
                    </div>
                    <div style="clear: both"></div>
                </div>
            </div>
            <?php } ?>
            <?php if(empty($posts)) { ?>
            <div class="no-post">هیچ پستی برای نمایش وجود ندارد!</div>
            <?php } ?>
            <!--nav-->
            <?php if($page_num > 1) { ?>
            <div class="nav">
                <?php for($i=1; $i<=$page_num; $i++) { ?>
                <?php   if($page_nav == $i) { ?>
                <span class="nav-selected"><a href="<?php echo $link_nav.$i; ?>"><?php echo $i; ?></a></span>
                <?php   } else { ?>
                <span class="nav-item"><a href="<?php echo $link_nav.$i; ?>"><?php echo $i; ?></a></span>
                <?php   } ?>
                <?php } ?>
            </div>
            <?php } ?>
            <!--/nav-->
        </div>
        <!--/postbar-->
    </div>
    <?php include dirname(__FILE__) . "/footer.php"; ?>
    <!--Script-->
    <script src="<?php echo $SITE; ?>/shared/library/json3.min.js" type="text/javascript"></script>
    <script src="<?php echo $SITE; ?>/shared/library/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="<?php echo $SITE; ?>/shared/theme/post.js" type="text/javascript"></script>
</body>

</html>