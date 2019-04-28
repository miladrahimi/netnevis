<?php

// Autoload
function core_blog_autoload($class) {
    include_once dirname(__FILE__) . "/" . strtolower($class) . ".php";
}
spl_autoload_register("core_blog_autoload");


class Blog {
    //******************************************************************************
    private $about;
    private $cover;
    private $description;
    private $email;
    private $favicon;
    private $g_auhtor;
    private $g_publisher;
    private $id;
    private $logo;
    private $metatags;
    private $moment;
    private $subdomain;
    private $title;
    //******************************************************************************
    public function connect() {
        $db    = Database::connect();
        $query = "SELECT about,cover,description,email,favicon,g_author,g_publisher,logo,subdomain,metatags,moment,title
                    FROM blog WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($this->about, $this->cover, $this->description, $this->email, $this->favicon,
            $this->g_auhtor, $this->g_publisher, $this->logo, $this->subdomain, $this->metatags,$this->moment,
            $this->title);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!($stmt->num_rows))
            throw new Exception("msg.blog_connect_error");
        $stmt->close();
    }
    //******************************************************************************
    public function connectBySubdomain() {
        $this->id = 0;
        $db       = Database::connect();
        $query    = "SELECT id FROM blog WHERE subdomain=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("s", $this->subdomain);
        $stmt->bind_result($this->id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $stmt->close();
    }
    //******************************************************************************
    public function cleanApplys() {
        $db    = Database::connect();
        $query = "DELETE FROM apply WHERE blog=?";
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
    public function cleanComments() {
        $db    = Database::connect();
        $query = "DELETE FROM comment WHERE blog=?";
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
    public function cleanJobs() {
        $db    = Database::connect();
        $query = "DELETE FROM job WHERE blog=?";
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
    public function cleanLinks() {
        $db    = Database::connect();
        $query = "DELETE FROM link WHERE blog=?";
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
    public function cleanPosts() {
        $db    = Database::connect();
        $query = "DELETE FROM post WHERE blog=?";
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
    public function create() {
        $db           = Database::connect();
        $this->moment = time();
        $query        = "INSERT INTO blog (email,subdomain,moment,title) VALUES (?,?,?,?)";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("ssis", $this->email, $this->subdomain, $this->moment, $this->title);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $this->id = $stmt->insert_id;
        $stmt->close();
    }
    //******************************************************************************
    public function delete() {
        $this->cleanApplys();
        $this->cleanComments();
        $this->cleanJobs();
        $this->cleanLinks();
        $this->cleanPosts();
        $db    = Database::connect();
        $query = "DELETE FROM blog WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!$stmt->affected_rows)
            throw new Exception("msg.blog_delete_error");
        $stmt->close();
    }
    //******************************************************************************
    public function detect() {
        if (empty($this->subdomain)) {
            $url             = $_SERVER["SERVER_NAME"];
            $url_parts       = explode(".", $url);
            $this->subdomain = $url_parts[0];
        }
        $this->connectBySubdomain();
    }
    //******************************************************************************
    public function edit() {
        $db    = Database::connect();
        $query = "UPDATE blog SET
                    about=?,cover=?,description=?,email=?,favicon=?,g_author=?,g_publisher=?,logo=?,subdomain=?,
                    metatags=?,moment=?,title=? WHERE id=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("ssssssssssisi", $this->about, $this->cover, $this->description, $this->email, $this->favicon,
            $this->g_auhtor, $this->g_publisher, $this->logo, $this->subdomain, $this->metatags, $this->moment,
            $this->title, $this->id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        if (!$stmt->affected_rows)
            throw new Exception("msg.blog_edit_error");
        $stmt->close();
    }
    //******************************************************************************
    public function getAbout() {
        return empty($this->about) ? "" : $this->about;
    }
    //******************************************************************************
    public static function getAll() {
        $db    = Database::connect();
        $query = "SELECT id FROM blog ORDER BY id DESC";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_result($id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $r = array();
        for ($i = 0; $stmt->fetch(); $i++)
            $r[$i] = $id;
        $stmt->close();
        return $r;
    }
    //******************************************************************************
    public function getAllPosts() {
        $db    = Database::connect();
        $query = "SELECT id FROM post WHERE blog=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $r = array();
        for ($i = 0; $stmt->fetch(); $i++)
            $r[$i] = $id;
        $stmt->close();
        return $r;
    }
    //******************************************************************************
    public function getApplys() {
        $db    = Database::connect();
        $query = "SELECT id,member FROM apply WHERE blog=? ORDER BY id DESC";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($apply_id, $member_id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $r = array();
        while ($stmt->fetch())
            $r[$apply_id] = $member_id;
        $stmt->close();
        return $r;
    }
    //******************************************************************************
    public function getArchive() {
        $tr      = $this->getTimeRange();
        $archive = array();
        if (!$tr[0])
            return $archive;
        $first = $tr[0];
        $last  = $tr[1];
        $ly    = JDate::date("Y", $last);
        $fy    = JDate::date("Y", $first);
        for ($i = 0, $k = 0; $fy <= $ly; $i++, $fy++) {
            for ($j = 0, $fm = 1; $fm <= 12; $j++, $fm++) {
                $beg = JDate::mktime(0, 0, 0, $fm, 1, $fy);
                $end = JDate::mktime(23, 59, 59, $fm, 31, $fy);
                $r   = $this->getNumberOfPeriod($beg, $end);
                if ($r) {
                    $archive[$k]          = array();
                    $archive[$k]["date"]  = $fy . "/" . $fm;
                    $archive[$k]["year"]  = $fy;
                    $archive[$k]["month"] = $fm;
                    $archive[$k]["num"]   = $r;
                    $k++;
                }
            }
        }
        return array_reverse($archive);
    }
    //******************************************************************************
    public function getAuthors() {
        $db    = Database::connect();
        $query = "SELECT id,member FROM job WHERE blog=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($job_id, $member_id);
        $stmt->execute();
        $stmt->store_result();
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $r = array();
        while ($stmt->fetch())
            $r[$job_id] = $member_id;
        $stmt->close();
        return $r;
    }
    //******************************************************************************
    public function getCats() {
        $db    = Database::connect();
        $query = "SELECT DISTINCT cat FROM post WHERE blog=? AND cat IS NOT NULL ORDER BY title";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($cat);
        $stmt->execute();
        $stmt->store_result();
        $cats = array();
        for ($i = 0; $stmt->fetch(); $i++)
            $cats[$i] = $cat;
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $stmt->close();
        return $cats;
    }
    //******************************************************************************
    public function getComments($status = "all") {
        $db    = Database::connect();
        if ($status == "new") {
            $query = "SELECT id FROM comment WHERE blog=? AND status=0 ORDER BY moment DESC";
        } else if ($status == "old") {
            $query = "SELECT id FROM comment WHERE blog=? AND status=1 ORDER BY moment DESC";
        } else {
            $query = "SELECT id FROM comment WHERE blog=? ORDER BY moment DESC";
        }
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
    public function getCover() {
        return empty($this->cover) ? "" : (string) trim($this->cover);
    }
    //******************************************************************************
    public function getDescription() {
        return empty($this->description) ? "" : (string) trim($this->description);
    }
    //******************************************************************************
    public function getEmail() {
        return empty($this->email) ? "" : (string) trim($this->email);
    }
    //******************************************************************************
    public function getFavicon() {
        return empty($this->favicon) ? "" : (string) trim($this->favicon);
    }
    //******************************************************************************
    public function getFavorites() {
        $db    = Database::connect();
        $query = "SELECT id FROM post WHERE blog=? AND likes > 0 ORDER BY likes DESC Limit 15";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
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
    public function getGAuthor() {
        return empty($this->g_auhtor) ? "" : (string) trim($this->g_auhtor);
    }
    //******************************************************************************
    public function getGPublisher() {
        return empty($this->g_publisher) ? "" : (string) trim($this->g_publisher);
    }
    //******************************************************************************
    public function getID() {
        return empty($this->id) ? 0 : (int) trim($this->id);
    }
    //******************************************************************************
    public function getKeywords() {
        $cats     = $this->getCats();
        $keywords = "";
        foreach ($cats as $cat) {
            $keywords .= trim($cat) . ", ";
        }
        return substr($keywords, 0, count($keywords) - 3);
    }
    //******************************************************************************
    public function getLastUpdate() {
        $db    = Database::connect();
        $query = "SELECT id,moment FROM post WHERE blog=? ORDER BY moment DESC LIMIT 1";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i",$this->id);
        $stmt->bind_result($id,$moment);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        return $moment;
    }
    //******************************************************************************
    public function getLinks() {
        $db    = Database::connect();
        $query = "SELECT id FROM link WHERE blog=? ORDER BY title";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($link_id);
        $stmt->execute();
        $stmt->store_result();
        $links = array();
        for ($i = 0; $stmt->fetch(); $i++)
            $links[$i] = $link_id;
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $stmt->close();
        return $links;
    }
    //******************************************************************************
    public function getLogo() {
        return empty($this->logo) ? "" : (string) trim($this->logo);
    }
    //******************************************************************************
    public function getMetatags() {
        return empty($this->metatags) ? '' : (string) trim($this->metatags);
    }
    //******************************************************************************
    public function getMoment() {
        return empty($this->moment) ? 0 : (int) trim($this->moment);
    }
    //******************************************************************************
    public static function getNews() {
        $db    = Database::connect();
        $query = "SELECT id,blog FROM post ORDER BY moment DESC LIMIT 15";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_result($post_id, $post_blog);
        $stmt->execute();
        $stmt->store_result();
        $posts = array();
        while ($stmt->fetch())
            $posts[$post_id] = $post_blog;
        if ($db->errno)
            throw new Exception("err.database:" . $db->error);
        $stmt->close();
        return $posts;
    }
    //******************************************************************************
    public function getNumberOfAuthors() {
        $db    = Database::connect();
        $query = "SELECT id FROM job WHERE blog=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($job_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows;
    }
    //******************************************************************************
    public function getNumberOfCats($cat) {
        $cat   = empty($cat) ? "" : (string) trim($cat);
        $db    = Database::connect();
        $query = "SELECT id FROM post WHERE blog=? AND cat=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("is", $this->id, $cat);
        $stmt->bind_result($post_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows;
    }
    //******************************************************************************
    public function getNumberOfPeriod($beg, $end) {
        $beg   = empty($beg) ? 0 : (int) $beg;
        $end   = empty($end) ? 0 : (int) $end;
        $db    = Database::connect();
        $query = "SELECT id FROM post WHERE blog=? AND moment>=? AND moment <=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("iii", $this->id, $beg, $end);
        $stmt->bind_result($post_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows;
    }
    //******************************************************************************
    public function getNumberOfPosts() {
        $db    = Database::connect();
        $query = "SELECT id FROM post WHERE blog=?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($post_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows;
    }
    //******************************************************************************
    public function getNumberOfTags($tag) {
        $tag   = empty($tag) ? "" : (string) trim($tag);
        $tag   = mysql_real_escape_string($tag);
        $db    = Database::connect();
        $query = "SELECT id FROM post WHERE blog=? AND content LIKE '%#{$tag}%'";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($post_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows;
    }
    //******************************************************************************
    public function getPosts($page = 1, $num = 20) {
        $page  = empty($page) ? 1 : (int) trim($page);
        $num   = empty($num) ? 20 : (int) trim($num);
        $beg   = ((int) $page) * ((int) $num) - ((int) $num);
        $db    = Database::connect();
        $query = "SELECT id FROM post WHERE blog=? ORDER BY moment DESC LIMIT {$beg},{$num}";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
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
    public function getPostsOfCat($cat, $page = 1, $num = 20) {
        $cat   = empty($cat) ? "" : (string) trim($cat);
        $page  = empty($page) ? 1 : (int) trim($page);
        $num   = empty($num) ? 20 : (int) trim($num);
        $beg   = ((int) $page) * ((int) $num) - ((int) $num);
        $db    = Database::connect();
        $query = "SELECT id FROM post WHERE blog=? AND cat=? ORDER BY moment DESC LIMIT ?,?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("isii", $this->id, $cat, $beg, $num);
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
    public function getPostsOfPeriod($beg, $end, $page = 1, $num = 20) {
        $beg   = empty($beg) ? 0 : (int) trim($beg);
        $end   = empty($end) ? 0 : (int) trim($end);
        $num   = empty($num) ? 20 : (int) trim($num);
        $page  = empty($page) ? 1 : (int) trim($page);
        $start = $page * $num - $num;
        $db    = Database::connect();
        $query = "SELECT id FROM post WHERE blog=? AND moment>=? AND moment <=? ORDER BY moment DESC LIMIT ?,?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("iiiii", $this->id, $beg, $end, $start, $num);
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
    public function getPostsOfTag($tag, $page = 1, $num = 20) {
        $db    = Database::connect();
        $tag   = empty($tag) ? "" : $db->escape_string(trim($tag));
        $page  = empty($page) ? 0 : (int) trim($page);
        $num   = empty($num) ? 0 : (int) trim($num);
        $beg   = ((int) $page) * ((int) $num) - ((int) $num);
        $query = "SELECT id FROM post WHERE blog=? AND content LIKE '%#$tag%'
                  OR content LIKE '%<a href=\"/tag/$tag\" title=\"$tag\">$tag</a>%'
                  OR content LIKE '%<a title=\"$tag\" href=\"/tag/$tag\">$tag</a>%'
                  ORDER BY moment DESC LIMIT ?,?";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("iii", $this->id, $beg, $num);
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
    public function getSubdomain() {
        return empty($this->subdomain) ? "" : (string) trim($this->subdomain);
    }
    //******************************************************************************
    public function getTimeRange() {
        $db    = Database::connect();
        $query = "SELECT moment FROM post WHERE blog=? ORDER BY moment LIMIT 1";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($moment);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $r     = array();
        $r[0]  = empty($moment) ? 0 : $moment;
        $query = "SELECT moment FROM post WHERE blog=? ORDER BY moment DESC LIMIT 1";
        if (!$stmt = $db->prepare($query))
            throw new Exception("err.database:" . $db->error);
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($moment);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $r[1] = empty($moment) ? 0 : $moment;
        return $r;
    }
    //******************************************************************************
    public function getTitle() {
        return empty($this->title) ? "" : (string) trim($this->title);
    }
    //******************************************************************************
    public function getURL() {
        return "http://" . $this->subdomain . ".netnevis.ir";
    }
    //******************************************************************************
    public function identify() {
        if (!isset($_SESSION))
            session_start();
        $this->id = empty($_SESSION["blog"]) ? 0 : (int) trim($_SESSION["blog"]);
        $this->connect();
    }
    //******************************************************************************
    public function setAbout($about) {
        $about = empty($about) ? "..." : htmlspecialchars(trim($about));
        $about = str_replace("&lt;br&gt;","<br>",$about);
        $about = str_replace("&lt;BR&gt;","<br>",$about);
        $about = str_replace("&lt;br/&gt;","<br>",$about);
        $about = str_replace("&lt;BR/&gt;","<br>",$about);
        $about = str_replace("&lt;br /&gt;","<br>",$about);
        $about = str_replace("&lt;BR /&gt;","<br>",$about);
        $this->about = $about;
    }
    //******************************************************************************
    public function setCover($cover) {
        $cover = (empty($cover)) ? "" : (string) trim($cover);
        if (!filter_var($cover, FILTER_VALIDATE_URL) || empty($cover))
            throw new Exception("msg.cover_wrong");
        $this->cover = $cover;
    }
    //******************************************************************************
    public function setDescription($description) {
        $this->description = empty($description) ? "" : (string) htmlspecialchars(trim($description));
    }
    //******************************************************************************
    public function setEmail($email) {
        $email = (empty($email)) ? "" : (string) trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new Exception("msg.email_wrong");
        $this->email = $email;
    }

    //******************************************************************************
    public function setFavicaon($url) {
        $url = (empty($url)) ? "" : (string) trim($url);
        if (!filter_var($url, FILTER_VALIDATE_URL) || empty($url))
            throw new Exception("msg.favicon_wrong");
        $this->favicon = $url;
    }
    //******************************************************************************
    public function setGAuthor($link) {
        $link = (empty($link)) ? "" : (string) trim($link);
        if (strpos($link, "http") === false)
            throw new Exception("msg.member_gauthor_wrong");
        $this->g_auhtor = $link;
    }
    //******************************************************************************
    public function setGPublisher($link) {
        $link = (empty($link)) ? "" : (string) trim($link);
        if (strpos($link, "http") === false)
            throw new Exception("msg.member_gpublisher_wrong");
        $this->g_publisher = $link;
    }
    //******************************************************************************
    public function setID($id) {
        $this->id = empty($id) ? 0 : (int) trim($id);
    }
    //******************************************************************************
    public function setLogo($logo) {
        $logo = (empty($logo)) ? "" : (string) trim($logo);
        if (!filter_var($logo, FILTER_VALIDATE_URL) || empty($logo))
            throw new Exception("msg.logo_wrong");
        $this->logo = $logo;
    }
    //******************************************************************************
    public function setMetatags($metatags) {
        $metatags = (empty($metatags)) ? "" : (string) trim($metatags);
        $metatags = strip_tags($metatags,'<meta>');
        preg_match_all('/<meta[^>]*>/',$metatags,$tags);
        $metatags = implode($tags[0],"\n");
        $this->metatags = $metatags;
    }
    //******************************************************************************
    public function setSubdomain($subdomain) {
        $subdomain = (empty($subdomain)) ? "" : strtolower((string) trim($subdomain));
        $pattern   = '/^[a-zA-Z0-9\-]*$/';
        if (!preg_match($pattern, $subdomain))
            throw new Exception("msg.subdomain_wrong");
        if (substr_count($subdomain, '-') > 1)
            throw new Exception("msg.subdomain_wrong");
        if (strlen($subdomain) < 5 || strlen($subdomain) > 20)
            throw new Exception("msg.subdomain_length");
        $subs = Config::load("forbiddens");
        $is_forbidden = 0;
        foreach($subs as $sub) {
            if(preg_match($sub,$subdomain))
                $is_forbidden = 1;
        }
        if($is_forbidden)
            throw new Exception("msg.subdomain_forbidden");
        $this->subdomain = $subdomain;
    }
    //******************************************************************************
    public function setTitle($title) {
        $this->title = empty($title) ? "---" : (string) htmlspecialchars(trim($title));
    }
    //******************************************************************************
}