<?php
require_once './header.php';
require_once './db.php';
session_start();
if (isset($_SESSION['login'])) {
} else {
    header('location: ./login.php');
}
if (isset($_GET['msg'])) {
    if ($_GET['msg'] != '') {
        echo '<pre style="padding: 10px;">';
        echo $_GET['msg'];
        echo '</pre>';
    }
}
$sql = "SELECT `base_url`,`filename`, `id` FROM `fileslist` WHERE 1 ORDER BY `id` DESC LIMIT 100";
$query = $db->query($sql);
$data = [];
if ($query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $data[] = $row;
    }
}
?>
<div style="overflow:hidden">
    <div class="modal" id="modal">

    </div>
    <div class="container">
        <div class="wrap">
            <h4>Base urls list...</h4>
            <?php
            foreach ($data as $item) {
            ?>
                <section>
                    <span class="title"><?php echo $item['filename']; ?></span>
                    <span class="baselink"><?php echo $item['base_url']; ?></span>
                    <br>
                    <button onclick="handleShowChilds(<?php echo $item['id'] ?>, this)" class="btn btn-primary">Show Childs <i class="fa fa-view"></i></button>
                    <button onclick="handleAddNewChild(<?php echo $item['id']; ?>, this)" class="btn btn-primary">Add New Child <i class="fa fa-plus"></i></button>
                </section>

            <?php
            }
            ?>
        </div>
    </div>
</div>