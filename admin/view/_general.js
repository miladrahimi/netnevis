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
// Filterback Output
function filterback(str) {
    if (typeof(str) == 'string') {
        str = str.replace(/&amp;/g, '&');
        str = str.replace(/&quot;/g, '"');
        str = str.replace(/&#039;/g, "'");
        str = str.replace(/&lt;/g, '<');
        str = str.replace(/&gt;/g, '>');
        str = str.replace(/%2F/g, '/');
        str = str.replace(/%5C/g, "\\");
        str = str.replace(/<br>/gm, '\n');
    }
    return decodeURIComponent(str);
}
//****************************************************************************
// Loading

var $loading_num = 1;

function loading_show() {
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
// Recaptcha
function recaptcha() {
    var $url = '/shared/image/captcha.php?r=' + Math.random();
    $('.captcha').css('background-image', 'url(\'' + $url + '\')');
    $('.captcha').val('کد امنیتی');
}
recaptcha();
//****************************************************************************
// Captcha
$(".captcha").focus(function() {
    if ($(this).val() == "کد امنیتی") $(this).val("");
}).blur(function() {
    if ($(this).val() == "") $(this).val("کد امنیتی");
}).val("کد امنیتی");
//****************************************************************************
// Button1
$(".button1, .button2, .button3").focus(function() {
    $(this).blur();
});