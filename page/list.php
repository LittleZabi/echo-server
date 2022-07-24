<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
<?php
require_once './db.php';
$sql = "SELECT `base_url`, `slug` FROM `fileslist` WHERE 1 ORDER BY `id` DESC LIMIT 10";
$query = $db->query($sql);
$data = [];
if ($query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $data[] = $row;
    }
}
?>

<div style="width: 18rem;">


    <?php
    foreach ($data as $item) {
    ?>
        <a href="./view.php?url=<?php echo $item['slug']; ?>" class="btn btn-primary" style="border-radius:0;margin-top: 5px;text-align:left;"><?php echo $item['base_url']; ?></a>
    <?php
    }

    ?>

</div>