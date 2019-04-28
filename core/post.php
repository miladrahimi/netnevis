<?php

// Autoload
function core_post_autoload($class) {
    include_once dirname(__FILE__) . "/" . strtolower($class) . ".php";
}
spl_autoload_register("core_post_autoload");

class post {
    //******************************************************************************
    private $author;
    private $blog;
    private $cat;
    private $comment;
    private $content;
    private $id;
    private $likes;
    private $moment;
    private $title;
    //******************************************************************************
    public function connect() {
        if (empty($this->id))
            throw new Exception("msg.post_absent");
        $db    = Database::connect();
        $query = "SELECT author,blog,cat,comment,content,likes,moment,title FROM post WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($this->author, $this->blog, $this->cat, $this->comment, $this->content, $this->likes,
            $this->moment, $this->title);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!($stmt->num_rows))
            throw new Exception("msg.post_connect_error");
        $stmt->close();
    }
    //******************************************************************************
    public function create() {
        $db           = Database::connect();
        $this->moment = time();
        $this->likes  = 0;
        $query = "INSERT INTO post (author,blog,cat,comment,content,likes,moment,title) VALUES (?,?,?,?,?,?,?,?)";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("iisisiis", $this->author, $this->blog, $this->cat, $this->comment, $this->content,
            $this->likes, $this->moment, $this->title);
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
        $query = "DELETE FROM post WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!$stmt->affected_rows)
            throw new Exception("msg.post_delete_error");
        $stmt->close();
    }
    //******************************************************************************
    public function edit($update=1) {
        $db    = Database::connect();
        if($update)
            $this->moment = time();
        $query = "UPDATE post SET author=?,blog=?,cat=?,comment=?,content=?,likes=?,moment=?,title=? WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("iisisiisi", $this->author, $this->blog, $this->cat, $this->comment, $this->content,
            $this->likes, $this->moment, $this->title, $this->id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!$stmt->affected_rows)
            throw new Exception("msg.post_edit_error");
        $stmt->close();
    }
    //******************************************************************************
    public static function filter($text) {
        return preg_replace("/#([^\ \.\,\&\!\(\)\<\>\{\}\[\]\/]*)/", '<a href="/tag/$1">#$1</a>', $text);
    }
    //******************************************************************************
    public function getAuthor() {
        return empty($this->author) ? 0 : (int) trim($this->author);
    }
    //******************************************************************************
    public function getBlog() {
        return empty($this->blog) ? 0 : (int) trim($this->blog);
    }
    //******************************************************************************
    public function getCat() {
        return empty($this->cat) ? "" : (string) trim($this->cat);
    }
    //******************************************************************************
    public function getComment() {
        return empty($this->comment) ? 0 : (int) trim($this->comment);
    }
    //******************************************************************************
    public function getComments() {
        $db    = Database::connect();
        $query = "SELECT id FROM comment WHERE post=? ORDER BY moment DESC";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($comment_id);
        $stmt->execute();
        $stmt->store_result();
        $comments = array();
        for ($i = 0; $stmt->fetch(); $i++)
            $comments[$i] = $comment_id;
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $stmt->close();
        return $comments;
    }
    //******************************************************************************
    public function getContent() {
        return empty($this->content) ? "" : self::filter($this->content);
    }
    //******************************************************************************
    public function getDescription() {
        if (empty($this->content))
            return "...";
        mb_internal_encoding('UTF-8');
        $content = str_replace("<p></p>","&nbsp;&nbsp;",$this->content);
        $content = str_replace("<br>","&nbsp;&nbsp;",$content);
        $content = str_replace("<br/>","&nbsp;&nbsp;",$content);
        $content = str_replace("<br />","&nbsp;&nbsp;",$content);
        $content = strip_tags($content);
        if (mb_strlen($content) > 256) {
            $content = mb_substr($content,0,256);
        }
        return $content . "...";
    }
    //******************************************************************************
    public function getID() {
        return empty($this->id) ? 0 : (int) trim($this->id);
    }
    //******************************************************************************
    public function getLikes() {
        return empty($this->likes) ? 0 : (int) trim($this->likes);
    }
    //******************************************************************************
    public function getMoment() {
        return empty($this->moment) ? 0 : (int) trim($this->moment);
    }
    //******************************************************************************
    public function getNumberOfComments() {
        $db    = Database::connect();
        $query = "SELECT id FROM comment WHERE post=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($comment_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows;
    }
    //******************************************************************************
    public function getTags() {
        preg_match_all("/#([^ ]*)/", $this->content, $tags);
        return $tags[1];
    }
    //******************************************************************************
    public function getTitle() {
        return empty($this->title) ? "" : (string) trim($this->title);
    }
    //******************************************************************************
    public function getRawContent() {
        return empty($this->content) ? "" : (string) trim($this->content);
    }
    //******************************************************************************
    public function getSummary() {
        $content  = str_replace("</p><p>", "<br>", $this->content);
        $content  = strip_tags($content,"<br>");
        mb_internal_encoding('UTF-8');
        if(mb_strlen($content)>512)
            $content = '<p>' . mb_substr($content,0,512) . '<strong>...</strong></p>';
        $has = preg_match('/<img[^>]*>/',$this->content,$img);
        if(!empty($img[0]) && $has)
            $content = $content . '<p>' . $img[0] . '</p>';
        return empty($content) ? '...' : $content;
    }
    //******************************************************************************
    public function setAuthor($author) {
        $this->author = empty($author) ? 0 : (int) trim($author);
    }
    //******************************************************************************
    public function setBlog($blog) {
        $this->blog = empty($blog) ? 0 : (int) trim($blog);
    }
    //******************************************************************************
    public function setCat($cat) {
        $this->cat = empty($cat) ? "" : (string) htmlspecialchars(trim($cat));
    }
    //******************************************************************************
    public function setComment($comment) {
        $this->comment = empty($comment) ? 0 : (int) trim($comment);
    }
    //******************************************************************************
    public function setContent($content) {
        $this->content = empty($content) ? "..." : (string) trim($content);
    }
    //******************************************************************************
    public function setID($id) {
        $this->id = empty($id) ? 0 : (int) trim($id);
    }
    //******************************************************************************
    public function setLikes($num) {
        $num = empty($num) ? 0 : (int) trim($num);
        if($num < 0 ) $num = 0;
        $this->likes =  $num;
    }
    //******************************************************************************
    public function setTitle($title) {
        $this->title = empty($title) ? "---" : (string) htmlspecialchars(trim($title));
    }
    //******************************************************************************
}