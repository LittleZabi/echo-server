<?php
require_once './db.php';


function get_client_ip()
{
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function random($limit)
{
    $newStr = '';
    $strings = '0987654321abcdefghijklmnopqrstuvwxyz0123456789xyzbcaklwmnop';
    for ($i = 0; $i < $limit; $i++) {
        $r = rand(0, (strlen($strings) - 1));
        $newStr .= $strings[$r];
    }
    return $newStr;
}
function clear($str)
{
    global $db;
    return $db->real_escape_string($str);
}

if (isset($_GET['delete-link'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM fileslist WHERE id = $id";
    $query = $db->query($sql);
    exit('success');
}

if (isset($_POST['edit-link'])) {
    $link = $_POST['file-link'];
    $filename = $_POST['file-name'];
    $finalLink = $_POST['final-link'];
    $complete = $finalLink !== '' ? 1 : 0;
    $cache = $_POST['cache-type'];
    $id = $_POST['id'];
    $sql = "UPDATE fileslist SET `base_url` = '$link', `filename`='$filename',`finalLink` = '$finalLink', `complete` = $complete,  `cache`='$cache' WHERE `id` = $id";
    $query = $db->query($sql);
    exit('success');
}

if (isset($_GET['submit-link'])) {
    $link = $_GET['file-link'];
    $filename = $_GET['file-name'];
    $cache = $_GET['cache-type'];
    $sql = "SELECT id FROM fileslist WHERE filename = '$filename'";
    $query = $db->query($sql);
    if ($query->num_rows > 0) {
        exit(json_encode(['id' => $query->fetch_assoc()['id'], 'message' => 'exist']));
    }
    $sql = "INSERT INTO `fileslist` (`base_url`, `filename`, `cache`) VALUES ('$link', '$filename',  '$cache')";
    $query = $db->query($sql);
    exit(json_encode(['message' => 'success', 'id' => 0]));
}
if (isset($_GET['set-req'])) {
    $pid = clear($_GET['pid']);
    $token = clear($_GET['token'] ?? '');
    $sql = "
    UPDATE
        fileslist
    SET
        `referToBot` = 1
    WHERE
        `id` = $pid
    ";
    $query = $db->query($sql);

    exit('Request succesfully send! wait until we done your process');
}


if (isset($_GET['client-req'])) {
    exit('asdfasdfasdfasdf');
    $pid = clear($_GET['parent']);
    $token = clear($_GET['token']);
    $ip = get_client_ip();
    $sql = "INSERT INTO client_requests(file_slug, parent_id, ip_address) VALUES('$token', $pid, '$ip')";
    exit($sql);
    $query = $db->query($sql);
    exit('success');
}
if (isset($_GET['show-childs'])) {
    $id = clear($_GET['id']);
    $sql = "SELECT * FROM `child_list`  WHERE `parent_file_id` = $id ORDER BY `id` DESC";
    $query = $db->query($sql);
    $arr = array();
    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) $arr[]  = $row;
        exit(json_encode($arr));
    } else {
        exit('');
    }
}

if (isset($_GET['search'])) {
    $q = clear($_GET['q']);
    $sql = "
    SELECT
        *
    FROM
        fileslist
    WHERE
        id LIKE '%$q%' OR base_url LIKE '%$q%' OR filename LIKE '%$q%'
    ";
    $k = [];
    $query = $db->query($sql);
    if ($query->num_rows > 0) while ($row = $query->fetch_assoc()) $k[] = $row;
    exit(json_encode($k));
}

if (isset($_GET['delete-child'])) {
    $id = clear($_GET['id']);
    $sql = "DELETE FROM child_list WHERE id = $id";
    $query = $db->query($sql);
    exit('success');
}

if (isset($_GET['add-child'])) {
    $id = clear($_GET['id']);
    $filename = clear($_GET['filename']);
    $slug = random(15);
    $sql = "SELECT * FROM `child_list`  WHERE `new_filename` = '$filename'";
    $query = $db->query($sql);
    $arr = array();
    if ($query->num_rows > 0) {
        exit('exist');
    } else {
        $sql = "INSERT INTO child_list (token, parent_file_id, new_filename) VALUES('$slug', $id, '$filename')";
        $query = $db->query($sql);
        $sql = "INSERT INTO `requests` (`child_token`, `count`) VALUES('$slug', 0)";
        $query = $db->query($sql);
        exit('success');
    }
}

if (isset($_POST['updateName'])) {
    $id = clear($_POST['id']);
    $filename = clear($_POST['to']);
    $parentName = clear($_POST['parentName']);
    $finalLink = clear($_POST['finalLink']);
    $complete = $finalLink != '' ? 1 : 0;
    $pid = clear($_POST['pid']);
    $sql = "UPDATE child_list SET `new_filename` = '$filename' WHERE id = $id";
    $query = $db->query($sql);
    $sql = "UPDATE fileslist SET `filename` = '$parentName', `complete` = $complete, `finalLink` = '$finalLink' WHERE id = $pid";
    $query = $db->query($sql);
    exit('success');
}
