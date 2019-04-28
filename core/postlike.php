<?php

// Autoload
function core_postlike_autoload($class) {
    include_once dirname(__FILE__) . "/" . strtolower($class) . ".php";
}
spl_autoload_register("core_postlike_autoload");

class PostLike {
    //******************************************************************************
    private $expiration;
    private $id;
    private $ip;
    private $post;
    private $status;
    //******************************************************************************
    public function __construct($post=0) {
        $this->post = empty($post) ? 0 : (int) trim($post);
        $this->ip = $_SERVER['REMOTE_ADDR'];
    }
    //******************************************************************************
    public function check() {
        if(!$this->connect())
            return 0;
        return $this->status;
    }
    //******************************************************************************
    public static function cleanExpireds() {
        $db    = Database::connect();
        $now = time();
        $query = "DELETE FROM postlike WHERE expiration < ?";
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
    private function connect() {
        $db    = Database::connect();
        $query = "SELECT id,status FROM postlike WHERE post=? AND ip=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("is", $this->post, $this->ip);
        $stmt->bind_result($this->id, $this->status);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $n = $stmt->num_rows;
        $stmt->close();
        if($n)
            return 1;
        return 0;
    }
    //******************************************************************************
    private function create() {
        $db    = Database::connect();
        $query = "INSERT INTO postlike (expiration,ip,post,status) VALUES (?,?,?,?)";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("isii", $this->expiration, $this->ip, $this->post, $this->status);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $this->id = $stmt->insert_id;
        $stmt->close();
    }
    //******************************************************************************
    private function edit() {
        $db    = Database::connect();
        $query = "UPDATE postlike SET status=? WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("ii", $this->status, $this->id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $stmt->close();
    }
    //******************************************************************************
    public function run() {
        if(!$this->connect()) {
            $this->expiration = time() + 3600 * 24;
            $this->status = 0;
            $this->create();
        }
        $this->status = empty($this->status)? 1 : 0;
        $this->edit();
        return $this->status;
    }
}