<?php
  include("check_login.php");
  require 'vendor/autoload.php';

  use Aws\S3\S3Client;
  
  $s3 = new S3Client([
      'version' => 'latest',
      'region'  => 'ap-northeast-1',
      'credentials' => [
          'key'    => 'your aws key',//change to your aws key
          'secret' => 'hh/VUvSLOymoWNH0cIRH+BXgJDWi3KtYRR6zxgzJ',
      ],
  ]);
  
  $filename = $_GET['video_name'];
  $bucket = 'nculbtbucket';
  $key = $filename.'.mp4';
  
  try{
    $s3->deleteObject(['Bucket' => $bucket, 'Key' => $key]);
    // echo "Object '{$key}' was deleted from '{$bucket}'\n";

    //連線到資料庫
    include 'connection.php';

    $sql = "DELETE FROM video WHERE video_name = '$filename'";
    $sql1 = "UPDATE comment 
            SET comment_situation='old'
            WHERE video_id = '$filename'";
    if ($conn->query($sql) === TRUE && $conn->query($sql1) === TRUE) {
        echo "<script>
                alert('刪除成功');
                window.location.href='student_video_list.php';
            </script>";
            mysqli_close($conn);
    }

  }catch (AwsException $e){
    echo "An error occurred uploading to S3: {$e->getMessage()}\n";
  }
  
  
?>