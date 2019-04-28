//****************************************************************************
// TinyMCE
function setup($content) {
    tinymce.init({
        selector: '#post_content',
        theme: 'modern',
        menubar: false,
        relative_urls: false,
        plugins: [
            'autolink link image lists charmap preview hr anchor pagebreak spellchecker headlines upload tag hashtag',
            'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
            'save table contextmenu directionality emoticons template paste textcolor'
        ],
        content_css: '/shared/tinymce/style.css',
        toolbar:
                'insertfile undo redo | ' +
                'bold italic underline | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'ltr rtl | ' +
                'bullist numlist outdent indent | ' +
                'emoticons | ' +
                'link image media upload | ' +
                'tag hashtag | table | style-p style-h2 style-h3 style-code',
        init_instance_callback: function () {
            tinyMCE.activeEditor.setContent($content);
        }
    });
}
//****************************************************************************
// New Post
function newPost() {
    loading_show();
    $('#message').slideUp('slow');
    var $cmt = 0;
    if ($('#post_comment').val() == 'دیدگاه‌ها فورا منتشر شوند.') {
        $cmt = 0;
    } else if ($('#post_comment').val() == 'دیدگاه‌ها پس از تایید منتشر شوند.') {
        $cmt = 1;
    } else {
        $cmt = 2;
    }
    var $info = {
        command: 'newPost',
        title: $('#post_title').val(),
        content: tinymce.get('post_content').getContent(),
        cat: $('#post_cat').val(),
        comment: $cmt,
        captcha: $('#publish_captcha').val()
    };
    $.post('/admin/app/post.php', $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطا در تماس با سرور. لطفا صفحه را دوباره بارگذاری کنید.');
        } else {
            $('#message').html($data);
            switch ($data) {
                case 'captcha_error':
                    $('#message').html('لطفا کد امنیتی را درست بنویسید.');
                    break;
                case 'identity_error':
                    $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'permission_error':
                    $('#message').html('شما دسترسی لازم برای انتشار پست ندارید.');
                    break;
                case 'member_connect_error':
                    $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'blog_connect_error':
                    $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'error':
                    $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
                    break;
                case 'done':
                    $('#message').html('پست به درستی منتشر شد.');
                    window.location = 'posts.php';
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
}
//****************************************************************************
// EditPost
function editPost($id) {
    loading_show();
    $('#message').slideUp('slow');
    var $cmt = 0;
    if ($('#post_comment').val() == 'دیدگاه‌ها فورا منتشر شوند.') {
        $cmt = 0;
    } else if ($('#post_comment').val() == 'دیدگاه‌ها پس از تایید منتشر شوند.') {
        $cmt = 1;
    } else {
        $cmt = 2;
    }
    var $info = {
        command: 'editPost',
        id: $id,
        title: $('#post_title').val(),
        content: tinymce.get('post_content').getContent(),
        cat: $('#post_cat').val(),
        comment: $cmt,
        captcha: $('#publish_captcha').val()
    };
    $.post('/admin/app/post.php', $info, function ($data, $status) {
        if ($status != 'success') {
            $('#message').html('خطا در تماس با سرور. لطفا صفحه را دوباره بارگذاری کنید.');
        } else {
            $('#message').html($data);
            switch ($data) {
                case 'captcha_error':
                    $('#message').html('لطفا کد امنیتی را درست بنویسید.');
                    break;
                case 'identity_error':
                    $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'permission_error':
                    $('#message').html('شما دسترسی لازم برای ویرایش این پست را ندارید.');
                    break;
                case 'post_edit_error':
                    $('#message').html('پست ویرایش نشد!');
                    break;
                case 'member_connect_error':
                    $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'blog_connect_error':
                    $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
                    break;
                case 'error':
                    $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
                    break;
                case 'done':
                    $('#message').html('پست به درستی منتشر شد.');
                    window.location = '#message';
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
}
//****************************************************************************
// Publish
$('#publish').click(function () {
    if (parseInt($(this).attr('data-id'))) {
        editPost(parseInt($(this).attr('data-id')));
    } else {
        newPost();
    }
});
//****************************************************************************
// Main
$(document).ready(function () {
    var $id = (document.location.search) ? parseInt((document.location.search).substr(1))  : 0;
    $('#publish').attr('data-id', $id);
    if ($id) {
        var $info = {
            command: 'getPost',
            id: $id
        };
        $.post('/admin/app/post.php', $info, function ($data, $status) {
            if ($status != 'success') {
                $('#message').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
                $('#message').slideDown('slow');
            } else if ($data == 'identity_error') {
                $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.');
                $('#message').slideDown('slow');
            } else if ($data == 'error') {
                $('#message').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...').slideDown('slow');
            } else if ($data == 'permission_error') {
                $('#message').html('شما اجاز ویرایش کردن این پست را ندارید.').slideDown('slow');
            } else if ($data == 'member_connect_error') {
                $('#message').html('هویت شما شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.').slideDown('slow');
            } else if ($data == 'blog_connect_error') {
                $('#message').html('وبلاگ شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید.').slideDown('slow');
            } else {
                var $post = JSON.parse($data);
                $('#post_title').val($post['title']);
                setup($post['content']);
                $('#post_cat').val($post['cat']);
                $('.menu-item').removeClass('menu-selected');
            }
            recaptcha();
            loading_hide();
        });
    } else {
        setup('<p dir="rtl"></p>');
        loading_hide();
    }
});