<?php
 include "config.php";
 include "library/db.php";
    $page = "home.php";
    if(isset($_GET['p']))
    {
        $p = $_GET['p'];
        switch($p)
        {
            case "shop":
                $page = "shop.php";
                break;
            case "contact":
                $page = "contact.php";
                break;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<?php include "includes/head.php"?>

<body>
    <!-- Topbar Start -->
    <?php include "includes/nav.php"?>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid mb-5">
        <div class="row border-top px-xl-5">
           <?php include "includes/aside.php"?>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                    <a href="" class="text-decoration-none d-block d-lg-none">
                        <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper</h1>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                   <?php include "includes/header.php"?>
                </nav>
                <?php include $page?>
            </div>
        </div>
    </div>
    <!-- Navbar End -->


    <!-- Featured Start -->
    <?php include "includes/sth.php"?>
</body>

</html>