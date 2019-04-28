<!doctype html>

<html>

<head lang="fa">
    <meta charset="UTF-8">
    <title><?php echo $blog->getTitle(); ?> | <?php echo $thepost["title"]; ?></title>
    <meta name="description" content="<?php echo $thepost["description"]; ?>">
    <meta name="keywords" content="<?php echo $thepost["tags"]; ?>">
    <link rel="author" href="<?php echo $member->getGooglePlus(); ?>">
    <link rel="publisher" href="<?php echo $blog->getGPublisher(); ?>">
    <link rel="canonical" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>">
    <link rel="icon" href="<?php echo $blog->getFavicon(); ?>">
    <link rel="shortcut icon" href="<?php echo $blog->getFavicon(); ?>">
    <link href="<?php echo $SITE; ?>/shared/theme/header.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $SITE; ?>/shared/theme/sidebar.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $SITE; ?>/shared/theme/single.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $SITE; ?>/shared/theme/footer.css" rel="stylesheet" type="text/css">
    <!--GooglePlus-->
    <meta itemprop="name" content="<?php echo $thepost["title"]; ?>">
    <meta itemprop="description" content="<?php echo $thepost["description"]; ?>">
    <meta itemprop="image" content="<?php echo $blog->getLogo(); ?>">
    <!--Facebook-->
    <meta property="og:url" content="http://<?php echo $_SERVER['HTTP_HOST']; ?>">
    <meta property="og:image" content="<?php echo $blog->getLogo(); ?>">
    <meta property="og:description" content="<?php echo $thepost["description"]; ?>">
    <meta property="og:title" content="<?php echo $thepost["title"]; ?>">
    <meta property="og:site_name" content="<?php echo $blog->getTitle(); ?>">
    <meta property="og:see_also" content="http://netnevis.ir">
    <!--Twitter-->
    <meta name="twitter:card" content="<?php echo $thepost["description"]; ?>">
    <meta name="twitter:url" content="http://<?php echo $_SERVER['HTTP_HOST']; ?>">
    <meta name="twitter:title" content="<?php echo $thepost["title"]; ?>">
    <meta name="twitter:description" content="<?php echo $thepost["description"]; ?>">
    <meta name="twitter:image" content="<?php echo $blog->getLogo(); ?>">
    <!--MetaTags-->
    <?php echo $blog->getMetatags(); ?>
    <!--End-->
</head>

<body>
<div class="loading" id="loading"></div>
<?php $toEm = 1; ?>
<?php include dirname(__FILE__) . "/header.php"; ?>
<div class="blog-main">
<?php include dirname(__FILE__) . "/sidebar.php"; ?>
    <!--postbar-->
    <div class="post-bar">
        <div class="post-item" id="post_<?php echo $thepost["id"]; ?>">
            <div class="post-header">
                <div class="post-header-avatar" style="background-image:url(<?php echo $thepost["avatar"]; ?>)"></div>
                <div class="post-header-title">
                    <h1><a href="<?php echo $thepost["link"]; ?>"><?php echo $thepost["title"]; ?></a></h1>
                </div>
                <div class="post-header-info">
                    <p>نویسنده: <a href="<?php echo $thepost["author"]; ?>">
                            <?php echo $thepost["writer"]; ?></a> - زمان انتشار:
                        <?php echo $thepost["time"]; ?> @
                        <a href="/archive/<?php echo $thepost["archive"]; ?>"><?php echo $thepost["date"]; ?>
                        </a></p>
                </div>
            </div>
            <div class="post-content"><?php echo $thepost["content"]; ?></div>
            <div class="post-cats">
                <?php $cat = empty($thepost["cat"]) ? "دسته‌بندی‌نشده" : $thepost["cat"] ?>
                <a href="/cat/<?php echo $cat; ?>"><?php echo $cat; ?></a>
                <img src="/shared/image/cat.png">
            </div>
            <div class="post-footer">
                <?php $like = (empty($thepost["like"])) ? "پسندیدن" : "نمی‌پسندم"; ?>
                <div class="post-footer-options">
                    <span onclick="like(<?php echo $thepost["id"]; ?>)"
                          id="like_<?php echo $thepost["id"]; ?>"><?php echo $like; ?></span> .
                    <span
                        onclick="share('<?php echo $SITE; ?>','<?php echo $thepost["title"]; ?>')">به‌اشتراک‌گذاشتن</span>
                </div>
                <div class="post-statistics">
                    <div class="post-footer-statistics-likes" id="like_num_<?php echo $thepost["id"]; ?>">
                        <?php echo $thepost["likes"]; ?></div>
                    <div class="post-footer-statistics-likes-image"></div>
                    <div class="post-footer-statistics-comments" id="comment_num">
                        <?php echo $thepost["comments"]; ?></div>
                    <div class="post-footer-statistics-comments-image"></div>
                </div>
            </div>
            <div class="comment-main">
                <?php if($thepost["comment"] == 0 || $thepost["comment"] == 1) { ?>
                <div class="comment-new" id="comment_new">
                    <label for="comment_message"></label>
                    <textarea class="comment-new-message" id="comment_message"
                              maxlength="1900">دیدگاه خود را بنویسید...</textarea><br>
                    <label for="comment_author"></label>
                    <input type="text" id="comment_author" class="comment-new-author" maxlength="35" value="نام" ><br>
                    <label for="comment_email"></label>
                    <input type="text" id="comment_email" class="comment-new-email" maxlength="40" value="ایمیل"><br>
                    <label for="comment_captcha"></label>
                    <input type="text" id="comment_captcha" class="comment-new-captcha" maxlength="3" value="کد">
                    <label for="comment_send"></label>
                    <input type="text" id="comment_send" onClick="comment(<?php echo $thepost["id"] ?>)"
                           class="comment-new-send" maxlength="0" value="فرستادن">
                    <label><input type="text" class="comment-new-response" id="comment_response" value="xxx"></label>
                </div>
                <?php } ?>
                <?php for($i=0; !empty($comments[$i]); $i++) { ?>
                <div class="comment-item">
                    <div class="comment-avatar"></div>
                    <div class="comment-info">
                        <span class="comment-author"><?php echo $comments[$i]["author"]; ?></span>
                        <span class="comment-time">
                        <?php echo $comments[$i]["rtime"]; ?> @ <?php echo $comments[$i]["rdate"]; ?>
                        </span>
                    </div>
                    <div class="comment-message"><p><?php echo $comments[$i]["message"]; ?></p></div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <!--/postbar-->
    <?php include dirname(__FILE__) . "/footer.php"; ?>
    <!--Script-->
    <script src="<?php echo $SITE; ?>/shared/library/json3.min.js" type="text/javascript"></script>
    <script src="<?php echo $SITE; ?>/shared/library/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="<?php echo $SITE; ?>/shared/theme/post.js" type="text/javascript"></script>
</div>
</body>

</html>