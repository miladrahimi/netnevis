<?php

// Autoload
function core_database_autoload($class) {
    include_once dirname(__FILE__) . "/" . strtolower($class) . ".php";
}
spl_autoload_register("core_database_autoload");

class Database {
    //************************************************************
    private static $handle;
    //************************************************************
    public static function connect() {
        // Load Config
        $dbc = Config::load("database");
        // Make new if no connection
        if (empty(self::$handle))
            self::$handle = new mysqli($dbc['host'], $dbc['user'], $dbc['pass'], $dbc['name']);
        // Error if connection hasn't made
        if (empty(self::$handle))
            throw new Exception("err.database_handle_empty");
        // Set UTF-8
        if (!self::$handle->set_charset("utf8"))
            throw new Exception("err.database_set_charset");
        // Check database errors
        if (self::$handle->errno)
            throw new Exception("err.database:" . self::$handle->error);
        // OK!
        return self::$handle;
    }
    //************************************************************
}