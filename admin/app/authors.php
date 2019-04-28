<?php

// Autoload
function admin_bridge_authors_autoload($class) {
    include_once dirname(__FILE__) . "/../../core/" . strtolower($class) . ".php";
}
spl_autoload_register("admin_bridge_authors_autoload");

// Get query
$q = empty($_GET["q"]) ? "" : trim($_GET["q"]);
$query = Security::JSONInput($q);

// getApplys
if ($query->command == "getApplys") {
    if (!Member::detect())
        exit("identity_error");
    try {
        $blog = new Blog();
        $blog->identify();
        $applys_id = $blog->getApplys();
        $applys    = array();
        $member = new Member();
        $i = 0;
        foreach ($applys_id as $apply_id=>$member_id) {
            $member->setID($member_id);
            $member->connectByID();
            $applys[$i]             = array();
            $applys[$i]["id"]       = $apply_id;
            $applys[$i]["nickname"] = $member->getNickname();
            $applys[$i]["email"]    = $member->getEmail();
            $i++;
        }
        exit(json_encode($applys));
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// getAuthors
if ($query->command == "getAuthors") {
    if (!Member::detect())
        exit("identity_error");
    try {
        $blog = new Blog();
        $blog->identify();
        $authors_id = $blog->getAuthors();
        $authors    = array();
        foreach ($authors_id as $job_id => $member_id) {
            $job = new Job();
            $job->setID($job_id);
            $job->connectByID();
            $authors[$job_id]         = array();
            $authors[$job_id]["role"] = $job->getRole();
            $member                   = new Member();
            $member->setID($member_id);
            $member->connectByID();
            $authors[$job_id]["nickname"] = $member->getNickname();
            $authors[$job_id]["email"]    = $member->getEmail();
        }
        exit(json_encode($authors));
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// Apply
if ($query->command == "apply") {
    if (!Member::detect())
        exit("identity_error");
    $id   = empty($query->id) ? 0 : trim($query->id);
    $role = empty($query->role) ? "" : trim($query->role);
    try {
        $member = new Member();
        $member->identify();
        if (!$member->hasPermissionToApply())
            exit("permission_error");
        $blog = new Blog();
        $blog->identify();
        $apply = new Apply();
        $apply->setID($id);
        $apply->connect();
        if ($apply->getBlog() != $blog->getID())
            exit("permission_error");
        $job = new Job();
        $job->setBlog($blog->getID());
        $job->setMember($apply->getMember());
        $job->setRole($role);
        $job->create();
        $apply->delete();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// Repost
if ($query->command == "repost") {
    if (!Member::detect())
        exit("identity_error");
    $id   = empty($query->id) ? 0 : trim($query->id);
    $role = empty($query->role) ? "" : trim($query->role);
    try {
        $member = new Member();
        $member->identify();
        if (!$member->hasPermissionToRepost())
            exit("permission_error");
        $blog = new Blog();
        $blog->identify();
        $job = new Job();
        $job->setID($id);
        $job->connectByID();
        if ($job->getBlog() != $blog->getID())
            exit("permission_error");
        if ($job->getRole() == "admin") {
            if ($job->getMember() != Member::detect()) {
                exit("disrate_error");
            } else {
                $admin = 0;
                $authors_id = $blog->getAuthors();
                $job = new Job();
                foreach($authors_id as $job_id=>$member_id) {
                    $job->setID($job_id);
                    $job->connectByID();
                    if ($job->getRole() == "admin")
                        $admin++;
                }
                if ($admin < 2)
                    exit("ace_error");
                $job->setID($id);
                $job->connectByID();
            }
        }
        $job->setRole($role);
        $job->edit();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e));
    }
}

// Reject
if ($query->command == "reject") {
    if (!Member::detect())
        exit("identity_error");
    $id = empty($query->id) ? 0 : trim($query->id);
    try {
        $member = new Member();
        $member->identify();
        if (!$member->hasPermissionToRejectAuthor())
            exit("permission_error");
        $blog = new Blog();
        $blog->identify();
        $apply = new Apply();
        $apply->setID($id);
        $apply->connect();
        if ($apply->getBlog() != $blog->getID())
            exit("permission_error");
        $apply->delete();
        exit("done");
    } catch (Exception $e) {
        exit(Reporter::report($e, true));
    }
}

// Delete
if ($query->command == "delete") {
    if (!Member::detect())
        exit("identity_error");
    $id = empty($query->id) ? 0 : trim($query->id);
    try {
        $member = new Member();
        $member->identify();
        if (!$member->hasPermissionToDeleteAuthor())
            exit("permission_error");
        $job = new Job();
        $job->setID($id);
        $job->connectByID();
        $blog = new Blog();
        $blog->identify();
        if ($job->getBlog() != $blog->getID())
            exit("permission_error");
        if ($job->getMember() == Member::detect()) {
            $admin = 0;
            $authors_id = $blog->getAuthors();
            $job = new Job();
            foreach($authors_id as $job_id=>$member_id) {
                $job->setID($job_id);
                $job->connectByID();
                if ($job->getRole() == "admin")
                    $admin++;
            }
            if ($admin < 2)
                exit("ace_error");
            $job->setID($id);
            $job->connectByID();
        } else {
            if($job->getRole() == "admin")
                exit("regicide_error");
        }
        $job->delete();
        exit("done");
    }
    catch (Exception $e) {
        exit(Reporter::report($e));
    }
}
