<?php
    ini_set('default_charset','utf-8');

    // server端
    $servername = "db.cot.org.tw";
    $user = "lbt";
    $pass ="!B4q-*C.6k%-";
    $dbname = "lbt";

    // 測試
    $servername = "db.cot.org.tw";
    $user = "admin_lbt";
    $pass ="r=HZe?SN6mR6";
    $dbname = "lbt";
    
    // admin_lbt'@'140.115.16.103
    // 連接到資料庫
    $conn = mysqli_connect($servername, $user, $pass, $dbname);
    mysqli_set_charset($conn, "utf8");
    // 確保連接成功
    if (!$conn) {
        die("連線失敗：" . mysqli_connect_error());
        }
?>