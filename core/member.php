<?php

// Autoload
function core_member_autoload($class) {
    include_once dirname(__FILE__) . "/" . strtolower($class) . ".php";
}
spl_autoload_register("core_member_autoload");

class Member {
    //******************************************************************************
    private $about;
    private $avatar;
    private $email;
    private $googleplus;
    private $id;
    private $moment;
    private $nickname;
    private $password;
    private $status;
    //******************************************************************************
    public function connectByID() {
        $db    = Database::connect();
        $query = "SELECT about,avatar,email,googleplus,moment,nickname,password,status FROM member WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($this->about, $this->avatar, $this->email, $this->googleplus, $this->moment, $this->nickname,
            $this->password, $this->status);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!($stmt->num_rows))
            throw new Exception("msg.member_connect_error");
        $stmt->close();
    }
    //******************************************************************************
    public function connectByEmail() {
        $db    = Database::connect();
        $query = "SELECT id FROM member WHERE email=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("s", $this->email);
        $stmt->bind_result($this->id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!($stmt->num_rows))
            throw new Exception("msg.member_connect_error");
        $stmt->close();
    }
    //******************************************************************************
    public function connectByAccount() {
        $db    = Database::connect();
        $query = "SELECT id,status FROM member WHERE email=? AND password=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("ss", $this->email, $this->password);
        $stmt->bind_result($this->id, $this->status);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!($stmt->num_rows))
            throw new Exception("msg.member_connect_error");
        $stmt->close();
        // Erase all memberlosts...
        $memberlost = new MemberLost();
        $memberlost->setMember($this->id);
        $memberlost->clean();
    }
    //******************************************************************************
    public function create() {
        $db           = Database::connect();
        $this->moment = (int) time();
        $this->status = 0;
        $query        = "INSERT INTO member (email,moment,nickname,password,status) VALUES (?,?,?,?,?)";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("sissi", $this->email, $this->moment, $this->nickname, $this->password, $this->status);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno) {
            if ($db->errno == 1062)
                throw new Exception("msg.member_create_duplicate");
            else
                throw new Exception("err.database:" . $db->error);
        }
        $this->id = $stmt->insert_id;
        $stmt->close();
    }
    //******************************************************************************
    public function delete() {
        $db    = Database::connect();
        $query = "DELETE FROM member WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!$stmt->affected_rows)
            throw new Exception("msg.member_delete_error");
        $stmt->close();
    }
    //******************************************************************************
    public static function detect() {
        if(!isset($_SESSION))
            session_start();
        return  empty($_SESSION["member"]) ? 0 : (int) trim($_SESSION["member"]);
    }
    //******************************************************************************
    public function edit() {
        $db    = Database::connect();
        $query = "UPDATE member SET about=?,avatar=?,email=?,googleplus=?,nickname=?,password=?,status=? WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("ssssssii", $this->about, $this->avatar, $this->email, $this->googleplus, $this->nickname,
            $this->password, $this->status, $this->id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno) {
            if ($db->errno == 1062)
                throw new Exception("msg.member_edit_duplicate");
            else
                throw new Exception("err.database:" . $db->error);
        }
        if (!$stmt->affected_rows)
            throw new Exception("msg.member_edit_error");
        $stmt->close();
    }
    //******************************************************************************
    public function getAbout() {
        return empty($this->about) ? "" : (string) trim($this->about);
    }
    //******************************************************************************
    public function getAvatar() {
        return empty($this->avatar) ? "" : (string) trim($this->avatar);
    }
    //******************************************************************************
    public function getBlogs() {
        $db    = Database::connect();
        $query = "SELECT id,blog FROM job WHERE member=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($job, $blog);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $r = array();
        while ($stmt->fetch())
            $r[$job] = $blog;
        $stmt->close();
        return $r;
    }
    //******************************************************************************
    public function getEmail() {
        return empty($this->email) ? "" : (string) trim($this->email);
    }
    //******************************************************************************
    public function getGooglePlus() {
        return empty($this->googleplus) ? "" : (string) trim($this->googleplus);
    }
    //******************************************************************************
    public function getID() {
        return (int) empty($this->id) ? 0 : (int) trim($this->id);
    }
    //******************************************************************************
    public function getMoment() {
        return empty($this->moment) ? 0 : (int) trim($this->moment);
    }
    //******************************************************************************
    public function getNickname() {
        return empty($this->nickname) ? "" : (string) trim($this->nickname);
    }
    //******************************************************************************
    public function getNumberOfPosts($blog) {
        $blog  = empty($blog) ? 0 : (int) trim($blog);
        $db    = Database::connect();
        $query = "SELECT id FROM post WHERE blog=? AND author=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("ii", $blog, $this->id);
        $stmt->bind_result($post_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows;
    }
    //******************************************************************************
    public function getPassword() {
        return empty($this->password) ? "" : (string) trim($this->password);
    }
    //******************************************************************************
    public function getPosts($blog, $page = 1, $num = 20) {
        $blog  = empty($blog) ? 0 : (int) trim($blog);
        $page  = empty($page) ? 1 : (int) trim($page);
        $num   = empty($num) ? 20 : (int) trim($num);
        $beg   = ((int) $page) * ((int) $num) - ((int) $num);
        $db    = Database::connect();
        $query = "SELECT id FROM post WHERE blog=? AND author=? ORDER BY id DESC LIMIT ?,?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("iiii", $blog, $this->id, $beg, $num);
        $stmt->bind_result($post_id);
        $stmt->execute();
        $stmt->store_result();
        $posts = array();
        for ($i = 0; $stmt->fetch(); $i++)
            $posts[$i] = $post_id;
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $stmt->close();
        return $posts;
    }
    //******************************************************************************
    public function getStatus() {
        return empty($this->status) ? 0 : (int) trim($this->status);
    }
    //******************************************************************************
    public function hasPermissionToAddCat() {
        $blog = new Blog();
        $blog->identify();
        $job = new Job();
        $job->setBlog($blog->getID());
        $job->setMember(self::detect());
        $job->checkRole();
        $r = $job->getRole();
        if ($r == "admin" || $r == "assistant" || $r == "editor")
            return 1;
        return 0;
    }
    //******************************************************************************
    public function hasPermissionToAddLink() {
        $blog = new Blog();
        $blog->identify();
        $job = new Job();
        $job->setBlog($blog->getID());
        $job->setMember($this->id);
        $job->checkRole();
        $r = $job->getRole();
        if ($r == "admin" || $r == "assistant" || $r == "editor")
            return 1;
        return 0;
    }
    //******************************************************************************
    public function hasPermissionToApply() {
        $blog = new Blog();
        $blog->identify();
        $job = new Job();
        $job->setBlog($blog->getID());
        $job->setMember($this->id);
        $job->checkRole();
        $r = $job->getRole();
        if ($r == "admin")
            return 1;
        return 0;
    }
    //******************************************************************************
    public function hasPermissionToConfirmComment($comment_id) {
        return $this->hasPermissionToDeleteComment($comment_id);
    }
    //******************************************************************************
    public function hasPermissionToDeleteAuthor() {
        $blog = new Blog();
        $blog->identify();
        $job = new Job();
        $job->setBlog($blog->getID());
        $job->setMember($this->id);
        $job->checkRole();
        $r = $job->getRole();
        if ($r == "admin")
            return 1;
        return 0;
    }
    //******************************************************************************
    public function hasPermissionToDeleteBlog() {
        $blog = new Blog();
        $blog->identify();
        $job = new Job();
        $job->setBlog($blog->getID());
        $job->setMember($this->id);
        $job->checkRole();
        $r = $job->getRole();
        if ($r == "admin")
            return 1;
        return 0;
    }
    //******************************************************************************
    public function hasPermissionToDeleteCat() {
        return $this->hasPermissionToAddCat();
    }
    //******************************************************************************
    public function hasPermissionToDeleteComment($comment_id) {
        $blog = new Blog();
        $blog->identify();
        $comment = new Comment();
        $comment->setID($comment_id);
        $comment->connect();
        if ($comment->getBlog() != $blog->getID())
            exit("permission_error");
        $job = new Job();
        $job->setBlog($blog->getID());
        $job->setMember($this->id);
        $job->checkRole();
        $r = $job->getRole();
        if ($r == "admin" || $r == "assistant" || $r == "editor")
            return 1;
        if ($r == "writer") {
            $comment->connect();
            $post = new Post();
            $post->setID($comment->getPost());
            $post->connect();
            if ($post->getAuthor() == $this->id)
                return 1;
            return 0;
        }
        return 0;
    }
    //******************************************************************************
    public function hasPermissionToDeleteLink($id) {
        $blog = new Blog();
        $blog->identify();
        $link = new Link();
        $link->setID($id);
        $link->connect();
        if($link->getBlog()!=$blog->getID())
            return 0;
        $job = new Job();
        $job->setBlog($blog->getID());
        $job->setMember($this->id);
        $job->checkRole();
        $r = $job->getRole();
        if ($r == "admin" || $r == "assistant" || $r == "editor")
            return 1;
        return 0;
    }
    //******************************************************************************
    public function hasPermissionToDeletePost($post_id) {
        $blog = new Blog();
        $blog->identify();
        $post = new Post();
        $post->setID($post_id);
        $post->connect();
        if($post->getBlog()!=$blog->getID())
            return 0;
        $job = new Job();
        $job->setBlog($blog->getID());
        $job->setMember($this->id);
        $job->checkRole();
        $r = $job->getRole();
        if ($r == "admin" || $r == "assistant" || $r == "editor")
            return 1;
        if ($r == "writer") {
            $post = new Post();
            $post->setID($post_id);
            $post->connect();
            if ($post->getAuthor() == $this->id)
                return 1;
            return 0;
        }
        return 0;
    }
    //******************************************************************************
    public function hasPermissionToEditPost($post_id) {
        $blog = new Blog();
        $blog->identify();
        $post = new Post();
        $post->setID($post_id);
        $post->connect();
        if($post->getBlog()!=$blog->getID())
            return 0;
        $job = new Job();
        $job->setBlog($blog->getID());
        $job->setMember($this->id);
        $job->checkRole();
        $r = $job->getRole();
        if ($r == "admin" || $r == "assistant" || $r == "editor")
            return 1;
        if ($r == "writer") {
            $post = new Post();
            $post->setID($post_id);
            $post->connect();
            if ($post->getAuthor() == $this->id)
                return 1;
            return 0;
        }
        return 0;
    }
    //******************************************************************************
    public function hasPermissionToPost() {
        $blog = new Blog();
        $blog->identify();
        $job = new Job();
        $job->setBlog($blog->getID());
        $job->setMember($this->id);
        $job->checkRole();
        switch ($job->getRole()) {
            case "admin":
            case "assistant":
            case "editor":
            case "writer":
                return 1;
            default:
                return 0;
        }
    }
    //******************************************************************************
    public function hasPermissionToRejectAuthor() {
        $blog = new Blog();
        $blog->identify();
        $job = new Job();
        $job->setBlog($blog->getID());
        $job->setMember($this->id);
        $job->checkRole();
        $r = $job->getRole();
        if ($r == "admin")
            return 1;
        return 0;
    }
    //******************************************************************************
    public function hasPermissionToRepost() {
        $blog = new Blog();
        $blog->identify();
        $job = new Job();
        $job->setBlog($blog->getID());
        $job->setMember($this->id);
        $job->checkRole();
        $r = $job->getRole();
        if ($r == "admin")
            return 1;
        return 0;
    }
    //******************************************************************************
    public function hasPermissionToSetting() {
        $blog = new Blog();
        $blog->identify();
        $job = new Job();
        $job->setBlog($blog->getID());
        $job->setMember($this->id);
        $job->checkRole();
        $r = $job->getRole();
        if ($r == "admin" || $r == "assistant")
            return 1;
        return 0;
    }
    //******************************************************************************
    public function identify() {
        if(!isset($_SESSION))
            session_start();
        $this->id = empty($_SESSION["member"]) ? 0 : (int) trim($_SESSION["member"]);
        $this->connectByID();
    }
    //******************************************************************************
    public function isRegistered() {
        $db    = Database::connect();
        $query = "SELECT id FROM member WHERE email=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("s", $this->email);
        $stmt->bind_result($this->id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $r = $stmt->num_rows;
        $stmt->close();
        return $r;
    }
    //******************************************************************************
    public function setAbout($about) {
        $about = empty($about) ? "" : htmlspecialchars(trim($about));
        $about = str_replace("&lt;br&gt;","<br>",$about);
        $about = str_replace("&lt;BR&gt;","<br>",$about);
        $about = str_replace("&lt;br/&gt;","<br>",$about);
        $about = str_replace("&lt;BR/&gt;","<br>",$about);
        $about = str_replace("&lt;br /&gt;","<br>",$about);
        $about = str_replace("&lt;BR /&gt;","<br>",$about);
        if (strlen($about) < 5)
            throw new Exception("msg.member_about_length");
        $this->about = $about;
    }
    //******************************************************************************
    public function setAvatar($avatar) {
        $avatar = (empty($avatar)) ? "" : (string) trim($avatar);
        if (!filter_var($avatar, FILTER_VALIDATE_URL))
            throw new Exception("msg.member_avatar_wrong");
        $this->avatar = $avatar;
    }
    //******************************************************************************
    public function setEmail($email) {
        $email = (empty($email)) ? "" : (string) trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new Exception("msg.member_email_wrong");
        $this->email = $email;
    }
    //******************************************************************************
    public function setID($id) {
        $this->id = empty($id) ? 0 : (int) trim($id);
    }
    //******************************************************************************
    public function setGooglePlus($googleplus) {
        $googleplus = (empty($googleplus)) ? "" : (string) strip_tags(trim($googleplus));
        if (strpos($googleplus, "http") === false)
            throw new Exception("msg.member_googleplus_wrong");
        $this->googleplus = $googleplus;
    }
    //******************************************************************************
    public function setNickname($nickname) {
        $nickname = (empty($nickname)) ? "---" : (string) htmlspecialchars(trim($nickname));
        if (strlen($nickname) < 2)
            throw new Exception("msg.member_nickname_length");
        $this->nickname = $nickname;
    }
    //******************************************************************************
    public function setPassword($password) {
        $password = (empty($password)) ? "" : (string) trim($password);
        if ($password == "password")
            $password = "";
        if (strlen($password) < 8 || strlen($password) > 20)
            throw new Exception("msg.member_password_length");
        $this->password = Security::hashPassword($password);
    }
    //******************************************************************************
    public function setStatus($status) {
        $this->status = empty($status) ? 0 : (int) trim($status);
    }
    //******************************************************************************
}