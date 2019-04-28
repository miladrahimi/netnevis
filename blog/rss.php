<?php
header('Content-type: text/xml; charset=utf-8');
echo '<?xml version="1.0" ?><rss version="2.0">' . "\r\n";
echo '<channel>' . "\r\n";
echo '<title>' . $blog->getTitle() . '</title>' . "\r\n";
echo '<description>' . $blog->getDescription() . '</description>' . "\r\n";
echo '<link>' . $blog->getURL() . '</link>' . "\r\n";
echo '<item>' . "\r\n";
echo '<title>' . $blog->getTitle() . '</title>' . "\r\n";
echo '<description>' . $blog->getDescription() . '</description>' . "\r\n";
echo '<link>' . $blog->getURL() . '</link>' . "\r\n";
echo '<pubDate>' . date(DATE_RSS, $blog->getMoment()) . '</pubDate>' . "\r\n";
echo '</item>' . "\r\n";
$posts = $blog->getAllPosts();
$post  = new Post();
foreach ($posts as $id) {
    $post->setID($id);
    $post->connect();
    echo '<item>' . "\r\n";
    echo '<title>' . $post->getTitle() . '</title>' . "\r\n";
    echo '<description>' . preg_replace("/&([^;]+);/","",strip_tags($post->getContent())) . '</description>' . "\r\n";
    echo '<link>' . $blog->getURL() . "/post/" . $post->getID() . '/' . $post->getTitle() . '</link>' . "\r\n";
    echo '<pubDate>' . date(DATE_RSS, $post->getMoment()) . '</pubDate>' . "\r\n";
    echo '</item>' . "\r\n";
}
echo '</channel></rss>';