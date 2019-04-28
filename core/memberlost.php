<?php

// Autoload
function core_memberlost_autoload($class) {
    include_once dirname(__FILE__) . "/" . strtolower($class) . ".php";
}
spl_autoload_register("core_memberlost_autoload");

class MemberLost {
    //******************************************************************************
    private $code;
    private $id;
    private $member;
    //******************************************************************************
    public function clean() {
        $db    = Database::connect();
        $query = "DELETE FROM memberlost WHERE member=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->member);
        $stmt->execute();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $stmt->close();
    }
    //******************************************************************************
    public function connectByCode() {
        $db    = Database::connect();
        $query = "SELECT id,member FROM memberlost WHERE code=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("s", $this->code);
        $stmt->bind_result($this->id, $this->member);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $r = $stmt->num_rows;
        $stmt->close();
        if (!$r)
            throw new Exception("msg.memberlost_connect_error");
    }
    //******************************************************************************
    public function create() {
        $this->erase();
        $db         = Database::connect();
        $this->code = mt_rand(1000000000, 9999999999);
        $query      = "INSERT INTO memberlost (code,member) VALUES (?,?)";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("ii", $this->code, $this->member);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno) {
            if ($db->errno == 1062) {
                $this->create();
                return;
            } else {
                throw new Exception("err.database:" . $db->error);
            }
        }
        $this->id = $stmt->insert_id;
        $stmt->close();
    }
    //******************************************************************************
    public function delete() {
        $db    = Database::connect();
        $query = "DELETE FROM memberlost WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $stmt->close();
    }
    //******************************************************************************
    public function getCode() {
        return empty($this->code) ? 0 : (string) trim($this->code);
    }
    //******************************************************************************
    public function getMember() {
        return empty($this->member) ? 0 : (int) trim($this->member);
    }
    //******************************************************************************
    public function setCode($code) {
        $this->code = empty($code) ? 0 : (int) trim($code);
    }
    //******************************************************************************
    public function setMember($member) {
        $this->member = empty($member) ? 0 : (int) trim($member);
    }
    //******************************************************************************
}