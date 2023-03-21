<?php
include("check_login.php");
$user_id = $_SESSION["id"];
$getreport_id = $_POST["getreport_id"];
$report_comment_id=$_POST["comment_id"];
$report_content=$_POST["report_content"];
$video_name=$_POST["video_name"];

// 建立連接到資料庫
include 'connection.php';

// 寫入資料庫
$sql = "INSERT INTO report (reporter_uid, getreport_uid,report_comment_id,report_content) VALUES ('$user_id','$getreport_id','$report_comment_id','$report_content')";


if (mysqli_query($conn, $sql)) {
  echo "<script>
          alert('回報成功');
          window.location.href='comment_detail.php?video_name=".$video_name."';
        </script>";
  // header("Location: comment_detail.php?video_name=".$video_name);
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// 關閉連接
mysqli_close($conn);
?>
