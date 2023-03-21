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
                      <a href="teacher_comment_list.php" class="list-group-item">影片評分';
                      if($resultT > 0){
                        echo '<span class="badge rounded-pill bg-danger" style="margin-left:5px;">'.$resultT.'</span>';
                      }
                      echo '</a>
                      <a href="report_list.php" class="list-group-item active">問題回報';
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

            // 執行 SQL 查詢，查詢回報清單
            $sql = "SELECT report_id,reporter_uid, getreport_uid, report_content,report_comment_id,checked, `name` from report join users on getreport_uid = id  where school = '$school' ;";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) != 0) {
              while($row = mysqli_fetch_assoc($result)) {
                  $report_list[] = $row['report_id'];                
                  $reporter_list[] = $row['reporter_uid'];
                  $name_list[] = $row['name'];
                  $content_list[] = $row['report_content'];
                  $comment_id[] = $row['report_comment_id'];
                  $checked[] = $row['checked'];
              } 
            }
          ?>

          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>回報學生</th>
                <th>被回報學生</th>
                <th>回報內容</th>
                <th>動作</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i=0;
              if(isset($reporter_list)){
                  foreach($reporter_list as $reporter_list){
                  $sql1 = "SELECT `name` FROM users where `id` = '$reporter_list'";
                  $result1 = mysqli_query($conn, $sql1);
                  while($row = mysqli_fetch_assoc($result1)) {
                    $name = $row['name'];
                  }
                    echo'<tr>
                          <td>'.$name.'</td>
                          <td>'.$name_list[$i].'</td>
                          <td>'.$content_list[$i].'</td>';
                          if($checked[$i] == 'unchecked'){
                            echo'<td><button class="btn btn-danger" onclick="location.href='."'".'check_comment.php?comment_id='.$comment_id[$i]."&report_id=".$report_list[$i]."'".';">查看</button></td></tr>';
                          }else{
                            echo'<td><button class="btn btn-success" onclick="location.href='."'".'check_comment.php?comment_id='.$comment_id[$i]."&report_id=".$report_list[$i]."'".';">查看</button></td></tr>';
                          }
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