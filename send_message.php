<?php
include("check_login.php");
$user_id = $_SESSION['id'];
$video_id = $_POST["video_id"];
$message = $_POST["message"];
$type = 'ms';

// 建立連接到資料庫
include 'connection.php';

// 寫入資料庫
$sql = "INSERT INTO comment (comment_user_id,video_id,`type`,comment) VALUES ('$user_id','$video_id','$type','$message')";

if (mysqli_query($conn, $sql)) {
header("Location: play.php?video_name=".$video_id);
//   echo "資料已經成功寫入資料庫！";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// 關閉連接
mysqli_close($conn);
?>
