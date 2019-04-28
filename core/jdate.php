<?php

class JDate {
    //************************************************************
    public static function date($format, $timestamp=null) {
        include_once dirname(__FILE__) . "/jdf.php";
        if($timestamp==null) $timestamp = time();
        return jdate($format,$timestamp,'','','en');
    }
    //************************************************************
    public static function mktime($hour, $minute, $second, $month, $day, $year) {
        include_once dirname(__FILE__) . "/jdf.php";
        return jmktime($hour,$minute,$second,$month,$day,$year);
    }
    //************************************************************
}