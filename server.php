<?php
$db = mysqli_connect('localhost', 'root', '', 'kandle');
$urlTable = `fileslist`;
if (isset($_GET['get-links'])) {
    // $sql = "SELECT `id`, `base_url`, `cache` FROM `fileslist` WHERE `requested` = 1 ORDER BY `id` DESC LIMIT 10";
    $sql = "SELECT `fileslist`.`id`, `fileslist`.`base_url`, `fileslist`.`slug`, `request_links`.`id` as file_id, `request_links`.`cache`,`request_links`.`file` FROM `fileslist`, `request_links` WHERE `fileslist`.`slug` = `request_links`.`file` AND `request_links`.`complete` = 0";
    $query = $db->query($sql);
    $data = [];
    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $data[] = $row;
        }
    }
    exit(json_encode($data));
}

if (isset($_GET['set-link'])) {
    $id = $_POST['id'] ?? $_GET['id'];
    $url = $_POST['url'] ?? $_GET['url'];
    $sql = "UPDATE `fileslist` SET `complete` = 1 WHERE `id` = $id";
    $db->query($sql);
    $sql = "UPDATE `request_links` SET `finalLink` = '$url', `complete` = 1 WHERE `file_id` = $id";
    $db->query($sql);
    // $sql = "SELECT * FROM `renamelist` WHERE `file` = $id";
    // $q = $db->query($sql);
    // if ($q->num_rows > 0) {
    //     $sql = "UPDATE `renamelist` SET `driveID` = '$gdriveID', `isDriveID` = $isDriveID, `renamed` = 0, `newName` = '$newName' WHERE `file` = $id";
    // } else {
    //     $sql = "INSERT INTO `renamelist` (`file`, `driveID`, `isDriveID`, `newName`, `message`) 
    //     VALUES($id, '$gdriveID', $isDriveID, '$newName', '$message')";
    // }
    // $q = $db->query($sql);
    exit('success');
}
