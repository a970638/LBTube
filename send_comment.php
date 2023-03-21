<?php
include("check_login.php");
$user_id = $_POST["user_id"];
$video_id = $_POST["video_id"];
$q1 = $_POST["question1"];
$q2 = $_POST["question2"];
$q3 = $_POST["question3"];
$q4 = $_POST["question4"];
$q5 = $_POST["question5"];
$comment = $_POST["comment"];
$type = 'cm';
$situation = $_POST["situation"];

// 建立連接到資料庫
include 'connection.php';

// 寫入資料庫
if($situation == 'new'){
  $sql = "INSERT INTO comment (comment_user_id,video_id,q1,q2,q3,q4,q5,`type`,comment) VALUES ('$user_id','$video_id','$q1','$q2','$q3','$q4','$q5','$type','$comment')";
}elseif($situation == 'edit'){
  $comment_id = $_POST['comment_id'];
  $sql = "UPDATE comment 
          SET q1='$q1',q2='$q2',q3='$q3',q4='$q4',q5='$q5',comment='$comment'
          WHERE comment_id = '$comment_id'";
}

if (mysqli_query($conn, $sql)) {
  if($_SESSION['position'] == 'S'){
    header("Location: student_comment_list.php");
  }else{
    header("Location: teacher_comment_list.php");
  }
//   echo "資料已經成功寫入資料庫！";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// 關閉連接
mysqli_close($conn);
?>
