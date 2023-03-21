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
    <link href="css/comment_detail.css" rel="stylesheet" type="text/css">
    <link href="css/color.css" rel="stylesheet" type="text/css">
    <script src="plugin/jquery-3.6.3.min.js"></script>
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
                      <a href="student_video_list.php" class="list-group-item active">我的影片</a>
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
            include 'connection.php';

            $video_name = $_GET["video_name"];
            $sql = "SELECT semester,unit from video where video_name = '$video_name'";
            $result = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($result) != 0) {
                    $row = mysqli_fetch_assoc($result);
                    echo'<h2><b>（'.$row['semester'].'）'.$row['unit'].'</b></h2>';
                  }
            echo'
                <div class="video">
                  <video class="card-img-top" id="'.$video_name.'" controls autoplay="false" playsinline webkit-playsinline>
                      <source src="https://nculbtbucket.s3.ap-northeast-1.amazonaws.com/'.$video_name.'.mp4" type="video/mp4">
                  </video>
                </div>
                <script>
                  var video = document.getElementById("'.$video_name.'");
                  video.pause();
                </script>';
          ?>


          <?php
            // 取得組別
            $group = $_SESSION['group'];
            $comment_group = $_SESSION['comment_group'];

            // 執行 SQL 查詢
            $sql2 = "SELECT `name`,comment_user_id,comment_id,q1,q2,q3,q4,q5,comment 
                    FROM comment
                    JOIN users
                    ON comment_user_id = id 
                    WHERE video_id = '$video_name' AND `type` = 'cm' AND comment_situation NoT IN ('old')
                    ORDER BY position DESC";
            $result2 = mysqli_query($conn, $sql2);
            if (mysqli_num_rows($result2) > 0) {
              $comment_list = array();
              while($row = mysqli_fetch_assoc($result2)) {
                $id_list[] = $row['comment_user_id'];
                $commentid_list[]=$row['comment_id'];
                $user_list[] = $row['name'];
                $q1_list[] = $row['q1'];
                $q2_list[] = $row['q2'];
                $q3_list[] = $row['q3'];
                $q4_list[] = $row['q4'];
                $q5_list[] = $row['q5'];
                $comment_list[] = $row['comment'];
              } 
            }
          ?>

          <div class="card mb-3" style="margin-top: 5px;">
            <div class="card-body">
              <h5 class="card-title" style="font-weight:bold;">
              <?php
              if($_SESSION['position'] == 'S'){
                echo '同儕互評';
              }else{
                echo '所有評論';
              }
              ?>
              
              </h5>
            
          
            <?php
              $i = 0;
              
              if(isset($user_list)){
                echo'<div class="card border mb-3">';
                foreach ($user_list as $user) {
                  echo'<div class="card-header" style="font-weight:bold; background-color:#F0F0F0;">'.$user.'</div>
                        <div class="card-body w-100">
                          <canvas id="myChart'.$i.'"width="700" height="150"></canvas>
                          <script>
                          var data = ['.$q1_list[$i].', '.$q2_list[$i].','.$q3_list[$i].', '.$q4_list[$i].', '.$q5_list[$i].'];
                          var labels = ["影片中解題過程及解答是否正確", "影片中解釋的觀念是否清楚易懂", "影片整體講解過程是否順暢", "影片設計、講解方式是否吸引你觀看", "影片的畫面及聲音是否清晰"];
                          var canvas = document.getElementById("myChart'.$i.'");
                          var ctx = canvas.getContext("2d");
                          var colors = ["#ED6335", "#ED6335", "#ED6335", "#ED6335", "#ED6335"];
                          var barWidth = canvas.height / data.length;
                          for (var i = 0; i < data.length; i++) {
                            var barHeight = data[i] * 30;
                            var x = i * barWidth;
                            // 在長條圖左方放上名稱
                            ctx.fillStyle = "#000000";
                            ctx.font = "16px Arial";
                            ctx.fillText(labels[i], 0, x + barWidth / 2);

                            ctx.fillStyle = colors[i];
                            ctx.fillRect(260, x, barHeight, barWidth - 10);  
                          }
                          </script>
                      
                          <p class="card-text">優缺點及其他建議：'.$comment_list[$i].'</p>
                        </div>';
                  $i++;
                }
                echo'</div>';
              }else{
                echo '尚未有評論';
              }
            ?>
              <button class="btn btn-danger dropdown-toggle"<?php if($_SESSION['position'] == 'S'){echo'style="display:block;"';}else{echo'style="display:none;"';}?>  type="button" data-bs-toggle="dropdown" aria-expanded="false">回報</button>
              <ul class="dropdown-menu dropdown-menu">
                <li><a class="dropdown-item active" data-value="pre">請選擇要回報的評論</a></li>
                <?php
                  $j = 0;
                  foreach($user_list as $user_list){
                    echo'<li><a class="dropdown-item" data-value="'.$id_list[$j].','.$commentid_list[$j].'">'.$user_list.'</a></li>';                    
                    $j++;
                  }
                ?>
              </ul>
              <form action="send_report.php" method="post" class="form-group d-flex align-items-end">
                <input type="hidden" id="getreport_id" name="getreport_id">
                <input type="hidden" id="comment_id" name="comment_id">
                <input type="hidden" name="video_name" value="<?php echo $video_name;?>">
                <textarea id="report_content" class="form-control" name="report_content" rows="3" placeholder="回報內容..." style="margin-top:5px; max-width:80%; display:none;"></textarea>
                <button id="report_btn" type="submit" class="btn btn-primary" style="margin-left:5px; display:none;">送出</button>
              </form>
            </div>
          </div>

          <script>
            var dropdownItems = document.querySelectorAll(".dropdown-item");
            dropdownItems.forEach(function(item) {
              item.addEventListener("click", function() {
                var value = this.getAttribute("data-value");
                if(value != "pre"){
                  let array = value.split(",");
                  document.getElementById("getreport_id").value = array[0];
                  document.getElementById("comment_id").value = array[1];
                  document.getElementById("report_content").style.display = "inline-block";
                  document.getElementById("report_btn").style.display = "inline-block";
                }
              });
            });
          </script>

  

          <?php
            $sql1 = "SELECT comment, `name` 
                     FROM comment 
                     JOIN users
                     ON comment_user_id = id 
                     WHERE `type` = 'ms' AND video_id = '$video_name'";
            $result1 = mysqli_query($conn, $sql1);

            // if (mysqli_num_rows($result1) > 0) {
            //   while($row = mysqli_fetch_assoc($result1)) {
            //       var_dump($row);
            //   }
            // } else {
            //     echo "No data found";
            // }

            if (mysqli_num_rows($result1) != 0) {
              $message_list = array();
              $muser_list = array();
              while($row = mysqli_fetch_assoc($result1)) {
                  $message_list[] = $row['comment'];
                  $muser_list[] = $row['name'];
              } 
            }
          ?>

          <hr>

          
          <?php
            if($_SESSION['position'] == 'S'){
                echo '<div class="card mb-3">
                        <div class="card-header" style="background-color:#F0F0F0;"><h5 style="font-weight:bold;">留言</h5></div>
                          <div class="card-body">';
                $i = 0;
                if(isset($message_list)){
                  foreach ($message_list as $message_list) {
                    echo'
                        <h6 class="card-title" style="font-weight:bold;">'.$muser_list[$i].'</h6>
                        <p class="card-text">'.$message_list.'</p>
                        <hr>';
                    $i++;
                  }
                }else{
                  echo '尚未有留言';
                }
                echo'</div>
                    </div>';
              }else{
                echo'<form action="comment_detail.php" method="post">
                      <input type="hidden" name="form-identifier" value="delete-form">
                      <input type="hidden" name="video_name" value="'.$video_name.'">
                      <button type="submit" id="delete-button" class="btn btn-danger">刪除評論</button>
                    </form><br>

                    <script>
                      $(document).ready(function() {
                        $("#delete-button").click(function(e) {
                          if (!confirm("確定要刪除嗎？")) {
                            e.preventDefault();
                          }
                        });
                      });
                    </script>';
                    if (isset($_POST['form-identifier']) && $_POST['form-identifier'] == 'delete-form') {
                      //刪除評論
                      $id = $_SESSION['id'];
                      $video_name = $_POST['video_name'];
                      $sqld = "UPDATE comment 
                              SET comment_situation='old'
                              WHERE video_id = '$video_name' AND comment_user_id = '$id'";
                      if (!$conn->query($sqld)) {
                        die('刪除失敗: ' . $conn->error);                      
                      }else{
                        echo '<script>
                              alert("刪除成功");
                              window.location.href="teacher_video_list.php";
                            </script>';
                      }
                    }
              }

              
          ?>
          

        </div>
      </div>
    </div>

    <script src="plugin/bootstrap/js/popper.min.js"></script>
    <script src="plugin/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="plugin/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugin/bootstrap/js/bootstrap.min.js"></script>
    
  </body>
</html>