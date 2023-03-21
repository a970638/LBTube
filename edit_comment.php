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
    <link href="css/comment.css" rel="stylesheet" type="text/css">
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
                      <a href="student_comment_list.php" class="list-group-item active">同儕互評';
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
                      <a href="report_list.php" class="list-group-item active">問題回報';
                      if($report_count > 0){
                        echo '<span class="badge rounded-pill bg-danger" style="margin-left:5px;">'.$report_count.'</span>';
                      }
                      echo '</a>';
              }
            ?>
          </ul>
          <button type="button" class="btn btn-danger btn-sm"  onclick="window.location.href='logout.php'" style="margin-top:10px;  display:block;"><i class="bi bi-box-arrow-left"></i><b>登出</b></button>
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
            $comment_id = $_GET['comment_id'];
            $sql2 = "SELECT q1,q2,q3,q4,q5,comment from comment where comment_id='$comment_id' AND comment_situation != 'old'";
            $result2 = mysqli_query($conn, $sql2);
            if (mysqli_num_rows($result2) != 0) {
              while($row = mysqli_fetch_assoc($result2)) {
                $q1 = $row['q1'];
                $q2 = $row['q2'];
                $q3 = $row['q3'];
                $q4 = $row['q4'];
                $q5 = $row['q5'];
                $comment = $row['comment'];
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
                  <script>
                    var video = document.getElementById("'.$video_name.'");
                    video.pause();
                  </script>';
          ?>
          </div>
          <form action="send_comment.php" method="post">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">問題</th>
                  <th scope="col">非常不同意</th>
                  <th scope="col">不同意　</th>
                  <th scope="col">沒有意見</th>
                  <th scope="col">同意　　</th>
                  <th scope="col">非常同意</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">影片中解題過程及解答是否正確? </th>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question1" id="question1-1" value="1"<?php if($q1 == '1'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question1" id="question1-2" value="2"<?php if($q1 == '2'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question1" id="question1-3" value="3"<?php if($q1 == '3'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question1" id="question1-4" value="4"<?php if($q1 == '4'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question1" id="question1-5" value="5"<?php if($q1 == '5'){echo 'checked';}?>>
                    </div>
                  </td>
                </tr>

                <tr>
                  <th scope="row">影片中解釋的觀念是否清楚易懂? </th>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question2" id="question2-1" value="1" <?php if($q2 == '1'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question2" id="question2-2" value="2" <?php if($q2 == '2'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question2" id="question2-3" value="3" <?php if($q2 == '3'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question2" id="question2-4" value="4" <?php if($q2 == '4'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question2" id="question2-5" value="5" <?php if($q2 == '5'){echo 'checked';}?>>
                    </div>
                  </td>
                </tr>

                <tr>
                  <th scope="row">影片整體講解過程是否順暢? </th>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question3" id="question3-1" value="1" <?php if($q3 == '1'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question3" id="question3-2" value="2" <?php if($q3 == '2'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question3" id="question3-3" value="3" <?php if($q3 == '3'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question3" id="question3-4" value="4" <?php if($q3 == '4'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question3" id="question3-5" value="5" <?php if($q3 == '5'){echo 'checked';}?>>
                    </div>
                  </td>
                </tr>

                <tr>
                  <th scope="row">影片設計、講解方式是否吸引你觀看? </th>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question4" id="question4-1" value="1" <?php if($q4 == '1'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question4" id="question4-2" value="2" <?php if($q4 == '2'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question4" id="question4-3" value="3" <?php if($q4 == '3'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question4" id="question4-4" value="4" <?php if($q4 == '4'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question4" id="question4-5" value="5" <?php if($q4 == '5'){echo 'checked';}?>>
                    </div>
                  </td>
                </tr>

                <tr>
                  <th scope="row">影片的畫面及聲音是否清晰? </th>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question5" id="question5-1" value="1" <?php if($q5 == '1'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question5" id="question5-2" value="2" <?php if($q5 == '2'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question5" id="question5-3" value="3" <?php if($q5 == '3'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question5" id="question5-4" value="4" <?php if($q5 == '4'){echo 'checked';}?>>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question5" id="question5-5" value="5" <?php if($q5 == '5'){echo 'checked';}?>>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="form-group d-flex align-items-end">
              <?php
                echo '<input type="hidden" name="user_id" value="'.$_SESSION['id'].'">
                <input type="hidden" name="video_id" value="'.$video_name.'">
                <input type="hidden" name="comment_id" value="'.$comment_id.'">';
              ?>
              <input type="hidden" name="situation" value="edit">
              <textarea class="form-control" required name="comment" rows="3"style="max-width:80%; display:inline-block;"><?php if(!empty($comment)){echo $comment;}?></textarea>
              <button type="submit" class="btn btn-primary" style="margin-left:5px;">送出</button>
            </div>
            <br><br>
            
          </form>

       

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