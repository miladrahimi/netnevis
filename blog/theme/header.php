<div class="header">
    <div class="header-cover" style="background-image: url(<?php echo $blog->getCover() ?>)">
        <div class="header-logo" style="background-image: url(<?php echo $blog->getLogo() ?>)"></div>
        <?php if(empty($toEm)) { ?>
        <div class="header-title"><h1><?php echo $blog->getTitle() ?></h1></div>
        <?php } else { ?>
        <div class="header-title"><em><?php echo $blog->getTitle() ?></em></div>
        <?php } ?>
        <div class="header-desc"><b><?php echo $blog->getDescription() ?></b></div>
    </div>
</div>
<div class="menu">
    <div class="menu-main">
        <div class="menu-item<?php echo $menu_item=="home"? " menu-selected" : "" ?>">
            <a href="/">خانه</a></div>
        <div class="menu-item<?php echo $menu_item=="archive"? " menu-selected" : "" ?>">
            <a href="/archive">آرشیو</a></div>
        <div class="menu-item">
            <a href="/rss">خبرخوان</a></div>
        <div class="menu-item<?php echo $menu_item=="about"? " menu-selected" : "" ?>">
            <a href="/about">درباره</a></div>
        <div class="menu-item<?php echo $menu_item=="contact"? " menu-selected" : "" ?>">
            <a href="/contact">ارتباط</a></div>
    </div>
</div>