<?php
//action
// 0 delete
// 1 moveup/move down 
// 2 enable/ disable
// 3 insert 
// 4 edit 
// 5 update
$error = -1;
$errmsg = "";
if (isset($_GET['action'])) {
  $action = $_GET['action'];
  switch ($action) {
    case "0":
      $ssid = $_GET['ssid'];
      $result = dbSelect("tbl_slideshow", "img", "ssid=$ssid", "");
      $row = mysqli_fetch_array($result);
      $img = $row['img'];
      $result = dbDelete("tbl_slideshow", "ssid = $ssid");
      $imgpath = "../img/$img";
      $imgthumbnailpath = "../img/thumbnail/$img";
      if (file_exists($imgpath) && $result) {
        unlink($imgpath);
        if(file_exists($imgthumbnailpath))
        {
           unlink($imgthumbnailpath);
        }
      }
      break;
    case "1":
      $ssid = $_GET['ssid'];
      $result = dbSelect("tbl_slideshow", "ssorder", "ssid = $ssid", "");
      $row = mysqli_fetch_array($result);
      $c_ssorder = $row['ssorder'];

      if ($_GET['d'] == "0") {
        $result = dbSelect("tbl_slideshow", "ssid,ssorder", "ssorder<$c_ssorder", "order by ssorder desc limit 1");
        $row = mysqli_fetch_array($result);
        $n_ssid = $row['ssid'];
        $n_ssorder = $row['ssorder'];

        dbUpdate("tbl_slideshow", ["ssorder" => $n_ssorder], "ssid=$ssid", "");
        dbUpdate("tbl_slideshow", ["ssorder" => $c_ssorder], "ssid=$n_ssid", "");
      } else {
        $result = dbSelect("tbl_slideshow", "ssid,ssorder", "ssorder>$c_ssorder", "order by ssorder asc limit 1");
        $row = mysqli_fetch_array($result);
        $n_ssid = $row['ssid'];
        $n_ssorder = $row['ssorder'];

        dbUpdate("tbl_slideshow", ["ssorder" => $n_ssorder], "ssid=$ssid", "");
        dbUpdate("tbl_slideshow", ["ssorder" => $c_ssorder], "ssid=$n_ssid", "");
      }
      break;

    case "2":
      $ssid = $_GET['ssid'];
      $enable = $_GET['enable'];
      $data = ["enable" => "$enable"];
      $result = dbUpdate("tbl_slideshow", $data, "ssid=$ssid");
      if ($result) {
        $error = 0;
        $errmsg = "A slideshow has been enabled/disabled sucessfully!";
      } else {
        $error = 1;
        $errmsg = "Failed t0 enabled/disabled slideshow";
      }
      break;
    case "3":
      $title = $_POST['txttitle'];
      $subtitle = $_POST['txtsubtitle'];
      $text = $_POST['tatext'];
      $link = $_POST['txtlink'];
      $enable = "0";
      if (isset($_POST['enable']))
        $enable = "1";
      $result = dbSelect("tbl_slideshow", "ssorder", "", "order by ssorder desc limit 1");
      $row = mysqli_fetch_array($result);
      $ssorder = $row['ssorder'] + 1;


      $org_name = $_FILES["fileimg"]["name"];
      $path_parts = pathinfo($org_name);
      $extension = $path_parts['extension'];
      $img = time() . ".". $extension;
      $path = "../img/";


      $data = ["title"=>"$title", "subtitle"=>"$subtitle","text"=>"$text","link"=>"$link", "enable"=>"$enable", "ssorder"=>$ssorder, "img"=>"$img"];
      $result = dbInsert("tbl_slideshow", $data);
      if($result)
      {
        $error = 0;
        $errmsg = "A slideshow has been added sucessfully!";  
        $d = getimagesize($_FILES['fileimg']['tmp_name']);
        $width = $d[0];
        $height = $d[1];
        $imageType = $d[2];

        createThumbnail($imageType, $_FILES['fileimg']['tmp_name'],$width, $height, $path,$img);
        // move_uploaded_file($_FILES["fileimg"]["tmp_name"],$path);
        
      } 
      else
      {
        $error = 1;
        $errmsg = "Failed to add slideshow";
      }
      break;
      case "5":
        $ssid = $_GET['ssid'];
        $title = $_POST['txttitle'];
        $subtitle = $_POST['txtsubtitle'];
        $text = $_POST['tatext'];
        $link = $_POST['txtlink'];
        $enable = "0";
        if (isset($_POST['enable']))
          $enable = "1";
        $data = "";
        if(file_exists($_FILES['fileimg']['tmp_name']))
        {
            $result = dbSelect("tbl_slideshow", "img", "ssid= $ssid","");
            $row = mysqli_fetch_array($result);
            $oldimg = $row['img'];

            $org_name = $_FILES["fileimg"]["name"];
            $path_parts = pathinfo($org_name);
            $extension = $path_parts['extension'];
            $img = time() . ".". $extension;
            $path = "../img/";

            $data = ["title"=>"$title", "subtitle"=>"$subtitle","text"=>"$text","link"=>"$link", "enable"=>"$enable",
              "img"=>"$img"];

              $d = getimagesize($_FILES['fileimg']['tmp_name']);
              $width = $d[0];
              $height = $d[1];
              $imageType = $d[2];
      
              createThumbnail($imageType, $_FILES['fileimg']['tmp_name'],$width, $height, $path,$img);
              
              $oldimagepath = "../img/$oldimg";
              $oldimagethumbnailpath = "../img/thumbnail/$oldimg";
              if(file_exists($oldimagepath))
              {
                  unlink($oldimagepath);
              }
              if(file_exists($oldimagethumbnailpath))
              {
                  unlink($oldimagethumbnailpath);
              }

        }
        else
        {
            $data = ["title"=>"$title", "subtitle"=>"$subtitle",
            "text"=>"$text", "link"=>"$link","enable"=>"$enable"];
        }
        $result = dbUpdate("tbl_slideshow",$data,"ssid=$ssid");
        break;

  }
}
?>

