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
    $cache = $_GET['cache-type'];
    $slug =  random(20);
    $sql = "INSERT INTO `fileslist` (`base_url`, `slug`, `cache`) VALUES ('$link', '$slug', '$cache')";
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
            $cache = $item['cache'];
            $sql = "INSERT INTO `request_links` (`file`, `cache`) VALUES('$slug', '$cache')";
            $db->query($sql);
            header('Location: ./view.php?url=' . $slug . '&msg=Request Successfully sent!');
        }
        // if ($item['finalLink'] == '') {
        //     $sql = "UPDATE `fileslist` SET `requested` = 1, `finalLink` = '' WHERE `slug` = '$slug'";
        //     $db->query($sql);
        //     header('Location: ./view.php?url=' . $slug . '&msg=Request Successfully sent!');
        // } else {
        //     echo $item['finalLink'];
        // }
    } else {

        header('Location: ./view.php?msg=Error file does not exist');
    }
}
