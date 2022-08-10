<?php
session_start();
print_r($_SESSION);
exit();
if (!isset($_SESSION['login'])) {
  header('location: ./login.php');
}
define('PATH', __DIR__);
define('ROOT_VIEW', '/page/view.php?slug=');
// define('ROOT_VIEW', 'https://aliunlockers.com/kandle/view.php?slug=');
require_once('./modules/constants.php');
require_once('./modules/db.php');
$page = 'dashboard';
if (isset($_GET['page'])) {
  $page = $_GET['page'] ?? 'dashboard';
  if (!file_exists('./pages/' . $page . '.phtml')) {
    $page = 'dashboard';
  }
}
?>
<?php require_once('./pages/header.php'); ?>
<?php require_once('./pages/modal.php'); ?>

<body id="page-top">
  <div id="wrapper">
    <?php require_once('./pages/sidebar.php'); ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?php require_once('./pages/navbar.php'); ?>
        <?php require_once('./pages/' . $page . '.phtml'); ?>
      </div>
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Echo Server <?php echo date('Y'); ?> Created by LittleZabi and Blueterminal Lab</span>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <?php require_once('./pages/scripts.php'); ?>
</body>

</html>