<?php
$db = mysqli_connect('localhost', 'root', '', 'kandle');
$urlTable = `fileslist`;
if (isset($_GET['get-links'])) {
    // $sql = "SELECT `id`, `base_url`, `cache` FROM `fileslist` WHERE `requested` = 1 ORDER BY `id` DESC LIMIT 10";
    $sql = "SELECT `fileslist`.`base_url`,`fileslist`.`newName`, `fileslist`.`slug`, `request_links`.`id`, `request_links`.`cache`,`request_links`.`file` FROM `fileslist`, `request_links` WHERE `fileslist`.`slug` = `request_links`.`file` AND `request_links`.`complete` = 0";
    $query = $db->query($sql);
    $data = [];
    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $data[] = $row;
        }
    }
    exit(json_encode($data));
}
$_POST = json_decode(file_get_contents("php://input"), true);

if (isset($_POST['set-link'])) {
    $id = $_POST['id'] ?? $_GET['id'];
    $url = $_POST['url'] ?? $_GET['url'];
    $isDriveID = $_POST['isDriveId'] ?? $_GET['isDriveId'] ?? 0;
    $gdriveID = $_POST['fileID'] ?? $_GET['fileID'];
    $newName = $_POST['newName'] ?? $_GET['newName'] ?? $url;
    $message = $_POST['message'] ?? $_GET['message'] ?? '';
    $sql = "UPDATE `request_links` SET `finalLink` = '$url', `complete` = 1 WHERE `id` = $id";
    $db->query($sql);
    $sql = "SELECT * FROM `renamelist` WHERE `file` = $id";
    $q = $db->query($sql);
    if ($q->num_rows > 0) {
        $sql = "UPDATE `renamelist` SET `driveID` = '$gdriveID', `isDriveID` = $isDriveID, `renamed` = 0, `newName` = '$newName' WHERE `file` = $id";
    } else {
        $sql = "INSERT INTO `renamelist` (`file`, `driveID`, `isDriveID`, `newName`, `message`) 
        VALUES($id, '$gdriveID', $isDriveID, '$newName', '$message')";
    }
    $q = $db->query($sql);
    exit('success');
}
