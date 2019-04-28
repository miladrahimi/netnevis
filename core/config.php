<?php

class Config {
    //************************************************************
    public static function load($name) {
        include_once dirname(__FILE__) . '/../config/' . $name . '.php';
        return empty($config) ? array() : $config;
    }
    //************************************************************
}