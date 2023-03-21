<?php
//連線
include 'connection.php';

$group = $_SESSION['group'];
$comment_group = $_SESSION['comment_group'];
$school = $_SESSION['school'];
$id = $_SESSION['id'];

if($_SESSION['position'] == 'S'){
    $sqlS1 = "SELECT COUNT(DISTINCT video_name) AS result
              FROM video 
              JOIN users
              ON video.video_group = users.group
              WHERE users.comment_group = '$comment_group' AND users.group != '$group'";
            
    $resultS1 = mysqli_query($conn, $sqlS1);
    $rowS1 = mysqli_fetch_assoc($resultS1);
    $video_count = $rowS1['result'];

    $sqlS2 = "SELECT COUNT(*) AS result
            FROM comment
            WHERE comment_user_id = '$id' AND `type`= 'cm'  AND comment_situation NoT IN ('old')";

    $resultS2 = mysqli_query($conn, $sqlS2);
    $rowS2 = mysqli_fetch_assoc($resultS2);
    $comment_count = $rowS2['result'];

    $resultS = $video_count - $comment_count;
}else{
    $sqlT1 = "SELECT COUNT(DISTINCT video_name)  AS result FROM video WHERE video_school = '$school'";
            
    $resultT1 = mysqli_query($conn, $sqlT1);
    $rowT1 = mysqli_fetch_assoc($resultT1);
    $video_count = $rowT1['result'];
    

    $sqlT2 = "SELECT COUNT(*) AS result
              FROM comment
              WHERE comment_user_id = '$id'  AND `type`= 'cm' AND comment_situation NoT IN ('old')";

    $resultT2 = mysqli_query($conn, $sqlT2);
    $rowT2 = mysqli_fetch_assoc($resultT2);
    $comment_count = $rowT2['result'];

    $resultT = $video_count - $comment_count;

    
    $sqlT3 = "SELECT COUNT(report_id) AS result FROM report JOIN users on getreport_uid = id  where school = '$school' AND checked ='unchecked'";

    $resultT3 = mysqli_query($conn, $sqlT3);
    $rowT3 = mysqli_fetch_assoc($resultT3);
    $report_count = $rowT3['result'];

}

?>