<?php $email = empty($_COOKIE["email"]) ? "email@example.com" : trim($_COOKIE["email"]); ?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>نت نویس | مدیریت اکانت</title>
    <link rel="shortcut icon" href="/shared/image/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/shared/image/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/shared/image/favicon.ico" type="image/x-icon">
    <link href="view/account.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="loading" id="loading"></div>
    <!--main-->
    <div class="main">
        <!--general-->
        <a href="/"><div class="logo"></div></a>
        <div id="message" class="message"></div>
        <!--signup-->
        <div id="signup_section" class="section">
            <div class="label"><b>» نام نویسی</b></div>
            <div class="label">با یکبار نام‌نویسی، هرتعداد وبلاگ که‌می‌خواهید بسازید.</div>
            <label for="signup_nickname">نام نمایشی:</label>
            <div><input type="text" id="signup_nickname" class="field1" style="direction:rtl"
                        maxlength="40" value="نام نمایشی"></div>
            <label for="signup_password1">گذرواژه:</label>
            <div><input type="password" id="signup_password1" class="field1" maxlength="20" value="password"></div>
            <label for="signup_password2"></label>
            <div><input type="password" id="signup_password2" class="field1" maxlength="20" value="password"></div>
            <label for="signup_email">ایمیل:</label>
            <div><input type="email" id="signup_email" class="field1" maxlength="40"  value="email@example.com"></div>
            <label for="signup_captcha"></label>
            <div><input type="text" id="signup_captcha" class="field1 captcha" value="کد امنیتی"></div>
            <div><br><input type="button" id="signup_button" class="button1" value="نام نویسی"></div>
            <div class="link"><br><span id="signup_signin">بخش ورود کاربران</span></div>
        </div>
        <!--signup-success-->
        <div id="signupsuccess_section" class="section">
            <div class="label"><b>» فعال‌سازی ایمیل</b></div>
            <div class="label">
                <span>نام نویسی شما به درستی پایان یافت. </span>
                <span>اکنون باید ایمیل خود را فعال‌سازی کنید تا هویت شما تایید شود. </span>
                <span>لینک و کد فعال‌سازی برای‌تان ایمیل شده است، </span>
                <span>لینک فرستاده شده را دنبال کنید و کد فعال‌سازی را وارد کنید. </span>
            </div>
        </div>
        <!--confirm-->
        <div id="confirm_section" class="section">
            <div class="label"><b>» فعال سازی ایمیل</b></div>
            <div class="label">
                <span></span>
            </div>
            <label for="confirm_code">کد فعال‌سازی:</label>
            <div><input type="text" id="confirm_code" class="field1" value=""></div>
            <div><label><input type="text" id="confirm_captcha" class="field1 captcha" value="کد امنیتی"></label></div>
            <div>
                <br>
                <input type="button" id="confirm_button" class="button1" value="فعال‌سازی">
            </div>
            <div class="link"><br><span id="confirm_signin">بخش ورود کاربران</span></div>
        </div>
        <!--singin-->
        <div id="signin_section" class="section">
            <div class="label"><b>» ورود کاربران</b></div>
            <label for="signin_email"></label>
            <div><input type="email" id="signin_email" class="field1" value="<?php echo $email ?>"></div>
            <label for="signin_password"></label>
            <div><input type="password" id="signin_password" class="field1" value="password"></div>
            <label for="signin_captcha"></label>
            <div>
                <input type="text" id="signin_captcha" class="field1 captcha" style="display:none" value="کد امنیتی">
            </div>
            <br>
            <div>
                <input type="button" id="signin_button" class="button1" value="ورود">
                <input type="button" id="signin_signup" class="button2" value="نام نویسی">
            </div>
            <div class="link"><br><span id="signin_lost">گذرواژه را فراموش کرده‌ام!</span></div>
        </div>
        <!--lost-->
        <div id="lost_section" class="section">
            <div class="label"><b>» دریافت کد بازیابی</b></div>
            <div class="label">
                <span>اگر گذرواژه خود را فراموش کرده‌اید، می‌توانید اکانت خود را بازیابی کنید. </span>
                <span>کد بازیابی برای‌تان ایمیل می‌شود.</span>
            </div>
            <label for="lost_email">ایمیل:</label>
            <div><input type="email" id="lost_email" class="field1" value="email@example.com"></div>
            <div><label><input type="text" id="lost_captcha" class="field1 captcha" value="کد امنیتی"></label></div>
            <div>
                <br>
                <input type="button" id="lost_button" class="button1" value="فرستادن">
                <input type="button" id="lost_signin" class="button2" value="ورود">
            </div>
            <div class="link"><br><span id="lost_recovery">کد بازیابی را پیش از این دریافت کرده‌ام~</span></div>
        </div>
        <!--recovery-->
        <div id="recovery_section" class="section">
            <div class="label"><b>» بازیابی اکانت</b></div>
            <div class="label">پس از نوشتن کد بازیابی، گذرواژه‌ای نو برگزینید:</div>
            <div><label><input type="text" id="recovery_code" class="field1" value="کد بازیابی"></label></div>
            <div><label><input type="password" id="recovery_password1" class="field1" value="password"></label></div>
            <div><label><input type="password" id="recovery_password2" class="field1" value="password"></label></div>
            <div><label><input type="text" id="recovery_captcha" class="field1 captcha" value="کد امنیتی"></label></div>
            <div>
                <br>
                <input type="button" id="recovery_button" class="button1" value="بازیابی">
                <input type="button" id="recovery_lost" class="button2" value="درخواست">
            </div>
            <div class="link"><br><span id="recovery_signin">بخش ورود کاربران</span></div>
        </div>
        <!--manage-->
        <div id="manage_section" class="section" style="width: 360px">
            <div>
                <br>
                <input type="button" id="manage_profile" class="button2" value="پروفایل">
                <input type="button" id="manage_signout" class="button2" value="بیرون‌رفتن">
                <br><br>
                <div class="label">لیست وبلاگ ها:</div>
                <div class="weblog-list" id="weblog_list"></div>
                <br>
                <input type="button" id="weblog_create" class="button1" style="width: 120px" value="ساخت وبلاگ نو">
                <input type="button" id="manage_apply" class="button2" style="width: 120px" value="درخواست عضویت">
            </div>
        </div>
        <!--profile-->
        <div id="profile_section" class="section">
            <div class="label"><b>» ویرایش پروفایل کاربری</b></div>
            <label for="profile_nickname">نام نمایشی:</label>
            <div><input type="text" id="profile_nickname" class="field1" style="direction:rtl" maxlength="40" value=""></div>
            <label for="profile_email">ایمیل:</label>
            <div><input type="email" id="profile_email" class="field1" maxlength="40" value=""></div>
            <div class="profile-changemail" id="profile_email_chnage">
                <span>ویرایش واقعی ایمیل پس‌از فعال‌سازی آن بوسیله </span>
                <span>ایمیلی که برای‌تان فرستاده‌می‌شود انجام خواهدشد.</span>
            </div>
            <label for="profile_googleplus">آدرس پروفایل گوگل+:</label>
            <div><input type="url" id="profile_googleplus" class="field1" maxlength="250"  value=""></div>
            <label for="profile_avatar">تصویر کاربری (» <a href="http://picofile.com"
                                                           target="_blank">آپلود تصویر در پیکوفایل</a>)</label>
            <div><input type="url" id="profile_avatar" class="field1" maxlength="250"  value=""></div>
            <label for="profile_about">درباره من:</label>
            <div><textarea class="field1" style="direction:rtl;height:100px" id="profile_about" maxlength="250"></textarea></div>
            <div class="link" id="profile_showpassword"><span>» تغییر گذرواژه</span></div>
            <div id="profile_passwords" style="display: none">
                <div class="label">» تغییر گذرواژه</div>
                <label for="profile_password1">گذرواژه پیشین:</label>
                <div><input type="password" id="profile_password1" class="field1" maxlength="20" value="password"></div>
                <label for="profile_password2">گذرواژه نو ( و بازنویسی آن):</label>
                <div><input type="password" id="profile_password2" class="field1" maxlength="20" value="password"></div>
                <label for="profile_password3"></label>
                <div><input type="password" id="profile_password3" class="field1" maxlength="20" value="password"></div>
            </div>
            <div><label><input type="text" id="profile_captcha" class="field1 captcha" value="کد امنیتی"></label></div>
            <div><br>
                <input type="button" id="profile_button" class="button1" value="بایگانی">
                <input type="button" id="profile_manage" class="button2" value="انصراف">
            </div>
        </div>
        <!--create-->
        <div id="create_section" class="section">
            <div class="label"><b>» ساخت وبلاگ نو</b></div>
            <label for="create_subdomain"></label>
            <div><input type="text" id="create_subdomain" class="field1" maxlength="20" value="زیردامنه"></div>
            <div class="blog-url">زیردامنه.netnevis.ir</div>
            <label for="create_title">عنوان وبلاگ:</label>
            <div><input type="text" id="create_title" class="field1" maxlength="40" value=""></div>
            <div><label><input type="text" id="create_captcha" class="field1 captcha" value="کد امنیتی"></label></div>
            <div>
                <br><input type="button" id="create_button" class="button1" value="ساختن">
                <input type="button" id="create_manage" class="button2" value="انصراف">
            </div>
        </div>
        <!--apply-->
        <div id="apply_section" class="section">
            <div class="label"><b>» درخواست عضویت</b></div>
            <div class="label">
                <span>در خواست عضویت برای مدیر وبلاگ مورد نظر فرستاده می‌شود </span>
                <span>و در صورت تایید، پس از آن می‌توانید در آن وبلاگ مشارکت کنید...</span>
            </div>
            <div><label for="apply_blog">وبلاگ: (مثلا example برای example.netnevis.ir)</label>
            <label><input type="text" id="apply_subdomain" class="field1" value="وبلاگ"></label></div>
            <div><label><input type="text" id="apply_captcha" class="field1 captcha" value="کد امنیتی"></label></div>
            <div>
                <br><input type="button" id="apply_button" class="button1" value="درخواست">
                <input type="button" id="apply_manage" class="button2" value="انصراف">
            </div>
        </div>
        <!--Script-->
        <script type="text/javascript" src="/shared/library/json3.min.js"></script>
        <script type="text/javascript" src="/shared/library/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="view/account.js"></script>
        <!--end-->
    </div>
    <!--/main-->
</body>

</html>