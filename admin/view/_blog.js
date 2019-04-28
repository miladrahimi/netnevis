loading_show();
var $info = JSON.stringify({
    command: 'getBlogAddress'
});
$.get('/admin/app/_blog.php?r=' + Math.random() + '&q=' + $info, function($data, $status) {
    if ($status != 'success') {
        $('#blog').html('خطا در تماس با سرور. لطفا صفحه را دوباره بارگذاری کنید.');
    } else if ($data == 'account_error') {
        $('#blog').html('کاربر شناسایی نشد! لطفا صفحه را دوباره بارگذاری کنید...');
    } else if ($data == 'error') {
        $('#blog').html('خطای سیستمی. لطفا بعدا امتحان کنید...');
    } else {
        var $url = '<a href="' + $data + '" target="_blank">' + $data + '</a>';
        $('#blog').html($url);
    }
    loading_hide();
});