<?php
  //設定編碼
  ini_set('default_charset','utf-8');
  error_reporting(0);
  //啟動SESSION
  session_start();
  

  if(empty($_SESSION["uid"])) {
    //若未登入導回明日星球登入介面
    echo "<script>
            alert('驗證失敗，請重新登入');
            window.location.href='http://www.cot.org.tw';
          </script>";
  }
  // 檢查用戶是否已登錄
  // if (!$_SESSION['loggedin']) {
  //   // 如果未登錄，則重定向回登錄頁面
  //   echo "<script>
  //           alert('您尚未登入，請先登入');
  //           window.location.href='login.html';
  //         </script>";
  //   // header("Location: login.html");
  //   exit;
  // }
?>
