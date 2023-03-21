<?php
    include("check_login.php");
    require 'vendor/autoload.php';

    use Aws\S3\S3Client;
    use Aws\Exception\AwsException;

    // 建立連接到資料庫
    include 'connection.php';

    $group = $_SESSION['group'];
    $sql = "SELECT COUNT(*) FROM video WHERE video_group = '$group'";

    $result = mysqli_query($conn, $sql);
    $count = mysqli_fetch_row($result)[0] + 1;

    $fileName = 'video_'.$_SESSION['group'].'_'.$count;
    $semester = $_POST['semester'];
    $unit = $_POST['unit'];
    $school = $_SESSION['school'];
    
    if (isset($_FILES['uploaded_file'])) {
        // AWS S3 client
        $s3Client = new S3Client([
            'region' => 'ap-northeast-1',
            'version' => 'latest',
            'credentials' => [
                'key' => 'your aws key', //change to your aws key
                'secret' => 'hh/VUvSLOymoWNH0cIRH+BXgJDWi3KtYRR6zxgzJ',
            ],
        ]);

        // Upload file to S3
        try {
            $s3Client->putObject([
                'Bucket' => 'nculbtbucket',
                'Key' => $fileName.'.mp4',
                'Body' => fopen($_FILES['uploaded_file']['tmp_name'], 'r'),
                'ContentType' => 'video/mp4',
                // 'ACL'    => 'public-read',
            ]);
            $sql = "INSERT INTO video (semester,unit,video_name,video_group,video_school) VALUES ('$semester','$unit','$fileName','$group','$school')";
            if (mysqli_query($conn, $sql)) {
                echo "<script>
                        alert('上傳成功');
                        window.location.href='upload.php';
                     </script>";
                mysqli_close($conn);
                }
        } catch (AwsException $e) {
            echo "error";
            echo $e->getMessage();
        }
    }
?>