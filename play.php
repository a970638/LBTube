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
    <link href="plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/play.css" rel="stylesheet" type="text/css">
    <link href="css/color.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  </head>
  <body>
    <nav class="navbar navbar-default" >
        <div class="container-fluid">
          <a class="navbar-brand" href="index.php">
            <img src="img/title.png" height="60">
          </a>
          <ul class="nav navbar-nav navbar-right">
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
                      <a href="upload.php" class="list-group-item">上傳影片</a>
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
        <div class="col-sm-10">
          <?php
            // 連接到資料庫
            include 'connection.php';

            $video_name = $_GET["video_name"];
            $sql = "SELECT semester,unit from video where video_name = '$video_name'";
            $result = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($result) != 0) {
                    $row = mysqli_fetch_assoc($result);
                    echo'<h2><b>（'.$row['semester'].'）'.$row['unit'].'</b></h2>';
                  }
            $sql1 = "SELECT `name` from video
                     JOIN users
                     on video.video_group = users.group 
                     where video_name = '$video_name'";
            $result1 = mysqli_query($conn, $sql1);
                  if (mysqli_num_rows($result1) != 0) {
                    while($row = mysqli_fetch_assoc($result1)) {
                      $name_list[] = $row['name'];
                    } 
                  }
                  echo '<h5><b>成員：';
                  foreach ($name_list as $key => $value) {
                    echo $value;
                    if ($key !== count($name_list) - 1) {
                      echo "、";
                    }
                  }
                  echo '</b></h5>';
                  echo'<div class="video">
                        <video class="card-img-top" id="'.$video_name.'" controls autoplay="false" playsinline webkit-playsinline>
                            <source src="https://nculbtbucket.s3.ap-northeast-1.amazonaws.com/'.$video_name.'.mp4" type="video/mp4">
                        </video>
                      </div>
                      <script>
                        var video = document.getElementById("'.$video_name.'");
                        video.pause();
                      </script>';
          ?>

          <br>
          <form action="send_message.php" method="post">
            <div class="form-group d-flex align-items-end">
              <?php
                echo '<input type="hidden" name="video_id" value="'.$video_name.'">'
              ?>
              <textarea class="form-control" name="message" rows="3" placeholder="發表留言..."style="max-width:80%; display:inline-block;"></textarea>
              <button type="submit" class="btn btn-primary" style="margin-left:5px;">送出</button>
            </div>
            
          </form>
          <br>

          <?php
            // 取得組別
            $group = $_SESSION['group'];
            $comment_group = $_SESSION['comment_group'];

            // 執行 SQL 查詢，查詢影片清單
            $sql2 = "SELECT comment, `name` 
                    FROM comment
                    JOIN users
                    ON comment_user_id = id  
                    WHERE `type` = 'ms' AND video_id = '$video_name'";
            $result2 = mysqli_query($conn, $sql2);

            if (mysqli_num_rows($result2) != 0) {
              $message_list = array();
              $user_list = array();
              while($row = mysqli_fetch_assoc($result2)) {
                  $message_list[] = $row['comment'];
                  $user_list[] = $row['name'];
              } 
            }
          ?>

          <div class="card mb-3">
            <div class="card-body">
              <?php
                $i = 0;
                if(isset($message_list)){
                  foreach ($message_list as $message_list) {
                    // $user = $user_list[$i];
                    echo'<h5 class="card-title">'.$user_list[$i].'</h5>
                        <p class="card-text">'.$message_list.'</p><hr>';
                    $i++;
                  }
                }else{
                  echo'尚未有留言';
                }
              ?>
            </div>
          </div>

        </div>
      </div>
    </div>

    <script src="plugin/bootstrap/js/popper.min.js"></script>
    <script src="plugin/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="plugin/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugin/bootstrap/js/bootstrap.min.js"></script>
    <script src="plugin/jquery-3.6.3.min.js"></script>
  </body>
</html>