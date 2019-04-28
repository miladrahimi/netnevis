<?php

// Autoload
function core_job_autoload($class) {
    include_once dirname(__FILE__) . "/" . strtolower($class) . ".php";
}
spl_autoload_register("core_job_autoload");

class Job {
    //******************************************************************************
    private $blog;
    private $id;
    private $member;
    private $role;
    //******************************************************************************
    public function checkRole() {
        $db    = Database::connect();
        $query = "SELECT id,role FROM job WHERE member=? AND blog=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("ii", $this->member, $this->blog);
        $stmt->bind_result($this->id, $this->role);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!($stmt->num_rows))
            throw new Exception("msg.job_connect_error");
        $stmt->close();
    }
    //******************************************************************************
    public function connectByID() {
        $db    = Database::connect();
        $query = "SELECT blog,member,role FROM job WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($this->blog, $this->member, $this->role);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!($stmt->num_rows))
            throw new Exception("msg.job_connect_error");
        $stmt->close();
    }
    //******************************************************************************
    public function create() {
        $db    = Database::connect();
        $query = "INSERT INTO job (blog,member,role) VALUES (?,?,?)";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("iis", $this->blog, $this->member, $this->role);
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
        $query = "DELETE FROM job WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!$stmt->affected_rows)
            throw new Exception("msg.job_delete_error");
        $stmt->close();
    }
    //******************************************************************************
    public function edit() {
        $db    = Database::connect();
        $query = "UPDATE job SET role=? WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("si", $this->role, $this->id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!$stmt->affected_rows)
            throw new Exception("msg.job_edit_error");
        $stmt->close();
    }
    //******************************************************************************
    public function getBlog() {
        return empty($this->blog) ? 0 : (int) trim($this->blog);
    }
    //******************************************************************************
    public function getMember() {
        return empty($this->member) ? 0 : (int) trim($this->member);
    }
    //******************************************************************************
    public function getRole() {
        return empty($this->role) ? "" : (string) trim($this->role);
    }
    //******************************************************************************
    public function setBlog($blog) {
        $this->blog = empty($blog) ? 0 : (int) $blog;
    }
    //******************************************************************************
    public function setID($id) {
        $this->id = empty($id) ? 0 : (int) $id;
    }
    //******************************************************************************
    public function setMember($member) {
        $this->member = empty($member) ? 0 : (int) trim($member);
    }
    //******************************************************************************
    public function setRole($role) {
        $role = (empty($role)) ? "" : (string) trim($role);
        switch ($role) {
            case "admin":
            case "assistant":
            case "editor":
            case "writer":
                break;
            default:
                throw new Exception("err.role_wrong:role=" . $role);
        }
        $this->role = $role;
    }
    //******************************************************************************
}