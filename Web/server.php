<?php
$db = mysqli_connect('localhost', 'root', '', 'kandle');
$urlTable = `fileslist`;
if (isset($_GET['get-links'])) {
    $sql = "
        SELECT
            id,
            base_url,
            cache
        FROM
            fileslist
        WHERE
            fileslist.referToBot = 1
        ";
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
    $sql = "
        UPDATE
            `fileslist`
        SET
            `complete` = 1,
            `referToBot` = 0,
            `finalLink` = '$url'
        WHERE
            `id` = $id";
    $db->query($sql);
    exit('success');
}
