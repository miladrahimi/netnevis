<?php
$url = $_GET['url'];
$title = $_GET['title'];
$f = 'https://www.facebook.com/sharer.php?u=' . $url . '&t=' . $title;
$t = 'http://twitter.com/intent/tweet?source=sharethiscom&text=' . $title . '&url=' . $url;
$g = 'https://plus.google.com/share?url=' . $url;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>نت نویس | به‌اشتراک‌گذاری</title>
    <meta name="robots" content="nofollow">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="networks">
        <img src="/shared/image/facebook24.png" alt="Facebook" onclick="window.open('<?php echo $f; ?>');">
        <img src="/shared/image/twitter24.png" alt="Twitter" onclick="window.open('<?php echo $t; ?>');">
        <img src="/shared/image/googleplus24.png" alt="Google+" onclick="window.open('<?php echo $g; ?>');">
    </div>
    <script src="style.js" type="text/javascript"></script>
</body>

</html>