<?php

class Security {
    //************************************************************
    public static function filterInput() {
        $query = array();
        if(empty($_POST))
            return $query;
        if(!is_array($_POST))
            return $query;
        foreach($_POST as $key=>$value)
            $query[$key] = $value;
        return ($query);
    }
    //************************************************************
    public static function hashPassword($password) {
        $password = substr($password, 1);
        $password = hash('whirlpool', "milad" . $password . "rahimi");
        return $password;
    }
    //************************************************************
    public static function JSONInput($q) {
        if(empty($q))
            $q = '{"command":""}';
        $query = json_decode($q);
        function filter(&$value) {
            $value = str_replace("&amp;","&",$value);
            $value = str_replace("&quot;","\"",$value);
            $value = str_replace("&#039;","'",$value);
            $value = str_replace("&lt;","<",$value);
            $value = str_replace("&gt;",">",$value);
            $value = str_replace("%2F","/",$value);
            $value = str_replace("%5C","\\",$value);
        }
        array_walk_recursive($query, "filter");
        return $query;
    }
    //************************************************************
}