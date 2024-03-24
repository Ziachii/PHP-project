<?php
    $ssid = "";
    $title = "";
    $subtitle = "";
    $text = "";
    $link = "";
    $enable = "1";
    $img = "";
    if(isset($_GET['ssid']))
    {
        $ssid = $_GET['ssid'];
        $result = dbSelect("tbl_slideshow","*","ssid=$ssid","");
        $row = mysqli_fetch_array($result);
        $title = $row['title'];
        $subtitle = $row['subtitle'];
        $text = $row['text'];
        $link = $row['link'];
        $enable = $row['enable'];
        $img = $row['img'];
    }
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<main class = "content">
    <div class = "container-fluid p-0">
    <h1 class = "h4 mb-b "><?=$ssid==""?"Add":"Edit"?> slideshow</h1> <br>
    <form action = "index.php?p=slideshow&action=<?=$ssid==""?"3":"5&ssid=$ssid"?>" method = "POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="txttitle" class="form-label" >Title</label>
            <input type="text" class="form-control" id="txttitle" name = "txttitle" required value = "<?=$title?>">
        </div>

        <div class="mb-3">
            <label for="txtsubtitle" class="form-label">Subtitle</label>
            <input type="text" class="form-control" id="txtsubtitle" name = "txtsubtitle" value = "<?=$subtitle?>" >
        </div>

        <div class="mb-3">
            <label for="tatext" class="form-label">Text</label>
            <textarea class="form-control" id="tatext" name = "tatext" rows="3"><?=$text?></textarea>
        </div>

        <div class="mb-3">
            <label for="txtlink" class="form-label">Link</label>
            <input type="text" class="form-control" id="txtlink" value="<?=$link?>" name = "txtlink">
        </div>

        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="chkenable" name = "chkenable" <?=$enable=="1"?"checked":""?>>
            <label class="form-check-label" for="chkenable">Enable</label>
        </div>

        <div class="mb-3">
            <label for="fileimg" class="form-label">Select your slideshow Image</label>
            <input class="form-control form-control-sm" id="fileimg" name = "fileimg" type="file"  accept="image/png,image/gif,image/jpeg" <?=$ssid=""?"required":""?>>
            <?php
                if($img != "")
                {
                    echo "<img src= '../img/thumbnail/$img'>";
                }
            ?>
        </div>
        
            <input type="submit" value = "Submit" class = "btn bg-gradient-primary" style=" text-transform: capitalize"/>
            <a href="index.php?p=slideshow" class = "btn btn-secondary" style=" text-transform: capitalize">Cancel</a>
    </form>
    </div>
</main>
