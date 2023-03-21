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
    <link href="css/teacher_video_list.css" rel="stylesheet" type="text/css">
    <link href="css/color.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  </head>
  <body>
    <nav class="navbar navbar-default" >
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="index.php">
              <img src="img/title.png" height="60">
            </a>
          </div>
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
                      <a href="teacher_video_list.php" class="list-group-item active">班級影片</a>
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
          <div class="container-fluid">
            <div class="row row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xxl-3">
              <?php
                $school = $_SESSION['school'];
                //連線到資料庫
                include 'connection.php';
                //取得老師班級內的組別資料
                $sql = "SELECT DISTINCT`group` FROM users WHERE school ='$school' AND position ='S'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                  while($row = mysqli_fetch_assoc($result)) {
                    $group_list[] = $row['group'];
                  } 
                }
              ?>

              <?php
                $i = 0;
                foreach ($group_list as $group_list) {
                  //取得各組別學生資料
                  $sql1 = "SELECT `name` FROM users WHERE `group` = '$group_list'";
                  $result1 = mysqli_query($conn, $sql1);
                  if (mysqli_num_rows($result1) > 0) {
                    $member_list = array();
                    while($row = mysqli_fetch_assoc($result1)) {
                        $member_list[] = $row['name'];
                    } 
                  }

                  echo '<div class="col-md" style="margin-top:5px;">
                          <div class="card h-100" >
                          <br>
                            <div class="card-body">
                              <h5 class="card-title"><b>';
                              foreach ($member_list as $key => $value) {
                                echo $value;
                                if ($key !== count($member_list) - 1) {
                                  echo "、";
                                }
                              }
                              echo '</b></h5>';
                              $sql2 = "SELECT count(video_name) FROM video WHERE `video_group` = '$group_list'";
                              $result2 = mysqli_query($conn, $sql2);
                              if(mysqli_num_rows($result2) != 0){
                                $row = mysqli_fetch_assoc($result2);
                                $video_count = $row['count(video_name)'];
                              }
                              echo'<p class="card-text">已上傳影片：<a style="color:red;">'.$video_count.'部</a></p>';
                              $id = $_SESSION['id'];
                              $sql3 = "SELECT count(comment_id) FROM comment WHERE `comment_user_id` = '$id' AND SUBSTRING(video_id, 7, 2) = '$group_list'  AND comment_situation NoT IN ('old')";
                              $result3 = mysqli_query($conn, $sql3);
                              if(mysqli_num_rows($result3) != 0){
                                $row = mysqli_fetch_assoc($result3);
                                $commnet_count = $row['count(comment_id)'];
                              }
                              echo'<p class="card-text">已評論影片：<a style="color:red;">'.$commnet_count.'部</a></p>';
                              echo '<button class="btn btn-primary" onclick="location.href='."'".'group_video_list.php?group_name='.$group_list."'".';">查看所有影片</button>';


                            echo '</div>
                          </div>
                        </div>';
                        $i++;
                }
              ?>
              
              
            </div>
          </div>

        
          
      </div>
    </div>
    <br><br>

    <script src="plugin/bootstrap/js/popper.min.js"></script>
    <script src="plugin/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="plugin/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugin/bootstrap/js/bootstrap.min.js"></script>
    <script src="plugin/jquery-3.6.3.min.js"></script>
  </body>
</html>