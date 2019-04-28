//****************************************************************************
// Filter Input
function filter(str) {
    if (typeof(str) == 'string') {
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
// Recaptcha
function recaptcha() {
    $('#contact_captcha') .css('background-image', 'url(/shared/image/captcha2.php?' + Math.random() + ')');
}
recaptcha();

//****************************************************************************
// Loading
var $loading_num = 0;

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
// Submit
$('#contact_submit') .click(function () {
    loading_show();
    $("#message").slideUp();
    var $info = JSON.stringify({
        command: 'send',
        author: filter($('#contact_author') .val()),
        email: filter($('#contact_email') .val()),
        message: filter($('#contact_message') .val()),
        captcha: filter($('#contact_captcha') .val())
    });
    $.get('/shared/theme/contact.php?r=' + Math.random() + '&q=' + $info, function ($data, $status) {
        if ($status != 'success') {
            alert('خطایی در تماس با سرور پیش آمد. لطفا صفحه را دوباره بارگذاری کنید.')
        } else {
            switch ($data) {
                case 'captcha_wrong':
                    $("#message").html("لطفا کد امنیتی را درست وارد کنید.").slideDown("slow");
                    break;
                case 'author_absent':
                    $("#message").html("لطفا نام خود را بنویسید.").slideDown("slow");
                    break;
                case 'email_absent':
                    $("#message").html("لطفا ایمیل خود را بنویسید.").slideDown("slow");
                    break;
                case 'email_invalid':
                    $("#message").html("لطفا ایمیل خود را درست بنویسید.").slideDown("slow");
                    break;
                case 'message_absent':
                    $("#message").html("پیام خود را فراموش کرده اید!").slideDown("slow");
                    break;
                case 'error':
                    $("#message").html("یک خطای سیستمی رخ داده است. لطفا بعدا تلاش کنید.").slideDown("slow");
                    break;
                case 'done':
                    $("#message").html("پیام شما به درستی فرستاده شد.").slideDown("slow");
                    break;
                default:
                    $("#message").html("یک خطای سیستمی رخ داده است. لطفا بعدا تلاش کنید.").slideDown("slow");
            }
        }
        recaptcha();
        loading_hide();
    });
});
//****************************************************************************
document.getElementById('contact_author') .onfocus = function () {
    if (document.getElementById('contact_author') .value == 'نام') {
        document.getElementById('contact_author') .value = '';
    }
};
document.getElementById('contact_author') .onblur = function () {
    if (document.getElementById('contact_author') .value == '') {
        document.getElementById('contact_author') .value = 'نام';
    }
};
document.getElementById('contact_email') .onfocus = function () {
    if (document.getElementById('contact_email') .value == 'ایمیل') {
        document.getElementById('contact_email') .value = '';
    }
};
document.getElementById('contact_email') .onblur = function () {
    if (document.getElementById('contact_email') .value == '') {
        document.getElementById('contact_email') .value = 'ایمیل';
    }
};
document.getElementById('contact_message') .onfocus = function () {
    if (document.getElementById('contact_message') .value == 'پیام') {
        document.getElementById('contact_message') .value = '';
    }
};
document.getElementById('contact_message') .onblur = function () {
    if (document.getElementById('contact_message') .value == '') {
        document.getElementById('contact_message') .value = 'پیام';
    }
};
document.getElementById('contact_captcha') .onfocus = function () {
    if (document.getElementById('contact_captcha') .value == 'کد امنیتی') {
        document.getElementById('contact_captcha') .value = '';
    }
};
document.getElementById('contact_captcha') .onblur = function () {
    if (document.getElementById('contact_captcha') .value == '') {
        document.getElementById('contact_captcha') .value = 'کد امنیتی';
    }
};
//****************************************************************************