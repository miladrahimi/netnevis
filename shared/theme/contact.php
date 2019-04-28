<?php

// Autoload
function blog_ajax_contact_autoload($class) {
    include_once dirname(__FILE__) . "/../../core/" . strtolower($class) . ".php";
}
spl_autoload_register("blog_ajax_contact_autoload");

// Get query
$q = empty($_GET["q"]) ? "" : trim($_GET["q"]);
$query = Security::JSONInput($q);

// Send
if ($query->command == "send") {
    try {
        if (!Captcha::check($query->captcha)) {
            exit("captcha_wrong");
        }
        if (empty($query->author)) {
            exit("author_absent");
        }
        if (empty($query->email)) {
            exit("email_absent");
        }
        if (empty($query->message)) {
            exit("message_absent");
        }
        if (!filter_var($query->email, FILTER_VALIDATE_EMAIL)) {
            exit("email_invalid");
        }
        $blog = new Blog();
        $blog->detect();
        $blog->connect();
        // Send E-Mail:
        $to      = $blog->getEmail();
        $subject = $blog->getTitle();
        $message = 'Contact us form of blog: ' . $blog->getURL() . '<br>';
        $message .= 'Author: ' . $query->author . '<br>';
        $message .= 'E-Mail: ' . $query->email . '<br>';
        $message .= 'Message: ' . '<br>';
        $message .= htmlspecialchars($query->message);
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From:' . $query->email . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        $r = mail($to, $subject, $message, $headers);
        if (!$r)
            throw new Exception("err.contact_mail_sending");
        exit("done");
    }
    catch (Exception $e) {
        exit(Reporter::report($e, true));
    }
}