<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<?php
require_once './db.php';
$sql = "SELECT `base_url`,`filename`, `slug` FROM `fileslist` WHERE 1 ORDER BY `id` DESC LIMIT 100";
$query = $db->query($sql);
$data = [];
if ($query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $data[] = $row;
    }
}
?>

<div style="width: 18rem;">
    <div class="container">
        <div class="wrap">
            <?php
            foreach ($data as $item) {
            ?>
                <a href="./view.php?url=<?php echo $item['slug']; ?>" class="btn btn-primary anchors" style="border-radius:0;margin-top: 5px;text-align:left;"><?php echo $item['filename']; ?></a>
            <?php
            }
            ?>
        </div>
    </div>
</div>