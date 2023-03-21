<?php
  // 包含檢查登錄代碼
  include("check_login.php");
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LBTube</title>
    <link href="css/upload.css" rel="stylesheet" type="text/css">
    <link href="plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/color.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  </head>
  <body>
  <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="index.php">
              <img src="img/title.png" height="60">
            </a>
          </div>
          <ul class="nav navbar-nav navbar-right">
          <!-- <li>
            
            <a href="logout.php" style="text-decoration: none; color:white;">
              <h5><i class="bi bi-door-closed-fill"></i><b>登出　</b></h5>
            </a>
          </li> -->
          <style>
            .nav.navbar-nav.navbar-right {
              display: flex;
              justify-content: space-between;
              flex-direction: row;
              align-items: flex-end;
              color: white;
            } 
          </style>
            <?php
              echo '<h3><b>'.$_SESSION['name'].'</b></h3>'; 
              if($_SESSION['position'] == 'S'){
                echo '<h5>同學</h5>';
              }else{
                echo '<h5>老師</h5>';
              }
              if($_SESSION['gender'] == 'M'){
                echo '<img src="img/boy.png" height="50">';
              }else{
                echo '<img src="img/girl.png" height="50">';
              }
            ?>
          </ul>
        </div>
    </nav>
    

    <div class="container-fluid" style="margin-top: 10px;">
      <div class="row">
        <div class="col-sm-2">
          <ul class="list-group">
          <?php
              if($_SESSION['position'] == 'S'){
                // 查詢學生通知
                include 'notice.php';      
                echo '<a href="index.php" class="list-group-item">首頁</a>
                      <a href="upload.php" class="list-group-item active">上傳影片</a>
                      <a href="student_video_list.php" class="list-group-item">我的影片</a>
                      <a href="student_comment_list.php" class="list-group-item">同儕互評';
                      if($resultS > 0 ){
                        echo '<span class="badge rounded-pill bg-danger" style="margin-left:5px;">'.$resultS.'</span>';
                      }
                      echo '</a>';
              }else{
                // 查詢老師通知
                include 'notice.php'; 
                echo '<a href="index.php" class="list-group-item">首頁</a>
                      <a href="teacher_video_list.php" class="list-group-item">班級影片</a>
                      <a href="teacher_comment_list.php" class="list-group-item">影片評分';
                      if($resultT > 0){
                        echo '<span class="badge rounded-pill bg-danger" style="margin-left:5px;">'.$resultT.'</span>';
                      }
                      echo '</a>
                      <a href="report_list.php" class="list-group-item">問題回報';
                      if($report_count > 0){
                        echo '<span class="badge rounded-pill bg-danger" style="margin-left:5px;">'.$report_count.'</span>';
                      }
                      echo '</a>';
              }
            ?>
          </ul>
          <button type="button" class="btn btn-danger btn-sm"  onclick="window.location.href='logout.php'" style="margin-top:10px; display:block;"><i class="bi bi-box-arrow-left"></i><b>登出</b></button>
          <a href="http://www.cot.org.tw">
            <img src="img/planet.png" height="120" style="margin-top: 10px;">
          </a>
        </div>
        <div class="col-sm-10 mx-auto mb-3">
          <form id="uploadForm" action="uploadfile.php" method="post" enctype="multipart/form-data" class="form-group">
            <div class="form-group">
              <select class="form-control dropdown" id="SemesterSelect" name="semester" style="max-width:20%; display:inline-block;" >
                <option>請選擇學期</option>
                <option>五上</option>
                <option>五下</option>
              </select>

              <select class="form-control dropdown" id="UnitSelect" name="unit" style="max-width:20%; display:inline-block;">
                <option>請選擇單元</option>
              </select>
            </div>

            <style>
              .dropdown {
                background-image: url('img/down.png');
                background-repeat: no-repeat;
                background-position: right -10px center;
                background-size: 60px 60px;
                padding-right: 0px;
              }
            </style>

            <input type="file" id="fileInput" name="uploaded_file" class="form-control" style="max-width:60%; margin-top:5px" required>
            <br>
            <video id="preview" style="width:60%"controls></video>
            <br>
            <button id="submit-button" type="submit" class="btn btn-outline-secondary">上傳</button>
          </form>

          <div id="loadingOverlay" style="display:none;">
              <img class="uploadimg" src="img/uploading.gif">
          </div>

          

        </div>
      </div>
    </div>
    
    <script src="js/upload.js"></script>
    <script src="plugin/bootstrap/js/popper.min.js"></script>
    <script src="plugin/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="plugin/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugin/bootstrap/js/bootstrap.min.js"></script>
    <script src="plugin/jquery-3.6.3.min.js"></script>
    
  </body>
</html>