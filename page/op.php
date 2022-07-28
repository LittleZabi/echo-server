<?php
require_once './db.php';
function random($limit)
{
    $newStr = '';
    $strings = 'abcdefghijklmnopqrstuvwxyz0123456789xyzbcaklwmnop';
    for ($i = 0; $i < $limit; $i++) {
        $r = rand(0, (strlen($strings) - 1));
        $newStr .= $strings[$r];
    }
    return $newStr;
}
if (isset($_GET['submit-link'])) {
    $link = $_GET['file-link'];
    $filename = $_GET['file-name'];
    $cache = $_GET['cache-type'];
    $slug =  random(12);
    $sql = "INSERT INTO `fileslist` (`base_url`, `filename`, `slug`, `cache`) VALUES ('$link', '$filename', '$slug', '$cache')";
    $query = $db->query($sql);
    header('Location: ./insert.php?msg=Successfully added');
    exit('success');
}
if (isset($_GET['set-req'])) {
    $slug = $_GET['slug'];
    $sql = "SELECT * FROM `fileslist` WHERE slug = '$slug'";
    $query = $db->query($sql);
    if ($query->num_rows > 0) {
        $item = $query->fetch_assoc();

        $sql = "SELECT * FROM `request_links` WHERE `file` ='$slug'";
        $q = $db->query($sql);
        if ($q->num_rows > 0) {
            header('Location: ./view.php?url=' . $slug . '&msg=Request is already set!');
        } else {
            $cache = $item['cache'] ?? 'Featured';
            $filename = $item['filename'];
            $file_id = $item['id'];
            $sql = "INSERT INTO `request_links` (`file_id`, `file`,`filename`, `cache`) VALUES($file_id, '$slug','$filename', '$cache')";
            $db->query($sql);
            header('Location: ./view.php?url=' . $slug . '&msg=Request Successfully sent!');
        }
    } else {

        header('Location: ./view.php?msg=Error file does not exist');
    }
}