<h1 class="h4 mb-b float-start">Slideshow</h1>
<a href="index.php?p=ssform" class="btn btn-primary float-end" style=" text-transform: capitalize">Add Slideshow</a>
<div style = "clear:both;"></div>
<?php
$result = dbSelect("tbl_slideshow", "*", "", "order by ssorder asc");
$num = mysqli_num_rows($result); //for count record
$maxperpage = MAX_PER_PAGE;
$numpage = ceil($num / $maxperpage);
$current_page = 1;
if (isset($_GET['pg']))
  $current_page = $_GET['pg'];
if(isset($action) && $action == "3")
  $current_page = $numpage;

$offset = ($current_page - 1) * $maxperpage;
$result = dbSelect("tbl_slideshow", "*", "", "order by ssorder asc limit $maxperpage offset $offset");

if ($error != -1) {

?>

  <div class="alert alert-<?= ($error == 1 ? 'danger' : 'success') ?> alert-dismissible fade show" role="alert">
    <?= $errmsg ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php
}
if ($num > 0) {


?>
  <table class="table">
    <tr>
      <th>No</th>
      <th>Image</th>
      <th>Title</th>
      <th>Subtitle</th>
      <th>Text</th>
      <th>Link</th>
      <th>Action</th>
    </tr>

    <?php
    $i = 1;
    while ($row = mysqli_fetch_array($result)) {
    ?>
      <tr>
        <td><?= $i+ $offset?></td>
        <td><img src="../img/thumbnail/<?= $row['img'] ?>" width="50px"></td>
        <td><?= $row['title'] ?></td>
        <td><?= $row['subtitle'] ?></td>
        <td><?= $row['text'] ?></td>
        <td><?= $row['link'] ?></td>
        <td>
          <a href="index.php?p=slideshow&action=1&d=0&ssid=<?= $row['ssid'] ?>"> <i class="material-icons opacity-10">arrow_upward</i></a>
          <a href="index.php?p=slideshow&action=1&d=1&ssid=<?= $row['ssid'] ?>"> <i class="material-icons opacity-10">arrow_downward</i></a>
          <a href="index.php?p=slideshow&action=2&enable=<?= ($row['enable'] == '1' ? '0' : '1') ?>&ssid=<?= $row['ssid'] ?>"> <i class="material-icons opacity-10"><?= ($row['enable'] == '1' ? 'remove_red_eye' : 'visibility_off') ?></i></a>
          <!-- <a class="" href="#"> <i class="material-cons opacity-10">visibility_off</i></a> -->
          <a href="index.php?p=ssform&ssid=<?=$row['ssid']?>"> <i class="material-icons opacity-10">edit</i></a>
          <a href="#" data-bs-toggle="modal" data-bs-target="#ssDelModal" onclick="updateID4Del('<?= $row['ssid'] ?>')"> <i class="material-icons opacity-10">delete</i></a>
        </td>
      </tr>
    <?php
      $i++;
    }
    ?>

  </table>
  <!--Start Pagination -->
  <nav aria-label="Page navigation example" class="d-flex justify-content-center">
    <ul class="pagination">
      <li class="page-item">
        <a class="page-link" href="index.php?p=slideshow&pg=<?= $current_page == 1 ? 1 : $current_page - 1 ?>" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
        </a>
      </li>
      <?php
      for ($i = 1; $i <= $numpage; $i++) {

      ?>
        <li class="page-item <?= $current_page == $i ? 'active' : '' ?>"><a class="page-link" href="index.php?p=slideshow&pg=<?= $i ?>"><?= $i ?></a></li>
      <?php
      }
      ?>
      <li class="page-item">
        <a class="page-link" href="index.php?p=slideshow&pg=<?= $current_page == $numpage ? $numpage : $current_page + 1 ?>" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
    </ul>
  </nav>
  <!--End Pagination -->

<?php
} else {
  echo "<p class = 'text-center fw-bold'> There is no slideshow!</p>";
}
?>
<!-- modal pop up -->
<!-- Modal -->
<div class="modal fade" id="ssDelModal" tabindex="-1" aria-labelledby="ssDelModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ssDelModalLabel">Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you wish to delete this slideshow?
      </div>
      <div class="modal-footer">
        <a href="#" class="btn btn-primary" id="linkDelSS">Yes</a>
        <a href="#" class="btn btn-secondary" data-bs-dismiss="modal">No</a>
      </div>
    </div>
  </div>
</div>
<script>
  function updateID4Del(ssid) {
    document.getElementById("linkDelSS").href = "index.php?p=slideshow&action=0&ssid=" + ssid;
    // alert(document.getElementById("linkDelSS").href);
  }
</script>