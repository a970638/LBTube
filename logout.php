<?php
    // 啟動 session
    session_start();

    // 刪除 session 中所有變數資料
    session_unset();
    header("Location: index.php");
?>