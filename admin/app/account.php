<?php

// Autoload
function admin_app_account_autoload($class) {
    include_once dirname(__FILE__) . "/../../core/" . strtolower($class) . ".php";
}
spl_autoload_register("admin_app_account_autoload");

// Get query
$q = empty($_GET["q"]) ? "" : trim($_GET["q"]);
$query = Security::JSONInput($q);

// apply
if ($query->command == "apply") {
    if (!Member::detect())
        exit("identity_error");
    $captcha = empty($query->captcha) ? "" : $query->captcha;
    if (!Captcha::check($captcha))
        exit("captcha_error");
    $subdomain = empty($query->subdomain) ? "" : $query->subdomain;
    try {
        $blog = new Blog();
        $blog->setSubdomain($subdomain);
        $blog->connectBySubdomain();
        if (!($blog->getID()))
            exit("blog_wrong");
        $job = new Job();
        $job->setMember(Member::detect());
        $job->setBlog($blog->getID());
        try {
            $job->checkRole();
            exit("role_exists");
        } catch (Exception $e) {
            if ($e->getMessage() != "msg.job_connect_error")
                throw new Exception($e->getMessage());
        }
        $apply = new Apply();
        $apply->setBlog($blog->getID());
        $apply->setMember(Member::detect());
        if($apply->exists())
            exit("request_exists");
        $apply->create();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// Confirm
if ($query->command == "confirm") {
    if (Member::detect())
        exit("identity_error");
    $captcha = empty($query->captcha) ? "" : $query->captcha;
    if (!Captcha::check($captcha))
        exit("captcha_error");
    $code = empty($query->code) ? "" : $query->code;
    try {
        $memberc = new MemberConfirm();
        $memberc->setCode($code);
        $memberc->connectByCode();
        $memberc->delete();
        $member = new Member();
        $member->setID($memberc->getMember());
        $member->connectByID();
        $member->setEmail($memberc->getEmail());
        $member->setStatus(1);
        $member->edit();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// createWeblog
if ($query->command == "createWeblog") {
    if (!Member::detect())
        exit("identity_error");
    $captcha = empty($query->captcha) ? "" : $query->captcha;
    if (!Captcha::check($captcha))
        exit("captcha_error");
    $subdomain = empty($query->subdomain) ? "" : $query->subdomain;
    $title     = empty($query->title) ? "" : $query->title;
    try {
        $member = new Member();
        $member->identify();
        $blog = new Blog();
        $blog->setEmail($member->getEmail());
        $blog->setSubdomain($subdomain);
        $blog->setTitle($title);
        $blog->connectBySubdomain();
        if ($blog->getID())
            exit("subdomain_duplicate");
        $blog->create();
        $job = new Job();
        $job->setMember($member->getID());
        $job->setBlog($blog->getID());
        $job->setRole("admin");
        $job->create();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// editProfile
if ($query->command == "editProfile") {
    if (!Member::detect())
        exit("identity_error");
    $captcha = empty($query->captcha) ? "" : $query->captcha;
    if (!Captcha::check($captcha))
        exit("captcha_error");
    $nickname   = empty($query->nickname) ? "" : $query->nickname;
    $email      = empty($query->email) ? "" : $query->email;
    $googleplus = empty($query->googleplus) ? "https://google.com/+NetNevisOfficial" : $query->googleplus;
    $avatar     = empty($query->avatar) ? "http://netnevis.ir/shared/image/sample_avatar.jpg" : $query->avatar;
    $about      = empty($query->about) ? "" : $query->about;
    $password1  = empty($query->password1) ? "" : $query->password1;
    $password2  = empty($query->password2) ? "" : $query->password2;
    $password3  = empty($query->password3) ? "" : $query->password3;
    try {
        $member = new Member();
        $member->identify();
        $member->setNickname($nickname);
        $member->setGooglePlus($googleplus);
        $member->setAvatar($avatar);
        $member->setAbout($about);
        if ($password1 != "password") {
            if ($member->getPassword() != Security::hashPassword($password1)) {
                exit("password_error");
            }
            if ($password2 != $password3)
                exit("password_different");
            $member->setPassword($password2);
        }
        if ($member->getEmail() != $email) {
            $membere = new Member();
            $membere->setEmail($email);
            if($membere->isRegistered())
                exit("email_duplicate");
            $memberc = new MemberConfirm();
            $memberc->setEmail($email);
            $memberc->setMember($member->getID());
            $memberc->create();
            // Send E-Mail:
            $to      = $email;
            $subject = 'NetNevis: Email Confirmation';
            $message = 'You can confirm your email with following link and code. <br>';
            $message .= 'Link: <a href="http://netnevis.ir/admin/account.php?confirm">';
            $message .= 'http://netnevis.ir/admin/account.php?confirm</a> <br>';
            $message .= 'Code: ' . $memberc->getCode() . ' <br><br>';
            $message .= '(C) NetNevis.com, All rights reserved.';
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'From: NetNevis <no-reply@netnevis.ir>' . "\r\n";
            $headers .= 'X-Mailer: PHP/' . phpversion();
            $r = mail($to, $subject, $message, $headers);
            if (!$r)
                throw new Exception("err.mail_error:Section=EditProfile,To=" . $to);

        }
        $member->edit();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// getProfile
if ($query->command == "getProfile") {
    try {
        $member = new Member();
        $member->setID(Member::detect());
        $member->connectByID();
        $profile = array(
            "nickname" => $member->getNickname(),
            "email" => $member->getEmail(),
            "about" => $member->getAbout(),
            "avatar" => $member->getAvatar(),
            "googleplus" => $member->getGooglePlus()
        );
        exit(json_encode($profile));
    }
    catch (Exception $e) {
        exit(Reporter::report($e, true));
    }
}

// getWeblogs
if ($query->command == "getWeblogs") {
    try {
        $member = new Member();
        $member->identify();
        $r = $member->getBlogs();
        $b = array();
        foreach ($r as $job_id => $blog_id) {
            $blog = new Blog();
            $blog->setID($blog_id);
            $blog->connect();
            $b[$blog_id]              = array();
            $b[$blog_id]["subdomain"] = $blog->getSubdomain();
            $b[$blog_id]["logo"]      = $blog->getLogo();
            $b[$blog_id]["title"]     = $blog->getTitle();
            $job                      = new Job();
            $job->setID($job_id);
            $job->connectByID();
            $b[$blog_id]["role"] = $job->getRole();
        }
        exit(json_encode($b));
    } catch (Exception $e) {
        exit(Reporter::report($e, true));
    }
}

// Identify
if ($query->command == "identify") {
    if (Member::detect())
        echo "yes";
    else
        echo "no";
}

// Lost
if ($query->command == "lost") {
    if (Member::detect())
        exit("identity_error");
    $captcha = empty($query->captcha) ? "" : $query->captcha;
    if (!Captcha::check($captcha))
        exit("captcha_error");
    $email = empty($query->email) ? "" : $query->email;
    try {
        $member = new Member();
        $member->setEmail($email);
        $member->connectByEmail();
        $memberlost = new MemberLost();
        $memberlost->setMember($member->getID());
        $memberlost->create();
        // Send E-Mail:
        $to      = $member->getEmail();
        $subject = 'NetNevis: Account Recovery';
        $message = 'Account recovery LINK: <br>';
        $message .= '<a href="http://netnevis.ir/admin/account.php?recovery">';
        $message .= 'http://netnevis.ir/admin/account.php?recovery</a> <br>';
        $message .= 'Account recovery CODE: <br>';
        $message .= $memberlost->getCode() . ' <br>';
        $message .= '(C) NetNevis.com, All rights reserved.';
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: NetNevis <no-reply@netnevis.ir>' . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        $r = mail($to, $subject, $message, $headers);
        if (!$r)
            throw new Exception("err.mail_error:Section=Lost,To=" . $to);
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// manageWeblog
if ($query->command == "manageWeblog") {
    $id = empty($query->id) ? 0 : (int) trim($query->id);
    try {
        $job = new Job();
        $job->setMember(Member::detect());
        $job->setBlog($id);
        $job->checkRole();
        if (!isset($_SESSION))
            session_start();
        $_SESSION["blog"] = $id;
        exit('done');
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// Recovery
if ($query->command == "recovery") {
    if (Member::detect())
        exit("identity_error");
    $captcha = empty($query->captcha) ? "" : $query->captcha;
    if (!Captcha::check($captcha))
        exit("captcha_error");
    $code      = empty($query->code) ? "" : $query->code;
    $password  = empty($query->password1) ? "" : $query->password1;
    $password2 = empty($query->password2) ? "" : $query->password2;
    try {
        $memberlost = new MemberLost();
        $memberlost->setCode($code);
        $memberlost->connectByCode();
        $member = new Member();
        $member->setID($memberlost->getMember());
        $member->connectByID();
        if ($password != $password2)
            throw new Exception("msg.password_different");
        $member->setPassword($password);
        $member->edit();
        $memberlost->delete();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// SignIn
if ($query->command == "signIn") {
    if (Member::detect())
        exit("identity_error");
    if(!isset($_SESSION))
        session_start();
    $sic = empty($_SESSION['signin']) ? 0 : (int) trim($_SESSION['signin']);
    if($sic > 1) {
        $captcha = empty($query->captcha) ? "" : $query->captcha;
        if (!Captcha::check($captcha))
            exit("captcha_error");
    }
    $_SESSION['signin'] = $sic + 1;
    $email    = empty($query->email) ? "" : $query->email;
    $password = empty($query->password) ? "" : $query->password;
    try {
        $member = new Member();
        $member->setEmail($email);
        $member->setPassword($password);
        $member->connectByAccount();
        if ($member->getStatus() == 0) {
            $memberc = new MemberConfirm();
            $memberc->setMember($member->getID());
            $memberc->setEmail($email);
            $memberc->create();
            // Send E-Mail:
            $to      = $email;
            $subject = 'NetNevis: Sign-up Confirmation';
            $message .= 'Welcome to NetNevis Blog Hosting... <br>';
            $message .= 'You can confirm your account with following link and code. <br>';
            $message .= 'Link: <a href="http://netnevis.ir/admin/account.php?confirm">';
            $message .= 'http://netnevis.ir/admin/account.php?confirm</a> <br>';
            $message .= 'Code: ' . $memberc->getCode() . ' <br><br>';
            $message .= 'After activating the account, You can sign-in with following informations. <br>';
            $message .= 'Username (E-mail): ' . $memberc->getEmail() . ' <br>';
            $message .= 'Password: [<em>Your Password</em>] <br><br>';
            $message .= '(C) NetNevis.com, All rights reserved.';
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'From: NetNevis <no-reply@netnevis.ir>' . "\r\n";
            $headers .= 'X-Mailer: PHP/' . phpversion();
            $r = mail($to, $subject, $message, $headers);
            if (!$r)
                throw new Exception("err.mail_error:Section=SigninActivation,To=" . $to);
            exit("activation_error");
        }
        $_SESSION["member"] = $member->getID();
        setcookie("email", $member->getEmail(), time() + 60 * 60 * 24 * 30, "/");
        $_SESSION['signin'] = 0;
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// signOut
if ($query->command == "signOut") {
    try {
        if(!isset($_SESSION))
            session_start();
        $_SESSION["member"] = 0;
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// SignUp
if (!empty($_POST["command"]) && $_POST["command"]=="signUp") {
    if (Member::detect())
        exit("identity_error");
    $captcha = empty($_POST["captcha"]) ? "" : $_POST["captcha"];
    if (!Captcha::check($captcha))
        exit("captcha_error");
    $nickname  = empty($_POST["nickname"]) ? "" : urldecode($_POST["nickname"]);
    $email     = empty($_POST["email"]) ? "" : urldecode($_POST["email"]);
    $password  = empty($_POST["password1"]) ? "" : urldecode($_POST["password1"]);
    $password2 = empty($_POST["password2"]) ? "" : urldecode($_POST["password2"]);
    try {
        $member = new Member();
        $member->setNickname($nickname);
        $member->setEmail($email);
        if ($password != $password2)
            exit("password_different");
        $member->setPassword($password);
        $member->create();
        $memberc = new MemberConfirm();
        $memberc->setMember($member->getID());
        $memberc->setEmail($email);
        $memberc->create();
        // Send E-Mail:
        $to      = $email;
        $subject = 'NetNevis: Sign-up Confirmation';
        $message = 'Welcome to NetNevis Blog Hosting... <br>';
        $message .= 'You can confirm your account with following link and code. <br>';
        $message .= 'Link: <a href="http://netnevis.ir/admin/account.php?confirm">';
        $message .= 'http://netnevis.ir/admin/account.php?confirm</a> <br>';
        $message .= 'Code: ' . $memberc->getCode() . ' <br><br>';
        $message .= 'After activating the account, You can sign-in with following informations. <br>';
        $message .= 'Username (E-mail): ' . $memberc->getEmail() . ' <br>';
        $message .= 'Password: [<em>Your Password</em>] <br><br>';
        $message .= '(C) NetNevis.com, All rights reserved.';
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: NetNevis <no-reply@netnevis.ir>' . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        $r = mail($to, $subject, $message, $headers);
        if (!$r)
            throw new Exception("err.mail_error:Section=Singup,To=" . $to);
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}