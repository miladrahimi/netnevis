<div class="side-bar">
    <!--categories-->
    <?php if(!empty($sidebar_cats)) { ?>
    <div class="side-item">
        <div class="side-title"><h2>دسته‌بندی</h2></div>
        <div class="side-links">
            <?php for($i=0; !empty($sidebar_cats[$i]); $i++) { ?>
            <a href="/cat/<?php echo $sidebar_cats[$i]["title"]; ?>">
                <span dir="rtl">
                    <?php echo empty($sidebar_cats[$i]["title"]) ? "دسته‌بندی‌نشده" : $sidebar_cats[$i]["title"]; ?>
                </span>
                <span dir="ltr">(<?php echo $sidebar_cats[$i]["number"]; ?>)</span>
            </a>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
    <!--auhtors-->
    <div class="side-item">
        <div class="side-title"><h2>نويسندگان</h2></div>
        <div class="side-complex side-authors">
            <?php for($i=0; !empty($sidebar_authors[$i]); $i++) { ?>
                <?php
                switch($sidebar_authors[$i]['role']) {
                    case "admin":
                        $sidebar_authors[$i]['role'] = "مدیر";
                        break;
                    case "assistant":
                        $sidebar_authors[$i]['role'] = "معاون";
                        break;
                    case "editor":
                        $sidebar_authors[$i]['role'] = "ویراستار";
                        break;
                    case "writer":
                        $sidebar_authors[$i]['role'] = "نویسنده";
                        break;
                    default:
                        $sidebar_authors[$i]['role'] = "نویسنده";
                }
                ?>
            <a href="<?php echo $sidebar_authors[$i]['link'] ?>" title="<?php echo $sidebar_authors[$i]['name'] ?>">
            <div class="side-auth-item">
                <div class="side-auth-avatar"
                     style="background-image: url('<?php echo $sidebar_authors[$i]['avatar'] ?>')"></div>
                <div class="side-auth-info">
                    <div class="side-auth-name"><?php echo $sidebar_authors[$i]['name'] ?></div>
                    <div class="side-auth-more">
                        <?php echo $sidebar_authors[$i]['role'] ?> ::
                        <?php echo $sidebar_authors[$i]['posts'] ?> پست
                    </div>
                </div>
            </div>
            </a>
            <?php } ?>
            <div style="clear:both"></div>
        </div>
    </div>
    <!--recents-->
    <?php if(!empty($sidebar_recents)) { ?>
        <div class="side-item">
            <div class="side-title"><h2>تازه‌ها</h2></div>
            <div class="side-links">
                <?php for($i=0; !empty($sidebar_recents[$i]); $i++) { ?>
                <a href="<?php echo $sidebar_recents[$i]["link"]; ?>"><?php echo $sidebar_recents[$i]["title"]; ?></a>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
    <!--Favorites-->
    <?php if(!empty($sidebar_favorites)) { ?>
        <div class="side-item">
            <div class="side-title"><h2>محبوب ترین‌ها</h2></div>
            <div class="side-links">
                <?php for($i=0; !empty($sidebar_favorites[$i]); $i++) { ?>
                    <a href="<?php echo $sidebar_favorites[$i]["link"]; ?>">
                        <?php echo $sidebar_favorites[$i]["title"]; ?>
                    </a>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
    <!--statistics-->
        <div class="side-item">
            <div class="side-title"><h2>آمار وبلاگ</h2></div>
            <div class="side-complex">
                <div><span>بازدید امروز: </span><span><?php echo $sidebar_statistics_today_pages; ?></span></div>
                <div><span>بازدید دیروز: </span><span><?php echo $sidebar_statistics_yesterday_pages; ?></span></div>
                <div><span>بازدید این ماه: </span><span><?php echo $sidebar_statistics_thismonth_pages; ?></span></div>
                <div><span>بازدید ماه پیش: </span><span><?php echo $sidebar_statistics_lastmonth_pages; ?></span></div>
                <div><span>همه بازدیدها: </span><span><?php echo $sidebar_statistics_blog_visits; ?></span></div>
                <div><span>تاریخ راه‌اندازی: </span><span><?php echo $sidebar_statistics_blog_born; ?></span></div>
                <div><span>آخرین بروزرسانی: </span><span><?php echo $sidebar_statistics_blog_update; ?></span></div>
                <div><span>تعداد نویسنده‌ها: </span><span><?php echo $sidebar_statistics_blog_authors; ?></span></div>
                <div><span>تعداد پست‌ها: </span><span><?php echo $sidebar_statistics_blog_posts; ?></span></div>
            </div>
        </div>
    <!--links-->
    <?php if(!empty($sidebar_links)) { ?>
    <div class="side-item">
        <div class="side-title"><h2>پيوندها</h2></div>
        <div class="side-links">
            <?php for($i=0; !empty($sidebar_links[$i]); $i++) { ?>
            <a href="<?php echo $sidebar_links[$i]["url"]; ?>" rel="nofollow">
                <?php echo $sidebar_links[$i]["title"]; ?>
            </a>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
</div>