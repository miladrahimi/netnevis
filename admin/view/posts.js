//****************************************************************************
// getPages
function getPages($year, $month) {
    loading_show();
    $('#pages').hide();
    var $data = JSON.stringify({
        command: 'getPages',
        year: filter($year),
        month: filter($month)
    });
    $.get('/admin/app/posts.php?r=' + Math.random() + '&q=' + $data, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
            $('#message').slideDown('slow');
            window.location = '#message';
        } else if ($data == 'identity_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            $('#message').slideDown('slow');
            window.location = '#message';
        } else if ($data == 'error') {
            $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
            $('#message').slideDown('slow');
            window.location = '#message';
        } else if ($data == 'blog_connect_error') {
            $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            $('#message').slideDown('slow');
            window.location = '#message';
        } else {
            var $pages = parseInt($data);
            if ($pages > 1) {
                $('#pages').show();
                $('#pages').html('');
                var $flag = 1;
                for (var $i = 1; $i <= $pages; $i++) {
                    if ($flag) {
                        $('#pages').append('<span class="pages-item pages-selected">' + $i + '</span>');
                        $flag = 0;
                    } else {
                        $('#pages').append('<span class="pages-item">' + $i + '</span>');
                    }
                }
            }
            getPosts($year, $month, 1);
        }
        loading_hide();
    });
}
//****************************************************************************
// getPosts
function getPosts($year, $month, $page) {
    loading_show();
    $('#posts').html('');
    var $data = JSON.stringify({
        command: 'getPosts',
        year: filter($year),
        month: filter($month),
        page: filter($page)
    });
    $.get('/admin/app/posts.php?r=' + Math.random() + '&q=' + $data, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
            $('#message').slideDown('slow');
            window.location = '#message';
        } else if ($data == 'identity_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            $('#message').slideDown('slow');
            window.location = '#message';
        } else if ($data == 'error') {
            $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
            $('#message').slideDown('slow');
            window.location = '#message';
        } else if ($data == 'blog_connect_error') {
            $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            $('#message').slideDown('slow');
            window.location = '#message';
        } else if ($data == 'post_connect_error') {
            $('#message').html('در دریافت اطلاعات پست خطایی پیش آمده! لطفا صفحه را دوباره بارگذاری کنید.');
            $('#message').slideDown('slow');
            window.location = '#message';
        } else {
            var $posts = JSON.parse($data);
            $('#posts').html('');
            $.each($posts, function (key, value) {
                $('#posts').append(
                    '<div class="post" id="post_' + value['id'] + '">' +
                    '<div class="post-icon" onclick="edit(' + value['id'] + ')"></div>' +
                    '<div class="post-title" onclick="edit(' + value['id'] + ')">' + value['title'] + '</div>' +
                    '<div class="post-time" onclick="edit(' + value['id'] + ')">' + value['time'] + '</div>' +
                    '<div class="post-delete" onclick="del(' + value['id'] + ')"></div>' +
                    '<div class="post-comment" onclick="comment(' + value['id'] + ')"></div>' +
                    '<span class="post-commentnum" onclick="comment(' + value['id'] + ')">(' + value['commentnum'] +
                    ')</span><div class="post-like" onclick="like(' + value['id'] + ')"></div>' +
                    '<span class="post-likenum" onclick="like(' + value['id'] + ')">(' + value['likenum'] +
                    ')</span>' +
                    '</div>'
                );
            });
        }
        loading_hide();
    });
}
//****************************************************************************
// getArchive
function getArchive() {
    loading_show();
    var $data = JSON.stringify({
        command: 'getArchive'
    });
    $.get('/admin/app/posts.php?r=' + Math.random() + '&q=' + $data, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
            $('#message').slideDown('slow');
        } else if ($data == 'identity_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.').slideDown('slow');
        } else if ($data == 'error') {
            $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...').slideDown('fast');
        } else if ($data == '[]') {
            $('#message').html('وبلاگ شما هم اکنون هیچ پستی ندارد! روی دکمه انتشار پست نو کلیک کنید...');
            $('#message').slideDown('slow');
            $('#archive').hide();
            $('#posts').hide();
        } else if ($data == 'blog_connect_error') {
            $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            $('#message').slideDown('slow');
            window.location = '#message';
        } else {
            // Add items to archive
            var $months = JSON.parse($data);
            $('#archive').find('option').remove();
            $.each($months, function ($key, $value) {
                var $d = $value['date'];
                var $n = $value['num'];
                $('#archive').append($('<option>' + $d + ' (' + $n + ')' + '</option>'));
            });
            // Load posts of last month
            var $date = $('#archive').val();
            var $y = parseInt($date.substr(0, 4));
            var $m = parseInt($date.substr(5, 2));
            getPages($y, $m);
        }
        loading_hide();
    });
}
//****************************************************************************
// Archive Item Click
$('#archive').change(function () {
    var $date = $('#archive').find(':selected').text();
    var $y = parseInt($date.substr(0, 4));
    var $m = parseInt($date.substr(5, 2));
    getPages($y, $m);
});
//****************************************************************************
// Page Item Click
$(document).on('click', '.pages-item', function () {
    $('.pages-item').removeClass('pages-selected');
    $(this).addClass('pages-selected');
    var $date = $('#archive').find(':selected').text();
    var $year = parseInt($date.substr(0, 4));
    var $month = parseInt($date.substr(5, 2));
    var $page = parseInt($(this).html());
    getPosts($year, $month, $page);
});
//****************************************************************************
// Edit Post
function edit($id) {
    loading_show();
    var $data = JSON.stringify({
        command: 'hasPermissionOfEdit',
        post: $id
    });
    $.get('/admin/app/posts.php?r=' + Math.random() + '&q=' + $data, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
        } else if ($data == 'identity_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
        } else if ($data == 'error') {
            $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
        } else if ($data == 'blog_connect_error') {
            $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            $('#message').slideDown('slow');
            window.location = '#message';
        } else {
            if ($data == 'no') {
                $('#message').html('شما دسترسی لازم برای ویرایش این پست را ندارید.');
            } else if ($data == 'yes') {
                $('#message').html('لطفا شکیبا باشید...');
                window.location = '/admin/post.php?' + $id;
            }
        }
        loading_hide();
        $('#message').slideDown('slow');
        window.location = '#message';
    });
}
//****************************************************************************
// Delete Post
function del($id) {
    $('#delete_confirm').attr('data-post', $id);
    $('#delete_confirm').slideDown('slow');
    window.location = 'posts.php#delete_confirm';
}
//****************************************************************************
// Comment Post
function comment($id) {
    window.location = 'comments.php?' + $id;
}
//****************************************************************************
// Delete Confirm
$('#delete_button').click(function () {
    loading_show();
    $('#delete_confirm').slideUp('slow');
    var $post = parseInt($('#delete_confirm').attr('data-post'));
    var $data = JSON.stringify({
        command: 'delete',
        post: filter($post),
        captcha: filter($('#delete_captcha').val())
    });
    $.get('/admin/app/posts.php?r=' + Math.random() + '&q=' + $data, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
        } else {
            switch ($data) {
                case 'captcha_error':
                    $('#message').html('لطفا کد امنیتی را درست بنویسید.');
                    $('#delete_confirm').slideDown('slow');
                    break;
                case 'identity_error':
                    $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'permission_error':
                    $('#message').html('شما دسترسی لازم برای پاک کردن این پست را ندارید..');
                    break;
                case 'post_connect_error':
                    $('#message').html('در دریافت اطلاعات پست خطایی پیش آمده! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'blog_connect_error':
                    $('#message').html('شما دسترسی لازم برای پاک کردن این پست را ندارید.');
                    break;
                case 'post_delete_error':
                    $('#message').html('در دریافت اطلاعات پست خطایی پیش آمده! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'error':
                    $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'done':
                    $('#message').html('پست با موفقیت پاک شد.');
                    getArchive();
                    break;
                default:
                    $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
            }
        }
        recaptcha();
        loading_hide();
        $('#message').slideDown('slow');
        window.location = '#message';
    });
});
//****************************************************************************
// Main
$(document).ready(function () {
    getArchive();
    loading_hide();
});