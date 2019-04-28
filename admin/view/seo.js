//****************************************************************************
// Main
$(document).ready(function() {
    loading_hide();
});
// ****************************************************************************
// Save
$('#save').click(function () {
    loading_show();
    var $info = {
        command: 'save',
        metatags: $('#metatags').val()
    };
    $.post('/admin/app/seo.php', $info, function ($data, $status) {
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
                    window.location = 'seo.php';
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