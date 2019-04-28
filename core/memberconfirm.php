<?php

// Autoload
function core_memberconfirm_autoload($class) {
    include_once dirname(__FILE__) . "/" . strtolower($class) . ".php";
}
spl_autoload_register("core_memberconfirm_autoload");

class MemberConfirm {
    //******************************************************************************
    private $code;
    private $email;
    private $expiration;
    private $id;
    private $member;
    //******************************************************************************
    public static function cleanExpireds() {
        $db    = Database::connect();
        $now = time();
        $query = "DELETE FROM memberconfirm WHERE expiration < ?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $now);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $stmt->close();
    }
    //******************************************************************************
    public function connectByCode() {
        $db    = Database::connect();
        $query = "SELECT id,email,member FROM memberconfirm WHERE code=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("s", $this->code);
        $stmt->bind_result($this->id, $this->email, $this->member);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $r = $stmt->num_rows;
        $stmt->close();
        if (!$r)
            throw new Exception("msg.memberconfirm_connect_error");
    }
    //******************************************************************************
    public function create() {
        $this->deleteByMember();
        $db         = Database::connect();
        $this->code = mt_rand(1000000000, 9999999999);
        $this->expiration = time() * 3600 * 24 * 3;
        $query      = "INSERT INTO memberconfirm (code,email,expiration,member) VALUES (?,?,?,?)";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("isii", $this->code, $this->email, $this->expiration, $this->member);
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
        $query = "DELETE FROM memberconfirm WHERE id=?";
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
    public function deleteByMember() {
        $db    = Database::connect();
        $query = "DELETE FROM memberconfirm WHERE member=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->member);
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
    public function getEmail() {
        return empty($this->email) ? 0 : (string) trim($this->email);
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
    public function setEmail($email) {
        $email = (empty($email)) ? "" : (string) trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new Exception("msg.memberconfirm_email_wrong");
        $this->email = $email;
    }
    //******************************************************************************
    public function setMember($member) {
        $this->member = empty($member) ? 0 : (int) trim($member);
    }
    //******************************************************************************
}