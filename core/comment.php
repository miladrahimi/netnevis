<?php

// Autoload
function core_comment_autoload($class) {
    include_once dirname(__FILE__) . "/" . strtolower($class) . ".php";
}
spl_autoload_register("core_comment_autoload");

class Comment {
    //******************************************************************************
    private $author;
    private $blog;
    private $email;
    private $id;
    private $message;
    private $moment;
    private $post;
    private $status;
    //******************************************************************************
    public function connect() {
        $db    = Database::connect();
        $query = "SELECT author,blog,email,moment,post,status,message FROM comment WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($this->author, $this->blog, $this->email, $this->moment, $this->post, $this->status,
            $this->message);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!$stmt->num_rows)
            throw new Exception("msg.comment_connect_error");
        $stmt->close();
    }
    //******************************************************************************
    public function create() {
        $db           = Database::connect();
        $this->moment = time();
        $query        = "INSERT INTO comment (author,blog,email,moment,post,status,message) VALUES (?,?,?,?,?,?,?)";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("sisiiis", $this->author, $this->blog, $this->email, $this->moment, $this->post,
            $this->status, $this->message);
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
        $query = "DELETE FROM comment WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!$stmt->affected_rows)
            throw new Exception("msg.comment_delete_error");
        $stmt->close();
    }
    //******************************************************************************
    public function edit() {
        $db    = Database::connect();
        $query = "UPDATE comment SET author=?,blog=?,email=?,moment=?,post=?,status=?,message=? WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("iisiiisi", $this->author, $this->blog, $this->email, $this->moment, $this->post,
            $this->status, $this->message, $this->id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!$stmt->affected_rows)
            throw new Exception("msg.comment_edit_error");
        $stmt->close();
    }
    //******************************************************************************
    public function getAuthor() {
        return empty($this->author) ? "" : (string) trim($this->author);
    }
    //******************************************************************************
    public function getBlog() {
        return empty($this->blog) ? 0 : (int) trim($this->blog);
    }
    //******************************************************************************
    public function getEmail() {
        return empty($this->email) ? "" : (string) trim($this->email);
    }
    //******************************************************************************
    public function getID() {
        return empty($this->id) ? 0 : (int) trim($this->id);
    }
    //******************************************************************************
    public function getMoment() {
        return empty($this->moment) ? 0 : (int) trim($this->moment);
    }
    //******************************************************************************
    public function getPost() {
        return empty($this->post) ? 0 : (int) trim($this->post);
    }
    //******************************************************************************
    public function getMessage() {
        return empty($this->message) ? "" : (string) trim($this->message);
    }
    //******************************************************************************
    public function getStatus() {
        return empty($this->status) ? 0 : (int) trim($this->status);
    }
    //******************************************************************************
    public function setAuthor($author) {
        $this->author = empty($author) ? "..." : htmlspecialchars(trim($author));
    }
    //******************************************************************************
    public function setBlog($blog) {
        $this->blog = empty($blog) ? 0 : (int) trim($blog);
    }
    //******************************************************************************
    public function setEmail($email) {
        $email = (empty($email)) ? "" : (string) trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new Exception("msg.email_wrong");
        $this->email = $email;
    }
    //******************************************************************************
    public function setID($id) {
        $this->id = empty($id) ? 0 : (int) trim($id);
    }
    //******************************************************************************
    public function setPost($post) {
        $this->post = empty($post) ? 0 : (int) trim($post);
    }
    //******************************************************************************
    public function setStatus($status) {
        $this->status = empty($status) ? 0 : (int) trim($status);
    }
    //******************************************************************************
    public function setMessage($message) {
        $message = empty($message) ? "..." : htmlspecialchars(trim($message));
        $message = str_replace("&lt;br&gt;","<br>",$message);
        $message = str_replace("&lt;BR&gt;","<br>",$message);
        $message = str_replace("&lt;br/&gt;","<br>",$message);
        $message = str_replace("&lt;BR/&gt;","<br>",$message);
        $message = str_replace("&lt;br /&gt;","<br>",$message);
        $message = str_replace("&lt;BR /&gt;","<br>",$message);
        $message = substr($message,0,2000);
        $this->message = $message;
    }
}