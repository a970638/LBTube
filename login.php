<?php
  ini_set('default_charset','utf-8');
  // 取得用戶輸入的用戶名和密碼
  $username = $_POST['username'];
  $password = $_POST['password'];

  // 連接到資料庫
  include 'connection.php';

  session_start();
  $_SESSION["uid"] = $username;

  // 執行 SQL 查詢，驗證用戶名和密碼
  $sql = "SELECT `id`,`name`,`group`, gender ,comment_group,school FROM users WHERE uid = '".$_SESSION["uid"]."'";
  $result = mysqli_query($conn, $sql);

  // 如果用戶名和密碼驗證成功，則將用戶設置為已登錄
  if (mysqli_num_rows($result) > 0) {
    
    $_SESSION['loggedin'] = true;
    // $_SESSION['username'] = $username;

    if ($row = mysqli_fetch_assoc($result)) {
        // 将查询结果存入session
        $_SESSION['id'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['group'] = $row['group'];
        $_SESSION['gender'] = $row['gender'];
        $_SESSION['position'] = $row['position'];
        $_SESSION['comment_group'] = $row['comment_group'];
        $_SESSION['school'] = $row['school'];
      }

    // 轉到受保護的頁面
    header("Location: index.php");
    exit;
  } else {
    // 如果驗證失敗，則重定向回登錄頁面並顯示錯誤消息
    echo "<script>
            alert('帳號或密碼錯誤，請重新輸入');
            window.location.href='login.html';
        </script>";
    // header("Location: index.php?error=incorrect");
    exit;
  }

  // 關閉連接
  mysqli_close($conn);
?>
