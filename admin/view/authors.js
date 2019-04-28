//****************************************************************************
// Get Applys
function getApplys() {
    loading_show();
    var $data = JSON.stringify({
        command: 'getApplys'
    });
    $.get('/admin/app/authors.php?r=' + Math.random() + '&q=' + $data, function ($data, $status) {
        $('#applys').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...<br><br>');
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
            $('#message').slideDown('slow');
        } else if ($data == 'identity_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.').slideDown('slow');
        } else if ($data == 'error') {
            $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...').slideDown('slow');
        } else if ($data == 'blog_connect_error') {
            $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.').slideDown('slow');
        } else if ($data == 'member_connect_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.').slideDown('slow');
        } else {
            var $applys = JSON.parse($data);
            if ($applys != '') $('#applys').html('');
            else $('#applys').html('درخواست تازه‌ای وجود ندارد. <br><br>');
            $.each($applys, function (key, value) {
                $('#applys').append('<div class="auth">' +
                        ' <div class="auth-delete" onclick="reject(' + value['id'] + ')"></div>' +
                        ' <div class="auth-info">' +
                        '  <span dir="rtl">' + value['nickname'] + '</span> ' +
                        '  <span dir="ltr">(' + value['email'] + ')</span>' +
                        ' </div>' +
                        ' <div class="auth-jobs">' +
                        '  <div class="auth-jobs-item" onclick="apply(' + value['id'] + ',\'admin\')">مدیر</div>' +
                        '  <div class="auth-jobs-item" onclick="apply(' + value['id'] + ',\'assistant\')">معاون</div>' +
                        '  <div class="auth-jobs-item" onclick="apply(' + value['id'] + ',\'editor\')">ویراستار</div>' +
                        '  <div class="auth-jobs-item" onclick="apply(' + value['id'] + ',\'writer\')">نویسنده</div>' +
                        ' </div>' +
                        '</div>'
                );
            });
        }
        loading_hide();
    });
}
//****************************************************************************
// Get Authors
function getAuthors() {
    loading_show();
    var $data = JSON.stringify({
        command: 'getAuthors'
    });
    $.get('/admin/app/authors.php?r=' + Math.random() + '&q=' + $data, function ($data, $status) {
        $('#authors').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...<br><br>');
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
            $('#message').slideDown('slow');
        } else if ($data == 'identity_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            $('#message').slideDown('slow');
        } else if ($data == 'error') {
            $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...').slideDown('slow');
        } else if ($data == 'blog_connect_error') {
            $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.').slideDown('slow');
        } else if ($data == 'member_connect_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.').slideDown('slow');
        } else if ($data == 'job_connect_error') {
            $('#message').html('خطایی در دریافت اطلاعات رخ داده است! لطفا صفحه را دوباره بارگذاری کنید.')
                .slideDown('slow');
        } else {
            var $authors = JSON.parse($data);
            $('#authors').html('');
            $.each($authors, function (key, value) {
                var $c1,
                    $c2,
                    $c3,
                    $c4;
                $c1 = $c2 = $c3 = $c4 = 'auth-jobs-item';
                switch (value['role']) {
                    case 'admin':
                        $c1 = 'auth-jobs-selected';
                        break;
                    case 'assistant':
                        $c2 = 'auth-jobs-selected';
                        break;
                    case 'editor':
                        $c3 = 'auth-jobs-selected';
                        break;
                    case 'writer':
                        $c4 = 'auth-jobs-selected';
                        break;
                }
                $('#authors').append('<div class="auth">' +
                        ' <div class="auth-delete" onclick="del(' + key + ')"></div>' +
                        ' <div class="auth-info">' +
                        '  <span dir="rtl">' + value['nickname'] + '</span> ' +
                        '  <span dir="ltr">(' + value['email'] + ')</span>' +
                        ' </div>' +
                        ' <div class="auth-jobs">' +
                        '  <div class="' + $c1 + '" onclick="repost(' + key + ',\'admin\')">مدیر</div>' +
                        '  <div class="' + $c2 + '" onclick="repost(' + key + ',\'assistant\')">معاون</div>' +
                        '  <div class="' + $c3 + '" onclick="repost(' + key + ',\'editor\')">ویراستار</div>' +
                        '  <div class="' + $c4 + '" onclick="repost(' + key + ',\'writer\')">نویسنده</div>' +
                        ' </div>' +
                        '</div>'
                );
            });
        }
        loading_hide();
    });
}
//****************************************************************************
// Apply
function apply($id, $role) {
    loading_show();
    var $data = JSON.stringify({
        command: 'apply',
        id: $id,
        role: $role
    });
    $.get('/admin/app/authors.php?r=' + Math.random() + '&q=' + $data, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
        } else if ($data == 'identity_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
        } else if ($data == 'error') {
            $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
        } else if ($data == 'permission_error') {
            $('#message').html('شما دسترسی لازم برای تایید نویسندگان را ندارید.');
        } else if ($data == 'blog_connect_error') {
            $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
        } else if ($data == 'member_connect_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
        } else if ($data == 'job_connect_error' || $data == 'apply_connect_error' || $data == 'apply_delete_error') {
            $('#message').html('خطایی در دریافت اطلاعات رخ داده است! لطفا صفحه را دوباره بارگذاری کنید.')
                .slideDown('slow');
        } else if ($data == 'done') {
            $('#message').html('نویسنده با موفقیت تایید شد.');
            getApplys();
            getAuthors();
        } else {
            $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
            alert($data);
        }
        loading_hide();
        $('#message').slideDown('slow');
    });
}
//****************************************************************************
// Repost
function repost($id, $role) {
    loading_show();
    var $data = JSON.stringify({
        command: 'repost',
        id: $id,
        role: $role
    });
    $.get('/admin/app/authors.php?r=' + Math.random() + '&q=' + $data, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
        } else if ($data == 'identity_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
        } else if ($data == 'error') {
            $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
        } else if ($data == 'permission_error') {
            $('#message').html('شما دسترسی لازم برای تغییر جایگاه نویسندگان ندارید.');
        } else if ($data == 'disrate_error') {
            $('#message').html('تغییر جایگاه مدیران تنها توسط خودشان ممکن است.');
        } else if ($data == 'ace_error') {
            $('#message').html('پیش از برکنارشدن باید یک جانشین (مدیر) انتخاب کنید.');
        } else if ($data == 'blog_connect_error') {
            $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
        } else if ($data == 'member_connect_error') {
            $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
        } else if ($data == 'job_connect_error') {
            $('#message').html('خطایی در دریافت اطلاعات رخ داده است! لطفا صفحه را دوباره بارگذاری کنید.')
                .slideDown('slow');
        } else if ($data == 'done') {
            $('#message').html('تغییر جایگاه با موفقیت انجام شد.');
            getApplys();
            getAuthors();
        } else {
            $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
            alert($data);
        }
        loading_hide();
        $('#message').slideDown('slow');
    });
}
//****************************************************************************
// Reject request
function reject($id) {
    if (confirm('از رد کردن این درخواست دل‌استوار هستید؟')) {
        loading_show();
        var $data = JSON.stringify({
            command: 'reject',
            id: $id
        });
        $.get('/admin/app/authors.php?r=' + Math.random() + '&q=' + $data, function ($data, $status) {
            if ($status != 'success') {
                $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
            } else if ($data == 'identity_error') {
                $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            } else if ($data == 'error') {
                $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
            } else if ($data == 'permission_error') {
                $('#message').html('شما دسترسی لازم برای رد کردن درخواست ها را ندارید.');
            } else if ($data == 'blog_connect_error') {
                $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            } else if ($data == 'member_connect_error') {
                $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            } else if ($data == 'apply_connect_error' || $data == 'apply_delete_error' ) {
                $('#message').html('خطایی در دریافت اطلاعات رخ داده است! لطفا صفحه را دوباره بارگذاری کنید.')
                    .slideDown('slow');
            } else if ($data == 'done') {
                $('#message').html('درخواست عضویت با موفقیت پاک شد.');
                getApplys();
            } else {
                $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
                alert($data);
            }
            loading_hide();
            $('#message').slideDown('slow');
        });
    }
}
//****************************************************************************
// Delete member
function del($id) {
    if (confirm('از برکنار کردن این نویسنده دل‌استوار هستید؟')) {
        loading_show();
        var $data = JSON.stringify({
            command: 'delete',
            id: $id
        });
        $.get('/admin/app/authors.php?r=' + Math.random() + '&q=' + $data, function ($data, $status) {
            if ($status != 'success') {
                $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
            } else if ($data == 'identity_error') {
                $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            } else if ($data == 'error') {
                $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
            } else if ($data == 'permission_error') {
                $('#message').html('شما دسترسی لازم برای برکنار کردن نویسندگان را ندارید.');
            } else if ($data == 'ace_error') {
                $('#message').html('پیش از برکنارشدن باید یک جانشین (مدیر) انتخاب کنید.');
            } else if ($data == 'regicide_error') {
                $('#message').html('برکنار شدن مدیران تنها توسط خودشان ممکن است.');
            } else if ($data == 'blog_connect_error') {
                $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            } else if ($data == 'member_connect_error') {
                $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
            } else if ($data == 'job_connect_error' || $data == 'job_delete_error' ) {
                $('#message').html('خطایی در دریافت اطلاعات رخ داده است! لطفا صفحه را دوباره بارگذاری کنید.')
                    .slideDown('slow');
            } else if ($data == 'done') {
                $('#message').html('نویسنده با موفقیت برکنار شد.');
                window.location = 'authors.php';
            } else {
                $('#message').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
                alert($data);
            }
            loading_hide();
            $('#message').slideDown('slow');
        });
    }
}
//****************************************************************************
// Main
$(document).ready(function () {
    getApplys();
    getAuthors();
    loading_hide();
});