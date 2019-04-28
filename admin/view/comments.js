//****************************************************************************
// Delete
function del($id) {
    if (confirm('از پاک کردن این دیدگاه دل‌استوار هستید؟')) {
        loading_show();
        var $data = JSON.stringify({
            command: 'delete',
            id: $id
        });
        $.get('/admin/app/comments.php?r=' + Math.random() + '&q=' + $data, function ($data, $status) {
            if ($status != 'success') {
                $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
                window.location = '#message';
            } else if ($data == 'identity_error') {
                $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
                window.location = '#message';
            } else if ($data == 'error') {
                $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
                window.location = '#message';
            } else if ($data == 'permission_error') {
                $('#message').html('شما دسترسی لازم برای پاک کردن این دیدگاه را ندارید.');
                window.location = '#message';
            } else if ($data == 'blog_connect_error') {
                $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
                window.location = '#message';
            } else if ($data == 'member_connect_error') {
                $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
                window.location = '#message';
            } else if ($data == 'done') {
                $('#message').html('دیدگاه به درستی پاک شد.');
                window.location = '#message';
                var $id = (document.location.search) ? parseInt((document.location.search).substr(1))  : 0;
                if ($id == 0) {
                    window.location = 'comments.php';
                } else {
                    window.location = 'comments.php?' + $id;
                }
            } else {
                $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
                alert($data);
            }
            loading_hide();
            $("message").slideDown("slow");
        });
    }
}
//****************************************************************************
// Confirm
function con($id) {
    loading_show();
    var $data = JSON.stringify({
        command: 'confirm',
        id: $id
    });
    $.get('/admin/app/comments.php?r=' + Math.random() + '&q=' + $data, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
            window.location = '#message';
        } else if ($data == 'identity_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            window.location = '#message';
        } else if ($data == 'error') {
            $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
            window.location = '#message';
        } else if ($data == 'permission_error') {
            $('#message').html('شما دسترسی لازم برای تایید کردن این دیدکاه را ندارید.');
            window.location = '#message';
        } else if ($data == 'blog_connect_error') {
            $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            window.location = '#message';
        } else if ($data == 'member_connect_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            window.location = '#message';
        } else if ($data == 'done') {
            $('#message').html('دیدگاه به درستی تایید شد.');
            var $id = (document.location.search) ? parseInt((document.location.search).substr(1))  : 0;
            if ($id == 0) {
                window.location = 'comments.php';
            } else {
                window.location = 'comments.php?' + $id;
            }
        } else {
            $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
            alert($data);
        }
        loading_hide();
        $("message").slideDown("slow");
    });
}
//****************************************************************************
// getPostComments
function getPostComments($id) {
    loading_show();
    var $data = JSON.stringify({
        command: 'getPostComments',
        id: filter($id)
    });
    $.get('/admin/app/comments.php?r=' + Math.random() + '&q=' + $data, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
            $('#message').slideDown('slow');
        } else if ($data == 'identity_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.').slideDown('slow');
        } else if ($data == 'error') {
            $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...').slideDown('slow');
        } else if ($data == 'permission_error') {
            $('#message').html('شما دسترسی لازم برای دیدن دیدگاه های این پست را ندارید.').slideDown('slow');
        } else if ($data == 'blog_connect_error') {
            $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            window.location = '#message';
        } else if ($data == 'member_connect_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            window.location = '#message';
        } else if ($data == 'post_connect_error') {
            $('#message').html('به نظر می‌رسد پست مورد نظر حذف شده است، لطفا صفحه را دوباره بارگذاری کنید.');
            window.location = '#message';
        } else {
            var keys = JSON.parse($data);
            if (keys == '') $('#message').html('دیدگاه تازه‌ای فرستاده نشده است.').slideDown('slow');
            $('#comments').html('');
            $.each(keys, function (key, value) {
                if ($('#message').html() == '')
                    $('#message').html('دیدگاه های ' + value['post']).slideDown('slow');
                if (value['status'] == 0) {
                    $('#comments').append(
                        '<div class="comment">' +
                        '<div class="author">' +
                        '<span dir="rtl">' + value['author'] + '</span> <span dir="ltr">(' + value['email'] +
                        ')</span> در <span dir="rtl">«' + value['post'] +
                        '»</span> <span dir="ltr">(' + value['time'] + ')</span></div>' +
                        '<div class="cmessage">' + value['message'] + '</div>' +
                        '<input type="button" class="button2" onclick="con(' + value['id'] + ')" value="تایید"> ' +
                        '<input type="button" class="button1" onclick="del(' + value['id'] + ')" value="حذف">' +
                        '</div><br>'
                    );
                } else {
                    $('#comments').append(
                        '<div class="comment">' +
                        '<div class="author">' +
                        '<span dir="rtl">' + value['author'] + '</span> <span dir="ltr">(' + value['email'] +
                        ')</span> در <span dir="rtl">«' + value['post'] +
                        '»</span> <span dir="ltr">(' + value['time'] + ')</span></div>' +
                        '<div class="cmessage">' + value['message'] + '</div>' +
                        '<input type="button" class="button3" onclick="del(' + value['id'] + ')" value="حذف">' +
                        '</div><br>'
                    );
                }
            });
        }
        loading_hide();
    });
}
//****************************************************************************
// getBlogComments
function getBlogComments() {
    loading_show();
    var $data = JSON.stringify({
        command: 'getBlogComments'
    });
    $.get('/admin/app/comments.php?r=' + Math.random() + '&q=' + $data, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
            $('#message').slideDown('slow');
        } else if ($data == 'identity_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.').slideDown('slow');
        } else if ($data == 'error') {
            $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...').slideDown('slow');
        } else if ($data == 'blog_connect_error') {
            $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            window.location = '#message';
        } else if ($data == 'member_connect_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            window.location = '#message';
        } else if ($data == 'post_connect_error') {
            $('#message').html('به نظر می‌رسد یکی از پست ها حذف شده‌است! لطفا صفحه را دوباره بارگذاری کنید.');
            window.location = '#message';
        } else {
            var $comments = JSON.parse($data);
            if ($comments == '') $('#message').html('دیدگاه تازه‌ای فرستاده نشده است.').slideDown('slow');
            $('#comments').html('');
            $.each($comments, function (key, value) {
                $('#comments').append(
                    '<div class="comment">' +
                    '<div class="author">' +
                    '<span dir="rtl">' + value['author'] + '</span> <span dir="ltr">(' + value['email'] +
                    ')</span> در <span dir="rtl">«' + value['post'] +
                    '»</span> <span dir="ltr">(' + value['time'] + ')</span></div>' +
                    '<div class="cmessage">' + value['message'] + '</div>' +
                    '<input type="button" class="button2" onclick="con(' + value['id'] + ')" value="تایید"> ' +
                    '<input type="button" class="button3" onclick="del(' + value['id'] + ')" value="حذف">' +
                    '</div><br>'
                );
            });
        }
        loading_hide();
    });
}
//****************************************************************************
// Main
$(document).ready(function () {
    var $id = (document.location.search) ? parseInt((document.location.search).substr(1))  : 0;
    if ($id > 0) {
        getPostComments($id);
    } else {
        $('#tip').show();
        getBlogComments();
    }
    loading_hide();
});