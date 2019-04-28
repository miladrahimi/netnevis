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
//****************************************************************
// Recaptcha
function recaptcha() {
    $('#comment_captcha').css('background-image', 'url(/shared/image/captcha2.php?' + Math.random() + ')');
    $('#comment_captcha').val('کد');
}
recaptcha();
//****************************************************************************
// Check Like
function like($pid) {
    var $onclick = document.getElementById('like_' + $pid).onclick;
    document.getElementById('like_' + $pid).onclick = null;
    var $link = 'like_' + $pid;
    var $numb = 'like_num_' + $pid;
    var $info = JSON.stringify({
        command: 'like',
        id: filter($pid)
    });
    $.get('/shared/theme/post.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            alert('خطا در تماس با سرور. لطفا صفحه را دوباره بارگذاری کنید.');
        } else {
            switch ($data) {
                case 'like':
                    $('#' + $link).html('پسندیدن');
                    $('#' + $numb).html(parseInt($('#' + $numb).html()) - 1);
                    break;
                case 'unlike':
                    $('#' + $link).html('نمی‌پسندم');
                    $('#' + $numb).html(parseInt($('#' + $numb).html()) + 1);
                    break;
                case 'error':
                    alert('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید...');
                    break;
                default:
                    alert('ERROR: ' + $data);
            }
        }
        document.getElementById('like_' + $pid).onclick = $onclick;
    });
}
//****************************************************************************
// Go Share
function goShare($link) {
    window.location = $link
}
//****************************************************************************
// Share
function share($site,$title) {
    var $link = window.location;
    window.open($site+'/shared/app/sharer/?url=' + $link + '&title=' + $title, '', 'width=600,height=100');
}
//****************************************************************************
// Go Comment
function goComments($link) {
    window.location = $link
}
//****************************************************************************
// Comment
function comment($pid)
{
    $('#comment_send').val('').addClass('comment-new-send-loading');
    $('#comment_response').fadeOut('slow');
    var $info = JSON.stringify({
        command: 'comment',
        post: filter($pid),
        message: filter($('#comment_message').val()),
        author: filter($('#comment_author').val()),
        email: filter($('#comment_email').val()),
        captcha: filter($('#comment_captcha').val())
    });
    $.get('/shared/theme/post.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            $('#comment_response').val('لطفا صفحه را دوباره بارگذاری کنید.').fadeIn('slow');
        } else {
            switch ($data) {
                case 'captcha_wrong':
                    $('#comment_response').val('لطفا کد امنیتی را درست بنویسید.').fadeIn('slow');
                    break;
                case 'email_wrong':
                    $('#comment_response').val('لطفا ایمیل خود را درست بنویسید.').fadeIn('slow');
                    break;
                case 'message_empty':
                    $('#comment_response').val('پیام خود را فراموش کرده اید!').fadeIn('slow');
                    break;
                case 'author_empty':
                    $('#comment_response').val('لطفا نام خود را بنویسید.').fadeIn('slow');
                    break;
                case 'error':
                    $('#comment_response').val('یک خطای سیستمی رخ داده است. لطفا بعدا تلاش کنید.').fadeIn('slow');
                    break;
                case 'done':
                    $('#comment_response').val('پیام به درستی فرستاده شده').fadeIn('slow');
                    var $info = JSON.stringify({
                        command: 'comment_method',
                        post: filter($pid)
                    });
                    $.get('/shared/theme/post.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
                        if ($status != 'success') {
                            $('#comment_response').val('لطفا صفحه را دوباره بارگذاری کنید.').fadeIn('slow');
                        } else {
                            if ($data == 'yes') {
                                var cmts = document.getElementById('comment_new');
                                cmts.insertAdjacentHTML('afterend', '<div class="comment-item">' +
                                        '<div class="comment-avatar"></div>' +
                                        '<div class="comment-info">' +
                                        ' <span class="comment-author">' +
                                        decodeURIComponent(filter($('#comment_author').val())) +
                                        '</span>' +
                                        ' <span class="comment-time">(چند لحظه پیش)</span></div>' +
                                        '<div class="comment-message">' +
                                        decodeURIComponent(filter($('#comment_message').val())) +
                                        '</div>' +
                                        '</div>'
                                );
                                $('#comment_response').fadeOut('slow');
                            } else {
                                $('#comment_response').val('دیدگاه پس از تایید نمایش داده خواهد شد.').fadeIn('slow');
                            }
                            document.getElementById('comment_message').value = 'دیدگاه خود را بنویسید...';
                            document.getElementById('comment_author').value = 'نام';
                            document.getElementById('comment_email').value = 'ایمیل';
                            document.getElementById('comment_captcha').value = 'کد';
                            document.getElementById('comment_num').innerHTML++;
                        }
                    });
                    break;
                default:
                    alert($data);
            }
            recaptcha();
            $('#comment_send').val('فرستادن').removeClass('comment-new-send-loading');
        }
    });
}
//****************************************************************************
// Comment Send
$('#comment_send').focus(function () {
    $(this).blur();
}).mouseover(function() {
    $(this).addClass("comment-new-send-hover");
}).mouseout(function() {
    $(this).removeClass("comment-new-send-hover");
});
//****************************************************************************
if (document.getElementById('comment_message')) {
    document.getElementById('comment_message').onfocus = function () {
        if (document.getElementById('comment_message').value == 'دیدگاه خود را بنویسید...') {
            document.getElementById('comment_message').value = '';
        }
    };
    document.getElementById('comment_message').onblur = function () {
        if (document.getElementById('comment_message').value == '') {
            document.getElementById('comment_message').value = 'دیدگاه خود را بنویسید...';
        }
    };
    document.getElementById('comment_author').onfocus = function () {
        if (document.getElementById('comment_author').value == 'نام') {
            document.getElementById('comment_author').value = '';
        }
    };
    document.getElementById('comment_author').onblur = function () {
        if (document.getElementById('comment_author').value == '') {
            document.getElementById('comment_author').value = 'نام';
        }
    };
    document.getElementById('comment_email').onfocus = function () {
        if (document.getElementById('comment_email').value == 'ایمیل') {
            document.getElementById('comment_email').value = '';
        }
    };
    document.getElementById('comment_email').onblur = function () {
        if (document.getElementById('comment_email').value == '') {
            document.getElementById('comment_email').value = 'ایمیل';
        }
    };
    document.getElementById('comment_captcha').onfocus = function () {
        if (document.getElementById('comment_captcha').value == 'کد') {
            document.getElementById('comment_captcha').value = '';
        }
    };
    document.getElementById('comment_captcha').onblur = function () {
        if (document.getElementById('comment_captcha').value == '') {
            document.getElementById('comment_captcha').value = 'کد';
        }
    };
}
//****************************************************************************