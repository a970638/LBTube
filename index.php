<?php
  include("save_info.php");
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LBTube</title>
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
    

    <div class="container-fluid" style="margin-top: 10px; ">
      <div class="row">
        <div class="col-sm-2">
          <ul class="list-group w-100">
            <?php
              if($_SESSION['position'] == 'S'){
                // 查詢學生通知
                include 'notice.php';      
                echo '<a href="index.php" class="list-group-item active">首頁</a>
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
                echo '<a href="index.php" class="list-group-item active">首頁</a>
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
          
          <div class="container-fluid">
            <div class="row row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xxl-4">
              <?php
                
                // 執行 SQL 查詢，查詢影片清單
                $sql = "SELECT semester,unit,video_name,video_group 
                        FROM video
                        WHERE video_school NOT IN ('test') 
                        ORDER BY RAND()
                        LIMIT 9";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                  // $semester_list = array();
                  // $unit_list = array();
                  // $group_list = array();
                  // $video_list = array();
                  while($row = mysqli_fetch_assoc($result)) {
                      $semester_list[] = $row['semester'];
                      $unit_list[] = $row['unit'];
                      $group_list[] = $row['video_group'];
                      $video_list[] = $row['video_name'];
                  } 
                }
              ?>
              <?php
                $i = 0;
                foreach ($video_list as $video_list) {
                  $sql1 = "SELECT `name` FROM users WHERE `group` = '$group_list[$i]'";
                  $result1 = mysqli_query($conn, $sql1);
                  if (mysqli_num_rows($result1) > 0) {
                    $member_list = array();
                    while($row = mysqli_fetch_assoc($result1)) {
                        $member_list[] = $row['name'];
                    } 
                  }
                  echo '<div class="col-md" style="margin-top:5px;">
                          <div class="card h-100">
                          <br>
                          <video class="card-img-top" id="'.$video_list.'" controls autoplay="false" playsinline webkit-playsinline>
                            <source src="https://nculbtbucket.s3.ap-northeast-1.amazonaws.com/'.$video_list.'.mp4" type="video/mp4">
                          </video>
                          <script>
                            var video = document.getElementById("'.$video_list.'");
                            video.pause();
                          </script> 
                            <div class="card-body">
                              <h5 class="card-title"><a href="'.'play.php?video_name='.$video_list.'" style="text-decoration:none; font-weight: bold;">'.$unit_list[$i].'</a></h5>
                              <p class="card-text">('.$semester_list[$i].')<br>成員名單：';
                              foreach ($member_list as $key => $value) {
                                echo $value;
                                if ($key !== count($member_list) - 1) {
                                  echo "、";
                                }
                              }
                              echo'</p>
                            </div>
                          </div>
                        </div>';
                        $i++;
                }
                mysqli_close($conn);
              ?>
                <!-- <div class="card" style="width: 18rem;">
                  <br>
                  <iframe class="card-img-top" src="https://nculbtbucket.s3.ap-northeast-1.amazonaws.com/'.$video_list.'.mp4"></iframe>
                  <div class="card-body">
                    <h5 class="card-title"><a href="'.'play.php?video_name='.$video_list.'">'.$video_list.'</a></h5>
                    <p class="card-text">'.$video_list.'</p>
                  </div>
                </div> -->
    
            </div>
        </div>
        <br><br>


        </div>
      </div>
    </div>

    
    <!-- <div>
      <img src="img/1.png" data-url="https://www.youtube.com/embed/F4R9FYoOr7w"/>
    </div> -->

    <!-- <script type="text/javascript">
      $('img').hover(function(event){	
        $(this).replaceWith($('<iframe />', {
          'src': $(this).data('url'),
          'width': $(this).width(),
          'height': $(this).height()
        }));
      })
    </script> -->
    <script src="plugin/bootstrap/js/popper.min.js"></script>
    <script src="plugin/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="plugin/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugin/bootstrap/js/bootstrap.min.js"></script>
    <script src="plugin/jquery-3.6.3.min.js"></script>
  </body>
</html>