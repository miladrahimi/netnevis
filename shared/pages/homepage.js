//****************************************************************************
// Filter Input
function filter(str) {
    if (typeof (str) == 'string') {
        str = str.replace(/&/g, '&amp;');
        str = str.replace(/"/g, '&quot;');
        str = str.replace(/'/g, '&#039;');
        str = str.replace(/</g, '&lt;');
        str = str.replace(/>/g, '&gt;');
        str = str.replace(/(\r\n|\n|\r)/gm, ' ');
    }
    return encodeURIComponent(str);
}
//****************************************************************************
// Subdomain Field
$('#main_subdomain').focus(function () {
    if ($(this).val() == 'example') $(this).val('');
}).blur(function () {
    if ($(this).val() == '') $(this).val('example');
});
//****************************************************************************
// Check Subdomain Button
$('#main_lookup').focus(function () {
    $(this).blur();
}).mouseover(function() {
    $(this).addClass("main-lookup-hover");
}).mouseout(function() {
    $(this).removeClass("main-lookup-hover");
}).click(function () {
    $(this).attr('disabled',true);
    $(this).addClass('main-lookup-loading');
    $('#main_ok').slideUp('slow');
    $('#main_err').slideUp('slow');
    var $data = JSON.stringify({
        command: 'lookup',
        subdomain: filter($('#main_subdomain').val())
    });
    $.get('/shared/pages/homepage.php?r=' + Math.random() + '&q=' + $data, function ($data, $status) {
        if ($status != 'success') {
            $('#main_err').html('خطایی در ارتباط با سرور رخ داده‌است. لطفا صفحه را دوباره بارگذاری‌کنید.');
            $('#main_err').slideDown('slow');
        } else if ($data == 'ok') {
            $('#main_ok').slideDown('slow');
        } else {
            switch ($data) {
                case 'reserved':
                    $('#main_err').html('این زیردامنه رزرو شده است. می توانید یکی دیگر را امتحان کنید...');
                    break;
                case 'subdomain_wrong':
                    $('#main_err').html('زیردامنه می‌تواند از نویسه های انگلیسی، شماره‌ها و یک - باشد.');
                    break;
                case 'subdomain_length':
                    $('#main_err').html('لطفا زیردامنه‌ای با پنج تا بیست نویسه بنویسید.');
                    break;
                case 'subdomain_forbidden':
                    $('#main_err').html('این زیردامنه مسدود است. لطفا یکی دیگر را امتحان کنید...');
                    break;
                case 'direct_access_error':
                    $('#main_err').html('لطفا کوکی مرورگر را فعال کنید.');
                    break;
                case 'error':
                    $('#main_err').html('خطایی در اجرای برنامه رخ داده است. لطفا بعدا تلاش کنید..');
                    break;
                default:
                    $('#main_err').html('یک خطای ناشناخته رخ داده است. لطفا به سایت اطلاع دهید.');
                    alert($data);
            }
            $('#main_err').slideDown('slow');
        }
        $('#main_lookup').removeClass('main-lookup-loading');
        $('#main_lookup').attr('disabled',false);
    });
});
//****************************************************************************
// News Item: Go to Weblog
function goWeblog($url) {
    window.open($url);
}