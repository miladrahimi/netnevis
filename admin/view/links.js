//****************************************************************************
// Add
$('#add').click(function () {
    loading_show();
    var $info = JSON.stringify({
        command: 'add',
        title: filter($('#title').val()),
        url: filter($('#url').val())
    });
    $.get('/admin/app/links.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
        } else if ($data == 'identity_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
        } else if ($data == 'error') {
            $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
        } else if ($data == 'permission_error') {
            $('#message').html('شما دسترسی لازم برای افزودن پیوند را ندارید.');
        } else if ($data == 'member_connect_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
        } else if ($data == 'blog_connect_error') {
            $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
        } else if ($data == 'url_wrong') {
            $('#message').html('لطفا آدرس پیوند را درست بنویسید.');
        } else if ($data == 'done') {
            $('#message').html('پیوند به درستی افزوده شد.');
            getlinks();
        } else {
            $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
            alert($data);
        }
        loading_hide();
        $('#message').slideDown('slow');
        window.location = '#message';
    });
});
//****************************************************************************
// Delete
function del($id) {
    if (confirm('از پاک کردن این پیوند دل‌استوار هستید؟')) {
        loading_show();
        var $info = JSON.stringify({
            command: 'delete',
            id: $id
        });
        $.get('/admin/app/links.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
            if ($status != 'success') {
                $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.')
            } else if ($data == 'identity_error') {
                $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            } else if ($data == 'error') {
                $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
            } else if ($data == 'permission_error') {
                $('#message').html('شما دسترسی لازم برای پاک کردن پیوند‌ها را ندارید.');
            } else if ($data == 'member_connect_error') {
                $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            } else if ($data == 'blog_connect_error') {
                $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            } else if ($data == 'done') {
                $('#message').html('پیوند به درستی پاک شد.');
                getlinks();
            } else {
                $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
                alert($data);
            }
            loading_hide();
            $('#message').slideDown('slow');
            window.location = '#message';
        });
    }
}
//****************************************************************************
// Get links
function getlinks() {
    loading_show();
    var $info = JSON.stringify({
        command: 'getLinks'
    });
    $.get('/admin/app/links.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
            $('#message').slideDown('slow');
        } else if ($data == 'identity_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.').slideDown('slow');
        } else if ($data == 'member_connect_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.').slideDown('slow');
        } else if ($data == 'blog_connect_error') {
            $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.').slideDown('slow');
        } else if ($data == 'link_connect_error') {
            $('#message').html('در بارگذاری پیوند خطایی رخ داد، لطفا صفحه را دوباره بارگذاری کنید.').slideDown('slow');
        } else if ($data == 'error') {
            $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...').slideDown('slow');
        } else {
            var $links = JSON.parse($data);
            $('#links').html('');
            $.each($links, function (key, value) {
                $('#links').append('<div class="link"><div class="link-delete" onclick="del(' + key +
                    ')"></div><div class="link-info"><span>' +
                    value['title'] + '</span><br><span class="link-url">' + value['url'] + '</span></div></div>');
            });
        }
        loading_hide();
    });
}
//****************************************************************************
// Main
$(document).ready(function () {
    getlinks();
    loading_hide();
});