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
    <link href="css/student_comment_list.css" rel="stylesheet" type="text/css">
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
                      <a href="teacher_video_list.php" class="list-group-item">班級影片</a>
                      <a href="teacher_comment_list.php" class="list-group-item active">影片評分';
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
            // 取得班級
            $school = $_SESSION['school'];

            // 連接到資料庫
            include 'connection.php';

            // 執行 SQL 查詢，查詢影片清單
            $sql = "SELECT video_name,video_group,unit FROM video WHERE video_school = '$school'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) != 0) {
              while($row = mysqli_fetch_assoc($result)) {
                  $video_list[] = $row['video_name'];
                  $group_list[] = $row['video_group'];
                  $unit_list[] = $row['unit'];
              } 
            }
          ?>

          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>組員</th>
                <th>單元名稱</th>
                <th>影片預覽</th>
                <th>動作</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $id = $_SESSION["id"];
                $sql0 = "SELECT count(video_name) FROM video WHERE video_school = '$school'";
                $sql1 = "SELECT count(video_id) FROM comment WHERE comment_user_id = '$id' AND `type`='cm'  AND comment_situation NoT IN ('old')";
                $result0 = mysqli_query($conn, $sql0);
                $result1 = mysqli_query($conn, $sql1);
                if(mysqli_num_rows($result0) != 0){
                  $row = mysqli_fetch_assoc($result0);
                  $video_count = $row['count(video_name)'];
                }
                if(mysqli_num_rows($result1) != 0){
                  $row = mysqli_fetch_assoc($result1);
                  $comment_count = $row['count(video_id)'];
                }
                if ($video_count == $comment_count) {
                  echo '<tr><td colspan="4">沒有需要評論的影片</td></tr>';
                }else{
                  $i = 0;
                  foreach ($video_list as $video_list) {
                    $id = $_SESSION["id"];
                    $sql2 = "SELECT video_id FROM comment WHERE video_id = '$video_list' AND comment_user_id = '$id' AND `type`='cm'  AND comment_situation NoT IN ('old')";
                    $result2 = mysqli_query($conn, $sql2);
                      // 檢查是否評論過
                      if (mysqli_num_rows($result2) == 0) {
                        // 查詢組員名單
                        $sql3 = "SELECT `name` from users where `group` = '$group_list[$i]'";
                        $result3 = mysqli_query($conn, $sql3);
                        if (mysqli_num_rows($result3) != 0) {
                          $name_list = array();
                          while($row = mysqli_fetch_assoc($result3)) {
                              $name_list[] = $row['name'];
                          } 
                        }
                        echo '<tr>';
                        echo '<td>';
                        foreach ($name_list as $key => $value) {
                          echo $value;
                          if ($key !== count($name_list) - 1) {
                            echo "、";
                          }
                        }
                        echo '</td>';                  
                        echo '<td>' . $unit_list[$i] . '</td>';
                        echo '<td>
                              <video class="card-img-top" id="'.$video_list.'" controls autoplay="false" playsinline webkit-playsinline>
                                <source src="https://nculbtbucket.s3.ap-northeast-1.amazonaws.com/'.$video_list.'.mp4" type="video/mp4">
                              </video>
                              <script>
                                var video = document.getElementById("'.$video_list.'");
                                video.pause();
                              </script>   
                              </td>';                        
                        echo '<td><button class="btn btn-primary" onclick="location.href='."'".'comment.php?video_name='.$video_list."'".';">前往評論</button></td>';
                      } else {
                      }
                    echo '</tr>';
                    $i++;
                  }
                }
                mysqli_close($conn);
              ?>


            </tbody>
            </table>       

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