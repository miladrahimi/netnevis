<?php

// Autoload
function core_visit_autoload($class) {
    include_once dirname(__FILE__) . "/" . strtolower($class) . ".php";
}
spl_autoload_register("core_visit_autoload");

class Visit {
    //******************************************************************************
    private $blog;
    private $day;
    private $id;
    private $pages;
    private $visitors;
    //******************************************************************************
    public function getBlog() {
        return empty($this->blog) ? 0 : (int) trim($this->blog);
    }
    //******************************************************************************
    public function getPages() {
        return empty($this->pages) ? 0 : (int) trim($this->pages);
    }
    //******************************************************************************
    public function ofBlog() {
        $db    = Database::connect();
        $query = "SELECT id,pages,visitors FROM visit WHERE blog=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->blog);
        $stmt->bind_result($this->id, $this->pages, $this->visitors);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $pages = $this->pages = $this->visitors = 0;
        while($stmt->fetch()) {
            $pages += $this->pages;
        }
        $this->pages = $pages;
        $stmt->close();
    }
    //******************************************************************************
    public function ofDay() {
        $db    = Database::connect();
        $query = "SELECT id,pages,visitors FROM visit WHERE blog=? AND day=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("is", $this->blog, $this->day);
        $stmt->bind_result($this->id, $this->pages, $this->visitors);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!($stmt->num_rows))
            $this->pages = $this->visitors = 0;
        $stmt->close();
    }
    //******************************************************************************
    public function ofMonth() {
        $db    = Database::connect();
        $date = explode('/',$this->day);
        $month = $date[0].'/'.$date[1];
        $query = "SELECT id,pages,visitors FROM visit WHERE blog=? AND day LIKE '$month%'";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->blog);
        $stmt->bind_result($this->id, $this->pages, $this->visitors);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $pages = $this->pages = $this->visitors = 0;
        while($stmt->fetch()) {
            $pages += $this->pages;
        }
        $this->pages = $pages;
        $stmt->close();
    }
    //******************************************************************************
    public function getVisitors() {
        return empty($this->visitors) ? 0 : (int) trim($this->visitors);
    }
    //******************************************************************************
    public function run() {
        $this->id = $this->pages = $this->visitors = 0;
        $this->day = date("Y/m/d");
        $db    = Database::connect();
        $query = "SELECT id,pages,visitors FROM visit WHERE blog=? AND day=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("is", $this->blog, $this->day);
        $stmt->bind_result($this->id,$this->pages,$this->visitors);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if ($stmt->num_rows) {
            $this->pages = ((int) $this->pages) + 1;
            $query = "UPDATE visit SET pages=?,visitors=? WHERE id=?";
            if (!$stmt = $db->prepare($query))
                throw new Exception("err.database:" . $db->error);
            $stmt->bind_param("iii", $this->pages,$this->visitors, $this->id);
            $stmt->execute();
            $stmt->store_result();
            if ($db->errno)
                throw new Exception("err.database:" . $db->error);
            $stmt->close();
        } else {
            $this->pages = $this->visitors = 1;
            $query = "INSERT INTO visit (blog,day,pages,visitors) VALUES (?,?,?,?)";
            if (!$stmt = $db->prepare($query))
                throw new Exception("err.database:" . $db->error);
            $stmt->bind_param("isii", $this->blog, $this->day, $this->pages, $this->visitors);
            $stmt->execute();
            $stmt->store_result();
            if ($db->errno)
                throw new Exception("err.database:" . $db->error);
            $stmt->close();
        }
    }
    //******************************************************************************
    public function setBlog($blog) {
        $this->blog = empty($blog) ? 0 : (int) trim($blog);
    }
    //******************************************************************************
    public function setDay($day) {
        $this->day = empty($day) ? '' : trim($day);
    }
    //******************************************************************************
}