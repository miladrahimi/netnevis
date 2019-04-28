<?php

class Captcha {
    //************************************************************
    public static function check($captcha) {
        // Check Input
        if (empty($captcha))
            return 0;
        $captcha = strtoupper(trim($captcha));
        // Check Session
        if (!isset($_SESSION))
            session_start();
        if (empty($_SESSION["captcha"]))
            return 0;
        // Comparison
        if ($captcha != $_SESSION["captcha"])
            return 0;
        // No Problem!
        return 1;
    }
    //************************************************************
}