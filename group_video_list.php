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
    <link href="css/student_video_list.css" rel="stylesheet" type="text/css">
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
          <!-- <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#">Browse</a></li>
            <li><a href="#">History</a></li>
          </ul> -->
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
        <?php
          // 取得組別
          $group = $_GET['group_name'];

          // 連接到資料庫
          include 'connection.php';
          $sql0 = "SELECT `name` FROM users WHERE `group` = '$group'";
          $result0 = mysqli_query($conn, $sql0);
          if (mysqli_num_rows($result0) > 0) {
            $member_list = array();
            while($row = mysqli_fetch_assoc($result0)) {
                $member_list[] = $row['name'];
            } 
          }
          echo '<h5><b>成員：';
          foreach ($member_list as $key => $value) {
            echo $value;
            if ($key !== count($member_list) - 1) {
              echo "、";
            }else{
              echo '</b></h5>';
            }
          }

          // 執行 SQL 查詢，查詢影片清單
          $sql = "SELECT video_name,unit FROM video WHERE video_group = '$group'";
          $result = mysqli_query($conn, $sql);

          if (mysqli_num_rows($result) != 0) {
            $video_list = array();
            $unit_list = array();
            while($row = mysqli_fetch_assoc($result)) {
                $video_list[] = $row['video_name'];
                $unit_list[] = $row['unit'];
            } 
          }
        ?>

        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>單元</th>
              <th>影片預覽</th>
              <th>我的評論</th>
              <th>狀態</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $i = 0;
              foreach ($video_list as $video_list) {
                
                echo '<tr>';
                echo '<td>' . $unit_list[$i] . '</td>';
                echo '<td>
                      <video class="card-img-top" id="'.$video_list.'" controls autoplay="false" playsinline webkit-playsinline>
                        <source src="https://nculbtbucket.s3.ap-northeast-1.amazonaws.com/'.$video_list.'.mp4" type="video/mp4">
                      </video>
                      <script>
                        var video = document.getElementById("'.$video_list.'");
                        video.pause();
                      </script>   
                      </td>';                  // if (mysqli_num_rows($result1) > 0) {
                  //   while($row = mysqli_fetch_assoc($result1)) {
                  //       var_dump($row);
                  //   }
                  // } else {
                  //     echo "No data found";
                  // }
                //查詢影片評論
                $id = $_SESSION['id'];  
                $sql1 = "SELECT q1,q2,q3,q4,q5 FROM comment WHERE video_id = '$video_list' AND `type` = 'cm' AND comment_user_id = '$id'  AND comment_situation NoT IN ('old')";
                $result1 = mysqli_query($conn, $sql1);
                if(mysqli_num_rows($result1) > 0){
                  $row = mysqli_fetch_assoc($result1);
                    echo '<td style="font-size:13px;">分數（5為最高，1為最低）
                          <br>影片中解題過程及解答是否正確：'.$row['q1']
                        .'<br>影片中解釋的觀念是否清楚易懂：'.$row['q2']
                        .'<br>影片整體講解過程是否順暢：'.$row['q3']
                        .'<br>影片設計、講解方式是否吸引你觀看：'.$row['q4']
                        .'<br>影片的畫面及聲音是否清晰：'.$row['q5'].'</td>';
                    echo '<td><button type="button" class="btn btn-primary" style="display:block;" onclick="location.href='."'".'comment_detail.php?video_name='.$video_list."'".';">查看詳細</button>';
                }else{
                  echo '<td>尚未有評論</td>';
                  echo '<td><button type="button" class="btn btn-danger" style="display:block;">尚未評論</button>';
                }  
                echo '</tr>';
                $i++;
              }
              mysqli_close($conn);
            ?>

          <div id="loadingOverlay" style="display:none;">
              <img class="deleteimg" src="img/deleting.gif">
          </div>
          </tbody>
        </table>


          
          
        </div>


        </div>
      </div>
    </div>
    <br><br>
    
    



    <script src="js/student_video_list.js"></script>
    <script src="plugin/bootstrap/js/popper.min.js"></script>
    <script src="plugin/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="plugin/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugin/bootstrap/js/bootstrap.min.js"></script>
    <script src="plugin/jquery-3.6.3.min.js"></script>
  </body>
</html>