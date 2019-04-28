<?php

// Autoload
function core_link_autoload($class) {
    include_once dirname(__FILE__) . "/" . strtolower($class) . ".php";
}
spl_autoload_register("core_link_autoload");

class Link {
    //******************************************************************************
    private $blog;
    private $id;
    private $title;
    private $url;
    //******************************************************************************
    public function connect() {
        $db    = Database::connect();
        $query = "SELECT blog,title,url FROM link WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($this->blog, $this->title, $this->url);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!($stmt->num_rows))
            throw new Exception("msg.link_connect_error");
        $stmt->close();
    }
    //******************************************************************************
    public function create() {
        $db    = Database::connect();
        $query = "INSERT INTO link (blog,title,url) VALUES (?,?,?)";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("iss", $this->blog, $this->title, $this->url);
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
        $query = "DELETE FROM link WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!$stmt->affected_rows)
            throw new Exception("msg.link_delete_error");
        $stmt->close();
    }
    //******************************************************************************
    public function edit() {
        $db    = Database::connect();
        $query = "UPDATE link SET blog=?,title=?,url=? WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("issi", $this->blog, $this->title, $this->url, $this->id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!$stmt->affected_rows)
            throw new Exception("msg.link_edit_error");
        $stmt->close();
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
    public function getTitle() {
        return empty($this->title) ? "" : (string) trim($this->title);
    }
    //******************************************************************************
    public function getURL() {
        return empty($this->url) ? "" : (string) trim($this->url);
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
    public function setTitle($title) {
        $this->title = empty($title) ? "---" : (string) htmlspecialchars(trim($title));
    }
    //******************************************************************************
    public function setURL($url) {
        $url = (empty($url)) ? "" : (string) trim($url);
        if (!filter_var($url, FILTER_VALIDATE_URL))
            throw new Exception("msg.url_wrong");
        $this->url = $url;
    }
    //******************************************************************************
}