<?php

// Autoload
function core_apply_autoload($class) {
    include_once dirname(__FILE__) . "/" . strtolower($class) . ".php";
}
spl_autoload_register("core_apply_autoload");

class Apply {
    //******************************************************************************
    private $blog;
    private $id;
    private $member;
    //******************************************************************************
    public function connect() {
        $db    = Database::connect();
        $query = "SELECT blog,member FROM apply WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($this->blog, $this->member);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!($stmt->num_rows))
            throw new Exception("msg.apply_connect_error");
        $stmt->close();
    }
    //******************************************************************************
    public function create() {
        $db    = Database::connect();
        $query = "INSERT INTO apply (blog,member) VALUES (?,?)";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("ii", $this->blog, $this->member);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $this->id = $stmt->insert_id;
        $stmt->close();
    }
    //******************************************************************************
    public function delete() {
        $db    = Database::connect();
        $query = "DELETE FROM apply WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!$stmt->affected_rows)
            throw new Exception("msg.apply_delete_error");
        $stmt->close();
    }
    //******************************************************************************
    public function exists() {
        $db    = Database::connect();
        $query = "SELECT id FROM apply WHERE blog=? AND member=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("ii", $this->blog, $this->member);
        $stmt->bind_result($this->id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $r = ($stmt->num_rows);
        $stmt->close();
        if($r)
            return 1;
        return 0;
    }
    //******************************************************************************
    public function getBlog() {
        return empty($this->blog) ? 0 : (int) trim($this->blog);
    }
    //******************************************************************************
    public function getID() {
        return empty($this->id) ? 0 : (int) trim($this->id);
    }
    //******************************************************************************
    public function getMember() {
        return empty($this->member) ? 0 : (int) trim($this->member);
    }
    //******************************************************************************
    public function setBlog($blog) {
        $this->blog = empty($blog) ? 0 : (int) trim($blog);
    }
    //******************************************************************************
    public function setID($id) {
        $this->id = empty($id) ? 0 : (int) trim($id);
    }
    //******************************************************************************
    public function setMember($member) {
        $this->member = empty($member) ? 0 : (int) trim($member);
    }
    //******************************************************************************
}