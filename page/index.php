<?php
require_once './db.php';
require_once './header.php';
session_start();
if (isset($_SESSION['login'])) {
} else {
    header('location: ./login.php');
}
$sql = "
        SELECT
            fileslist.filename,
            fileslist.base_url,
            fileslist.finalLink,
            fileslist.id,
            fileslist.createdAt,
            fileslist.complete
        FROM
            fileslist
";
$baseFiles = [];
$data = $db->query($sql);
if ($data->num_rows) while ($row = $data->fetch_assoc()) $baseFiles[] = $row;
// print_r($baseFiles);
function date_($date)
{
    return date_format(date_create($date), 'Y/m/d');
}
?>

<div class="container">
    <div class="left">
        <div class="list-group">
            <?php
            foreach ($baseFiles as $item) {
            ?>
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?php echo $item['filename'] ?></h5>
                        <small><?php echo date_($item['createdAt']); ?></small>
                    </div>
                    <p class="mb-1"><?php echo $item['base_url']; ?></p>
                    <small><b>Status: </b><?php echo $item['finalLink'] == '' ? 'URL is not set' : $item['finalLink']; ?></small>
                    <p class="mb-1">Bot Operation: <?php echo $item['complete'] ? 'Performed' : 'Not Perform Yet!'; ?></p>
                </a>
            <?php } ?>
        </div>
    </div>
    <div class="right">
        right
    </div>
</div>