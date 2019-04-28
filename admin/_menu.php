<?php
$script =  basename($_SERVER["SCRIPT_NAME"]);
$posts = $post = $comments = $links = $authors = $appearance = $seo = $setting = "";
switch($script) {
    case "posts.php": $posts = " menu-selected"; break;
    case "post.php": $post = " menu-selected"; break;
    case "comments.php": $comments = " menu-selected"; break;
    case "links.php": $links = " menu-selected"; break;
    case "authors.php": $authors = " menu-selected"; break;
    case "appearance.php": $appearance = " menu-selected"; break;
    case "seo.php": $seo = " menu-selected"; break;
    case "setting.php": $setting = " menu-selected"; break;
}
?>
<!--menu-->
<div class="menu">
    <div class="menu-publish-bar">
        <input type="button" class="button1 button-x" onclick="window.location = '/admin/post.php'" value="انتشار پست نو">
    </div>
    <div class="menu-options">
        <a href="posts.php"><div class="menu-item<?php echo $posts; ?>">» پست‌ها</div></a>
        <a href="post.php"><div class="menu-item<?php echo $post; ?>">» پست‌نو</div></a>
        <a href="comments.php"><div class="menu-item<?php echo $comments; ?>">» دیدگاه‌ها</div> </a>
        <a href="links.php"><div class="menu-item<?php echo $links; ?>">» پیوند‌ها</div></a>
        <a href="authors.php"><div class="menu-item<?php echo $authors; ?>">» نویسندگان</div></a>
        <a href="appearance.php"><div class="menu-item<?php echo $appearance; ?>">» نمایش</div></a>
        <a href="seo.php"><div class="menu-item<?php echo $seo; ?>">» بهینه‌سازی</div></a>
        <a href="setting.php"><div class="menu-item<?php echo $setting; ?>">» تنظیمات</div></a>
    </div>
</div>