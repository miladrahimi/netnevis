//****************************************************************************
// Main
$(document).ready(function () {
    var $info = JSON.stringify({
        command: 'getSetting'
    });
    $.get('/admin/app/setting.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
            $('#message').slideDown('slow');
            window.location = '#message';
        } else if ($data == 'identity_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.').slideDown('fast');
            window.location = '#message';
        } else if ($data == 'blog_connect_error') {
            $('#message').html('وبلاگ شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.').slideDown('fast');
            window.location = '#message';
        } else if ($data == 'error') {
            $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...').slideDown('fast');
            window.location = '#message';
        } else {
            var $setting = JSON.parse($data);
            $('#subdomain').val(filterback($setting['subdomain']));
            $('#title').val(filterback($setting['title']));
            $('#description').val(filterback($setting['desc']));
            $('#email').val(filterback($setting['email']));
            $('#gauthor').val(filterback($setting['g_author'].replace(/ /g, '+')));
            $('#gpublisher').val(filterback($setting['g_publisher'].replace(/ /g, '+')));
            $('#about').val(filterback($setting['about']));
        }
        loading_hide();
    });
});
//****************************************************************************
// Save
$('#save').click(function () {
    loading_show();
    var $data = JSON.stringify({
        command: 'save',
        subdomain: filter($('#subdomain').val()),
        title: filter($('#title').val()),
        description: filter($('#description').val()),
        gauthor: filter($('#gauthor').val()),
        gpublisher: filter($('#gpublisher').val()),
        email: filter($('#email').val()),
        about: filter($('#about').val())
    });
    $.get('/admin/app/setting.php?r=' + Math.random() + '&q=' + $data, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
        } else if ($status == 'success') {
            $('#message').hide();
            switch ($data) {
                case 'identity_error':
                    $data = 'هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.';
                    break;
                case 'permission_error':
                    $data = 'شما دسترسی لازم برای ویرایش تنظیمات را ندارید.';
                    break;
                case 'subdomain_wrong':
                    $data = 'زیردامنه می‌تواند از نویسه های انگلیسی، شماره‌ها و یک - باشد.';
                    break;
                case 'subdomain_length':
                    $data = 'لطفا زیردامنه‌ای با پنج تا بیست نویسه بنویسید.';
                    break;
				case 'subdomain_forbidden':
                    $data = 'این زیردامنه مسدود است.';
                    break;
                case 'subdomain_exists':
                    $data = 'این زیردامنه پیش از این رزرو شده است. لطفا یکی دیگر را امتحان کنید.';
                    break;
                case 'member_gauthor_wrong':
                    $data = 'لطفا آدرس پرفایل گوگل+ را درست بنویسید یا آنرا خالی بگذارید.';
                    break;
                case 'member_gpublisher_wrong':
                    $data = 'لطفا آدرس پیج گوگل+ وبلاگ را درست بنویسید یا آنرا خالی بگذارید.';
                    break;
                case 'email_wrong':
                    $data = 'لطفا ایمیل مدیر (وبلاگ) را درست بنویسید.';
                    break;
                case 'blog_edit_error':
                    $data = 'چیز تازه‌ای برای ویرایش وجود نداشت!';
                    break;
                case 'member_connect_error':
                    $data = 'هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.';
                    break;
                case 'blog_connect_error':
                    $data = 'وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.';
                    break;
                case 'error':
                    $data = 'خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...';
                    break;
                case 'done':
                    $data = 'تنظیمات به درستی ویرایش شدند.';
                    break;
                default:
                    $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
                    alert($data);
            }
            $('#message').html($data).slideDown('slow');
            window.location = '#message';
        }
        loading_hide();
    });
});
//****************************************************************************
// Delete Link
$('#delete_link').click(function () {
    loading_show();
    var $info = JSON.stringify({
        command: 'deletePermission'
    });
    $.get('/admin/app/setting.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
            $('#message').slideDown('slow');
            window.location = '#message';
        } else if ($status == 'success') {
            if ($data == 'yes') {
                $('#delete_link').hide();
                $('#delete_main').fadeIn('slow');
                window.location = '#delete_main';
            } else if ($data == 'no') {
                $('#message').html('شما دسترسی لازم برای حذف وبلاگ را ندارید.').slideDown('fast');
                window.location = '#message';
            } else if ($data == 'identity_error') {
                $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.').slideDown('fast');
                window.location = '#message';
            } else if ($data == 'member_connect_error') {
                $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.').slideDown('fast');
                window.location = '#message';
            } else if ($data == 'blog_connect_error') {
                $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.').slideDown('fast');
                window.location = '#message';
            } else {
                $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.').slideDown('fast');
                window.location = '#message';
                alert($data);
            }
        }
        loading_hide();
    });
});
//****************************************************************************
// Delete Blog
$('#delete').click(function () {
    loading_show();
    var $info = JSON.stringify({
        command: 'deleteBlog',
        password: filter($('#delete_password').val()),
        captcha: filter($('#delete_captcha').val())
    });
    $.get('/admin/app/setting.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
        } else if ($status == 'success') {
            switch ($data) {
                case 'captcha_error':
                    $('#message').html('لطفا کد امنیتی را درست بنویسید.');
                    break;
                case 'identity_error':
                    $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'permission_error':
                    $('#message').html('شما دسترسی لازم برای حذف وبلاگ را ندارید.');
                    break;
                case 'password_error':
                    $('#message').html('گذرواژه مدیریت نادرست است.');
                    break;
                case 'member_connect_error':
                    $data = 'هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.';
                    break;
                case 'blog_connect_error':
                    $data = 'وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.';
                    break;
                case 'blog_delete_error':
                    $data = 'خطایی در حذف کردن وبلاگ پیش آمده است، لطفا صفحه را دوباره بارگذاری کنید.';
                    break;
                case 'error':
                    $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
                    break;
                case 'done':
                    $('#message').html('وبلاگ به درستی حذف شد.');
                    window.location = 'account.php?manage';
                    break;
                default:
                    alert($data);
                    $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
                    break;
            }
        }
        recaptcha();
        loading_hide();
        $('#message').slideDown('slow');
        window.location = '#message';
    });
});