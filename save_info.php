<?php
    include ("check_login.php");
    // 連接到資料庫
    include 'connection.php';

    // 執行 SQL 查詢，驗證用戶名和密碼
    $sql = "SELECT `id`,`group`, gender,comment_group,position,school FROM users WHERE uid = '".$_SESSION["uid"]."'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        if ($row = mysqli_fetch_assoc($result)) {
            // 将查询结果存入session
            $_SESSION['id'] = $row['id'];
            $_SESSION['group'] = $row['group'];
            $_SESSION['gender'] = $row['gender'];
            $_SESSION['comment_group'] = $row['comment_group'];            
            $_SESSION['position'] = $row['position'];
            $_SESSION['school'] = $row['school'];
          }
      } else {
        // 如果驗證失敗，則重定向回登錄頁面並顯示錯誤消息
        echo "<script>
                alert('驗證失敗，請重新登入');
                window.location.href='http://www.cot.org.tw';
            </script>";
        // header("Location: index.php?error=incorrect");
      }

    // 關閉連接
    mysqli_close($conn);
?>