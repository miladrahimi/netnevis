//****************************************************************************
// Filter Input
function filter(str) {
    if (typeof (str) == 'string') {
        str = str.replace(/&/g, '&amp;');
        str = str.replace(/"/g, '&quot;');
        str = str.replace(/'/g, '&#039;');
        str = str.replace(/</g, '&lt;');
        str = str.replace(/>/g, '&gt;');
        str = str.replace(/\//g, '%2F');
        str = str.replace(/\\/g, '%5C');
        str = str.replace(/(\b|\f|\t|\v)/gm, '');
        str = str.replace(/(\r\n|\n|\r)/gm, '<br>');
    }
    return encodeURIComponent(str);
}
//****************************************************************************
// Filterback Output
function filterback(str) {
    if (typeof (str) == 'string') {
        str = str.replace(/&amp;/g, '&');
        str = str.replace(/&quot;/g, '"');
        str = str.replace(/&#039;/g, '\'');
        str = str.replace(/&lt;/g, '<');
        str = str.replace(/&gt;/g, '>');
        str = str.replace(/%2F/g, '/');
        str = str.replace(/%5C/g, '\\');
        str = str.replace(/<br>/gm, '\n');
    }
    return decodeURIComponent(str);
}
//****************************************************************************
// Loading
var $loading_num = 1;
function loading_show() {
    $('#message').hide();
    $loading_num++;
    if ($loading_num > 0) {
        $('#loading').fadeIn('slow');
    }
}
function loading_hide() {
    $loading_num--;
    if ($loading_num < 0) $loading_num = 0;
    if ($loading_num == 0) {
        $('#loading').fadeOut('slow');
    }
}
//****************************************************************************
// Recaptcha
function recaptcha() {
    var $url = '/shared/image/captcha.php?r=' + Math.random();
    $('.captcha').css('background-image', 'url(\'' + $url + '\')');
    $('.captcha').val('کد امنیتی');
}
//****************************************************************************
// Sign Out
function signOut() {
    var $info = JSON.stringify({
        command: 'signOut'
    });
    $.get('/admin/app/account.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
        } else {
            $('#message').slideUp('slow');
        }
    });
}
//****************************************************************************
// Get Weblogs
function getWeblogs() {
    loading_show();
    $('#weblog_list').html('');
    var $info = JSON.stringify({
        command: 'getWeblogs'
    });
    $.get('/admin/app/account.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
            $('#message').slideDown('slow');
        } else if ($data == 'error') {
            $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...').slideDown('slow');
        } else if ($data == '[]') {
            $('#weblog_list').addClass('weblog-list-empty');
            $('#weblog_list').html('شما هیچ وبلاگی ندارید!<br>می توانید یکی برای خود بسازید یا عضو وبلاگی شوید.');
        } else {
            $('#weblog_list').removeClass('weblog-list-empty');
            var $weblog = JSON.parse($data);
            $.each($weblog, function (key, value) {
                var $logo = 'url(\'' + value['logo'] + '\')';
                var $url = value['subdomain'] + '.netnevis.ir';
                var $role = '';
                switch (value['role']) {
                    case 'admin':
                        $role = 'مدیر';
                        break;
                    case 'assistant':
                        $role = 'معاون';
                        break;
                    case 'editor':
                        $role = 'ویراستار';
                        break;
                    case 'writer':
                        $role = 'نویسنده';
                        break;
                    default:
                        $role = 'نویسنده';
                }
                $('#weblog_list').append('<div class="weblog-item" onclick="manageWeblog(' + key + ')">' +
                        '<div class="weblog_logo" style="background-image: ' + $logo + '"></div>' +
                        '<div class="weblog_title">' + value['title'] + ' <span>(' + $role + ')</span></div>' +
                        '<div class="weblog_url">' + $url + '</div>' +
                        '</div>'
                );
            });
        }
        loading_hide();
    });
}
//****************************************************************************
// Get Profile
function getProfile() {
    loading_show();
    $('#profile_nickname').val('');
    $('#profile_email').val('');
    $('#profile_avatar').val('');
    $('#profile_about').val('');
    $('#profile_googleplus').val('');
    var $info = JSON.stringify({
        command: 'getProfile'
    });
    $.get('/admin/app/account.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
            $('#message').slideDown('slow');
            window.location = '#message';
        } else if ($data == 'error') {
            $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
            $('#message').slideDown('slow');
            window.location = '#message';
        } else {
            var $profile = JSON.parse($data);
            $('#profile_nickname').val(filterback($profile['nickname']));
            $('#profile_email').val(filterback($profile['email']));
            $('#profile_avatar').val(filterback($profile['avatar']));
            $('#profile_about').val(filterback($profile['about']));
            $('#profile_googleplus').val(filterback($profile['googleplus']));
        }
        loading_hide();
    });
}
//****************************************************************************
// Manage Weblog
function manageWeblog($id) {
    loading_show();
    var $info = JSON.stringify({
        command: 'manageWeblog',
        id: filter($id)
    });
    $.get('/admin/app/account.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
        } else {
            switch ($data) {
                case 'job_connect_error':
                    $('#message').html('خطایی در دسترسی به وبلاگ رخ داده‌است. لطفا دوباره صفحه را بارگذاری‌کنید.');
                    break;
                case 'error':
                    $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
                    break;
                case 'done':
                    $('#message').html('لطفا شکیبا باشید...');
                    window.location = '#message';
                    window.location = '/admin';
                    break;
                default:
                    $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
                    alert($data);
            }
        }
        loading_hide();
        $('#message').slideDown('slow');
        window.location = '#message';
    });
}
//****************************************************************************
// SingUp
$('#signup_button').click(function () {
    loading_show();
    var $info = {
        command: 'signUp',
        nickname: filter($('#signup_nickname').val()),
        password1: filter($('#signup_password1').val()),
        password2: filter($('#signup_password2').val()),
        email: filter($('#signup_email').val()),
        captcha: filter($('#signup_captcha').val())
    }
    $.post('/admin/app/account.php?r=',$info, function ($data, $status) {
        alert($data);
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
        } else {
            switch ($data) {
                case 'identity_error':
                    $('#message').html('شما پیش از این وارد شده‌اید! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'captcha_error':
                    $('#message').html('لطفا کد امنیتی را درست بنویسید.');
                    break;
                case 'member_nickname_length':
                    $('#message').html('لطفا یک نام‌نمایشی بلندتر بنویسید.');
                    break;
                case 'member_email_wrong':
                    $('#message').html('لطفا ایمیل خود را درست بنویسید.');
                    break;
                case 'password_different':
                    $('#message').html('لطفا گذرواژه و بازنویسی آنرا یکسان بنویسید.');
                    break;
                case 'member_password_length':
                    $('#message').html('لطفا گذرواژه‌ای بین هشت تا بیست نویسه بنویسید.');
                    break;
                case 'member_create_duplicate':
                    $('#message').html('این ایمیل پیش از این نام‌نویسی شده است!');
                    break;
                case 'error':
                    $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
                    break;
                case 'done':
                    $('#message').html('نام‌نویسی به درستی پایان یافت.');
                    $('#signup_section').hide();
                    $('#signupsuccess_section').show();
                    break;
                default:
                    $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
                    alert($data);
            }
        }
        recaptcha();
        loading_hide();
        $('#message').slideDown('slow');
    });
});
//****************************************************************************
// Confrim
$('#confirm_button').click(function () {
    loading_show();
    var $info = JSON.stringify({
        command: 'confirm',
        code: filter($('#confirm_code').val()),
        captcha: filter($('#confirm_captcha').val())
    });
    $.get('/admin/app/account.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
        } else {
            switch ($data) {
                case 'identity_error':
                    $('#message').html('شما پیش از این وارد شده‌اید! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'captcha_error':
                    $('#message').html('لطفا کد امنیتی را درست بنویسید.');
                    break;
                case 'memberconfirm_connect_error':
                    $('#message').html('کد فعال‌سازی نامعتبر است. ممکن است زمان آن منقضی شده باشد.');
                    break;
                case 'member_connect_error':
                    $('#message').html('اکانت مورد نظر حذف شده است.');
                    break;
                case 'member_email_wrong':
                    $('#message').html('مشکلی درخصوص ایمیل شما رخ داده است، لطفا با پشتیبانی تماس بگیرید.');
                    break;
                case 'member_edit_duplicate':
                    $('#message').html('ایمیل درخواست شده برای اکانت دیگری فعال شده است.');
                    break;
                case 'error':
                    $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
                    break;
                case 'member_edit_error':
                case 'done':
                    $('#message').html('ایمیل شما فعال شد! اکنون وارد شوید...');
                    $('#confirm_section').hide();
                    $('#signin_section').show();
                    break;
                default:
                    $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
                    alert($data);
            }
        }
        recaptcha();
        loading_hide();
        $('#message').slideDown('slow');
    });
});
//****************************************************************************
// SignIn
$('#signin_button').click(function () {
    loading_show();
    var $info = JSON.stringify({
        r: Math.random(),
        command: 'signIn',
        email: filter($('#signin_email').val()),
        password: filter($('#signin_password').val()),
        captcha: filter($('#signin_captcha').val())
    });
    $.get('/admin/app/account.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
        } else {
            $('#message').html($data);
            switch ($data) {
                case 'identity_error':
                    $('#message').html('شما پیش از این وارد شده‌اید! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'captcha_error':
                    $('#message').html('لطفا کد امنیتی را درست بنویسید.');
                    break;
                case 'member_email_wrong':
                    $('#message').html('لطفا ایمیل خود را درست بنویسید.');
                    break;
                case 'member_password_length':
                    $('#message').html('لطفا گذرواژه خود را درست بنویسید.');
                    break;
                case 'member_connect_error':
                    $('#message').html('ایمیل یا گذرواژه را نادرست نوشته‌اید.');
                    break;
                case 'activation_error':
                    $('#message').html('لینک و کد فعال‌سازی اکانت  مجددا برای‌تان ایمیل شد.');
                    break;
                case 'error':
                    $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
                    break;
                case 'done':
                    $('#message').html('با موفقیت به بخش مدیریت وارد شدید.');
                    $('#signin_section').hide();
                    $('#manage_section').show(0, function () {
                        getWeblogs();
                    });
                    break;
                default:
                    $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
                    alert($data);
            }
        }
        recaptcha();
        loading_hide();
        $("#signin_captcha").fadeIn("slow");
        $('#message').slideDown('slow');
    });
});
//****************************************************************************
// Lost
$('#lost_button').click(function () {
    loading_show();
    var $info = JSON.stringify({
        command: 'lost',
        email: filter($('#lost_email').val()),
        captcha: filter($('#lost_captcha').val())
    });
    $.get('/admin/app/account.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
        } else {
            switch ($data) {
                case 'identity_error':
                    $('#message').html('شما پیش از این وارد شده‌اید! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'captcha_error':
                    $('#message').html('لطفا کد امنیتی را درست بنویسید.');
                    break;
                case 'member_email_wrong':
                    $('#message').html('لطفا ایمیل خود را درست بنویسید.');
                    break;
                case 'member_connect_error':
                    $('#message').html('این ایمیل متعلق به هیچ کاربری نیست!');
                    break;
                case 'error':
                    $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
                    break;
                case 'done':
                    $('#message').html('کد بازیابی با موفقیت برای شما ایمیل شد.');
                    $('#lost_section').hide();
                    $('#recovery_section').show();
                    break;
                default:
                    $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
                    alert($data);
            }
        }
        recaptcha();
        loading_hide();
        $('#message').slideDown('slow');
    });
});
//****************************************************************************
// Recovery
$('#recovery_button').click(function () {
    loading_show();
    var $info = JSON.stringify({
        command: 'recovery',
        code: filter($('#recovery_code').val()),
        password1: filter($('#recovery_password1').val()),
        password2: filter($('#recovery_password2').val()),
        captcha: filter($('#recovery_captcha').val())
    });
    $.get('/admin/app/account.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
        } else {
            switch ($data) {
                case 'identity_error':
                    $('#message').html('شما پیش از این وارد شده‌اید! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'captcha_error':
                    $('#message').html('لطفا کد امنیتی را درست بنویسید.');
                    break;
                case 'memberlost_connect_error':
                    $('#message').html('کد بازیابی نامعتبر است. می‌توانید برای دریافت آن دوباره اقدام کنید.');
                    break;
                case 'member_connect_error':
                    $('#message').html('این کاربر دیگر در سایت وجود ندارد!');
                    break;
                case 'password_different':
                    $('#message').html('لطفا گذرواژه و بازنویسی آنرا یکسان بنویسید.');
                    break;
                case 'member_password_length':
                    $('#message').html('لطفا گذرواژه‌ای بین هشت تا بیست نویسه بنویسید.');
                    break;
                case 'member_edit_error':
                    $('#message').html('لطفا گذرواژه‌ای نو بجای گذرواژه کهنه خود بنویسید!');
                    break;
                case 'error':
                    $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
                    break;
                case 'done':
                    $('#message').html('اکانت با موفقیت بازیابی شد. اکنون می توانید وارد شوید...');
                    $('#recovery_section').hide();
                    $('#signin_section').show();
                    break;
                default:
                    $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
                    alert($data);
            }
        }
        recaptcha();
        loading_hide();
        $('#message').slideDown('slow');
    });
});
//****************************************************************************
// Profile Edit
$('#profile_button').click(function () {
    loading_show();
    var $info = JSON.stringify({
        command: 'editProfile',
        nickname: filter($('#profile_nickname').val()),
        password1: filter($('#profile_password1').val()),
        password2: filter($('#profile_password2').val()),
        password3: filter($('#profile_password3').val()),
        email: filter($('#profile_email').val()),
        googleplus: filter($('#profile_googleplus').val()),
        avatar: filter($('#profile_avatar').val()),
        about: filter($('#profile_about').val()),
        captcha: filter($('#profile_captcha').val())
    });
    $.get('/admin/app/account.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
        } else {
            switch ($data) {
                case 'identity_error':
                    $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'captcha_error':
                    $('#message').html('لطفا کد امنیتی را درست بنویسید.');
                    break;
                case 'member_connect_error':
                    $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'member_nickname_length':
                    $('#message').html('لطفا یک نام نمایشی بین دو تا چهل حرف بنویسید.');
                    break;
                case 'memberconfirm_email_wrong':
                    $('#message').html('لطفا ایمیل خود را درست بنویسید.');
                    break;
                case 'member_googleplus_wrong':
                    $('#message').html('لطفا آدرس پرفایل گوگل+ را درست بنویسید یا اینکه آنرا خالی بگذارید.');
                    break;
                case 'member_avatar_wrong':
                    $('#message').html('لطفا آدرس تصویر کاربری را درست بنویسید یا اینکه آنرا خالی بگذارید.');
                    break;
                case 'member_about_length':
                    $('#message').html('لطفا کمی بیشتر درباره خودتان بنویسید!');
                    break;
                case 'password_error':
                    $('#message').html('گذرواژه تغییر نکرد! گذرواژه پیشین نادرست بود.');
                    break;
                case 'password_different':
                    $('#message').html('لطفا گذرواژه نو و بازنویسی آنرا یکسان بنویسید.');
                    break;
                case 'member_password_length':
                    $('#message').html('لطفا گذرواژه‌ای با هشت تا بست نویسه بنویسید.');
                    break;
                case 'member_edit_duplicate':
                case 'email_duplicate':
                    $('#message').html('این ایمیل پیش از این نام‌نویسی شده است، یکی دیگر را بنویسید.');
                    break;
                case 'member_edit_error':
                    $('#message').html('چیز خاصی برای ویرایش وجود نداشت!');
                    break;
                case 'error':
                    $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
                    break;
                case 'done':
                    $('#message').html('پروفایل به درستی ویرایش شد.');
                    $('#profile_section').hide();
                    $('#manage_section').show(0, function () {
                        getWeblogs();
                    });
                    break;
                default:
                    $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
                    alert($data);
            }
        }
        recaptcha();
        loading_hide();
        $('#message').slideDown('slow');
        window.location = '#message';
    });
});
//****************************************************************************
// Profile Show Password
$('#profile_showpassword').click(function () {
    $('#profile_passwords').show();
    $(this).hide();
});
//****************************************************************************
// Create Weblog
$('#create_button').click(function () {
    loading_show();
    var $info = JSON.stringify({
        command: 'createWeblog',
        subdomain: filter($('#create_subdomain').val()),
        title: filter($('#create_title').val()),
        captcha: filter($('#create_captcha').val())
    });
    $.get('/admin/app/account.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
        } else {
            switch ($data) {
                case 'identity_error':
                    $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'captcha_error':
                    $('#message').html('لطفا کد امنیتی را درست بنویسید.');
                    break;
                case 'member_connect_error':
                    $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'email_wrong':
                    $('#message').html('ایمیل شما تایید نشد! لطفا از بخش پروفایل آنرا بررسی کنید.');
                    break;
                case 'subdomain_wrong':
                    $('#message').html('زیردامنه می‌تواند از نویسه های انگلیسی، شماره‌ها و یک - باشد.');
                    break;
                case 'subdomain_length':
                    $('#message').html('زیردامنه می تواند پنج تا بیست نویسه باشد.');
                    break;
                case 'subdomain_forbidden':
                    $('#message').html('این زیردامنه مسدود است. لطفا یکی دیگر را امتحان کنید...');
                    break;
                case 'error':
                    $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
                    break;
                case 'subdomain_duplicate':
                    $('#message').html('این زیردامنه پیش از این رزرو شده است. یکی دیگر را امتحان کنید...');
                    break;
                case 'done':
                    $('#message').html('وبلاگ نو به درستی ساخته شد. اکنون می توانید آنرا مدیریت کنید.');
                    $('#create_section').hide();
                    $('#manage_section').show(0, function () {
                        getWeblogs();
                    });
                    break;
                default:
                    $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
                    alert($data);
            }
        }
        recaptcha();
        loading_hide();
        $('#message').slideDown('slow');
    });
});
//****************************************************************************
// Apply
$('#apply_button').click(function () {
    loading_show();
    var $info = JSON.stringify({
        command: 'apply',
        subdomain: filter($('#apply_subdomain').val()),
        captcha: filter($('#apply_captcha').val())
    });
    $.get('/admin/app/account.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
        } else {
            switch ($data) {
                case 'identity_error':
                    $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'captcha_error':
                    $('#message').html('لطفا کد امنیتی را درست بنویسید.');
                    break;
                case 'subdomain_wrong':
                case 'subdomain_length':
                case 'blog_wrong':
                    $('#message').html('این وبلاگ وجود ندارد!');
                    break;
                case 'role_exists':
                    $('#message').html('شما هم اکنون عضو این وبلاگ هستید!');
                    break;
                case 'request_exists':
                    $('#message').html('شما پیش از این درخواست عضویت فرستاده‌اید.');
                    break;
                case 'error':
                    $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
                    break;
                case 'done':
                    $('#message').html('درخواست عضویت با موفقیت فرستاده شد.');
                    $('#apply_section').hide();
                    $('#manage_section').show(0, function () {
                        getWeblogs();
                    });
                    break;
                default:
                    $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
                    alert($data);
            }
        }
        recaptcha();
        loading_hide();
        $('#message').slideDown('slow');
    });
});
//****************************************************************************
// SignOut
$('#manage_signout').click(function () {
    loading_show();
    var $info = JSON.stringify({
        command: 'signOut'
    });
    $.get('/admin/app/account.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
            $('#message').slideDown('slow');
            loading_hide();
        } else {
            location.reload();
        }
    });
});
//****************************************************************************
// Main
$(document).ready(function () {
    var $page = (document.location.search) ? (document.location.search).substr(1)  : '';
    var $info = JSON.stringify({
        command: 'identify'
    });
    $.get('/admin/app/account.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
            $('#message').slideDown('slow');
        } else {
            switch ($page) {
                case 'signin':
                    signOut();
                    $('#signin_section').show();
                    break;
                case 'lost':
                    signOut();
                    $('#lost_section').show();
                    break;
                case 'recovery':
                    signOut();
                    $('#recovery_section').show();
                    break;
                case 'signup':
                    signOut();
                    $('#signup_section').show();
                    break;
                case 'confirm':
                    signOut();
                    $('#confirm_section').show();
                    break;
                case 'manage':
                    if ($data == 'yes') $('#manage_section').show(0, function () {
                        getWeblogs();
                    });
                    else $('#signin_section').show();
                    break;
                case 'profile':
                    if ($data == 'yes') $('#profile_section').show(0, function () {
                        getProfile();
                    });
                    else $('#signin_section').show();
                    break;
                case 'create':
                    if ($data == 'yes') $('#create_section').show();
                    else $('#signin_section').show();
                    break;
                case 'apply':
                    if ($data == 'yes') $('#apply_section').show();
                    else $('#signin_section').show();
                    break;
                case 'signout':
                    signOut();
                    window.location = "account.php";
                    break;
                default:
                    if ($data == 'yes') $('#manage_section').show(0, function () {
                        getWeblogs();
                    });
                    else $('#signin_section').show();
            }
        }
        recaptcha();
        loading_hide();
    });
});
//****************************************************************************
$('#signin_signup').click(function () {
    $('#signin_section').hide();
    $('#signup_section').show();
});
//****************************************************************************
$('#signin_lost').click( function () {
    $('#signin_section').hide();
    $('#lost_section').show();
});
//****************************************************************************
$('#lost_recovery').click( function () {
    $('#lost_section').hide();
    $('#recovery_section').show();
});
//****************************************************************************
$('#lost_signin').click( function () {
    $('#lost_section').hide();
    $('#signin_section').show();
});
//****************************************************************************
document.getElementById('recovery_signin').onclick = function () {
    document.getElementById('recovery_section').style.display = 'none';
    document.getElementById('signin_section').style.display = 'block';
};
//****************************************************************************
document.getElementById('recovery_lost').onclick = function () {
    document.getElementById('recovery_section').style.display = 'none';
    document.getElementById('lost_section').style.display = 'block';
};
//****************************************************************************
document.getElementById('signup_signin').onclick = function () {
    document.getElementById('signup_section').style.display = 'none';
    document.getElementById('signin_section').style.display = 'block';
};
//****************************************************************************
document.getElementById('confirm_signin').onclick = function () {
    document.getElementById('confirm_section').style.display = 'none';
    document.getElementById('signin_section').style.display = 'block';
};
//****************************************************************************
document.getElementById('weblog_create').onclick = function () {
    document.getElementById('manage_section').style.display = 'none';
    document.getElementById('create_section').style.display = 'block';
};
//****************************************************************************
document.getElementById('manage_profile').onclick = function () {
    document.getElementById('manage_section').style.display = 'none';
    $('#profile_section').show(0, function () {
        getProfile();
    });
};
//****************************************************************************
document.getElementById('profile_manage').onclick = function () {
    document.getElementById('profile_section').style.display = 'none';
    $('#manage_section').show(0, function () {
        getWeblogs();
    });
};
//****************************************************************************
document.getElementById('create_manage').onclick = function () {
    $('#create_section').hide();
    $('#manage_section').show(0, function () {
        getWeblogs();
    });
};
//****************************************************************************
$('#manage_apply').click(function () {
    $('#manage_section').hide();
    $('#apply_section').show();
});
//****************************************************************************
$('#apply_manage').click(function () {
    $('#apply_section').hide();
    $('#manage_section').show(0, function () {
        getWeblogs();
    });
});
//****************************************************************************
$('.captcha').focus(function () {
    if ($(this).val() == 'کد امنیتی') $(this).val('');
}).blur(function () {
    if ($(this).val() == '') $(this).val('کد امنیتی');
}).val('کد امنیتی');
//****************************************************************************
$(':password').css('color', 'rgb(200,200,200)');
$(':password').focus(function () {
    if ($(this).val() == 'password') {
        $(this).val('');
        $(this).css('color', 'rgb(30,30,30)');
    }
}).blur(function () {
    if ($(this).val() == '') {
        $(this).val('password');
        $(this).css('color', 'rgb(200,200,200)');
    }
});
//****************************************************************************
$('#profile_email').keypress(function() {
    $('#profile_email_chnage').slideDown("slow");
});
//****************************************************************************
document.getElementById('signin_email').onfocus = function () {
    if (document.getElementById('signin_email').value == 'email@example.com') {
        document.getElementById('signin_email').value = '';
    }
};
document.getElementById('signin_email').onblur = function () {
    if (document.getElementById('signin_email').value == '') {
        document.getElementById('signin_email').value = 'email@example.com';
    }
};
//****************************************************************************
document.getElementById('signin_password').onfocus = function () {
    if (document.getElementById('signin_password').value == 'password') {
        document.getElementById('signin_password').value = '';
    }
};
document.getElementById('signin_password').onblur = function () {
    if (document.getElementById('signin_password').value == '') {
        document.getElementById('signin_password').value = 'password';
    }
};
//****************************************************************************
document.getElementById('lost_email').onfocus = function () {
    if (document.getElementById('lost_email').value == 'email@example.com') {
        document.getElementById('lost_email').value = '';
    }
};
document.getElementById('lost_email').onblur = function () {
    if (document.getElementById('lost_email').value == '') {
        document.getElementById('lost_email').value = 'email@example.com';
    }
};
//****************************************************************************
document.getElementById('recovery_code').onfocus = function () {
    if (document.getElementById('recovery_code').value == 'کد بازیابی') {
        document.getElementById('recovery_code').value = '';
    }
};
document.getElementById('recovery_code').onblur = function () {
    if (document.getElementById('recovery_code').value == '') {
        document.getElementById('recovery_code').value = 'کد بازیابی';
    }
};
//****************************************************************************
document.getElementById('recovery_password1').onfocus = function () {
    if (document.getElementById('recovery_password1').value == 'password') {
        document.getElementById('recovery_password1').value = '';
    }
};
document.getElementById('recovery_password1').onblur = function () {
    if (document.getElementById('recovery_password1').value == '') {
        document.getElementById('recovery_password1').value = 'password';
    }
};
//****************************************************************************
document.getElementById('recovery_password2').onfocus = function () {
    if (document.getElementById('recovery_password2').value == 'password') {
        document.getElementById('recovery_password2').value = '';
    }
};
document.getElementById('recovery_password2').onblur = function () {
    if (document.getElementById('recovery_password2').value == '') {
        document.getElementById('recovery_password2').value = 'password';
    }
};
//****************************************************************************
document.getElementById('signup_nickname').onfocus = function () {
    if (document.getElementById('signup_nickname').value == 'نام نمایشی') {
        document.getElementById('signup_nickname').value = '';
    }
};
document.getElementById('signup_nickname').onblur = function () {
    if (document.getElementById('signup_nickname').value == '') {
        document.getElementById('signup_nickname').value = 'نام نمایشی';
    }
};
//****************************************************************************
document.getElementById('signup_password1').onfocus = function () {
    if (document.getElementById('signup_password1').value == 'password') {
        document.getElementById('signup_password1').value = '';
    }
};
document.getElementById('signup_password1').onblur = function () {
    if (document.getElementById('signup_password1').value == '') {
        document.getElementById('signup_password1').value = 'password';
    }
};
//****************************************************************************
document.getElementById('signup_password2').onfocus = function () {
    if (document.getElementById('signup_password2').value == 'password') {
        document.getElementById('signup_password2').value = '';
    }
};
document.getElementById('signup_password2').onblur = function () {
    if (document.getElementById('signup_password2').value == '') {
        document.getElementById('signup_password2').value = 'password';
    }
};
//****************************************************************************
document.getElementById('signup_email').onfocus = function () {
    if (document.getElementById('signup_email').value == 'email@example.com') {
        document.getElementById('signup_email').value = '';
    }
};
document.getElementById('signup_email').onblur = function () {
    if (document.getElementById('signup_email').value == '') {
        document.getElementById('signup_email').value = 'email@example.com';
    }
};
//****************************************************************************
document.getElementById('profile_password1').onfocus = function () {
    if (document.getElementById('profile_password1').value == 'password') {
        document.getElementById('profile_password1').value = '';
    }
};
document.getElementById('profile_password1').onblur = function () {
    if (document.getElementById('profile_password1').value == '') {
        document.getElementById('profile_password1').value = 'password';
    }
};
//****************************************************************************
document.getElementById('profile_password2').onfocus = function () {
    if (document.getElementById('profile_password2').value == 'password') {
        document.getElementById('profile_password2').value = '';
    }
};
document.getElementById('profile_password2').onblur = function () {
    if (document.getElementById('profile_password2').value == '') {
        document.getElementById('profile_password2').value = 'password';
    }
};
//****************************************************************************
document.getElementById('profile_password3').onfocus = function () {
    if (document.getElementById('profile_password3').value == 'password') {
        document.getElementById('profile_password3').value = '';
    }
};
document.getElementById('profile_password3').onblur = function () {
    if (document.getElementById('profile_password3').value == '') {
        document.getElementById('profile_password3').value = 'password';
    }
};
//****************************************************************************
document.getElementById('create_subdomain').onfocus = function () {
    if (document.getElementById('create_subdomain').value == 'زیردامنه') {
        document.getElementById('create_subdomain').value = '';
    }
};
document.getElementById('create_subdomain').onblur = function () {
    if (document.getElementById('create_subdomain').value == '') {
        document.getElementById('create_subdomain').value = 'زیردامنه';
    }
};
//****************************************************************************
document.getElementById('apply_subdomain').onfocus = function () {
    if (document.getElementById('apply_subdomain').value == 'وبلاگ') {
        document.getElementById('apply_subdomain').value = '';
    }
};
document.getElementById('apply_subdomain').onblur = function () {
    if (document.getElementById('apply_subdomain').value == '') {
        document.getElementById('apply_subdomain').value = 'وبلاگ';
    }
};