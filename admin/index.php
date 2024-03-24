<?php
include "../config.php";
include "../library/db.php";
include "../library/img.php";

$page = "dashboard.php";
if (isset($_GET['p'])) {
  $p = $_GET['p'];
  switch ($p) {
    case "slideshow":
      $page = "slideshow.php";
      break;
    case "ssform":
      $page = "ssform.php";
      break;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include "includes/head.php" ?>

<body class="g-sidenav-show  bg-gray-200">
  <?php include "includes/navbar.php" ?>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <?php include "includes/header.php" ?>
    <!-- End Navbar -->

    <div class="container-fluid py-4">
      <?php include $page ?>

    </div>
    <?php include "includes/footer.php" ?>
  </main>
  <?php include "includes/sth.php" ?>
</body>

</html>