<!-- <div id="header-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active" style="height: 410px;">
                            <img class="img-fluid" src="img/carousel-1.jpg" alt="Image">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h4 class="text-light text-uppercase font-weight-medium mb-3">10% Off Your First Order</h4>
                                    <h3 class="display-4 text-white font-weight-semi-bold mb-4">Fashionable Dress</h3>
                                    <a href="" class="btn btn-light py-2 px-3">Shop Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item" style="height: 410px;">
                            <img class="img-fluid" src="img/carousel-2.jpg" alt="Image">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h4 class="text-light text-uppercase font-weight-medium mb-3">10% Off Your First Order</h4>
                                    <h3 class="display-4 text-white font-weight-semi-bold mb-4">Reasonable Price</h3>
                                    <a href="" class="btn btn-light py-2 px-3">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
                        <div class="btn btn-dark" style="width: 45px; height: 45px;">
                            <span class="carousel-control-prev-icon mb-n2"></span>
                        </div>
                    </a>
                    <a class="carousel-control-next" href="#header-carousel" data-slide="next">
                        <div class="btn btn-dark" style="width: 45px; height: 45px;">
                            <span class="carousel-control-next-icon mb-n2"></span>
                        </div>
                    </a>
                </div> -->

<?php
$result = dbSelect("tbl_slideshow", "*", "enable='1' ", "order by ssorder asc");
$num = mysqli_num_rows($result);
?>
<div id="header-carousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <?php
        $i = 0;
        while ($i < $num) {
        ?>
            <li data-target="#header-carousel" data-slide-to="<?= $i ?>" <?= ($i == 0 ? "class='active'" : "") ?>><span class="small-circle"></span></li>
        <?php
            $i++;
        }
        ?>


    </ol>
    <div class="carousel-inner">
        <?php
        $i = 0;
        while ($row = mysqli_fetch_array($result)) {
        ?>
            <div class="carousel-item <?= ($i == 0 ? 'active' : '') ?>" style="height: 410px;">
                <img class="img-fluid" src="img/<?= $row['img'] ?>" alt="Image">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3" style="max-width: 700px;">
                        <h4 class="text-light text-uppercase font-weight-medium mb-3"><?= $row['subtitle'] ?></h4>
                        <h3 class="display-4 text-white font-weight-semi-bold mb-4"><?= $row['title'] ?></h3>
                        <p><?= $row['text'] ?></p>
                        <a href="<?= $row['link'] ?>" class="btn btn-light py-2 px-3">Shop Now</a>
                    </div>
                </div>
            </div>

            <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
                <div class="btn btn-dark" style="width: 45px; height: 45px;">
                    <span class="carousel-control-prev-icon mb-n2"></span>
                </div>
            </a>
            <a class="carousel-control-next" href="#header-carousel" data-slide="next">
                <div class="btn btn-dark" style="width: 45px; height: 45px;">
                    <span class="carousel-control-next-icon mb-n2"></span>
                </div>
            </a>
        <?php
            $i++;
        }
        ?>
    </div>
</div>