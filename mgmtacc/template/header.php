<?php require_once("includes/init.php");
if (!$session->is_signed_in()) {
  redirect("index");
}
require_once "model/message.php";
$msg = new message;
$unreads = $msg->GetTotalUnreadsCA(0);
$count = $unreads['unreads'] > 0 ? '<span class="badge">' . $unreads['unreads'] . '</span>' : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PESO ADMIN</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="../assets/css/styles.css">
<script src="../js/jquery-ui.min.js"></script>
<script src="../js/paging.js"></script>
<link rel="stylesheet" type="text/css" href="../fonts/flaticons/flaticon.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
<link rel="icon" href="https://mb.pesoapp.ph/assets/icon/favicon.ico" type="image/x-icon" />
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<link rel="stylesheet" href="../assets/css/jquery-ui.css">
<script src="../js/jquery-autocomplete.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://cdn.tiny.cloud/1/4ht0sgq3apbnbyqvf9h73ef3o0i02niv8smfw8qympkohuyn/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<!-- forsummernote -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

  <!-- Datatables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

  <!-- Button Datatables -->
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
  <script src="../assets/js/loading.js"></script>
  <style>
    .notification {
      position: relative;
      display: inline-block;
    }

    .notification .badge {
      position: absolute !important;
      top: -10px !important;
      right: -10px !important;
      padding: 5px 10px !important;
      border-radius: 100% !important;
      background: red !important;
      color: white !important;
    }

    .loading {
      width: 100%;
      height: 100%;
      text-align: center;
      background-color: rgba(255, 255, 255, 0.7);
      position: fixed;
      z-index: 99999;
      top: 0;
      left: 0;
    }

    .load {
      position: relative;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .load .dot:nth-last-child(1) {
      animation: loadingC 0.6s 0.1s linear infinite;
    }

    .load .dot:nth-last-child(2) {
      animation: loadingC 0.6s 0.2s linear infinite;
    }

    .load .dot:nth-last-child(3) {
      animation: loadingC 0.6s 0.3s linear infinite;
    }

    .dot {
      display: inline-block;
      width: 15px;
      height: 15px;
      border-radius: 15px;
      background-color: #4b9cdb;
    }

    @keyframes loadingC {
      0 {
        transform: translate(0, 0);
      }

      50% {
        transform: translate(0, 15px);
      }

      100% {
        transform: translate(0, 0);
      }
    }
  </style>

</head>

<body>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" rel="home" href="home.php">
          <img src="../assets/peso-logo.png">
        </a>
      </div>

      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="background: #fff">
        <ul class="nav navbar-nav">
          <?php
          require_once "../include/database.php";
          $db = new Database();
          $con = $db->getmyDB();
          $userId = $_SESSION['user_id'];

          $stmt = $con->prepare("SELECT id as typeId, name FROM page_type ORDER BY order_number");
          $stmt->execute();

          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

          foreach ($result as $row) {

            $stmt2 = $con->prepare("SELECT OP.page as page, OP.page_link AS pageLink FROM oc_pages AS OP
            INNER JOIN oc_permission_pages as OPP ON OP.page_id = OPP.user_pages WHERE OPP.user_id = :userId AND OP.page_type_id = :typeId ORDER BY OP.order_number");
            $stmt2->execute([':userId' => $userId, ':typeId' => $row['typeId']]);

            $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            if ($result2) {
              echo "<li class='dropdown'>
                  <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>{$row['name']}<span class='caret'></span></a>
                  <ul class='dropdown-menu'>";

              foreach ($result2 as $row2) {
                echo "<li><a href='{$row2['pageLink']}'>{$row2['page']}</a></li>";
              }

              echo "</ul></li>";
            }
          }
          ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <?php $perm2 = $_SESSION['permission'];if (!strpos($perm2, "'2525';") !== false){
              }else{ ?>
                <li><a href="message_admin.php">
                  <div class="btn btn-warning notification">Messages<?php echo $count ?></div>
                </a></li>
             <?php }?>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>
</body>

</html>