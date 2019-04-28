<?php

class Reporter {
    //******************************************************************************
    static public function report($e, $force_store = false) {
        // Force store (even messages!)
        if ($force_store) {
            self::save($e);
            return ("error");
        }
        // Detect exception type
        $exception = $e->getMessage();
        $type      = substr($exception, 0, 3);
        // Do based on type
        if ($type == "msg") {
            // Return Messages
            $message = substr($exception, 4);
            return ($message);
        } else {
            // Store Errors
            self::save($e);
            return "error";
        }
    }
    //******************************************************************************
    static private function save($e) {
        // Store exceptions in file
        $fp = fopen(dirname(__FILE__) . "/../error_log.txt", "a+");
        fwrite($fp, $e);
        fwrite($fp, "\r\n###\r\n");
        fclose($fp);
    }
    //******************************************************************************
}