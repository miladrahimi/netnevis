//****************************************************************************
// Main
$(document).ready(function () {
    var $info = JSON.stringify({
        command: 'load'
    });
    $.get('/admin/app/appearance.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
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
            $('#logo').val(filterback($setting['logo']));
            $('#cover').val(filterback($setting['cover']));
            $('#favicon').val(filterback($setting['favicon']));
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
        logo: filter($('#logo').val()),
        cover: filter($('#cover').val()),
        favicon: filter($('#favicon').val())
    });
    $.get('/admin/app/appearance.php?r=' + Math.random() + '&q=' + $data, function ($data, $status) {
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
                case 'logo_wrong':
                    $data = 'لطفا آدرس لوگوی وبلاگ را درست بنویسید یا آنرا خالی بگذارید.';
                    break;
                case 'cover_wrong':
                    $data = 'لطفا آدرس کاور وبلاگ را درست بنویسید یا آنرا خالی بگذارید.';
                    break;
                case 'favicon_wrong':
                    $data = 'لطفا آدرس ایکون وبلاگ را درست بنویسید یا آنرا خالی بگذارید.';
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
                    $data = 'گزینه‌ها به درستی ویرایش شدند.';
                    window.location = 'appearance.php';
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