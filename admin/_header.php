<?php

$member = new Member();
$member->identify();
$avatar = $member->getAvatar();
$name = $member->getNickname();

?>
<!--header-->
<div class="header">
    <a href="/"><img src="/shared/image/logo60.png" class="header-logo"></a>
    <div class="header-member">
        <a href="account.php?profile"><img class="header-member-avatar" src="<?php echo $avatar; ?>"></a>
        <div class="header-member-info">
            <span class="header-member-name"><?php echo $name; ?></span><br>
            <span class="header-member-link"><a href="/admin/account.php">وبلاگ‌ها</a> .</span>
            <span class="header-member-link"><a href="/admin/account.php?profile">پروفایل</a> .</span>
            <span class="header-member-link"><a href="/admin/account.php?signout">بیرون‌رفتن</a></span>
        </div>
    </div>
</div>