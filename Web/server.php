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
    $data = array();
    if ($query->num_rows > 0) while ($row = $query->fetch_assoc())  $data['fileslist'][] = $row;
    $sql = "SELECT
                `child_list`.`gdrive`,
                `child_list`.`id` as `child_id`,
                `child_list`.`parent_file_id`,
                `child_list`.`new_filename`,
                `fileslist`.`finalLink`
            FROM
                `child_list`, `fileslist`
            WHERE
                `child_list`.`parent_file_id` = `fileslist`.`id` AND `fileslist`.`finalLink` != '' AND `child_list`.`gdrive` = ''";
    $q = $db->query($sql);

    if ($q->num_rows > 0) while ($row = $q->fetch_assoc()) $data['child_list'][] = $row;
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
$k = file_get_contents('php://input');
$k = json_decode($k);

if ($k->names) {
    $sql = '';
    foreach ($k->childs as $u) $sql .= "UPDATE `child_list` SET `gdrive` = '$u->link' WHERE id = $u->id; ";
    $p = $db->multi_query($sql);
    exit('success');
}
